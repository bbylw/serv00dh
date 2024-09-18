<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <base href="<?php echo $base_url; ?>/">
  <title>WebNav Hub</title>
  <style>
    :root {
      --primary-color: #ff9000;
      --bg-color: #0d0d0d;
      --card-bg-color: #1a1a1a;
      --text-color: #fff;
    }
    
    html {
      font-size: 16px;
    }
    
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background-color: var(--bg-color);
      color: var(--text-color);
      line-height: 1.6;
    }
    
    header {
      background-color: #000;
      padding: 1rem;
      text-align: center;
    }
    
    header h1 {
      font-size: 2rem;
      font-weight: bold;
      color: var(--primary-color);
      margin: 0;
      text-transform: uppercase;
    }
    
    nav {
      background-color: var(--card-bg-color);
      padding: 0.5rem;
      position: sticky;
      top: 0;
      z-index: 1000;
    }
    
    nav ul {
      list-style-type: none;
      padding: 0;
      margin: 0;
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
    }
    
    nav li {
      margin: 0.3rem;
    }
    
    nav a {
      color: var(--text-color);
      text-decoration: none;
      font-size: 1rem;
      font-weight: bold;
      padding: 0.5rem 0.8rem;
      border-radius: 1.25rem;
      transition: background-color 0.3s, color 0.3s;
    }
    
    nav a:hover, nav a.active {
      background-color: var(--primary-color);
      color: #000;
    }
    
    main {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1rem;
    }
    
    .category-title {
      font-size: 1.5rem;
      font-weight: bold;
      margin: 2rem 0 1rem;
      color: var(--primary-color);
      border-left: 4px solid var(--primary-color);
      padding-left: 0.6rem;
      text-transform: uppercase;
    }
    
    .link-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(8rem, 1fr));
      gap: 1rem;
      margin-bottom: 2rem;
    }
    
    .link-card {
      background-color: var(--card-bg-color);
      border-radius: 5px;
      padding: 1rem;
      text-align: center;
      transition: all 0.3s ease;
      cursor: pointer;
    }
    
    .link-card:hover {
      background-color: #2a2a2a;
      transform: translateY(-5px);
    }
    
    .link-card i {
      font-size: 1.75rem;
      margin-bottom: 0.5rem;
      color: var(--primary-color);
    }
    
    .link-card h3 {
      font-size: 0.9rem;
      margin-bottom: 0.3rem;
      color: var(--text-color);
    }
    
    footer {
      background-color: #000;
      color: #ccc;
      text-align: center;
      padding: 1rem;
      font-size: 0.75rem;
    }
    
    footer nav {
      margin-top: 0.6rem;
      background-color: transparent;
    }
    
    footer nav a {
      color: #ccc;
      margin: 0 0.6rem;
      font-size: 0.75rem;
    }
    
    @media (max-width: 768px) {
      html {
        font-size: 14px;
      }
      
      .link-grid {
        grid-template-columns: repeat(auto-fill, minmax(7rem, 1fr));
      }
      
      nav {
        padding: 0.3rem;
      }
      
      nav a {
        padding: 0.4rem 0.6rem;
      }
    }
    
    @media (max-width: 480px) {
      html {
        font-size: 12px;
      }
      
      .link-grid {
        grid-template-columns: repeat(auto-fill, minmax(6rem, 1fr));
      }
      
      header h1 {
        font-size: 1.8rem;
      }
    }
  </style>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      var navLinks = document.querySelectorAll('nav a');

      navLinks.forEach(function(link) {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          navLinks.forEach(l => l.classList.remove('active'));
          this.classList.add('active');
          var targetId = this.getAttribute('href').split('#')[1];
          var targetElement = document.getElementById(targetId);
          if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });

            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '#' + targetId;
            window.history.pushState({path: newUrl}, '', newUrl);
          }
        });
      });

      function handleHashChange() {
        var hash = window.location.hash;
        if (hash) {
          var targetElement = document.getElementById(hash.substring(1));
          if (targetElement) {
            targetElement.scrollIntoView({ behavior: 'smooth' });
            var activeLink = document.querySelector('nav a[href="' + hash + '"]');
            if (activeLink) {
              navLinks.forEach(l => l.classList.remove('active'));
              activeLink.classList.add('active');
            }
          }
        }
      }

      window.addEventListener('hashchange', handleHashChange);
      handleHashChange();

      var linkCards = document.querySelectorAll('.link-card');
      linkCards.forEach(function(card) {
        card.addEventListener('click', function() {
          window.open(this.getAttribute('href'), '_blank');
        });
      });

      // 添加显示/隐藏内容的功能
      var footerLinks = document.querySelectorAll('footer nav a');
      footerLinks.forEach(function(link) {
        if (link.getAttribute('href').startsWith('#')) {
          link.addEventListener('click', function(e) {
            e.preventDefault();
            var targetId = this.getAttribute('href').substring(1);
            var targetElement = document.getElementById(targetId);
            if (targetElement) {
              // 隐藏所有内容
              document.querySelectorAll('#privacy, #terms, #contact').forEach(function(el) {
                el.style.display = 'none';
              });
              // 显示目标内容
              targetElement.style.display = 'block';
              // 滚动到内容
              targetElement.scrollIntoView({behavior: 'smooth'});
            }
          });
        }
      });

      // 确保管理链接正常工作
      var adminLink = document.querySelector('footer nav a[href$="/admin.php"]');
      if (adminLink) {
        adminLink.addEventListener('click', function(e) {
          e.preventDefault();
          window.location.href = this.href;
        });
      }
    });
  </script>
</head>
<body>
  <header>
    <h1>WebNav Hub</h1>
  </header>
  <nav>
    <ul>
      <li><a href="#ai-search">Ai搜索</a></li>
      <li><a href="#social">社交媒体</a></li>
      <li><a href="#tools">实用工具</a></li>
      <li><a href="#tech-news">科技资讯</a></li>
      <li><a href="#cloud-storage">云存储</a></li>
      <li><a href="#email">电子邮箱</a></li>
    </ul>
  </nav>
  <main>
    <?php
    require_once 'db_connect.php';
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("连接失败: " . $conn->connect_error);
    }

    $categories = [
        'ai-search' => 'Ai搜索',
        'social' => '社交媒体',
        'tools' => '实用工具',
        'tech-news' => '科技资讯',
        'cloud-storage' => '云存储',
        'email' => '电子邮箱'
    ];

    foreach ($categories as $slug => $name) {
        echo "<h2 class='category-title' id='$slug'>$name</h2>";
        echo "<section class='link-grid'>";

        // 获取原有的链接
        $original_links = get_original_links($slug);
        foreach ($original_links as $link) {
            echo $link;
        }

        // 从数据库获取额外的链接
        $sql = "SELECT * FROM links WHERE category_id = (SELECT id FROM categories WHERE slug = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $slug);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            echo "<div class='link-card' href='" . htmlspecialchars($row['url']) . "'>";
            echo "<i class='" . htmlspecialchars($row['icon']) . "'></i>";
            echo "<h3>" . htmlspecialchars($row['title']) . "</h3>";
            echo "</div>";
        }

        echo "</section>";
    }

    $conn->close();

    function get_base_url() {
        $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
        $host = $_SERVER['HTTP_HOST'];
        return $protocol . "://" . $host;
    }

    function get_original_links($category) {
        $base_url = get_base_url();
        // 这里返回原有的链接HTML
        $links = [];
        if ($category == 'ai-search') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.google.com'><i class='fab fa-google'></i><h3>Google</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.bing.com'><i class='fab fa-microsoft'></i><h3>Bing</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://websim.ai/'><i class='fas fa-search'></i><h3>websim</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://chatgpt.com/'><i class='fab fa-google'></i><h3>chatgpt</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.doubao.com/chat/'><i class='fas fa-paw'></i><h3>傻豆包</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://yuanbao.tencent.com/'><i class='fas fa-robot'></i><h3>傻元宝</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://poe.com/'><i class='fas fa-robot'></i><h3>poe</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://claude.ai/'><i class='fas fa-robot'></i><h3>claude</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://chandler.bet/'><i class='fas fa-robot'></i><h3>ChandlerAi</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://mistral.ai/'><i class='fas fa-brain'></i><h3>mistral</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=http://u.90tsg.com/'><i class='fas fa-clinic-medical'></i><h3>循证医学UTD</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.medscape.com/'><i class='fas fa-stethoscope'></i><h3>medscape</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://chat.oaichat.cc/'><i class='fab fa-rocketchat'></i><h3>免费oaichat</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://app.leonardo.ai/'><i class='far fa-images'></i><h3>leonardo.ai绘图</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://huggingface.co/'><i class='fas fa-meh-rolling-eyes'></i><h3>huggingface</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://lmarena.ai/'><i class='fas fa-robot'></i><h3>lmarena</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://kelaode.ai/'><i class='fas fa-robot'></i><h3>kelaode</h3></div>";
        } elseif ($category == 'social') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.facebook.com'><i class='fab fa-facebook'></i><h3>Facebook</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://twitter.com'><i class='fab fa-twitter'></i><h3>Twitter</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.instagram.com'><i class='fab fa-instagram'></i><h3>Instagram</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.linkedin.com'><i class='fab fa-linkedin'></i><h3>LinkedIn</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.tiktok.com'><i class='fab fa-tiktok'></i><h3>TikTok</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.reddit.com'><i class='fab fa-reddit'></i><h3>Reddit</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://github.com/'><i class='fab fa-github'></i><h3>GitHub</h3></div>";
        } elseif ($category == 'tools') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://translate.google.com'><i class='fas fa-language'></i><h3>Google翻译</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://d.186404.xyz/'><i class='fas fa-link'></i><h3>短链</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://dynv6.com/'><i class='fas fa-network-wired'></i><h3>dynv6</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://fast.com/'><i class='fas fa-tachometer-alt'></i><h3>网速测试</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.cloudns.net/'><i class='fas fa-cloud'></i><h3>Cloudns</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.cloudflare.com/zh-cn/'><i class='fas fa-shield-alt'></i><h3>Cloudflare</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://ygpy.net/'><i class='fas fa-user-friends'></i><h3>一个朋友</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://notebooklm.google/'><i class='fas fa-book'></i><h3>谷歌笔记</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://email.ml/'><i class='fas fa-envelope'></i><h3>临时邮箱</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.ahhhhfs.com/'><i class='fas fa-blog'></i><h3>A姐</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://ip.sb/'><i class='fas fa-map-marker-alt'></i><h3>IP查询</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://img.186404.xyz/'><i class='fas fa-image'></i><h3>图床</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.site.ac/'><i class='fas fa-exchange-alt'></i><h3>Site域名转发</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://zh.go-to-library.sk/'><i class='fas fa-book-reader'></i><h3>Z-Library</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://nic.us.kg/'><i class='fas fa-globe'></i><h3>us.kg域名</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.spaceship.com/zh/'><i class='fas fa-space-shuttle'></i><h3>Spaceship廉价域名</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://itsyebekhe.github.io/HiN-VPN/'><i class='fas fa-walking'></i><h3>HiN-VPN</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://fontawesome.com/'><i class='fas fa-icons'></i><h3>FontAwesome图标</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://scamalytics.com/'><i class='fas fa-icons'></i><h3>ip清洁度查询</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://test-ipv6.com/'><i class='fas fa-ethernet'></i><h3>test-ipv6</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://html.zone/ip'><i class='fab fa-sourcetree'></i><h3>zone/ip</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.lumiproxy.com/zh-hans/online-proxy/proxysite/'><i class='fas fa-unlock'></i><h3>免费网络代理</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://ipcheck.ing/'><i class='fas fa-map-marker-alt'></i><h3>ipcheck</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://console.cron-job.org/'><i class='fas fa-ethernet'></i><h3>定时任务cron-job</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://uptimerobot.com/'><i class='fas fa-map-marker-alt'></i><h3>uptimerobot</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://forwardemail.net/'><i class='fas fa-mail-bulk'></i><h3>forwardemail</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://improvmx.com/'><i class='fas fa-mail-bulk'></i><h3>improvmx</h3></div>";
        } elseif ($category == 'tech-news') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.techcrunch.com'><i class='fas fa-newspaper'></i><h3>TechCrunch</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.wired.com'><i class='fas fa-bolt'></i><h3>Wired</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.theverge.com'><i class='fas fa-laptop'></i><h3>The Verge</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://arstechnica.com'><i class='fas fa-rocket'></i><h3>Ars Technica</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.engadget.com'><i class='fas fa-mobile-alt'></i><h3>Engadget</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://techradar.com'><i class='fas fa-satellite'></i><h3>TechRadar</h3></div>";
        } elseif ($category == 'cloud-storage') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.dropbox.com'><i class='fas fa-cloud'></i><h3>Dropbox</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://drive.google.com'><i class='fab fa-google-drive'></i><h3>Google Drive</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://onedrive.live.com'><i class='fab fa-microsoft'></i><h3>OneDrive</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.box.com'><i class='fas fa-box'></i><h3>Box</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.mediafire.com'><i class='fas fa-file-alt'></i><h3>MediaFire</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://mega.nz'><i class='fas fa-cloud-upload-alt'></i><h3>MEGA</h3></div>";
        } elseif ($category == 'email') {
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://mail.google.com'><i class='fas fa-envelope'></i><h3>Gmail</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://outlook.live.com'><i class='fab fa-microsoft'></i><h3>Outlook</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://22.do/'><i class='fas fa-envelope-open'></i><h3>GMail临时邮箱</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.agogmail.com/'><i class='fas fa-envelope-square'></i><h3>临时gMail</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.protonmail.com'><i class='fas fa-shield-alt'></i><h3>ProtonMail</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://mail.qq.com'><i class='fab fa-qq'></i><h3>QQ邮箱</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.emailnator.com/'><i class='fas fa-at'></i><h3>临时G邮箱</h3></div>";
            $links[] = "<div class='link-card' href='{$base_url}/redirect.php?url=https://www.linshigmail.com/'><i class='fas fa-mail-bulk'></i><h3>临时谷歌邮箱</h3></div>";
        }
        return $links;
    }
    ?>
  </main>
  <footer>
    <p>&copy; 2023 WebNav Hub. 保留所有权利。</p>
    <nav>
      <a href="#privacy">隐私政策</a>
      <a href="#terms">使用条款</a>
      <a href="#contact">联系我们</a>
    </nav>
  </footer>

  <!-- 添加隐私政策、使用条款和联系我们的内容 -->
  <div id="privacy" style="display: none;">
    <h2>隐私政策</h2>
    <p>这里是隐私政策的内容。</p>
  </div>

  <div id="terms" style="display: none;">
    <h2>使用条款</h2>
    <p>这里是使用条款的内容。</p>
  </div>

  <div id="contact" style="display: none;">
    <h2>联系我们</h2>
    <p>这里是联系信息。</p>
  </div>
</body>
</html>