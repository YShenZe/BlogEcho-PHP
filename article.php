<?php
$apiUrl = 'http://127.0.0.1:5000/api';
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

// 检查数据是否存在
if (empty($data['data'])) {
    echo "没有获取到RSS数据！";
    exit;
}

// 定义字数限制
function truncate_text($text, $max_length = 100) {
    if (mb_strlen($text, 'UTF-8') > $max_length) {
        return mb_substr($text, 0, $max_length, 'UTF-8') . '...';
    }
    return $text;
}

// 修订后的 clean_text 函数
function clean_text($text) {
    // 解码所有 HTML 实体
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    // 移除 HTML 标签
    $text = strip_tags($text);
    // 替换换行符为一个空格
    $text = preg_replace("/\r\n|\r|\n/", " ", $text);
    // 转义特殊字符
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
    return $text;
}
?>
<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回响</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body class="bg-gray-100 text-gray-900">

    <div class="max-w-7xl mx-auto px-4 py-6">
        <h1 class="text-3xl font-semibold text-center">回响</h1>
        <p class="text-lg text-center mt-2">欢迎使用回响！念念不忘必有回响。</p>

        <!-- 文章列表 -->
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-6">

            <?php 
            // 遍历 data 数组中的所有键，包括0和其他数字键
            foreach ($data['data'] as $key => $items): 
                foreach ($items as $item): 
            ?>
                <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition duration-300 max-w-sm overflow-hidden break-words">

                    <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($item['title']); ?></h2>

                    <!-- 处理描述 -->
                    <p class="text-gray-600 mt-2">
                        <?php 
                        $description = clean_text($item['description'] ?? '无描述');
                        echo truncate_text($description, 150); 
                        ?>
                    </p>

                    <!-- 显示字数 -->
                    <p class="text-sm text-gray-500 mt-2">
                        字数: <?php echo mb_strlen($item['description'] ?? '无描述', 'UTF-8'); ?>
                    </p>

                    <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($item['pubDate']); ?></p>

                    <a href="<?php echo htmlspecialchars($item['link']); ?>" target="_blank" class="mt-4 inline-block text-white bg-blue-500 hover:bg-blue-600 rounded-md py-2 px-4 transition duration-300">
                        阅读原文
                    </a>

                </div>
            <?php 
                endforeach;
            endforeach;
            ?>

        </div>

        <!-- 其他内容 -->
        <div class="text-center text-sm text-gray-500 my-6">
            <p>&copy; 2024 BlogEcho。</p>
        </div>

    </div>

</body>

</html>
