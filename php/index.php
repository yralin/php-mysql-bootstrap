<?php
session_start();
require 'config.php';

if (isset($_SESSION['uid'])) {
    header("Location: dashboard.php");
    exit();
}

$login_error = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $login_error = "用户名和密码不能为空。";
    } else {
        $stmt = $conn->prepare("SELECT uid, user_password FROM user_table WHERE user_name = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            $stmt->bind_result($uid, $stored_password);
            $stmt->fetch();

            if (password_verify($password, $stored_password)) {
                $_SESSION['uid'] = $uid;
                $_SESSION['username'] = $username;

                header("Location: dashboard.php");
                exit();
            } else {
                $login_error = "密码无效。";
            }
        } else {
            $login_error = "用户名不存在。";
        }

        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <title>图片评论板</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
            background: url('path_to_your_background_image.jpg') no-repeat center center fixed;
            background-size: cover;
            background-color: #f0f8ff; 
        }
        .login-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-box {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 400px;
        }
        .footer {
            position: fixed;
            bottom: 10px;
            width: 100%;
            text-align: center;
            color: #fff;
            background: rgba(0, 0, 0, 0.5);
            padding: 10px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h2 class="text-center mb-4">图片评论板</h2>
            <?php if (!empty($login_error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($login_error); ?></div>
            <?php endif; ?>
            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label for="username" class="form-label">用户名</label>
                    <input type="text" class="form-control" id="username" name="username" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">密码</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">登录</button>
            </form>
            <p class="mt-3 text-center">没有账号？ <a href="register.php">注册</a></p>
        </div>
    </div>
    <div class="footer">
        
    </div>
    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>
