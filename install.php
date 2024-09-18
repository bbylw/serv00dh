<?php
session_start();
$step = isset($_GET['step']) ? $_GET['step'] : 1;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if ($step == 1) {
        $_SESSION['db_host'] = $_POST['db_host'];
        $_SESSION['db_user'] = $_POST['db_user'];
        $_SESSION['db_pass'] = $_POST['db_pass'];
        $_SESSION['db_name'] = $_POST['db_name'];
        header('Location: install.php?step=2');
        exit;
    } elseif ($step == 2) {
        $_SESSION['admin_username'] = $_POST['admin_username'];
        $_SESSION['admin_password'] = $_POST['admin_password'];
        header('Location: install.php?step=3');
        exit;
    } elseif ($step == 3) {
        // 创建数据库连接
        $conn = new mysqli($_SESSION['db_host'], $_SESSION['db_user'], $_SESSION['db_pass'], $_SESSION['db_name']);
        if ($conn->connect_error) {
            die("连接失败: " . $conn->connect_error);
        }

        // 创建表
        $sql = "CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            slug VARCHAR(50) NOT NULL UNIQUE
        )";
        $conn->query($sql);

        $sql = "CREATE TABLE IF NOT EXISTS links (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_id INT,
            title VARCHAR(100) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icon VARCHAR(50) NOT NULL,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        )";
        $conn->query($sql);

        // 创建管理员用户表
        $sql = "CREATE TABLE IF NOT EXISTS admin_users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
        )";
        $conn->query($sql);

        // 添加主页已有的分类
        $categories = [
            ['Ai搜索', 'ai-search'],
            ['社交媒体', 'social'],
            ['实用工具', 'tools'],
            ['科技资讯', 'tech-news'],
            ['云存储', 'cloud-storage'],
            ['电子邮箱', 'email']
        ];

        $stmt = $conn->prepare("INSERT IGNORE INTO categories (name, slug) VALUES (?, ?)");
        foreach ($categories as $category) {
            $stmt->bind_param("ss", $category[0], $category[1]);
            $stmt->execute();
        }

        // 添加主页已有的链接
        $links = [
            ['ai-search', 'Google', 'https://www.google.com', 'fab fa-google'],
            ['ai-search', 'Bing', 'https://www.bing.com', 'fab fa-microsoft'],
            ['ai-search', 'websim', 'https://websim.ai/', 'fas fa-search'],
            ['ai-search', 'chatgpt', 'https://chatgpt.com/', 'fab fa-google'],
            ['ai-search', '傻豆包', 'https://www.doubao.com/chat/', 'fas fa-paw'],
            ['ai-search', '傻元宝', 'https://yuanbao.tencent.com/', 'fas fa-robot'],
            ['ai-search', 'poe', 'https://poe.com/', 'fas fa-robot'],
            ['ai-search', 'claude', 'https://claude.ai/', 'fas fa-robot'],
            ['ai-search', 'ChandlerAi', 'https://chandler.bet/', 'fas fa-robot'],
            ['ai-search', 'mistral', 'https://mistral.ai/', 'fas fa-brain'],
            ['ai-search', '循证医学UTD', 'http://u.90tsg.com/', 'fas fa-clinic-medical'],
            ['ai-search', 'medscape', 'https://www.medscape.com/', 'fas fa-stethoscope'],
            ['ai-search', '免费oaichat', 'https://chat.oaichat.cc/', 'fab fa-rocketchat'],
            ['ai-search', 'leonardo.ai绘图', 'https://app.leonardo.ai/', 'far fa-images'],
            ['ai-search', 'huggingface', 'https://huggingface.co/', 'fas fa-meh-rolling-eyes'],
            ['ai-search', 'lmarena', 'https://lmarena.ai/', 'fas fa-robot'],
            ['ai-search', 'kelaode', 'https://kelaode.ai/', 'fas fa-robot'],
            ['social', 'Facebook', 'https://www.facebook.com', 'fab fa-facebook'],
            ['social', 'Twitter', 'https://twitter.com', 'fab fa-twitter'],
            ['social', 'Instagram', 'https://www.instagram.com', 'fab fa-instagram'],
            ['social', 'LinkedIn', 'https://www.linkedin.com', 'fab fa-linkedin'],
            ['social', 'TikTok', 'https://www.tiktok.com', 'fab fa-tiktok'],
            ['social', 'Reddit', 'https://www.reddit.com', 'fab fa-reddit'],
            ['social', 'GitHub', 'https://github.com/', 'fab fa-github'],
            ['tools', 'Google翻译', 'https://translate.google.com', 'fas fa-language'],
            ['tools', '短链', 'https://d.186404.xyz/', 'fas fa-link'],
            ['tools', 'dynv6', 'https://dynv6.com/', 'fas fa-network-wired'],
            ['tools', '网速测试', 'https://fast.com/', 'fas fa-tachometer-alt'],
            ['tools', 'Cloudns', 'https://www.cloudns.net/', 'fas fa-cloud'],
            ['tools', 'Cloudflare', 'https://www.cloudflare.com/zh-cn/', 'fas fa-shield-alt'],
            ['tools', '一个朋友', 'https://ygpy.net/', 'fas fa-user-friends'],
            ['tools', '谷歌笔记', 'https://notebooklm.google/', 'fas fa-book'],
            ['tools', '临时邮箱', 'https://email.ml/', 'fas fa-envelope'],
            ['tools', 'A姐', 'https://www.ahhhhfs.com/', 'fas fa-blog'],
            ['tools', 'IP查询', 'https://ip.sb/', 'fas fa-map-marker-alt'],
            ['tools', '图床', 'https://img.186404.xyz/', 'fas fa-image'],
            ['tools', 'Site域名转发', 'https://www.site.ac/', 'fas fa-exchange-alt'],
            ['tools', 'Z-Library', 'https://zh.go-to-library.sk/', 'fas fa-book-reader'],
            ['tools', 'us.kg域名', 'https://nic.us.kg/', 'fas fa-globe'],
            ['tools', 'Spaceship廉价域名', 'https://www.spaceship.com/zh/', 'fas fa-space-shuttle'],
            ['tools', 'HiN-VPN', 'https://itsyebekhe.github.io/HiN-VPN/', 'fas fa-walking'],
            ['tools', 'FontAwesome图标', 'https://fontawesome.com/', 'fas fa-icons'],
            ['tools', 'ip清洁度查询', 'https://scamalytics.com/', 'fas fa-icons'],
            ['tools', 'test-ipv6', 'https://test-ipv6.com/', 'fas fa-ethernet'],
            ['tools', 'zone/ip', 'https://html.zone/ip', 'fab fa-sourcetree'],
            ['tools', '免费网络代理', 'https://www.lumiproxy.com/zh-hans/online-proxy/proxysite/', 'fas fa-unlock'],
            ['tools', 'ipcheck', 'https://ipcheck.ing/', 'fas fa-map-marker-alt'],
            ['tools', '定时任务cron-job', 'https://console.cron-job.org/', 'fas fa-ethernet'],
            ['tools', 'uptimerobot', 'https://uptimerobot.com/', 'fas fa-map-marker-alt'],
            ['tools', 'forwardemail', 'https://forwardemail.net/', 'fas fa-mail-bulk'],
            ['tools', 'improvmx', 'https://improvmx.com/', 'fas fa-mail-bulk'],
            ['tech-news', 'TechCrunch', 'https://www.techcrunch.com', 'fas fa-newspaper'],
            ['tech-news', 'Wired', 'https://www.wired.com', 'fas fa-bolt'],
            ['tech-news', 'The Verge', 'https://www.theverge.com', 'fas fa-laptop'],
            ['tech-news', 'Ars Technica', 'https://arstechnica.com', 'fas fa-rocket'],
            ['tech-news', 'Engadget', 'https://www.engadget.com', 'fas fa-mobile-alt'],
            ['tech-news', 'TechRadar', 'https://techradar.com', 'fas fa-satellite'],
            ['cloud-storage', 'Dropbox', 'https://www.dropbox.com', 'fas fa-cloud'],
            ['cloud-storage', 'Google Drive', 'https://drive.google.com', 'fab fa-google-drive'],
            ['cloud-storage', 'OneDrive', 'https://onedrive.live.com', 'fab fa-microsoft'],
            ['cloud-storage', 'Box', 'https://www.box.com', 'fas fa-box'],
            ['cloud-storage', 'MediaFire', 'https://www.mediafire.com', 'fas fa-file-alt'],
            ['cloud-storage', 'MEGA', 'https://mega.nz', 'fas fa-cloud-upload-alt'],
            ['email', 'Gmail', 'https://mail.google.com', 'fas fa-envelope'],
            ['email', 'Outlook', 'https://outlook.live.com', 'fab fa-microsoft'],
            ['email', 'GMail临时邮箱', 'https://22.do/', 'fas fa-envelope-open'],
            ['email', '临时gMail', 'https://www.agogmail.com/', 'fas fa-envelope-square'],
            ['email', 'ProtonMail', 'https://www.protonmail.com', 'fas fa-shield-alt'],
            ['email', 'QQ邮箱', 'https://mail.qq.com', 'fab fa-qq'],
            ['email', '临时G邮箱', 'https://www.emailnator.com/', 'fas fa-at'],
        ];

        $stmt = $conn->prepare("INSERT IGNORE INTO links (category_id, title, url, icon) VALUES ((SELECT id FROM categories WHERE slug = ?), ?, ?, ?)");
        foreach ($links as $link) {
            $stmt->bind_param("ssss", $link[0], $link[1], $link[2], $link[3]);
            $stmt->execute();
        }

        // 添加管理员用户
        $admin_username = $_SESSION['admin_username'];
        $admin_password = password_hash($_SESSION['admin_password'], PASSWORD_DEFAULT);
        $sql = "INSERT INTO admin_users (username, password) VALUES (?, ?) ON DUPLICATE KEY UPDATE password = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $admin_username, $admin_password, $admin_password);
        $stmt->execute();

        // 创建配置文件
        $config = "<?php\n";
        $config .= "\$servername = '{$_SESSION['db_host']}';\n";
        $config .= "\$username = '{$_SESSION['db_user']}';\n";
        $config .= "\$password = '{$_SESSION['db_pass']}';\n";
        $config .= "\$dbname = '{$_SESSION['db_name']}';\n";
        file_put_contents('db_config.php', $config);

        header('Location: install.php?step=4');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>WebNav Hub 安装</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        h1 { color: #333; }
        form { background: #f4f4f4; padding: 20px; border-radius: 5px; }
        label { display: block; margin-bottom: 5px; }
        input[type="text"], input[type="password"] { width: 100%; padding: 8px; margin-bottom: 10px; }
        input[type="submit"] { background: #333; color: #fff; padding: 10px 15px; border: none; cursor: pointer; }
        input[type="submit"]:hover { background: #555; }
    </style>
</head>
<body>
    <div class="container">
        <h1>WebNav Hub 安装</h1>
        <?php if ($step == 1): ?>
            <form method="post">
                <h2>步骤 1: 数据库配置</h2>
                <label for="db_host">数据库主机:</label>
                <input type="text" id="db_host" name="db_host" value="localhost" required>
                
                <label for="db_user">数据库用户名:</label>
                <input type="text" id="db_user" name="db_user" required>
                
                <label for="db_pass">数据库密码:</label>
                <input type="password" id="db_pass" name="db_pass" required>
                
                <label for="db_name">数据库名称:</label>
                <input type="text" id="db_name" name="db_name" required>
                
                <input type="submit" value="下一步">
            </form>
        <?php elseif ($step == 2): ?>
            <form method="post">
                <h2>步骤 2: 设置管理员账号</h2>
                <label for="admin_username">管理员用户名:</label>
                <input type="text" id="admin_username" name="admin_username" required>
                
                <label for="admin_password">管理员密码:</label>
                <input type="password" id="admin_password" name="admin_password" required>
                
                <input type="submit" value="下一步">
            </form>
        <?php elseif ($step == 3): ?>
            <form method="post">
                <h2>步骤 3: 创建表和初始数据</h2>
                <p>点击下面的按钮创建必要的表和初始数据。</p>
                <input type="submit" value="完成安装">
            </form>
        <?php elseif ($step == 4): ?>
            <h2>安装完成</h2>
            <p>WebNav Hub 已成功安装。您现在可以 <a href="index.php">访问您的网站</a> 或 <a href="login.php">登录管理界面</a>。</p>
        <?php endif; ?>
    </div>
</body>
</html>