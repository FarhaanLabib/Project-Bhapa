<?php include 'navbar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetCareHub - About Us</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Reused styles from events page with pet-themed colors */
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
      scroll-behavior: smooth;
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
    
    .section-title {
      margin-bottom: 2.5rem;
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
    
    .section-gray {
      background: #f8f9fa;
    }
    
    .btn {
      display: inline-block;
      padding: 12px 28px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-align: center;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: #2c6185;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-accent {
      background: var(--accent);
      color: white;
    }
    
    .btn-accent:hover {
      background: #ec971f;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-lg {
      padding: 15px 40px;
      font-size: 1.1rem;
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
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxjaXJjbGUgY3g9IjIwIiBjeT0iMjAiIHI9IjEuNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
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
    
    /* About Us Page Specific Styles */
    .mission-section {
      padding: 80px 0;
    }
    
    .mission-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
      align-items: center;
    }
    
    .mission-image {
      height: 400px;
      border-radius: 12px;
      background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);
      box-shadow: var(--shadow);
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 5rem;
    }
    
    .mission-content h2 {
      margin-bottom: 25px;
    }
    
    .values-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }
    
    .value-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-align: center;
    }
    
    .value-card:hover {
      transform: translateY(-10px);
    }
    
    .value-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(58, 124, 165, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }
    
    .value-icon i {
      font-size: 2.5rem;
      color: var(--primary);
    }
    
    .team-section {
      background: #f8f9fa;
      padding: 80px 0;
    }
    
    .team-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }
    
    .team-member {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
    }
    
    .team-member:hover {
      transform: translateY(-10px);
      box-shadow: 0 15px 30px rgba(0,0,0,0.15);
    }
    
    .member-photo {
      height: 250px;
      background-size: cover;
      background-position: center;
    }
    
    .member-info {
      padding: 20px;
      text-align: center;
    }
    
    .member-social {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-top: 15px;
    }
    
    .member-social a {
      color: var(--gray);
      transition: var(--transition);
    }
    
    .member-social a:hover {
      color: var(--primary);
    }
    
    .stats-section {
      margin-bottom:100px;
      padding: 80px 0;
      background: linear-gradient(135deg, var(--primary) 0%, #2c6185 100%);
      color: white;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
      text-align: center;
    }
    
    .stat-item {
      padding: 20px;
    }
    
    .stat-number {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 10px;
    }
    
    .stat-label {
      font-size: 1.2rem;
      opacity: 0.9;
    }
    
    /* Features Section */
    .features-section {
      padding: 80px 0;
      background: white;
    }
    
    .features-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
      gap: 30px;
      margin-top: 40px;
    }
    
    .feature-card {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: var(--shadow);
      transition: var(--transition);
      text-align: center;
      border-top: 5px solid var(--primary);
    }
    
    .feature-card:hover {
      transform: translateY(-10px);
    }
    
    .feature-icon {
      width: 80px;
      height: 80px;
      border-radius: 50%;
      background: rgba(58, 124, 165, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 20px;
    }
    
    .feature-icon i {
      font-size: 2.5rem;
      color: var(--primary);
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .mission-container {
        grid-template-columns: 1fr;
      }
      
      .mission-image {
        height: 300px;
        order: -1;
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
  <!-- About Us Page -->
  <main id="about">
    <!-- About Hero -->
    <section class="hero">
      <div class="hero-content">
        <h1>Our Pet Care Journey</h1>
        <p>Discover how PetCareHub is revolutionizing pet wellness, adoption, and care management for pet lovers everywhere</p>
      </div>
    </section>

    <!-- Our Mission -->
    <section class="mission-section section-light">
      <div class="container">
        <div class="mission-container">
          <div class="mission-content">
            <h2>Our Mission</h2>
            <p>At PetCareHub, we believe every pet deserves exceptional care and every owner deserves peace of mind. Our mission is to create a comprehensive platform that simplifies pet care while strengthening the bond between pets and their families.</p>
            <p>Founded in 2020 by a team of veterinarians and pet enthusiasts, we recognized the need for a centralized solution to manage all aspects of pet ownership—from health tracking to adoption services.</p>
            <p>Today, we serve over 200,000 pet parents worldwide, helping them provide the best care for their furry family members.</p>
          </div>
          <div class="mission-image">
            <i class="fas fa-paw"></i>
          </div>
        </div>
        
        <!-- Our Values -->
        <div class="values-grid">
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-heart"></i>
            </div>
            <h3>Compassionate Care</h3>
            <p>We prioritize the wellbeing of all pets and strive to make quality care accessible to everyone.</p>
          </div>
          
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Trust & Safety</h3>
            <p>Your pet's health data is secure with us, and we verify all adoption partners thoroughly.</p>
          </div>
          
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-hand-holding-heart"></i>
            </div>
            <h3>Adoption First</h3>
            <p>We believe in giving every pet a loving home and facilitate thousands of adoptions yearly.</p>
          </div>
          
          <div class="value-card">
            <div class="value-icon">
              <i class="fas fa-lightbulb"></i>
            </div>
            <h3>Innovation</h3>
            <p>We continuously develop new features to make pet care easier and more effective.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section class="features-section">
      <div class="container">
        <h2 class="text-center">Our Features</h2>
        <p class="text-center" style="max-width: 700px; margin: 0 auto 40px;">PetCareHub offers comprehensive tools to manage all aspects of your pet's life</p>
        
        <div class="features-grid">
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-pills"></i>
            </div>
            <h3>Medication Tracking</h3>
            <p>Never miss a dose with our smart medication reminders and history tracking.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-home"></i>
            </div>
            <h3>Adoption Services</h3>
            <p>Connect with verified shelters and find your perfect furry companion.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-dog"></i>
            </div>
            <h3>My Pets Profile</h3>
            <p>Store all your pet's information in one place - from medical records to favorite toys.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-calendar-alt"></i>
            </div>
            <h3>Appointment Scheduling</h3>
            <p>Book vet appointments, grooming sessions, and more with our integrated calendar.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-users"></i>
            </div>
            <h3>Pet Community</h3>
            <p>Connect with other pet owners, share experiences, and get advice.</p>
          </div>
          
          <div class="feature-card">
            <div class="feature-icon">
              <i class="fas fa-first-aid"></i>
            </div>
            <h3>Health Analytics</h3>
            <p>Track your pet's health trends and get insights for preventative care.</p>
          </div>
        </div>
      </div>
    </section>

    <!-- Our Team -->
    <section class="team-section">
      <div class="container">
        <h2 class="text-center">Meet Our Team</h2>
        <p class="text-center" style="max-width: 700px; margin: 0 auto 40px;">We're a passionate group of pet lovers, veterinarians, and tech experts dedicated to improving pet care</p>
        
        <div class="team-grid">
          <div class="team-member">
            <div class="member-photo" style="background: linear-gradient(135deg, #5b86e5 0%, #36d1dc 100%);"></div>
            <div class="member-info">
              <h3>Dr. Emily Rodriguez</h3>
              <p>Chief Veterinarian & Founder</p>
              <div class="member-social">
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
                <a href="#"><i class="fas fa-envelope"></i></a>
              </div>
            </div>
          </div>
          
          <div class="team-member">
            <div class="member-photo" style="background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);"></div>
            <div class="member-info">
              <h3>Michael Thompson</h3>
              <p>CTO</p>
              <div class="member-social">
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
                <a href="#"><i class="fas fa-envelope"></i></a>
              </div>
            </div>
          </div>
          
          <div class="team-member">
            <div class="member-photo" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);"></div>
            <div class="member-info">
              <h3>Sarah Johnson</h3>
              <p>Adoption Coordinator</p>
              <div class="member-social">
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fas fa-envelope"></i></a>
              </div>
            </div>
          </div>
          
          <div class="team-member">
            <div class="member-photo" style="background: linear-gradient(135deg, #a1c4fd 0%, #c2e9fb 100%);"></div>
            <div class="member-info">
              <h3>David Wilson</h3>
              <p>Lead Developer</p>
              <div class="member-social">
                <a href="#"><i class="fab fa-linkedin-in"></i></a>
                <a href="#"><i class="fab fa-github"></i></a>
                <a href="#"><i class="fas fa-envelope"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <!-- Stats Section -->
    <section class="stats-section">
      <div class="container">
        <div class="stats-grid">
          <div class="stat-item">
            <div class="stat-number">200K+</div>
            <div class="stat-label">Pet Parents</div>
          </div>
          
          <div class="stat-item">
            <div class="stat-number">15K+</div>
            <div class="stat-label">Successful Adoptions</div>
          </div>
          
          <div class="stat-item">
            <div class="stat-number">50+</div>
            <div class="stat-label">Shelter Partners</div>
          </div>
          
          <div class="stat-item">
            <div class="stat-number">96%</div>
            <div class="stat-label">User Satisfaction</div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <script>
    // Simple animation for stats counting
    document.addEventListener('DOMContentLoaded', function() {
      const statNumbers = document.querySelectorAll('.stat-number');
      const statsSection = document.querySelector('.stats-section');
      
      // Check if stats section is in viewport
      function isInViewport(element) {
        const rect = element.getBoundingClientRect();
        return (
          rect.top >= 0 &&
          rect.left >= 0 &&
          rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
          rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
      }
      
      // Animate numbers counting up
      function animateNumbers() {
        if (isInViewport(statsSection)) {
          statNumbers.forEach(stat => {
            const target = parseInt(stat.textContent);
            let count = 0;
            const duration = 2000; // ms
            const frameDuration = 1000 / 60; // ms per frame
            const totalFrames = Math.round(duration / frameDuration);
            const easeOutQuad = t => t * (2 - t);
            
            const counter = setInterval(() => {
              const progress = ++count / totalFrames;
              const current = Math.round(target * easeOutQuad(progress));
              
              stat.textContent = current.toLocaleString() + 
                (stat.textContent.includes('%') ? '%' : 
                 stat.textContent.includes('+') ? '+' : '');
              
              if (progress === 1) {
                clearInterval(counter);
              }
            }, frameDuration);
          });
          
          // Remove event listener after animation
          window.removeEventListener('scroll', checkStatsAnimation);
        }
      }
      
      // Check if stats should animate on scroll
      function checkStatsAnimation() {
        animateNumbers();
      }
      
      window.addEventListener('scroll', checkStatsAnimation);
    });
  </script>
</body>
</html>