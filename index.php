<?php
include 'db.php';
include 'navbar.php';

// Fetch featured articles for the blog section
$articles = [];
$result = $conn->query("SELECT * FROM medication_articles ORDER BY created_at DESC LIMIT 3");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $articles[] = $row;
    }
}

// Fetch statistics for the stats section
$stats = [
    'pets_helped' => 12500,
    'adoptions' => 3500,
    'users' => 8200,
    'satisfaction' => 98
];

// Close database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetCareHub - Premium Pet Care Solutions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    /* Professional color scheme */
    :root {
      --primary: #2c3e50;    /* Deep blue */
      --secondary: #18bc9c;  /* Teal */
      --accent: #e74c3c;     /* Coral */
      --light: #ecf0f1;      /* Light gray */
      --dark: #2c3e50;       /* Dark blue */
      --gray: #95a5a6;       /* Medium gray */
      --success: #27ae60;    /* Green */
      --shadow: 0 10px 30px rgba(0,0,0,0.1);
      --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    html, body {
      height: 100%;
      margin: 0;
      font-family: 'Montserrat', sans-serif;
      background: #ffffff;
      color: var(--dark);
      line-height: 1.6;
      overflow-x: hidden;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    h1, h2, h3, h4, h5 {
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    
    h1 {
      font-size: 3.8rem;
      font-family: 'Playfair Display', serif;
      font-weight: 700;
    }
    
    h2 {
      font-size: 2.8rem;
      position: relative;
      padding-bottom: 15px;
      font-family: 'Playfair Display', serif;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 80px;
      height: 4px;
      background: var(--secondary);
      border-radius: 2px;
    }
    
    h3 {
      font-size: 1.8rem;
    }
    
    p {
      margin-bottom: 1.5rem;
      color: #555;
      font-size: 1.1rem;
      line-height: 1.8;
    }
    
    .text-center {
      text-align: center;
    }
    
    .section-title {
      margin-bottom: 4rem;
    }
    
    .section-title.text-center h2:after {
      left: 50%;
      transform: translateX(-50%);
    }

    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    section {
      padding: 100px 0;
    }
    
    .section-light {
      background: white;
    }
    
    .section-gray {
      background: #f9fafb;
    }
    
    .section-dark {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .btn {
      display: inline-block;
      padding: 16px 36px;
      border-radius: 50px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-align: center;
      text-transform: uppercase;
      letter-spacing: 1px;
      position: relative;
      overflow: hidden;
    }
    
    .btn-primary {
      background: var(--secondary);
      color: white;
      box-shadow: 0 5px 15px rgba(24, 188, 156, 0.3);
    }
    
    .btn-primary:hover {
      background: #16a085;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(24, 188, 156, 0.4);
    }
    
    .btn-secondary {
      background: transparent;
      color: white;
      border: 2px solid white;
    }
    
    .btn-secondary:hover {
      background: white;
      color: var(--primary);
      transform: translateY(-3px);
    }
    
    .btn-accent {
      background: var(--accent);
      color: white;
      box-shadow: 0 5px 15px rgba(231, 76, 60, 0.3);
    }
    
    .btn-accent:hover {
      background: #c0392b;
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(231, 76, 60, 0.4);
    }
    
    .btn-lg {
      padding: 18px 42px;
      font-size: 1.1rem;
    }

    /* Hero Section with modern design */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      color: white;
      padding: 160px 0 100px;
      position: relative;
      overflow: hidden;
      min-height: 100vh;
      display: flex;
      align-items: center;
    }
    
    .hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
      background-size: 100% 100%;
    }
    
    .hero-content {
      max-width: 650px;
      position: relative;
      z-index: 2;
    }
    
    .hero h1 {
      margin-bottom: 24px;
      font-size: 4.2rem;
      line-height: 1.1;
    }
    
    .hero p {
      font-size: 1.3rem;
      margin-bottom: 40px;
      opacity: 0.9;
      font-weight: 400;
    }
    
    .hero-btns {
      display: flex;
      gap: 20px;
      margin-top: 30px;
    }
    
    .hero-image {
      position: absolute;
      right: 5%;
      top: 50%;
      transform: translateY(-50%);
      width: 45%;
      max-width: 600px;
      z-index: 1;
      border-radius: 20px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }
    
    .hero-image img {
      width: 100%;
      height: auto;
      display: block;
    }
    
    .hero-pattern {
      position: absolute;
      bottom: -100px;
      left: -100px;
      width: 400px;
      height: 400px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect fill="rgba(24, 188, 156, 0.1)" width="200" height="200"/><path d="M0,0 L200,200" stroke="rgba(24, 188, 156, 0.2)" stroke-width="2"/></svg>');
      z-index: 0;
    }

    /* Features Section with modern cards */
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 40px;
      margin-top: 50px;
    }
    
    .feature-card {
      background: white;
      border-radius: 16px;
      padding: 40px 30px;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-align: center;
      border-top: 5px solid var(--secondary);
      position: relative;
      overflow: hidden;
    }
    
    .feature-card:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: linear-gradient(135deg, rgba(24, 188, 156, 0.05) 0%, rgba(44, 62, 80, 0.05) 100%);
      z-index: 0;
      opacity: 0;
      transition: var(--transition);
    }
    
    .feature-card:hover {
      transform: translateY(-15px);
    }
    
    .feature-card:hover:before {
      opacity: 1;
    }
    
    .feature-icon {
      width: 90px;
      height: 90px;
      border-radius: 50%;
      background: rgba(24, 188, 156, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 25px;
      position: relative;
      z-index: 1;
    }
    
    .feature-icon i {
      font-size: 2.8rem;
      color: var(--secondary);
    }
    
    .feature-card h3 {
      margin-bottom: 15px;
      position: relative;
      z-index: 1;
    }
    
    .feature-card p {
      margin-bottom: 20px;
      position: relative;
      z-index: 1;
    }
    
    .feature-link {
      color: var(--secondary);
      font-weight: 600;
      text-decoration: none;
      display: inline-flex;
      align-items: center;
      transition: var(--transition);
      position: relative;
      z-index: 1;
    }
    
    .feature-link i {
      margin-left: 8px;
      transition: var(--transition);
    }
    
    .feature-link:hover {
      color: var(--primary);
    }
    
    .feature-link:hover i {
      transform: translateX(5px);
    }

    /* Stats Section with animated numbers */
    .stats-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 30px;
      text-align: center;
      position: relative;
      z-index: 2;
    }
    
    .stat-box {
      background: rgba(255, 255, 255, 0.1);
      padding: 40px 20px;
      border-radius: 16px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: var(--transition);
    }
    
    .stat-box:hover {
      transform: translateY(-10px);
      background: rgba(255, 255, 255, 0.15);
    }
    
    .stat-number {
      font-size: 3.5rem;
      font-weight: 800;
      margin-bottom: 10px;
      color: white;
      font-family: 'Playfair Display', serif;
    }
    
    .stat-text {
      font-size: 1.2rem;
      opacity: 0.9;
      font-weight: 500;
    }
    
    .stats-pattern {
      position: absolute;
      top: -100px;
      right: -100px;
      width: 300px;
      height: 300px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><circle cx="100" cy="100" r="90" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="2"/></svg>');
      z-index: 0;
    }

    /* Testimonials with modern cards */
    .testimonials-container {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      margin-top: 50px;
    }
    
    .testimonial-card {
      background: white;
      padding: 40px;
      border-radius: 16px;
      box-shadow: var(--shadow);
      position: relative;
      transition: var(--transition);
    }
    
    .testimonial-card:hover {
      transform: translateY(-10px);
    }
    
    .testimonial-card:before {
      content: '"';
      position: absolute;
      top: 20px;
      left: 20px;
      font-size: 6rem;
      color: rgba(24, 188, 156, 0.1);
      font-family: serif;
      line-height: 1;
      z-index: 0;
    }
    
    .testimonial-content {
      font-style: italic;
      margin-bottom: 25px;
      position: relative;
      z-index: 2;
      font-size: 1.1rem;
    }
    
    .testimonial-author {
      display: flex;
      align-items: center;
    }
    
    .author-avatar {
      width: 60px;
      height: 60px;
      border-radius: 50%;
      background: var(--secondary);
      margin-right: 15px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-weight: bold;
      font-size: 1.5rem;
    }
    
    .author-info h4 {
      margin-bottom: 5px;
      font-weight: 600;
    }
    
    .author-info p {
      color: var(--gray);
      font-size: 0.95rem;
      margin: 0;
    }
    
    .testimonial-rating {
      color: #f39c12;
      margin-top: 10px;
    }

    /* Blog Section with elegant cards */
    .blog-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
      gap: 40px;
      margin-top: 50px;
    }
    
    .blog-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .blog-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .blog-image {
      height: 220px;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    .blog-image:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 50%;
      background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }
    
    .blog-category {
      position: absolute;
      top: 20px;
      right: 20px;
      background: var(--secondary);
      color: white;
      padding: 6px 15px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      z-index: 2;
    }
    
    .blog-content {
      padding: 30px;
    }
    
    .blog-meta {
      display: flex;
      align-items: center;
      margin-bottom: 15px;
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .blog-meta span {
      margin-right: 15px;
      display: flex;
      align-items: center;
    }
    
    .blog-meta i {
      margin-right: 5px;
    }
    
    .blog-title {
      margin-bottom: 15px;
      font-size: 1.5rem;
      font-family: 'Playfair Display', serif;
    }
    
    .blog-excerpt {
      margin-bottom: 25px;
      color: #555;
    }
    
    .read-more {
      display: inline-flex;
      align-items: center;
      color: var(--secondary);
      font-weight: 600;
      text-decoration: none;
      transition: var(--transition);
    }
    
    .read-more i {
      margin-left: 8px;
      transition: var(--transition);
    }
    
    .read-more:hover {
      color: var(--primary);
    }
    
    .read-more:hover i {
      transform: translateX(5px);
    }

    /* CTA Section with elegant design */
    .cta-container {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      border-radius: 20px;
      padding: 80px 60px;
      text-align: center;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
    }
    
    .cta-container h2 {
      color: white;
      margin-bottom: 20px;
    }
    
    .cta-container p {
      max-width: 600px;
      margin: 0 auto 40px;
      color: rgba(255,255,255,0.9);
      font-size: 1.2rem;
    }
    
    .cta-pattern {
      position: absolute;
      bottom: -50px;
      right: -50px;
      width: 200px;
      height: 200px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><polygon points="0,0 200,0 200,200" fill="rgba(255,255,255,0.05)"/></svg>');
      z-index: 0;
    }

    /* Responsive Design */
    @media (max-width: 1200px) {
      .hero-image {
        width: 40%;
      }
      
      h1 {
        font-size: 3.5rem;
      }
    }
    
    @media (max-width: 992px) {
      .hero-image {
        display: none;
      }
      
      .hero-content {
        max-width: 100%;
        text-align: center;
      }
      
      .hero-btns {
        justify-content: center;
      }
      
      h1 {
        font-size: 3rem;
      }
      
      h2 {
        font-size: 2.5rem;
      }
      
      section {
        padding: 80px 0;
      }
    }
    
    @media (max-width: 768px) {
      h1 {
        font-size: 2.5rem;
      }
      
      h2 {
        font-size: 2rem;
      }
      
      section {
        padding: 60px 0;
      }
      
      .hero {
        padding: 120px 0 80px;
        min-height: auto;
      }
      
      .hero-btns {
        flex-direction: column;
        align-items: center;
      }
      
      .hero-btns .btn {
        margin-bottom: 15px;
        width: 100%;
        max-width: 280px;
      }
      
      .cta-container {
        padding: 60px 30px;
      }
      
      .features-grid,
      .blog-grid,
      .testimonials-container {
        grid-template-columns: 1fr;
      }
    }

    /* Animations */
    @keyframes fadeInUp {
      from {
        opacity: 0;
        transform: translateY(40px);
      }
      to {
        opacity: 1;
        transform: translateY(0);
      }
    }
    
    .animate {
      animation: fadeInUp 0.8s ease-out forwards;
    }
    
    .delay-1 {
      animation-delay: 0.2s;
    }
    
    .delay-2 {
      animation-delay: 0.4s;
    }
    
    .delay-3 {
      animation-delay: 0.6s;
    }
  </style>
</head>
<body>
  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1 class="animate">Premium Care for Your Beloved Pets</h1>
        <p class="animate delay-1">The all-in-one platform for pet health, medication management, and wellness. Join thousands of pet owners who trust us with their furry family members.</p>
        <div class="hero-btns">
          <a href="register.php" class="btn btn-primary btn-lg animate delay-2">Get Started</a>
          <a href="about.php" class="btn btn-secondary btn-lg animate delay-2">Learn More</a>
        </div>
      </div>
    </div>
    <div class="hero-image">
      <img src="https://plus.unsplash.com/premium_photo-1694819488591-a43907d1c5cc?q=80&w=714&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D" alt="Happy pets">
    </div>
    <div class="hero-pattern"></div>
  </section>

  <!-- Features Section -->
  <section class="section-light">
    <div class="container">
      <div class="section-title text-center">
        <h2>Our Premium Features</h2>
        <p>Everything you need for comprehensive pet care in one place</p>
      </div>
      
      <div class="features-grid">
        <div class="feature-card animate">
          <div class="feature-icon">
            <i class="fas fa-pills"></i>
          </div>
          <h3>Medication Management</h3>
          <p>Never miss a dose with our intelligent medication tracking and reminder system.</p>
          <a href="medication.php" class="feature-link">Explore <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card animate delay-1">
          <div class="feature-icon">
            <i class="fas fa-home"></i>
          </div>
          <h3>Pet Adoption</h3>
          <p>Find your perfect companion through our verified network of shelters and rescues.</p>
          <a href="adoption.php" class="feature-link">Find Pets <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card animate delay-2">
          <div class="feature-icon">
            <i class="fas fa-dog"></i>
          </div>
          <h3>Pet Profiles</h3>
          <p>Complete digital profiles for all your pets with medical history and preferences.</p>
          <a href="profile.php" class="feature-link">Create Profile <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card animate">
          <div class="feature-icon">
            <i class="fas fa-calendar-alt"></i>
          </div>
          <h3>Appointment Scheduling</h3>
          <p>Book vet visits, grooming, and other services with our integrated calendar system.</p>
          <a href="appointments.php" class="feature-link">Schedule Now <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card animate delay-1">
          <div class="feature-icon">
            <i class="fas fa-users"></i>
          </div>
          <h3>Pet Community</h3>
          <p>Connect with other pet owners, share experiences, and get expert advice.</p>
          <a href="community.php" class="feature-link">Join Community <i class="fas fa-arrow-right"></i></a>
        </div>
        
        <div class="feature-card animate delay-2">
          <div class="feature-icon">
            <i class="fas fa-first-aid"></i>
          </div>
          <h3>Health Analytics</h3>
          <p>Track your pet's health trends and receive personalized insights and recommendations.</p>
          <a href="analytics.php" class="feature-link">View Analytics <i class="fas fa-arrow-right"></i></a>
        </div>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="section-dark">
    <div class="container">
      <div class="stats-container">
        <div class="stat-box animate">
          <div class="stat-number"><?php echo number_format($stats['pets_helped']); ?>+</div>
          <div class="stat-text">Pets Helped</div>
        </div>
        
        <div class="stat-box animate delay-1">
          <div class="stat-number"><?php echo number_format($stats['adoptions']); ?>+</div>
          <div class="stat-text">Successful Adoptions</div>
        </div>
        
        <div class="stat-box animate delay-2">
          <div class="stat-number"><?php echo number_format($stats['users']); ?>+</div>
          <div class="stat-text">Happy Pet Owners</div>
        </div>
        
        <div class="stat-box animate delay-3">
          <div class="stat-number"><?php echo $stats['satisfaction']; ?>%</div>
          <div class="stat-text">Satisfaction Rate</div>
        </div>
      </div>
      <div class="stats-pattern"></div>
    </div>
  </section>

  <!-- Testimonials Section -->
  <section class="section-light">
    <div class="container">
      <div class="section-title text-center">
        <h2>What Pet Owners Say</h2>
        <p>Join thousands of satisfied pet parents who use PetCareHub</p>
      </div>
      
      <div class="testimonials-container">
        <div class="testimonial-card animate">
          <div class="testimonial-content">
            PetCareHub has been a lifesaver for managing my dog's medication schedule. The reminders ensure I never miss a dose, and the health tracking features help me spot trends in his wellbeing.
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">S</div>
            <div class="author-info">
              <h4>Sarah Johnson</h4>
              <p>Dog Owner</p>
              <div class="testimonial-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="testimonial-card animate delay-1">
          <div class="testimonial-content">
            As a multi-pet household, keeping track of everyone's vet appointments and medications was challenging. PetCareHub simplified everything and even helped us find our newest family member!
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">M</div>
            <div class="author-info">
              <h4>Michael Torres</h4>
              <p>Cat & Dog Owner</p>
              <div class="testimonial-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
              </div>
            </div>
          </div>
        </div>
        
        <div class="testimonial-card animate delay-2">
          <div class="testimonial-content">
            The medication blog has been incredibly helpful for understanding my pet's health issues. The articles are well-researched and written in a way that's easy to understand for pet owners.
          </div>
          <div class="testimonial-author">
            <div class="author-avatar">E</div>
            <div class="author-info">
              <h4>Emily Rodriguez</h4>
              <p>Bird & Rabbit Owner</p>
              <div class="testimonial-rating">
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star"></i>
                <i class="fas fa-star-half-alt"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Blog Section -->
  <section class="section-gray">
    <div class="container">
      <div class="section-title text-center">
        <h2>From Our Blog</h2>
        <p>Expert advice on pet health and medication</p>
      </div>
      
      <div class="blog-grid">
        <?php if (!empty($articles)): ?>
          <?php foreach ($articles as $article): ?>
            <div class="blog-card animate">
              <div class="blog-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <?php if (!empty($article['image_url'])): ?>
                  <img src="<?php echo $article['image_url']; ?>" alt="<?php echo htmlspecialchars($article['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
                <div class="blog-category"><?php echo htmlspecialchars($article['category']); ?></div>
              </div>
              <div class="blog-content">
                <div class="blog-meta">
                  <span><i class="far fa-clock"></i> <?php echo $article['read_time']; ?> min read</span>
                  <span><i class="far fa-calendar"></i> <?php echo date('M j, Y', strtotime($article['created_at'])); ?></span>
                </div>
                <h3 class="blog-title"><?php echo htmlspecialchars($article['title']); ?></h3>
                <p class="blog-excerpt"><?php echo htmlspecialchars($article['excerpt']); ?></p>
                <a href="article_details.php?id=<?php echo $article['id']; ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <!-- Sample blog posts if database is empty -->
          <div class="blog-card animate">
            <div class="blog-image" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);">
              <div class="blog-category">Medication Tips</div>
            </div>
            <div class="blog-content">
              <div class="blog-meta">
                <span><i class="far fa-clock"></i> 7 min read</span>
                <span><i class="far fa-calendar"></i> Apr 10, 2023</span>
              </div>
              <h3 class="blog-title">How to Administer Medication to Your Pet</h3>
              <p class="blog-excerpt">Giving medication to pets can be challenging. Learn techniques for pills, liquids, and topical medications to make the process easier.</p>
              <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          
          <div class="blog-card animate delay-1">
            <div class="blog-image" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);">
              <div class="blog-category">Feline Health</div>
            </div>
            <div class="blog-content">
              <div class="blog-meta">
                <span><i class="far fa-clock"></i> 8 min read</span>
                <span><i class="far fa-calendar"></i> Apr 5, 2023</span>
              </div>
              <h3 class="blog-title">Recognizing Signs of Pain in Cats</h3>
              <p class="blog-excerpt">Cats often hide their pain. Learn to recognize the subtle signs that your cat might be suffering and what you can do to help.</p>
              <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
          
          <div class="blog-card animate delay-2">
            <div class="blog-image" style="background: linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%);">
              <div class="blog-category">Preventative Care</div>
            </div>
            <div class="blog-content">
              <div class="blog-meta">
                <span><i class="far fa-clock"></i> 12 min read</span>
                <span><i class="far fa-calendar"></i> Mar 28, 2023</span>
              </div>
              <h3 class="blog-title">Heartworm Prevention: What Every Pet Owner Should Know</h3>
              <p class="blog-excerpt">Heartworm disease is serious but preventable. Learn about transmission, prevention options, and why year-round protection is essential.</p>
              <a href="#" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
            </div>
          </div>
        <?php endif; ?>
      </div>
      
      <div class="text-center" style="margin-top: 60px;">
        <a href="medication_blog.php" class="btn btn-primary">View All Articles</a>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section>
    <div class="container">
      <div class="cta-container">
        <h2>Ready to Give Your Pet the Best Care?</h2>
        <p>Join thousands of pet owners who use PetCareHub to manage their pets' health, medications, and overall wellbeing.</p>
        <a href="register.php" class="btn btn-accent btn-lg">Get Started Today</a>
        <div class="cta-pattern"></div>
      </div>
    </div>
  </section>

  <?php include 'footer.php'; ?>

  <script>
    // Animation on scroll
    document.addEventListener('DOMContentLoaded', function() {
      const animatedElements = document.querySelectorAll('.animate');
      
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
          }
        });
      }, {
        threshold: 0.1
      });
      
      animatedElements.forEach(element => {
        element.style.opacity = '0';
        element.style.transform = 'translateY(40px)';
        element.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
        observer.observe(element);
      });
      
      // Animate stats counting
      const statNumbers = document.querySelectorAll('.stat-number');
      const statsSection = document.querySelector('.section-dark');
      
      function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
          rect.top >= 0 &&
          rect.left >= 0 &&
          rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
          rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
      }
      
      function animateNumbers() {
        if (isInViewport(statsSection)) {
          statNumbers.forEach(stat => {
            const text = stat.textContent;
            const isPercentage = text.includes('%');
            const isPlus = text.includes('+');
            const target = parseInt(text.replace(/[^0-9]/g, ''));
            let count = 0;
            const duration = 2000;
            const frameDuration = 1000 / 60;
            const totalFrames = Math.round(duration / frameDuration);
            const easeOutQuad = t => t * (2 - t);
            
            const counter = setInterval(() => {
              const progress = ++count / totalFrames;
              const current = Math.round(target * easeOutQuad(progress));
              
              stat.textContent = current.toLocaleString() + 
                (isPercentage ? '%' : '') +
                (isPlus ? '+' : '');
              
              if (progress === 1) {
                clearInterval(counter);
              }
            }, frameDuration);
          });
          
          window.removeEventListener('scroll', checkStatsAnimation);
        }
      }
      
      function checkStatsAnimation() {
        animateNumbers();
      }
      
      window.addEventListener('scroll', checkStatsAnimation);
    });
  </script>
</body>
</html>