<?php
include 'db.php';
include 'navbar.php';

// Fetch all medication articles from the database
$articles = [];
$result = $conn->query("SELECT * FROM medication_articles ORDER BY created_at DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetCareHub - Medication Blog</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Pet-themed color scheme */
    :root {
      --primary: #3a7ca5;    /* Pet blue */
      --secondary: #5cb85c;  /* Healthy green */
      --accent: #f0ad4e;     /* Warning orange */
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --success: #5cb85c;
      --shadow: 0 5px 15px rgba(0,0,0,0.08);
      --transition: all 0.3s ease;
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      color: var(--dark);
      line-height: 1.6;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    h1, h2, h3, h4 {
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    
    h1 {
      font-size: 2.8rem;
    }
    
    h2 {
      font-size: 2.2rem;
      position: relative;
      padding-bottom: 15px;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
    }
    
    h3 {
      font-size: 1.6rem;
    }
    
    p {
      margin-bottom: 1rem;
      color: #555;
    }
    
    .text-center {
      text-align: center;
    }
    
    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    section {
      padding: 70px 0;
    }
    
    .section-light {
      background: white;
    }
    
    /* Hero Sections */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, #2c6185 100%);
      color: white;
      padding: 120px 0 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNpY2VPbk5Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxjaXJjbGUgY3g9IjIwIiBjeT0iMjAiIHI9IjEuNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg�IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
      opacity: 0.3;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: 0 auto;
    }
    
    .hero h1 {
      margin: 0 auto 20px;
    }
    
    .hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 30px;
      color: rgba(255,255,255,0.85);
    }
    
    /* Blog Styles */
    .blog-container {
      display: grid;
      grid-template-columns: 1fr 3fr;
      gap: 40px;
    }
    
    .categories-sidebar {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: var(--shadow);
      height: fit-content;
    }
    
    .categories-sidebar h3 {
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--light);
    }
    
    .category-list {
      list-style: none;
    }
    
    .category-list li {
      margin-bottom: 12px;
    }
    
    .category-list a {
      display: flex;
      justify-content: space-between;
      color: var(--dark);
      text-decoration: none;
      padding: 8px 12px;
      border-radius: 8px;
      transition: var(--transition);
    }
    
    .category-list a:hover {
      background-color: rgba(58, 124, 165, 0.1);
      color: var(--primary);
    }
    
    .category-list a.active {
      background-color: var(--primary);
      color: white;
    }
    
    .articles-container {
      display: grid;
      grid-template-columns: 1fr;
      gap: 30px;
    }
    
    .article-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-decoration: none;
      color: inherit;
      display: block;
    }
    
    .article-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      text-decoration: none;
      color: inherit;
    }
    
    .article-image {
      height: 200px;
      background-size: cover;
      background-position: center;
    }
    
    .article-content {
      padding: 25px;
    }
    
    .article-meta {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .article-meta span {
      margin-right: 15px;
      display: flex;
      align-items: center;
    }
    
    .article-meta i {
      margin-right: 5px;
    }
    
    .article-title {
      margin-bottom: 15px;
      font-size: 1.5rem;
      color: var(--dark);
    }
    
    .article-excerpt {
      margin-bottom: 20px;
      color: #555;
    }
    
    .read-more {
      display: inline-block;
      color: var(--primary);
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }
    
    .read-more:hover {
      color: #2c6185;
    }
    
    /* Search Box */
    .search-box {
      margin-bottom: 30px;
      position: relative;
    }
    
    .search-box input {
      width: 100%;
      padding: 12px 15px 12px 45px;
      border: 1px solid #ddd;
      border-radius: 30px;
      font-size: 1rem;
      transition: var(--transition);
    }
    
    .search-box input:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(58, 124, 165, 0.2);
    }
    
    .search-box i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    /* Featured Article */
    .featured-article {
      grid-column: 1 / -1;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 0;
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      text-decoration: none;
      color: inherit;
    }
    
    .featured-article:hover {
      text-decoration: none;
      color: inherit;
    }
    
    .featured-image {
      height: 100%;
      min-height: 300px;
      background-size: cover;
      background-position: center;
    }
    
    .featured-content {
      padding: 40px;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .featured-badge {
      display: inline-block;
      background: var(--accent);
      color: white;
      padding: 5px 12px;
      border-radius: 30px;
      font-size: 0.8rem;
      font-weight: 600;
      margin-bottom: 15px;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .blog-container {
        grid-template-columns: 1fr;
      }
      
      .categories-sidebar {
        order: 2;
      }
      
      .articles-container {
        order: 1;
      }
      
      .featured-article {
        grid-template-columns: 1fr;
      }
      
      .featured-image {
        min-height: 200px;
      }
    }
    
    @media (max-width: 768px) {
      h1 {
        font-size: 2.3rem;
      }
      
      h2 {
        font-size: 1.8rem;
      }
      
      section {
        padding: 50px 0;
      }
    }
  </style>
</head>
<body>
  <!-- Medication Blog Page -->
  <main id="medication-blog">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Pet Medication & Health Blog</h1>
        <p>Expert advice and solutions for common pet health problems</p>
      </div>
    </section>

    <!-- Blog Section -->
    <section class="section-light">
      <div class="container">
        <div class="blog-container">
          <!-- Categories Sidebar -->
          <div class="categories-sidebar">
            <h3>Categories</h3>
            <ul class="category-list">
              <li><a href="#" class="active">All Topics <span><?php echo count($articles); ?></span></a></li>
              <?php
              // Get categories and counts from articles
              $categories = [];
              foreach ($articles as $article) {
                  $cat = $article['category'];
                  if (!isset($categories[$cat])) {
                      $categories[$cat] = 0;
                  }
                  $categories[$cat]++;
              }
              
              foreach ($categories as $cat => $count) {
                  echo "<li><a href='#' data-category='$cat'>$cat <span>$count</span></a></li>";
              }
              ?>
            </ul>
            
            <div style="margin-top: 30px;">
              <h3>Emergency Guide</h3>
              <p style="font-size: 0.9rem;">If your pet is experiencing a medical emergency, contact your veterinarian immediately.</p>
              <div style="background: #fff4f4; padding: 15px; border-radius: 8px; margin-top: 15px;">
                <h4 style="font-size: 1rem; color: #dc3545;">Emergency Hotline</h4>
                <p style="font-size: 1.2rem; font-weight: 600; color: #dc3545;">(555) 123-HELP</p>
                <p style="font-size: 0.8rem;">24/7 Pet Poison Control</p>
              </div>
            </div>
          </div>
          
          <!-- Articles Container -->
          <div class="articles-container">
            <!-- Search Box -->
            <div class="search-box">
              <i class="fas fa-search"></i>
              <input type="text" placeholder="Search for pet health topics..." id="search-input">
            </div>
            
            <!-- Featured Article -->
            <?php if (!empty($articles)): ?>
              <?php $featured = $articles[0]; ?>
              <a href="article_details.php?id=<?php echo $featured['id']; ?>" class="featured-article">
                <div class="featured-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                  <?php if (!empty($featured['image_url'])): ?>
                    <img src="<?php echo $featured['image_url']; ?>" alt="<?php echo htmlspecialchars($featured['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                  <?php endif; ?>
                </div>
                <div class="featured-content">
                  <span class="featured-badge">Featured</span>
                  <h2 class="article-title"><?php echo htmlspecialchars($featured['title']); ?></h2>
                  <p class="article-excerpt"><?php echo htmlspecialchars($featured['excerpt']); ?></p>
                  <span class="read-more">Read Complete Guide</span>
                  <div class="article-meta">
                    <span><i class="far fa-clock"></i> <?php echo $featured['read_time']; ?> min read</span>
                    <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($featured['created_at'])); ?></span>
                  </div>
                </div>
              </a>
            <?php endif; ?>
            
            <!-- Article List -->
            <?php if (empty($articles)): ?>
              <!-- Sample articles if database is empty -->
              <a href="article_details.php?id=1" class="article-card">
                <div class="article-image" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);"></div>
                <div class="article-content">
                  <h3 class="article-title">How to Administer Medication to Your Pet</h3>
                  <p class="article-excerpt">Giving medication to pets can be challenging. Learn techniques for pills, liquids, and topical medications to make the process easier for you and your pet.</p>
                  <span class="read-more">Read More</span>
                  <div class="article-meta">
                    <span><i class="far fa-clock"></i> 7 min read</span>
                    <span><i class="far fa-calendar"></i> April 10, 2023</span>
                  </div>
                </div>
              </a>
              
              <a href="article_details.php?id=2" class="article-card">
                <div class="article-image" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);"></div>
                <div class="article-content">
                  <h3 class="article-title">Recognizing Signs of Pain in Cats</h3>
                  <p class="article-excerpt">Cats often hide their pain. Learn to recognize the subtle signs that your cat might be suffering and what you can do to help.</p>
                  <span class="read-more">Read More</span>
                  <div class="article-meta">
                    <span><i class="far fa-clock"></i> 8 min read</span>
                    <span><i class="far fa-calendar"></i> April 5, 2023</span>
                  </div>
                </div>
              </a>
              
              <a href="article_details.php?id=3" class="article-card">
                <div class="article-image" style="background: linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%);"></div>
                <div class="article-content">
                  <h3 class="article-title">Heartworm Prevention: What Every Pet Owner Should Know</h3>
                  <p class="article-excerpt">Heartworm disease is serious but preventable. Learn about transmission, prevention options, and why year-round protection is essential.</p>
                  <span class="read-more">Read More</span>
                  <div class="article-meta">
                    <span><i class="far fa-clock"></i> 12 min read</span>
                    <span><i class="far fa-calendar"></i> March 28, 2023</span>
                  </div>
                </div>
              </a>
            <?php else: ?>
              <!-- Dynamic articles from database (skip the first one as it's featured) -->
              <?php for ($i = 1; $i < count($articles); $i++): ?>
                <?php $article = $articles[$i]; ?>
                <a href="article_details.php?id=<?php echo $article['id']; ?>" class="article-card">
                  <div class="article-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php if (!empty($article['image_url'])): ?>
                      <img src="<?php echo $article['image_url']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <div class="article-content">
                    <h3 class="article-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                    <p class="article-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                    <span class="read-more">Read More</span>
                    <div class="article-meta">
                      <span><i class="far fa-clock"></i> <?php echo $article['read_time']; ?> min read</span>
                      <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($article['created_at'])); ?></span>
                    </div>
                  </div>
                </a>
              <?php endfor; ?>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    // Simple filtering functionality for categories
    document.addEventListener('DOMContentLoaded', function() {
      const categoryLinks = document.querySelectorAll('.category-list a');
      const articles = document.querySelectorAll('.article-card, .featured-article');
      const searchInput = document.getElementById('search-input');
      
      categoryLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          
          // Remove active class from all links
          categoryLinks.forEach(l => l.classList.remove('active'));
          
          // Add active class to clicked link
          this.classList.add('active');
          
          const category = this.getAttribute('data-category');
          
          // Filter articles by category
          articles.forEach(article => {
            if (category === null || category === 'all') {
              article.style.display = 'block';
            } else {
              const articleCategory = article.getAttribute('data-category');
              if (articleCategory === category) {
                article.style.display = 'block';
              } else {
                article.style.display = 'none';
              }
            }
          });
        });
      });
      
      // Search functionality
      searchInput.addEventListener('keyup', function(e) {
        const searchTerm = this.value.toLowerCase();
        
        articles.forEach(article => {
          const title = article.querySelector('.article-title').textContent.toLowerCase();
          const excerpt = article.querySelector('.article-excerpt').textContent.toLowerCase();
          
          if (title.includes(searchTerm) || excerpt.includes(searchTerm)) {
            article.style.display = 'block';
          } else {
            article.style.display = 'none';
          }
        });
      });
    });
  </script>
</body>
</html>