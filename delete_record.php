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

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $sql = "DELETE FROM records WHERE id='$id'";

    if ($conn->query($sql) === TRUE) {
        echo "申请信息删除成功!";
    } else {
        echo "删除失败: " . $conn->error;
    }
} else {
    echo "错误: 未提供申请 ID。";
}

$conn->close();
?>
