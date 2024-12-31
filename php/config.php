<?php
$servername = "localhost:3329";
$username = "root";
$password = "password";
$dbname = "image_comment_board";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$conn->set_charset("utf8");
?>
