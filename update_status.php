<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: admin_login.html");
    exit;
}

$servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$id = $_GET['id'];

$sql = "UPDATE records SET status='approved' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
    echo "回响的申请状态更新成功!";
} else {
    echo "更新失败: " . $conn->error;
}

$conn->close();
?>
