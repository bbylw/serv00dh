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

    nav a:hover,
    nav a.active {
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
            targetElement.scrollIntoView({
              behavior: 'smooth'
            });

            var newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname + '#' + targetId;
            window.history.pushState({
              path: newUrl
            }, '', newUrl);
          }
        });
      });

      function handleHashChange() {
        var hash = window.location.hash;
        if (hash) {
          var targetElement = document.getElementById(hash.substring(1));
          if (targetElement) {
            targetElement.scrollIntoView({
              behavior: 'smooth'
            });
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
              targetElement.scrollIntoView({
                behavior: 'smooth'
              });
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

      // 从数据库获取链接
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

    function get_base_url()
    {
      $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http";
      $host = $_SERVER['HTTP_HOST'];
      return $protocol . "://" . $host;
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