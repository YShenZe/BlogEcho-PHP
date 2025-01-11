<?php
$servername = "localhost";
$username = "数据库用户名";
$password = "密码";
$dbname = "数据库名";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$id = "";
$record = null;

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "SELECT * FROM records WHERE id='$id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $record = $result->fetch_assoc();
    } else {
        echo "未找到匹配的申请信息";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['report_reason']) && isset($_POST['record_id'])) {
    $record_id = $_POST['record_id'];
    $report_reason = $_POST['report_reason'];

    $sql_report = "INSERT INTO reports (record_id, report_reason) VALUES ('$record_id', '$report_reason')";
    $sql_status = "UPDATE records SET status='待确认举报' WHERE id='$record_id'";

    if ($conn->query($sql_report) === TRUE && $conn->query($sql_status) === TRUE) {
        echo "举报提交成功!";
    } else {
        echo "提交失败: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回响 - 查询申请信息</title>
    <link href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <style>
    /* 让表格单元格内容不换行 */
    .tb td, .tb th {
        white-space: nowrap;
    }
    </style>
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-6xl mx-auto p-6">
        <h1 class="text-3xl font-bold text-center mb-6">回响 - 查询申请信息</h1>

        <form method="get" action="query_record.php" class="mb-6">
            <div class="mb-4">
                <label for="id" class="block text-lg font-medium text-gray-700 mb-1">网站ID:</label>
                <input type="text" id="id" name="id" value="<?php echo htmlspecialchars($id); ?>" disabled
                    class="w-full p-3 border border-gray-300 rounded-md bg-gray-100 cursor-not-allowed">
            </div>
            <p class="text-gray-500">如需修改查询，请更改 URL 中的 ID 参数或返回首页重新查询。</p>
        </form>

        <?php if ($record): ?>
        <h2 class="text-2xl font-semibold mb-4">申请信息查询结果如下：</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full border-collapse border border-gray-200 tb">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border border-gray-300 px-4 py-2 text-left">ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">网站名</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">邮箱</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">网址</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">网站描述</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">状态</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">创建时间</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">操作</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['id']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['site_name']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['email']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['url']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['site_description']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['status']; ?></td>
                        <td class="border border-gray-300 px-4 py-2"><?php echo $record['created_at']; ?></td>
                        <td class="border border-gray-300 px-4 py-2">
                            <form method="post" action="query_record.php" class="space-y-2">
                                <input type="hidden" name="record_id" value="<?php echo $record['id']; ?>">
                                <input type="text" name="report_reason" placeholder="举报原因" required
                                    class="w-full p-2 border border-gray-300 rounded-md">
                                <input type="submit" value="举报"
                                    class="w-full bg-red-500 text-white py-2 rounded-md hover:bg-red-600 transition duration-300">
                            </form>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <?php endif; ?>

        <div class="mt-6">
            <a href="https://blogecho.zeimg.top"
                class="text-blue-600 hover:underline inline-block">返回首页</a>
        </div>

        <div class="text-center text-sm text-gray-500 mt-6">
            &copy; 2024 BlogEcho。
        </div>
    </div>

</body>

</html>

<?php
$conn->close();
?>
