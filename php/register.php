<?php
session_start();
require 'config.php';

if (isset($_SESSION['uid'])) {
    header("Location: dashboard.php");
    exit();
}

$registration_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $username = trim($_POST['username']);
    $password = $_POST['password']; 
    $bio = trim($_POST['bio']);


    if (empty($username) || empty($password)) {
        $registration_error = "用户名和密码不能为空。";
    } else {

        $stmt = $conn->prepare("SELECT uid FROM user_table WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $registration_error = "用户名已存在。";
        } else {
            $stmt->close();


            $avatar = 'user_img/default.png'; 
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['avatar']['name'];
                $filetmp = $_FILES['avatar']['tmp_name'];
                $filesize = $_FILES['avatar']['size'];
                $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));


                if (!in_array($ext, $allowed)) {
                    $registration_error = "无效的头像文件类型。";
                } elseif ($filesize > 2 * 1024 * 1024) { 
                    $registration_error = "头像文件过大。";
                } else {

                    $target_dir = "user_img/";
                    if (!is_dir($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }


                    $new_filename = uniqid() . '.' . $ext;
                    $target_file = $target_dir . $new_filename;


                    if (move_uploaded_file($filetmp, $target_file)) {
                        $avatar = $target_file;
                    } else {
                        $registration_error = "头像上传失败。";
                    }
                }
            }

            if (empty($registration_error)) {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);


                $stmt = $conn->prepare("INSERT INTO user_table (user_name, user_password, user_img, user_txt) VALUES (?, ?, ?, ?)");
                $stmt->bind_param("ssss", $username, $hashed_password, $avatar, $bio);

                if ($stmt->execute()) {

                    header("Location: index.php");
                    exit();
                } else {
                    $registration_error = "注册失败: " . $stmt->error;
                }

                $stmt->close();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>注册 - 图片评论板</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            background: url('path_to_your_background_image.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: Arial, sans-serif;
        }
        .register-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .register-box {
            background: rgba(255, 255, 255, 0.95);
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.2);
            width: 100%;
            max-width: 450px;
        }
        .register-box h2 {
            margin-bottom: 30px;
            text-align: center;
            color: #333;
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="register-container">
        <div class="register-box">
            <h2>注册</h2>
            <?php if (!empty($registration_error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($registration_error); ?></div>
            <?php endif; ?>
            <form action="register.php" method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="username" class="form-label">用户名</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="mb-4">
                    <label for="password" class="form-label">密码</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <div class="mb-4">
                    <label for="avatar" class="form-label">头像</label>
                    <input class="form-control" type="file" id="avatar" name="avatar" accept="image/*">
                </div>
                <div class="mb-4">
                    <label for="bio" class="form-label">简介</label>
                    <textarea class="form-control" id="bio" name="bio" rows="3"><?php echo isset($_POST['bio']) ? htmlspecialchars($_POST['bio']) : ''; ?></textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100">注册</button>
            </form>
            <p class="mt-4 text-center">已有账号？ <a href="index.php">登录</a></p>
        </div>
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
