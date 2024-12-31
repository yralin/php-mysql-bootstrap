<?php

session_start();
require 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {

    $_SESSION = array();


    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }


    session_destroy();


    header("Location: index.php");
    exit();
}


if (!isset($_SESSION['uid'])) {
    header("Location: index.php");
    exit();
}

$uid = $_SESSION['uid'];
$username = $_SESSION['username'];


$is_admin = ($username === 'lin');


$image_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if (!$image_id) {
    echo "Invalid image ID.";
    exit();
}

$image_query = $conn->prepare("SELECT * FROM image_table WHERE image_id = ?");
$image_query->bind_param("i", $image_id);
$image_query->execute();
$image_result = $image_query->get_result();
if ($image_result->num_rows === 0) {
    echo "Image not found.";
    exit();
}
$image = $image_result->fetch_assoc();
$image_query->close();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['add_comment'])) {
        $comment_text = trim($_POST['comment_text']);
        if (!empty($comment_text)) {

            $add_comment = $conn->prepare("INSERT INTO images_comment_table (image_id, uid, comment_txt, comment_time, comment_love) VALUES (?, ?, ?, NOW(), 0)");
            $add_comment->bind_param("iis", $image_id, $uid, $comment_text);
            if ($add_comment->execute()) {
                $add_comment->close();

                header("Location: view_image.php?id=$image_id");
                exit();
            } else {
                $comment_error = "评论发布失败: " . $add_comment->error;
                $add_comment->close();
            }
        } else {
            $comment_error = "评论内容不能为空。";
        }
    }


    if (isset($_POST['like_comment'])) {
        $comment_id = intval($_POST['comment_id']);
        $check_like = $conn->prepare("SELECT * FROM likes_table WHERE uid = ? AND comment_id = ?");
        $check_like->bind_param("ii", $uid, $comment_id);
        $check_like->execute();
        $is_liked = $check_like->get_result()->num_rows > 0;
        $check_like->close();

        if (!$is_liked) {
            $add_like = $conn->prepare("INSERT INTO likes_table (uid, comment_id, like_time) VALUES (?, ?, NOW())");
            $add_like->bind_param("ii", $uid, $comment_id);
            $add_like->execute();
            $add_like->close();

            $update_comment_like = $conn->prepare("UPDATE images_comment_table SET comment_love = comment_love + 1 WHERE comment_id = ?");
            $update_comment_like->bind_param("i", $comment_id);
            $update_comment_like->execute();
            $update_comment_like->close();
        }

        header("Location: view_image.php?id=$image_id");
        exit();
    }


    if ($is_admin && isset($_POST['delete_comment'])) {
        $comment_id = intval($_POST['comment_id']);


        $delete_comment = $conn->prepare("DELETE FROM images_comment_table WHERE comment_id = ?");
        $delete_comment->bind_param("i", $comment_id);
        if ($delete_comment->execute()) {
            $delete_comment->close();

            header("Location: view_image.php?id=$image_id");
            exit();
        } else {
            $comment_delete_error = "删除评论失败: " . $delete_comment->error;
            $delete_comment->close();
        }
    }

    if ($is_admin && isset($_POST['modify_comment'])) {
        $comment_id = intval($_POST['comment_id']);
        $new_comment_txt = trim($_POST['new_comment_txt']);

        if (!empty($new_comment_txt)) {

            $update_comment = $conn->prepare("UPDATE images_comment_table SET comment_txt = ? WHERE comment_id = ?");
            $update_comment->bind_param("si", $new_comment_txt, $comment_id);
            if ($update_comment->execute()) {
                $update_comment->close();
                header("Location: view_image.php?id=$image_id");
                exit();
            } else {
                $comment_modify_error = "修改评论失败: " . $update_comment->error;
                $update_comment->close();
            }
        } else {
            $comment_modify_error = "新评论内容不能为空。";
        }
    }


    if ($is_admin && isset($_POST['delete_image'])) {
        $image_id_post = intval($_POST['image_id']);


        $stmt = $conn->prepare("SELECT image_a FROM image_table WHERE image_id = ?");
        $stmt->bind_param("i", $image_id_post);
        $stmt->execute();
        $stmt->bind_result($image_a_path);
        if ($stmt->fetch()) {
            $stmt->close();


            $delete_stmt = $conn->prepare("DELETE FROM image_table WHERE image_id = ?");
            $delete_stmt->bind_param("i", $image_id_post);
            if ($delete_stmt->execute()) {
                $delete_stmt->close();


                if (file_exists($image_a_path)) {
                    unlink($image_a_path);
                }


                $delete_likes = $conn->prepare("DELETE FROM likes_table WHERE image_id = ?");
                $delete_likes->bind_param("i", $image_id_post);
                $delete_likes->execute();
                $delete_likes->close();


                $delete_comments = $conn->prepare("DELETE FROM images_comment_table WHERE image_id = ?");
                $delete_comments->bind_param("i", $image_id_post);
                $delete_comments->execute();
                $delete_comments->close();

                header("Location: dashboard.php");
                exit();
            } else {
                $delete_error = "删除图片失败: " . $delete_stmt->error;
                $delete_stmt->close();
            }
        } else {
            $delete_error = "未找到要删除的图片。";
            $stmt->close();
        }
    }


    if ($is_admin && isset($_POST['modify_image'])) {
        $image_id_post = intval($_POST['image_id']);
        $new_title = trim($_POST['new_title']);

        if (!empty($new_title)) {

            $modify_stmt = $conn->prepare("UPDATE image_table SET image_title = ? WHERE image_id = ?");
            $modify_stmt->bind_param("si", $new_title, $image_id_post);
            if ($modify_stmt->execute()) {
                $modify_stmt->close();
                header("Location: view_image.php?id=$image_id_post");
                exit();
            } else {
                $modify_error = "修改图片标题失败: " . $modify_stmt->error;
                $modify_stmt->close();
            }
        } else {
            $modify_error = "新标题不能为空。";
        }
    }
}


$comment_query = $conn->prepare("SELECT c.*, u.user_name, u.user_img FROM images_comment_table c JOIN user_table u ON c.uid = u.uid WHERE c.image_id = ? ORDER BY c.comment_time DESC");
$comment_query->bind_param("i", $image_id);
$comment_query->execute();
$comments = $comment_query->get_result()->fetch_all(MYSQLI_ASSOC);
$comment_query->close();
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($image['image_title']); ?>图片</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --primary-blue: #0077b6;
            --light-blue: #f0f8ff;
            --hover-blue: #023e8a;
            --text-dark: #2c3e50;
            --border-color: #e0e7ff;
        }

        body {
            background-color: var(--light-blue);
            color: var(--text-dark);
            padding-top: 30px;
        }


        .navbar {
            background: white;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            height: 56px;
            padding: 0.5rem 1rem;
        }

        .navbar-brand {
            color: var(--primary-blue) !important;
            font-weight: 600;
            font-size: 1.2rem;
        }


        .main-container {
            margin-top: 2rem;
        }

        .card {
            border: none;
            background-color: var(--light-blue); 
            border-radius: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .card-img-top {
            max-height: 700px;
            object-fit: contain;
            border-radius: 12px 12px 0 0;
            padding: 20px 0px 0 0px;
        }

        .card-title {
            color: var(--text-dark);
            font-weight: 400;
            text-align: center;
            margin-bottom: 1rem;
        }


        .stats-row {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 15rem;
            margin: 1rem 0;
            padding: 0.5rem;
            border-radius: 8px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--primary-blue);
            font-size: 1.1rem;
        }


        .admin-actions {
            display: flex;
            gap: 15rem;
            justify-content: center;
            margin-top: 1rem;
        }

        .admin-actions .btn {
            padding: 0.25rem 1rem;
            border-radius: 15px;
            font-size: 0.9rem;
        }


        .comment-section {
            margin-top: 2rem;
            padding-bottom: 400px;
        }

        .comment-avatar {
            width: 48px;
            height: 48px;
            object-fit: cover;
            border-radius: 50%;
            border: 2px solid var(--border-color);
        }


        .fixed-comment-box {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: white;
            padding: 1rem;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
            transform: translateY(70%);
            z-index: 1000;
        }

        .fixed-comment-box:hover {
            transform: translateY(0);
        }

        .like-button {
            background: none;
            border: none;
            color: #ff4757;
            cursor: pointer;
            transition: transform 0.2s ease;
            font-size: 1.2rem;
        }

        .like-button:hover {
            transform: scale(1.1);
        }

        @media (max-width: 768px) {
            .stats-row {
                flex-direction: row;
                gap: 1rem;
            }
            
            .comment-avatar {
                width: 40px;
                height: 40px;
            }
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="dashboard.php">返回主页</a>
            <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST" class="ms-auto">
                <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">登出</button>
            </form>
        </div>
    </nav>


    <div class="container main-container">
        <?php if (isset($delete_error) || isset($modify_error)): ?>
            <div class="alert alert-danger">
                <?php echo isset($delete_error) ? htmlspecialchars($delete_error) : htmlspecialchars($modify_error); ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <img src="<?php echo htmlspecialchars($image['image_a']); ?>" class="card-img-top" alt="Image">
            <div class="card-body">
                <h3 class="card-title"><?php echo htmlspecialchars($image['image_title']); ?></h3>
                
                <div class="stats-row">
                    <div class="stat-item">
                        <span>❤️</span>
                        <span><?php echo $image['image_love']; ?></span>
                    </div>
                    <div class="stat-item">
                        <span>💬</span>
                        <span><?php echo $image['image_comment']; ?></span>
                    </div>
                </div>

                <?php if ($is_admin): ?>
                    <div class="admin-actions">
                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modifyImageModal">
                            修改标题
                        </button>
                        <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST" class="d-inline" onsubmit="return confirm('确定要删除这张图片吗？');">
                            <input type="hidden" name="image_id" value="<?php echo $image_id; ?>">
                            <button type="submit" name="delete_image" class="btn btn-danger btn-sm">删除</button>
                        </form>
                    </div>


                    <div class="modal fade" id="modifyImageModal" tabindex="-1" aria-labelledby="modifyImageModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="modifyImageModalLabel">修改图片标题</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="new_title" class="form-label">新标题</label>
                                            <input type="text" class="form-control" id="new_title" name="new_title" value="<?php echo htmlspecialchars($image['image_title']); ?>" required>
                                            <input type="hidden" name="image_id" value="<?php echo $image_id; ?>">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                        <button type="submit" name="modify_image" class="btn btn-primary">保存修改</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>


        <div class="comment-section">
            <h4>评论</h4>
            <?php if (empty($comments)): ?>
                <p>暂无评论。成为第一个评论的人吧！</p>
            <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                    <div class="d-flex mb-4">
                        <img src="<?php echo htmlspecialchars($comment['user_img'] ? $comment['user_img'] : 'default_avatar.png'); ?>" alt="Avatar" class="comment-avatar me-3">
                        <div class="flex-grow-1">
                            <div class="d-flex justify-content-between">
                                <h5 class="mb-1"><?php echo htmlspecialchars($comment['user_name']); ?></h5>
                                <small class="text-muted"><?php echo date("Y-m-d H:i", strtotime($comment['comment_time'])); ?></small>
                            </div>
                            <p><?php echo nl2br(htmlspecialchars($comment['comment_txt'])); ?></p>
                            <div class="d-flex align-items-center">
                                <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST" class="d-inline me-3">
                                    <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                    <button type="submit" name="like_comment" class="like-button">
                                        ❤️ <?php echo $comment['comment_love']; ?>
                                    </button>
                                </form>
                                <?php if ($is_admin): ?>
                                    <button type="button" class="btn btn-warning btn-sm me-2" data-bs-toggle="modal" data-bs-target="#modifyCommentModal<?php echo $comment['comment_id']; ?>">
                                        修改
                                    </button>
                                    <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST" class="d-inline" onsubmit="return confirm('确定要删除这条评论吗？');">
                                        <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                        <button type="submit" name="delete_comment" class="btn btn-danger btn-sm">删除</button>
                                    </form>


                                    <div class="modal fade" id="modifyCommentModal<?php echo $comment['comment_id']; ?>" tabindex="-1" aria-labelledby="modifyCommentModalLabel<?php echo $comment['comment_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modifyCommentModalLabel<?php echo $comment['comment_id']; ?>">修改评论</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="new_comment_txt<?php echo $comment['comment_id']; ?>" class="form-label">新评论内容</label>
                                                            <textarea class="form-control" id="new_comment_txt<?php echo $comment['comment_id']; ?>" name="new_comment_txt" rows="3" required><?php echo htmlspecialchars($comment['comment_txt']); ?></textarea>
                                                            <input type="hidden" name="comment_id" value="<?php echo $comment['comment_id']; ?>">
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                                                        <button type="submit" name="modify_comment" class="btn btn-primary">保存修改</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>


        <div class="fixed-comment-box">
            <div class="container">
                <form action="view_image.php?id=<?php echo $image_id; ?>" method="POST" class="d-flex">
                    <textarea class="form-control me-2" name="comment_text" rows="1" placeholder="添加您的评论..." required></textarea>
                    <button type="submit" name="add_comment" class="btn btn-primary">发布</button>
                </form>
            </div>
        </div>
    </div>


<script src="js/bootstrap.bundle.min.js"></script>

</body>
</html>
