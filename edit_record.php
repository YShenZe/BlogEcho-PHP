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

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $site_name = $_POST['site_name'];
        $email = $_POST['email'];
        $url = $_POST['url'];
        $site_description = $_POST['site_description'];

        $sql = "UPDATE records SET site_name='$site_name', email='$email', url='$url', site_description='$site_description' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "申请信息更新成功!";
        } else {
            echo "更新失败: " . $conn->error;
        }
    }

    $sql = "SELECT * FROM records WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
    } else {
        echo "未找到此申请信息";
        exit;
    }
} else {
    echo "错误: 未提供申请 ID。";
    exit;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>回响 - 修改申请信息</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-md rounded-lg mt-10">
        <h1 class="text-2xl font-bold text-center mb-6">回响 - 修改申请信息</h1>

        <form method="post" action="edit_record.php?id=<?php echo $id; ?>" class="space-y-4">
            <div>
                <label for="site_name" class="block text-sm font-medium text-gray-700 mb-1">网站名:</label>
                <input type="text" id="site_name" name="site_name" value="<?php echo $row['site_name']; ?>" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">邮箱:</label>
                <input type="email" id="email" name="email" value="<?php echo $row['email']; ?>" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="url" class="block text-sm font-medium text-gray-700 mb-1">网址:</label>
                <input type="url" id="url" name="url" value="<?php echo $row['url']; ?>" required
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300">
            </div>

            <div>
                <label for="site_description" class="block text-sm font-medium text-gray-700 mb-1">网站描述:</label>
                <textarea id="site_description" name="site_description" required rows="4"
                    class="w-full p-3 border border-gray-300 rounded-md focus:ring focus:ring-blue-300"><?php echo $row['site_description']; ?></textarea>
            </div>

            <div class="flex justify-center">
                <button type="submit"
                    class="px-6 py-2 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition duration-300">
                    更新
                </button>
            </div>
        </form>
    </div>

</body>

</html>
