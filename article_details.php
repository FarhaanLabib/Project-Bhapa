<?php
include 'db.php';
include 'navbar.php';

// Get article ID from URL
$article_id = $_GET['id'] ?? 0;

// Fetch article from database
$article = null;
$related_articles = [];
if ($article_id) {
    $stmt = $conn->prepare("SELECT * FROM medication_articles WHERE id = ?");
    $stmt->bind_param("i", $article_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $article = $result->fetch_assoc();
    $stmt->close();
    
    // Fetch related articles (same category)
    if ($article) {
        $category = $article['category'];
        $stmt = $conn->prepare("SELECT * FROM medication_articles WHERE category = ? AND id != ? ORDER BY created_at DESC LIMIT 3");
        $stmt->bind_param("si", $category, $article_id);
        $stmt->execute();
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            $related_articles[] = $row;
        }
        $stmt->close();
    }
}

// Close database connection
$conn->close();

// If article not found, redirect to blog page
if (!$article) {
    header("Location: medication_blog.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title><?php echo htmlspecialchars($article['title']); ?> - PetCareHub</title>
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
    
    /* Article Hero Section */
    .article-hero {
      background: linear-gradient(135deg, var(--primary) 0%, #2c6185 100%);
      color: white;
      padding: 100px 0 60px;
      position: relative;
      overflow: hidden;
    }
    
    .article-hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxjaXJjbGUgY3g9IjIwIiBjeT0iMjAiIHI9IjEuNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg�IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
      opacity: 0.3;
    }
    
    .article-hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: 0 auto;
      text-align: center;
    }
    
    .article-hero h1 {
      margin: 0 auto 20px;
    }
    
    .article-hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 30px;
      color: rgba(255,255,255,0.85);
    }
    
    /* Article Content Styles */
    .article-container {
      display: grid;
      grid-template-columns: 1fr 300px;
      gap: 40px;
      margin: -40px auto 0;
      position: relative;
    }
    
    .article-content {
      background: white;
      border-radius: 12px;
      padding: 40px;
      box-shadow: var(--shadow);
    }
    
    .article-meta {
      display: flex;
      align-items: center;
      margin-bottom: 30px;
      font-size: 0.9rem;
      color: var(--gray);
      flex-wrap: wrap;
    }
    
    .article-meta span {
      margin-right: 20px;
      display: flex;
      align-items: center;
    }
    
    .article-meta i {
      margin-right: 8px;
    }
    
    .article-image {
      width: 100%;
      border-radius: 12px;
      margin-bottom: 30px;
      box-shadow: var(--shadow);
    }
    
    .article-body {
      font-size: 1.1rem;
      line-height: 1.8;
    }
    
    .article-body h2 {
      margin-top: 40px;
      color: var(--primary);
    }
    
    .article-body h3 {
      margin-top: 30px;
      color: var(--secondary);
    }
    
    .article-body p {
      margin-bottom: 1.5rem;
    }
    
    .article-body ul, .article-body ol {
      margin-bottom: 1.5rem;
      padding-left: 1.5rem;
    }
    
    .article-body li {
      margin-bottom: 0.5rem;
    }
    
    .article-body blockquote {
      border-left: 4px solid var(--accent);
      padding-left: 20px;
      margin: 30px 0;
      font-style: italic;
      color: #666;
    }
    
    /* Sidebar Styles */
    .article-sidebar {
      position: sticky;
      top: 20px;
      height: fit-content;
    }
    
    .sidebar-widget {
      background: white;
      border-radius: 12px;
      padding: 25px;
      box-shadow: var(--shadow);
      margin-bottom: 25px;
    }
    
    .sidebar-widget h3 {
      margin-bottom: 20px;
      padding-bottom: 10px;
      border-bottom: 2px solid var(--light);
    }
    
    .author-info {
      display: flex;
      align-items: center;
      margin-bottom: 20px;
    }
    
    .author-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
      margin-right: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.5rem;
    }
    
    .share-buttons {
      display: flex;
      gap: 10px;
      margin-top: 20px;
    }
    
    .share-button {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: var(--light);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--dark);
      text-decoration: none;
      transition: var(--transition);
    }
    
    .share-button:hover {
      background: var(--primary);
      color: white;
    }
    
    /* Related Articles */
    .related-articles {
      margin-top: 60px;
    }
    
    .related-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 25px;
      margin-top: 30px;
    }
    
    .related-article {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-decoration: none;
      color: inherit;
      display: block;
    }
    
    .related-article:hover {
      transform: translateY(-5px);
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      text-decoration: none;
      color: inherit;
    }
    
    .related-image {
      height: 160px;
      background-size: cover;
      background-position: center;
    }
    
    .related-content {
      padding: 20px;
    }
    
    .related-title {
      font-size: 1.2rem;
      margin-bottom: 10px;
      color: var(--dark);
    }
    
    /* Back Button */
    .back-button {
      display: inline-flex;
      align-items: center;
      color: white;
      text-decoration: none;
      margin-bottom: 20px;
      font-weight: 600;
      transition: var(--transition);
    }
    
    .back-button:hover {
      color: rgba(255,255,255,0.8);
      text-decoration: none;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .article-container {
        grid-template-columns: 1fr;
      }
      
      .article-sidebar {
        order: 2;
      }
      
      .article-content {
        order: 1;
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
      
      .article-content {
        padding: 25px;
      }
      
      .article-meta {
        flex-direction: column;
        align-items: flex-start;
      }
      
      .article-meta span {
        margin-bottom: 10px;
      }
      
      .related-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  <!-- Article Details Page -->
  <main id="article-details">
    <!-- Article Hero Section -->
    <section class="article-hero">
      <div class="container">
        <a href="medication_blog.php" class="back-button">
          <i class="fas fa-arrow-left"></i> Back to Blog
        </a>
        <div class="article-hero-content">
          <h1><?php echo htmlspecialchars($article['title']); ?></h1>
          <p><?php echo htmlspecialchars($article['excerpt']); ?></p>
        </div>
      </div>
    </section>

    <!-- Article Content Section -->
    <section class="section-light" style="padding-top: 40px;">
      <div class="container">
        <div class="article-container">
          <!-- Main Article Content -->
          <div class="article-content">
            <div class="article-meta">
              <span><i class="far fa-user"></i> By Dr. Emily Rodriguez</span>
              <span><i class="far fa-clock"></i> <?php echo $article['read_time']; ?> min read</span>
              <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($article['created_at'])); ?></span>
              <span><i class="fas fa-tag"></i> <?php echo htmlspecialchars($article['category']); ?></span>
            </div>
            
            <?php if (!empty($article['image_url'])): ?>
              <img src="<?php echo $article['image_url']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" class="article-image">
            <?php else: ?>
              <div class="article-image" style="height: 400px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem;">
                <i class="fas fa-paw"></i>
              </div>
            <?php endif; ?>
            
            <div class="article-body">
              <?php 
              // Display the article content (assuming it's stored as HTML)
              echo $article['content']; 
              
              // If no content in database, show sample content
              if (empty($article['content'])): ?>
                <h2>Understanding Pet Medications</h2>
                <p>Administering medication to your pet can be a challenging task, but it's essential for their health and well-being. Whether your pet needs antibiotics, pain relief, or long-term medication for a chronic condition, following the proper techniques can make the process easier for both you and your furry friend.</p>
                
                <h3>Tips for Giving Pills to Your Pet</h3>
                <p>Many pets are experts at detecting and avoiding medication. Here are some effective strategies:</p>
                <ul>
                  <li>Hide pills in a small amount of wet food or a special treat</li>
                  <li>Use pill pockets designed specifically for this purpose</li>
                  <li>If necessary, gently open your pet's mouth and place the pill at the back of the tongue</li>
                  <li>Follow up with a treat and praise to create positive associations</li>
                </ul>
                
                <blockquote>
                  <p>Always consult with your veterinarian before administering any medication to your pet, and never give human medications unless specifically instructed by your vet.</p>
                </blockquote>
                
                <h3>Liquid Medications</h3>
                <p>Liquid medications can be easier to administer to some pets. Use these techniques:</p>
                <ol>
                  <li>Shake the bottle well if instructed to do so</li>
                  <li>Draw up the correct dose into the dropper or syringe</li>
                  <li>Gently insert the tip into the side of your pet's mouth</li>
                  <li>Slowly dispense the liquid, allowing your pet to swallow</li>
                </ol>
                
                <h2>Recognizing Side Effects</h2>
                <p>It's important to monitor your pet for any potential side effects after administering medication. Contact your veterinarian immediately if you notice:</p>
                <ul>
                  <li>Vomiting or diarrhea</li>
                  <li>Lethargy or weakness</li>
                  <li>Loss of appetite</li>
                  <li>Allergic reactions (swelling, hives, difficulty breathing)</li>
                  <li>Behavioral changes</li>
                </ul>
                
                <p>Always complete the full course of medication as prescribed, even if your pet appears to be feeling better. Stopping medication early can lead to recurrence of the condition or antibiotic resistance.</p>
              <?php endif; ?>
            </div>
          </div>
          
          <!-- Article Sidebar -->
          <div class="article-sidebar">
            <!-- Author Info -->
            <div class="sidebar-widget">
              <h3>About the Author</h3>
              <div class="author-info">
                <div class="author-avatar">
                  <i class="fas fa-user-md"></i>
                </div>
                <div>
                  <h4>Dr. Emily Rodriguez</h4>
                  <p>Veterinarian & Pet Health Expert</p>
                </div>
              </div>
              <p>Dr. Rodriguez has over 10 years of experience in veterinary medicine and specializes in pet medication management and preventive care.</p>
              
              <div class="share-buttons">
                <a href="#" class="share-button"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="share-button"><i class="fab fa-twitter"></i></a>
                <a href="#" class="share-button"><i class="fab fa-linkedin-in"></i></a>
                <a href="#" class="share-button"><i class="fas fa-link"></i></a>
              </div>
            </div>
            
            <!-- Table of Contents -->
            <div class="sidebar-widget">
              <h3>Article Contents</h3>
              <ul style="list-style: none; padding-left: 0;">
                <li style="margin-bottom: 10px;"><a href="#section1" style="color: var(--primary); text-decoration: none;">Understanding Pet Medications</a></li>
                <li style="margin-bottom: 10px;"><a href="#section2" style="color: var(--primary); text-decoration: none;">Tips for Giving Pills</a></li>
                <li style="margin-bottom: 10px;"><a href="#section3" style="color: var(--primary); text-decoration: none;">Liquid Medications</a></li>
                <li style="margin-bottom: 10px;"><a href="#section4" style="color: var(--primary); text-decoration: none;">Recognizing Side Effects</a></li>
              </ul>
            </div>
            
            <!-- Emergency Info -->
            <div class="sidebar-widget" style="background: #fff4f4; border-left: 4px solid #dc3545;">
              <h3 style="color: #dc3545;">Emergency Information</h3>
              <p style="font-size: 0.9rem;">If your pet is experiencing a medical emergency or adverse reaction to medication, contact your veterinarian immediately.</p>
              <div style="margin-top: 15px;">
                <p style="font-size: 1.2rem; font-weight: 600; color: #dc3545;">(555) 123-HELP</p>
                <p style="font-size: 0.8rem;">24/7 Pet Poison Control</p>
              </div>
            </div>
          </div>
        </div>
        
        <!-- Related Articles -->
        <?php if (!empty($related_articles)): ?>
          <div class="related-articles">
            <h2>Related Articles</h2>
            <div class="related-grid">
              <?php foreach ($related_articles as $related): ?>
                <a href="article_details.php?id=<?php echo $related['id']; ?>" class="related-article">
                  <div class="related-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <?php if (!empty($related['image_url'])): ?>
                      <img src="<?php echo $related['image_url']; ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php endif; ?>
                  </div>
                  <div class="related-content">
                    <h3 class="related-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                    <div style="display: flex; align-items: center; color: var(--gray); font-size: 0.9rem;">
                      <span><i class="far fa-clock"></i> <?php echo $related['read_time']; ?> min read</span>
                    </div>
                  </div>
                </a>
              <?php endforeach; ?>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  </main>

  <script>
    // Smooth scrolling for table of contents
    document.addEventListener('DOMContentLoaded', function() {
      const tocLinks = document.querySelectorAll('a[href^="#"]');
      
      tocLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();
          const targetId = this.getAttribute('href');
          const targetElement = document.querySelector(targetId);
          
          if (targetElement) {
            window.scrollTo({
              top: targetElement.offsetTop - 100,
              behavior: 'smooth'
            });
          }
        });
      });
      
      // Add section IDs to the article content
      const headings = document.querySelectorAll('.article-body h2, .article-body h3');
      headings.forEach((heading, index) => {
        heading.id = 'section' + (index + 1);
      });
    });
  </script>
</body>
</html>