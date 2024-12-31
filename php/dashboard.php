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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['upload_image'])) {

        if (isset($_FILES['image_file']) && isset($_POST['image_title'])) {
            $image_title = trim($_POST['image_title']);
            $image_file = $_FILES['image_file'];

            if ($image_file['error'] === UPLOAD_ERR_OK) {
                $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
                if (in_array($image_file['type'], $allowed_types)) {

                    $upload_dir = 'uploads/';
                    if (!is_dir($upload_dir)) {
                        mkdir($upload_dir, 0755, true);
                    }


                    $file_ext = pathinfo($image_file['name'], PATHINFO_EXTENSION);
                    $file_name = uniqid() . '.' . $file_ext;
                    $file_path = $upload_dir . $file_name;


                    if (move_uploaded_file($image_file['tmp_name'], $file_path)) {

                        $stmt = $conn->prepare("INSERT INTO image_table (image_title, image_time, image_a, image_comment, image_love, uid) VALUES (?, NOW(), ?, 0, 0, ?)");
                        $stmt->bind_param("ssi", $image_title, $file_path, $uid);
                        if ($stmt->execute()) {

                            $stmt->close();
                            header("Location: dashboard.php");
                            exit();
                        } else {
                            $upload_error = "数据库插入失败: " . $stmt->error;
                            $stmt->close();
                        }
                    } else {
                        $upload_error = "上传文件失败。";
                    }
                } else {
                    $upload_error = "不支持的文件类型。仅支持 JPEG, PNG, GIF。";
                }
            } else {
                $upload_error = "文件上传错误。";
            }
        } else {
            $upload_error = "请上传图片并输入标题。";
        }
    }


    if (isset($_POST['like_image'])) {
        $image_id = intval($_POST['image_id']);


        $check_like = $conn->prepare("SELECT * FROM likes_table WHERE uid = ? AND image_id = ?");
        $check_like->bind_param("ii", $uid, $image_id);
        $check_like->execute();
        $is_liked = $check_like->get_result()->num_rows > 0;
        $check_like->close();

        if ($is_liked) {

            $remove_like = $conn->prepare("DELETE FROM likes_table WHERE uid = ? AND image_id = ?");
            $remove_like->bind_param("ii", $uid, $image_id);
            $remove_like->execute();
            $remove_like->close();


            $update_likes = $conn->prepare("UPDATE image_table SET image_love = image_love - 1 WHERE image_id = ?");
            $update_likes->bind_param("i", $image_id);
            $update_likes->execute();
            $update_likes->close();
        } else {

            $add_like = $conn->prepare("INSERT INTO likes_table (uid, image_id, like_time) VALUES (?, ?, NOW())");
            $add_like->bind_param("ii", $uid, $image_id);
            $add_like->execute();
            $add_like->close();


            $update_likes = $conn->prepare("UPDATE image_table SET image_love = image_love + 1 WHERE image_id = ?");
            $update_likes->bind_param("i", $image_id);
            $update_likes->execute();
            $update_likes->close();
        }


        header("Location: dashboard.php");
        exit();
    }


    if ($is_admin && isset($_POST['delete_image'])) {
        $image_id = intval($_POST['image_id']);


        $stmt = $conn->prepare("SELECT image_a FROM image_table WHERE image_id = ?");
        $stmt->bind_param("i", $image_id);
        $stmt->execute();
        $stmt->bind_result($image_a);
        if ($stmt->fetch()) {
            $stmt->close();


            $delete_stmt = $conn->prepare("DELETE FROM image_table WHERE image_id = ?");
            $delete_stmt->bind_param("i", $image_id);
            if ($delete_stmt->execute()) {
                $delete_stmt->close();


                if (file_exists($image_a)) {
                    unlink($image_a);
                }


                $delete_likes = $conn->prepare("DELETE FROM likes_table WHERE image_id = ?");
                $delete_likes->bind_param("i", $image_id);
                $delete_likes->execute();
                $delete_likes->close();


                $delete_comments = $conn->prepare("DELETE FROM images_comment_table WHERE image_id = ?");
                $delete_comments->bind_param("i", $image_id);
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
        $image_id = intval($_POST['image_id']);
        $new_title = trim($_POST['new_title']);


        $modify_stmt = $conn->prepare("UPDATE image_table SET image_title = ? WHERE image_id = ?");
        $modify_stmt->bind_param("si", $new_title, $image_id);
        if ($modify_stmt->execute()) {
            $modify_stmt->close();
            header("Location: dashboard.php");
            exit();
        } else {
            $modify_error = "修改图片标题失败: " . $modify_stmt->error;
            $modify_stmt->close();
        }
    }
}


$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$sql = "SELECT * FROM image_table";
if ($search_query) {
    $sql .= " WHERE image_title LIKE ?";
    $stmt = $conn->prepare($sql);
    $search_param = "%$search_query%";
    $stmt->bind_param("s", $search_param);
} else {
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$result = $stmt->get_result();
$images = $result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>图片评论板</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f8ff; 
        }


        .image-grid {
            column-count: 5; 
            column-gap: 16px; 
        }
        @media (max-width: 1200px) {
            .image-grid {
                column-count: 4; 
            }
        }
        @media (max-width: 992px) {
            .image-grid {
                column-count: 3; 
            }
        }
        @media (max-width: 768px) {
            .image-grid {
                column-count: 2; 
            }
        }
        @media (max-width: 576px) {
            .image-grid {
                column-count: 1; 
            }
        }

        .image-card {
            break-inside: avoid; 
            margin-bottom: 16px; 
            background: #fff; 
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .image-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        }

  
        .image-card img {
            width: 100%;
            height: auto; 
            object-fit: cover; 
            transition: transform 0.3s ease;
        }


        .image-card img:hover {
            transform: scale(1.05);
        }

        .navbar .form-control {
            border-radius: 20px;
            max-width: 300px;
            height: 36px;
            padding: 0.375rem 1rem;
        }

        .navbar .btn-primary {
            height: 36px;
            padding: 0 1.5rem;
            min-width: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .navbar .d-flex {
            align-items: center;
        }

        .contro-size {
            height: 36px;
            display: flex;
            align-items: center;
        }

        /* 修改图片信息显示样式 */
        .image-info {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 16px;
            flex-direction: row;
            min-height: 40px;
        }

        .image-time {
            white-space: nowrap;
            color: #6c757d;
            font-size: 0.75rem;
        }

        .image-stats {
            display: flex;
            flex-direction: column;
            gap: 4px;
            align-items: flex-end;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 4px;
            color: #6c757d;
            text-decoration: none;
            font-size: 0.75rem;
            line-height: 1;
        }

        .stat-item i {
            font-size: 0.875rem;
        }

        .stat-item:hover {
            color: #0077b6;
        }

        .btn {
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-primary {
            background-color: #0077b6;
            border-color: #0077b6;
        }

        .btn-primary:hover {
            background-color: #023e8a;
            border-color: #023e8a;
        }

        .navbar {
            background: #fff;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .navbar a.navbar-brand {
            color: #0077b6;
            font-weight: bold;
        }

        .navbar a.navbar-brand:hover {
            color: #023e8a;
        }

        .card-title {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .admin-actions {
            margin-top: 8px;
            display: flex;
            gap: 8px;
            justify-content: center;
        }
        
        .admin-actions .btn {
            height: 32px;
            width: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-light">
        <div class="container-fluid">

            <a class="navbar-brand" href="#">图片评论板</a>

            <form class="d-flex mx-auto contro-size" action="dashboard.php" method="GET">
                <input class="form-control me-2" type="search" name="search" placeholder="搜索图片..." aria-label="Search" value="<?php echo htmlspecialchars($search_query); ?>">
                <button class="btn btn-primary" type="submit">搜索</button>
            </form>

            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    上传图片
                </button>
                <form action="dashboard.php" method="POST" class="d-inline">
                    <button type="submit" name="logout" class="btn btn-outline-danger btn-sm">登出</button>
                </form>
            </div>
        </div>
    </nav>


    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="dashboard.php" method="POST" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadModalLabel">上传图片</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                    </div>
                    <div class="modal-body">
                        <?php if (isset($upload_error)): ?>
                            <div class="alert alert-danger">
                                <?php echo htmlspecialchars($upload_error); ?>
                            </div>
                        <?php endif; ?>
                        <div class="mb-3">
                            <label for="image_title" class="form-label">图片标题</label>
                            <input type="text" class="form-control" id="image_title" name="image_title" required>
                        </div>
                        <div class="mb-3">
                            <label for="image_file" class="form-label">选择图片</label>
                            <input class="form-control" type="file" id="image_file" name="image_file" accept="image/*" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">取消</button>
                        <button type="submit" name="upload_image" class="btn btn-primary">上传</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="container mt-4">
        <?php if (isset($delete_error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($delete_error); ?>
            </div>
        <?php endif; ?>
        <?php if (isset($modify_error)): ?>
            <div class="alert alert-danger">
                <?php echo htmlspecialchars($modify_error); ?>
            </div>
        <?php endif; ?>
        <div class="image-grid">
            <?php if (empty($images)): ?>
                <p>No images found. Try uploading one!</p>
            <?php else: ?>
                <?php foreach ($images as $image): ?>
                    <div class="image-card">
                        <div class="card">
                            <a href="view_image.php?id=<?php echo $image['image_id']; ?>">
                                <img src="<?php echo htmlspecialchars($image['image_a']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($image['image_title']); ?>">
                            </a>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($image['image_title']); ?></h5>
                                <div class="image-info">
        <span class="image-time"><?php echo date("Y-m-d H:i", strtotime($image['image_time'])); ?></span>
        <div class="image-stats">
            <form action="dashboard.php" method="POST" class="d-inline">
                <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                <button type="submit" name="like_image" class="btn btn-link p-0 stat-item">
                    <i class="bi bi-heart"></i>
                    <span><?php echo $image['image_love']; ?></span>
                </button>
            </form>
            <a href="view_image.php?id=<?php echo $image['image_id']; ?>" class="stat-item">
                <i class="bi bi-chat"></i>
                <span><?php echo $image['image_comment']; ?></span>
            </a>
        </div>
    </div>
                                <?php if ($is_admin): ?>
                                    <div class="admin-actions">
                                        <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modifyModal<?php echo $image['image_id']; ?>">
                                            修改
                                        </button>
                                        <form action="dashboard.php" method="POST" class="d-inline" onsubmit="return confirm('确定要删除这张图片吗？');">
                                            <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
                                            <button type="submit" name="delete_image" class="btn btn-sm btn-danger">删除</button>
                                        </form>
                                    </div>
                                    <div class="modal fade" id="modifyModal<?php echo $image['image_id']; ?>" tabindex="-1" aria-labelledby="modifyModalLabel<?php echo $image['image_id']; ?>" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <form action="dashboard.php" method="POST">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modifyModalLabel<?php echo $image['image_id']; ?>">修改图片标题</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="关闭"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label for="new_title<?php echo $image['image_id']; ?>" class="form-label">新标题</label>
                                                            <input type="text" class="form-control" id="new_title<?php echo $image['image_id']; ?>" name="new_title" value="<?php echo htmlspecialchars($image['image_title']); ?>" required>
                                                        </div>
                                                        <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>">
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
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>