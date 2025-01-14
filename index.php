<!DOCTYPE html>
<html lang="zh">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>回响</title>
    <link rel="icon" type="image/png" href="/favicon.png">
    <link href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://lf3-cdn-tos.bytecdntp.com/cdn/expire-1-M/font-awesome/4.7.0/css/font-awesome.min.css">
      <script>
document.addEventListener('DOMContentLoaded', function () {
    const menuToggle = document.getElementById('menu-toggle');
    const sidebar = document.getElementById('sidebar');
    const closeMenu = document.getElementById('close-menu');

    menuToggle.addEventListener('click', () => {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
    });

    closeMenu.addEventListener('click', () => {
        sidebar.classList.remove('translate-x-0');
        sidebar.classList.add('-translate-x-full');
    });
});
    </script>

</head>

<body class="bg-gray-100 text-gray-900">

        <!-- 顶栏 -->
    <header class="bg-blue-600 text-white p-4">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                
                <h1 class="text-2xl font-semibold">回响-BlogEcho</h1>
            </div>

            <button class="lg:hidden text-white" id="menu-toggle">
                <i class="fa fa-bars"></i>
            </button>
        </div>
    </header>

    <!-- 侧边导航栏（从左侧展开） -->
    <div id="sidebar" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-50 transform -translate-x-full lg:hidden transition-transform duration-300">
        <div class="w-64 h-full bg-blue-600 text-white p-6">
            <div class="flex justify-end">
                <button id="close-menu" class="text-white">
                    <i class="fa fa-times text-2xl"></i>
                </button>
            </div>

            <nav class="mt-8 space-y-6">
                <a href="https://blogecho.zeimg.top/record_form.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-edit"></i>
                    <span>申请加入我们</span>
                </a>
                <a href="https://blogecho.zeimg.top/list.php" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-search"></i>
                    <span>查询回响号</span>
                </a>
                <a href="https://blogecho.zeimg.top/announcement.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-bullhorn"></i>
                    <span>站点公告</span>
                </a>
                <a href="https://blogecho.zeimg.top/admin_login.html" class="flex items-center space-x-3 hover:bg-yellow-500 px-4 py-2 rounded-md">
                    <i class="fa fa-shield"></i>
                    <span>管理员登录</span>
                </a>
                <a href="https://blogecho.zeimg.top/about.html" class="flex items-center space-x-3 hover:bg-green-500 px-4 py-2 rounded-md">
                    <i class="fa fa-info-circle"></i>
                    <span>关于本站点</span>
                </a>
            </nav>
        </div>
    </div>

    <div class="flex lg:flex-row flex-col">
        <!-- 侧边栏（桌面端显示） -->
        <div class="hidden lg:block w-64 bg-blue-600 text-white p-6">
            <nav class="mt-8 space-y-6">
                <a href="https://blogecho.zeimg.top/record_form.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-edit"></i>
                    <span>申请加入我们</span>
                </a>
                <a href="https://blogecho.zeimg.top/list.php" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-search"></i>
                    <span>查询回响号</span>
                </a>
                <a href="https://blogecho.zeimg.top/announcement.html" class="flex items-center space-x-3 hover:bg-blue-500 px-4 py-2 rounded-md">
                    <i class="fa fa-bullhorn"></i>
                    <span>站点公告</span>
                </a>
                <a href="https://blogecho.zeimg.top/admin_login.html" class="flex items-center space-x-3 hover:bg-yellow-500 px-4 py-2 rounded-md">
                    <i class="fa fa-shield"></i>
                    <span>管理员登录</span>
                </a>
                <a href="https://blogecho.zeimg.top/about.html" class="flex items-center space-x-3 hover:bg-green-500 px-4 py-2 rounded-md">
                    <i class="fa fa-info-circle"></i>
                    <span>关于本站点</span>
                </a>
            </nav>
        </div>
        </div>
    <!-- 页面内容 -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="bg-blue-100 text-green-800 p-4 my-4 rounded-md shadow-md">
            <i class="fa fa-user text-xl mr-2"></i><strong>项目简介: </strong>本项目为Rss综合聚集平台，您提交您网站和网站Rss链接之后，您的最新文章将会在本项目首页推荐，并获得一定流量。
        </div>

        <div class="bg-blue-100 text-blue-800 p-4 my-4 rounded-md shadow-md">
            <i class="fa fa-info text-xl mr-2"></i><strong>信息: </strong>为了防止恶意修改，如要修改或删除申请信息，请联系管理员。联系方式为：<a href="mailto:BlogEcho@outlook.com" class="underline">yshenze123@gmail.com</a>。
        </div>

        <div class="bg-yellow-100 text-yellow-800 p-4 my-4 rounded-md shadow-md">
            <i class="fa fa-bullhorn text-xl mr-2"></i><strong>公告: </strong>回响 - 公告 现已发布。<a href="https://blogecho.zeimg.top/announcement.html" class="underline">转到公告</a> 以了解详情。
        </div>
    </div>
    <!-- 文章列表 -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 my-6">
        <h1 class="text-2xl font-semibold">成员最新文章</h1>
        <hr/>
            <?php 
$apiUrl = 'http://127.0.0.1:5000/api';
$response = file_get_contents($apiUrl);
$data = json_decode($response, true);

if (empty($data['data'])) {
    echo "没有获取到RSS数据！";
    exit;
}

function clean_text($text) {
    if (empty($text)) {
        return '无描述';
    }
    $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
    $text = preg_replace(['/<br\s*\/?>/i', '/<\/p>\s*<p>/i'], "\n", $text);
    $text = strip_tags($text);
    $text = preg_replace("/\s+/", " ", $text);
    return trim($text);
}

function truncate_text($text, $max_length = 150) {
    if (mb_strlen($text, 'UTF-8') > $max_length) {
        return mb_substr($text, 0, $max_length, 'UTF-8') . '...';
    }
    return $text;
}

foreach ($data['data'] as $item): 
?>
    <div class="bg-white p-4 rounded-lg shadow-md hover:shadow-xl transition duration-300 max-w-sm overflow-hidden break-words">
        <h2 class="text-xl font-semibold"><?php echo htmlspecialchars($item['title'] ?? '无标题'); ?></h2>
        <p class="text-gray-600 mt-2">
            <?php 
            $description = clean_text($item['description'] ?? $item['summary'] ?? '无描述');
            echo truncate_text($description, 150); 
            ?>
        </p>
        
        <p class="text-sm text-gray-500 mt-2"><?php echo htmlspecialchars($item['pubDate'] ?? $item['updated'] ?? '无日期'); ?></p>
        <a href="<?php echo htmlspecialchars($item['link'] ?? '#'); ?>" target="_blank" class="mt-4 inline-block text-white bg-blue-500 hover:bg-blue-600 rounded-md py-2 px-4 transition duration-300">
            -阅读原文-
        </a>
    </div>
<?php 
endforeach;
?>
        </div>
    </div>

    <!-- 页脚 -->
    <div class="text-center text-sm text-gray-500 my-6">
        <p>&copy; 2024 BlogEcho。</p>
    </div>


</body>

</html>
