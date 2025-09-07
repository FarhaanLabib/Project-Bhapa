
<?php
include 'db.php';

// Initialize arrays
$lost_pets = [];
$found_pets = [];

// Check if tables exist and fetch lost pets
$lost_query = "SELECT * FROM lost_pets ORDER BY id DESC LIMIT 10";
$lost_result = $conn->query($lost_query);
if ($lost_result && $lost_result->num_rows > 0) {
    while ($row = $lost_result->fetch_assoc()) {
        $lost_pets[] = $row;
    }
}

// Check if tables exist and fetch found pets
$found_query = "SELECT * FROM found ORDER BY id DESC LIMIT 10";
$found_result = $conn->query($found_query);
if ($found_result && $found_result->num_rows > 0) {
    while ($row = $found_result->fetch_assoc()) {
        $found_pets[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PetCareHub - Lost & Found</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #2c3e50;
      --secondary: #18bc9c;
      --accent: #e74c3c;
      --light: #ecf0f1;
      --dark: #2c3e50;
      --gray: #95a5a6;
      --success: #27ae60;
      --warning: #f39c12;
      --shadow: 0 10px 30px rgba(0,0,0,0.1);
      --transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    
    body {
      font-family: 'Montserrat', sans-serif;
      background: #f9fafb;
      color: var(--dark);
      line-height: 1.6;
      padding: 0;
      margin: 0;
    }
    
    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    /* Header Styles */
    header {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      color: white;
      padding: 20px 0;
      box-shadow: var(--shadow);
    }
    
    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .logo {
      font-family: 'Playfair Display', serif;
      font-size: 2rem;
      font-weight: 700;
      display: flex;
      align-items: center;
    }
    
    .logo i {
      color: var(--secondary);
      margin-right: 10px;
    }
    
    nav ul {
      display: flex;
      list-style: none;
    }
    
    nav li {
      margin-left: 30px;
    }
    
    nav a {
      color: white;
      text-decoration: none;
      font-weight: 600;
      transition: var(--transition);
      padding: 8px 0;
      position: relative;
    }
    
    nav a:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--secondary);
      transition: var(--transition);
    }
    
    nav a:hover:after {
      width: 100%;
    }
    
    /* Hero Section */
    .hero {
      background: linear-gradient(rgba(44, 62, 80, 0.8), rgba(26, 37, 48, 0.8)), url('https://images.unsplash.com/photo-1450778869180-41d0601e046e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1150&q=80');
      background-size: cover;
      background-position: center;
      color: white;
      text-align: center;
      padding: 100px 0;
    }
    
    .hero-content {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .hero h1 {
      font-family: 'Playfair Display', serif;
      font-size: 3.5rem;
      margin-bottom: 20px;
    }
    
    .hero p {
      font-size: 1.2rem;
      margin-bottom: 40px;
      opacity: 0.9;
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
    
    /* Lost & Found Section */
    .section-title {
      text-align: center;
      margin: 60px 0 40px;
    }
    
    .section-title h2 {
      font-family: 'Playfair Display', serif;
      font-size: 2.8rem;
      position: relative;
      padding-bottom: 15px;
      margin-bottom: 20px;
    }
    
    .section-title h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 50%;
      transform: translateX(-50%);
      width: 80px;
      height: 4px;
      background: var(--secondary);
      border-radius: 2px;
    }
    
    .section-title p {
      font-size: 1.2rem;
      color: var(--gray);
      max-width: 600px;
      margin: 0 auto;
    }
    
    /* Filters */
    .filters {
      display: flex;
      justify-content: center;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 40px;
    }
    
    .filter-btn {
      padding: 12px 25px;
      background: white;
      border: 2px solid var(--secondary);
      border-radius: 50px;
      cursor: pointer;
      font-weight: 600;
      transition: var(--transition);
      display: flex;
      align-items: center;
    }
    
    .filter-btn i {
      margin-right: 8px;
    }
    
    .filter-btn.active, .filter-btn:hover {
      background: var(--secondary);
      color: white;
    }
    
    /* Pet Cards Grid */
    .pets-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
      gap: 30px;
      margin-bottom: 60px;
    }
    
    .pet-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .pet-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .pet-image {
      height: 250px;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    .pet-status {
      position: absolute;
      top: 15px;
      right: 15px;
      padding: 6px 15px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      z-index: 2;
    }
    
    .status-lost {
      background: var(--accent);
      color: white;
    }
    
    .status-found {
      background: var(--success);
      color: white;
    }
    
    .status-reunited {
      background: var(--secondary);
      color: white;
    }
    
    .pet-details {
      padding: 25px;
    }
    
    .pet-name {
      font-family: 'Playfair Display', serif;
      font-size: 1.8rem;
      margin-bottom: 10px;
    }
    
    .pet-meta {
      display: flex;
      flex-wrap: wrap;
      gap: 15px;
      margin-bottom: 15px;
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .pet-meta span {
      display: flex;
      align-items: center;
    }
    
    .pet-meta i {
      margin-right: 5px;
    }
    
    .pet-description {
      margin-bottom: 25px;
      color: #555;
      line-height: 1.6;
    }
    
    .pet-contact {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 15px;
      border-top: 1px solid #eee;
    }
    
    .contact-info {
      font-size: 0.9rem;
    }
    
    .contact-info strong {
      display: block;
      margin-bottom: 5px;
    }
    
    /* Stats Section */
    .stats {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      color: white;
      padding: 80px 0;
      text-align: center;
    }
    
    .stats-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 30px;
      margin-top: 50px;
    }
    
    .stat-item {
      background: rgba(255, 255, 255, 0.1);
      padding: 30px 20px;
      border-radius: 16px;
      backdrop-filter: blur(10px);
      border: 1px solid rgba(255, 255, 255, 0.1);
      transition: var(--transition);
    }
    
    .stat-item:hover {
      transform: translateY(-10px);
      background: rgba(255, 255, 255, 0.15);
    }
    
    .stat-number {
      font-size: 3rem;
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
    
    /* How It Works Section */
    .how-it-works {
      padding: 80px 0;
      background: white;
    }
    
    .steps {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 40px;
      margin-top: 50px;
    }
    
    .step {
      text-align: center;
      padding: 40px 30px;
      border-radius: 16px;
      background: #f9fafb;
      transition: var(--transition);
      position: relative;
    }
    
    .step:hover {
      transform: translateY(-10px);
      box-shadow: var(--shadow);
    }
    
    .step-number {
      position: absolute;
      top: -20px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 50px;
      background: var(--secondary);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      font-weight: 700;
    }
    
    .step-icon {
      font-size: 3rem;
      color: var(--secondary);
      margin-bottom: 20px;
    }
    
    .step h3 {
      margin-bottom: 15px;
      font-size: 1.5rem;
    }
    
    /* Success Stories */
    .success-stories {
      padding: 80px 0;
      background: #f9fafb;
    }
    
    .stories {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
      gap: 30px;
      margin-top: 50px;
    }
    
    .story {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }
    
    .story-image {
      height: 200px;
      background-size: cover;
      background-position: center;
    }
    
    .story-content {
      padding: 25px;
    }
    
    .story-content h3 {
      margin-bottom: 15px;
      font-size: 1.5rem;
    }
    
    .story-content p {
      margin-bottom: 20px;
      color: #555;
    }
    
    /* Footer */
    footer {
      background: var(--dark);
      color: white;
      padding: 60px 0 30px;
    }
    
    .footer-content {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 40px;
      margin-bottom: 40px;
    }
    
    .footer-column h3 {
      font-size: 1.5rem;
      margin-bottom: 20px;
      position: relative;
      padding-bottom: 10px;
    }
    
    .footer-column h3:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 40px;
      height: 3px;
      background: var(--secondary);
    }
    
    .footer-column p {
      margin-bottom: 20px;
      opacity: 0.8;
    }
    
    .footer-links {
      list-style: none;
    }
    
    .footer-links li {
      margin-bottom: 12px;
    }
    
    .footer-links a {
      color: white;
      text-decoration: none;
      opacity: 0.8;
      transition: var(--transition);
    }
    
    .footer-links a:hover {
      opacity: 1;
      color: var(--secondary);
      padding-left: 5px;
    }
    
    .social-links {
      display: flex;
      gap: 15px;
      margin-top: 20px;
    }
    
    .social-links a {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 40px;
      height: 40px;
      background: rgba(255, 255, 255, 0.1);
      border-radius: 50%;
      color: white;
      transition: var(--transition);
    }
    
    .social-links a:hover {
      background: var(--secondary);
      transform: translateY(-3px);
    }
    
    .copyright {
      text-align: center;
      padding-top: 30px;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
      opacity: 0.7;
      font-size: 0.9rem;
    }
    
    /* Modal Styles */
    .modal {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0,0,0,0.7);
      z-index: 1000;
      overflow-y: auto;
    }
    
    .modal-content {
      background: white;
      margin: 50px auto;
      padding: 40px;
      border-radius: 16px;
      width: 90%;
      max-width: 700px;
      position: relative;
      animation: modalFadeIn 0.5s;
    }
    
    @keyframes modalFadeIn {
      from {opacity: 0; transform: translateY(-50px);}
      to {opacity: 1; transform: translateY(0);}
    }
    
    .close-modal {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--gray);
    }
    
    .form-group {
      margin-bottom: 25px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 2px solid #e1e1e1;
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
    }
    
    .form-control:focus {
      border-color: var(--secondary);
      outline: none;
    }
    
    .form-row {
      display: flex;
      gap: 20px;
    }
    
    .form-row .form-group {
      flex: 1;
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
      .header-content {
        flex-direction: column;
        text-align: center;
      }
      
      nav ul {
        margin-top: 20px;
        justify-content: center;
      }
      
      nav li {
        margin: 0 15px;
      }
      
      .hero h1 {
        font-size: 2.8rem;
      }
    }
    
    @media (max-width: 768px) {
      .hero {
        padding: 60px 0;
      }
      
      .hero h1 {
        font-size: 2.2rem;
      }
      
      .section-title h2 {
        font-size: 2.2rem;
      }
      
      .form-row {
        flex-direction: column;
        gap: 0;
      }
      
      .pets-grid {
        grid-template-columns: 1fr;
      }
    }

    /* Navigation Buttons Section */
    .nav-buttons-section {
      background: white;
      padding: 40px 0;
      border-bottom: 1px solid #e5e7eb;
    }

    .nav-buttons {
      display: flex;
      justify-content: center;
      gap: 30px;
      flex-wrap: wrap;
    }

    .nav-btn {
      display: flex;
      flex-direction: column;
      align-items: center;
      padding: 20px 30px;
      background: white;
      border: 2px solid #e5e7eb;
      border-radius: 12px;
      text-decoration: none;
      color: var(--dark);
      transition: var(--transition);
      min-width: 150px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }

    .nav-btn:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.15);
      border-color: var(--secondary);
    }

    .nav-btn i {
      font-size: 2rem;
      margin-bottom: 10px;
      color: var(--secondary);
    }

    .nav-btn span {
      font-weight: 600;
      font-size: 1rem;
    }

    .home-btn:hover {
      border-color: var(--primary);
    }

    .home-btn:hover i {
      color: var(--primary);
    }

    .lost-btn:hover {
      border-color: var(--accent);
    }

    .lost-btn:hover i {
      color: var(--accent);
    }

    .found-btn:hover {
      border-color: var(--success);
    }

    .found-btn:hover i {
      color: var(--success);
    }

    @media (max-width: 768px) {
      .nav-buttons {
        gap: 15px;
      }
      
      .nav-btn {
        min-width: 120px;
        padding: 15px 20px;
      }
      
      .nav-btn i {
        font-size: 1.5rem;
      }
      
      .nav-btn span {
        font-size: 0.9rem;
      }
    }

    /* Pet Report Cards */
    .pet-cards-container {
      margin-top: 40px;
    }

    .pet-cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 25px;
      margin-top: 30px;
    }

    .pet-card {
      background: white;
      border-radius: 16px;
      padding: 25px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      transition: var(--transition);
      border-left: 5px solid var(--secondary);
      position: relative;
      overflow: hidden;
    }

    .pet-card:hover {
      transform: translateY(-8px);
      box-shadow: 0 12px 30px rgba(0,0,0,0.15);
    }

    .pet-card.lost {
      border-left-color: var(--accent);
    }

    .pet-card.found {
      border-left-color: var(--success);
    }

    .pet-card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .pet-status {
      padding: 6px 12px;
      border-radius: 20px;
      font-size: 0.8rem;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .pet-status.lost {
      background: rgba(231, 76, 60, 0.1);
      color: var(--accent);
    }

    .pet-status.found {
      background: rgba(39, 174, 96, 0.1);
      color: var(--success);
    }

    .pet-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 15px;
    }

    .pet-card.lost .pet-icon {
      background: rgba(231, 76, 60, 0.1);
      color: var(--accent);
    }

    .pet-card.found .pet-icon {
      background: rgba(39, 174, 96, 0.1);
      color: var(--success);
    }

    .pet-name {
      font-size: 1.4rem;
      font-weight: 700;
      margin-bottom: 8px;
      color: var(--dark);
    }

    .pet-details {
      margin-bottom: 15px;
    }

    .pet-detail-item {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
      font-size: 0.95rem;
      color: #666;
    }

    .pet-detail-item i {
      width: 20px;
      margin-right: 10px;
      color: var(--secondary);
    }

    .pet-location {
      background: #f8f9fa;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 15px;
      border-left: 3px solid var(--secondary);
    }

    .pet-location-label {
      font-size: 0.8rem;
      font-weight: 600;
      color: var(--gray);
      text-transform: uppercase;
      letter-spacing: 0.5px;
      margin-bottom: 5px;
    }

    .pet-location-text {
      font-size: 1rem;
      color: var(--dark);
      font-weight: 500;
    }

    .pet-date {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-top: 15px;
      border-top: 1px solid #e5e7eb;
      font-size: 0.9rem;
      color: var(--gray);
    }

    .pet-id {
      background: var(--light);
      padding: 4px 8px;
      border-radius: 4px;
      font-family: monospace;
      font-size: 0.8rem;
    }

    .no-pets-message {
      text-align: center;
      padding: 60px 20px;
      color: var(--gray);
    }

    .no-pets-message i {
      font-size: 4rem;
      margin-bottom: 20px;
      color: #ddd;
    }

    .no-pets-message h3 {
      margin-bottom: 10px;
      color: var(--dark);
    }

    .no-pets-message p {
      font-size: 1.1rem;
    }

    @media (max-width: 768px) {
      .pet-cards-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }
      
      .pet-card {
        padding: 20px;
      }
      
      .pet-name {
        font-size: 1.2rem;
      }
    }
  </style>
</head>
<body>
  <!-- Header -->


  <!-- Hero Section -->
  <section class="hero">
    <div class="container">
      <div class="hero-content">
        <h1>Helping Lost Pets Find Their Way Home</h1>
        <p>Our community-powered platform helps reunite lost pets with their families. Report a lost or found pet, or browse active listings in your area.</p>
        <div class="hero-buttons">
          <a href="#" class="btn btn-primary" id="report-pet-btn">Report a Pet</a>
          <a href="#" class="btn btn-secondary">Search Listings</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Navigation Buttons Section -->
  <section class="nav-buttons-section">
    <div class="container">
      <div class="nav-buttons">
        <a href="index.php" class="nav-btn home-btn">
          <i class="fas fa-home"></i>
          <span>Home</span>
        </a>
        <a href="reportlost.php" class="nav-btn lost-btn">
          <i class="fas fa-search"></i>
          <span>Report Lost Pets</span>
        </a>
        <a href="reportfound.php" class="nav-btn found-btn">
          <i class="fas fa-heart"></i>
          <span>Report Found</span>
        </a>
      </div>
    </div>
  </section>

  <!-- Lost & Found Section -->
  <section>
    <div class="container">
      <div class="section-title">
        <h2>Lost & Found Pets</h2>
        <p>Browse through recent lost and found pet reports in your community</p>
      </div>
      
      <!-- Real Database Reports Section -->
      <div class="pet-cards-container">
        <div class="section-title" style="margin-bottom: 30px;">
          <h2>Recent Pet Reports</h2>
          <p>Latest lost and found pet reports from our community</p>
        </div>
        
        <!-- Lost Pets Section -->
        <?php if (!empty($lost_pets)): ?>
          <div class="pet-cards-grid">
            <?php foreach ($lost_pets as $pet): ?>
              <div class="pet-card lost">
                <div class="pet-card-header">
                  <div class="pet-icon">
                    <i class="fas fa-search"></i>
                  </div>
                  <div class="pet-status lost">Lost</div>
                </div>
                
                <div class="pet-name">
                  <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
                </div>
                
                <div class="pet-details">
                  <div class="pet-detail-item">
                    <i class="fas fa-paw"></i>
                    <span><?php echo htmlspecialchars($pet['animal']); ?></span>
                  </div>
                  <?php if (!empty($pet['breed'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-tag"></i>
                      <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($pet['age'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-calendar"></i>
                      <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                    </div>
                  <?php endif; ?>
                </div>
                
                <div class="pet-location">
                  <div class="pet-location-label">Last Seen</div>
                  <div class="pet-location-text"><?php echo htmlspecialchars($pet['last_seen'] ?? 'Location not specified'); ?></div>
                </div>
                
                <div class="pet-date">
                  <span><i class="fas fa-clock"></i> Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?></span>
                  <?php if (!empty($pet['pet_id'])): ?>
                    <span class="pet-id">ID: <?php echo htmlspecialchars($pet['pet_id']); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-pets-message">
            <i class="fas fa-search"></i>
            <h3>No Lost Pets Reported</h3>
            <p>Be the first to report a lost pet in your area.</p>
          </div>
        <?php endif; ?>

        <!-- Found Pets Section -->
        <?php if (!empty($found_pets)): ?>
          <div class="pet-cards-grid" style="margin-top: 40px;">
            <?php foreach ($found_pets as $pet): ?>
              <div class="pet-card found">
                <div class="pet-card-header">
                  <div class="pet-icon">
                    <i class="fas fa-home"></i>
                  </div>
                  <div class="pet-status found">Found</div>
                </div>
                
                <div class="pet-name">
                  <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
                </div>
                
                <div class="pet-details">
                  <div class="pet-detail-item">
                    <i class="fas fa-paw"></i>
                    <span><?php echo htmlspecialchars($pet['animal']); ?></span>
                  </div>
                  <?php if (!empty($pet['breed'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-tag"></i>
                      <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($pet['age'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-calendar"></i>
                      <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                    </div>
                  <?php endif; ?>
                </div>
                
                <div class="pet-location">
                  <div class="pet-location-label">Found At</div>
                  <div class="pet-location-text"><?php echo htmlspecialchars($pet['found_at'] ?? 'Location not specified'); ?></div>
                </div>
                
                <div class="pet-date">
                  <span><i class="fas fa-clock"></i> Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?></span>
                  <?php if (!empty($pet['pet_id'])): ?>
                    <span class="pet-id">ID: <?php echo htmlspecialchars($pet['pet_id']); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-pets-message" style="margin-top: 40px;">
            <i class="fas fa-home"></i>
            <h3>No Found Pets Reported</h3>
            <p>Be the first to report a found pet in your area.</p>
          </div>
        <?php endif; ?>
      </div>
      
      <div style="text-align: center; margin-top: 40px;">
        <a href="reportlost.php" class="btn btn-primary" style="margin-right: 15px;">Report Lost Pet</a>
        <a href="reportfound.php" class="btn btn-secondary">Report Found Pet</a>
      </div>

      <!-- Demo Data Section -->
      <div class="section-title" style="margin-top: 80px;">
        <h2>Community Showcase</h2>
        <p>Browse through sample lost and found pet reports</p>
      </div>
      
      <div class="filters">
        <button class="filter-btn active" data-filter="all">
          <i class="fas fa-list"></i> All Reports
        </button>
        <button class="filter-btn" data-filter="lost">
          <i class="fas fa-search"></i> Lost Pets
        </button>
        <button class="filter-btn" data-filter="found">
          <i class="fas fa-home"></i> Found Pets
        </button>
        <button class="filter-btn" data-filter="reunited">
          <i class="fas fa-heart"></i> Success Stories
        </button>
      </div>
      
      <div class="pets-grid" id="pets-container">
        <!-- Demo pet cards will be loaded here via JavaScript -->
      </div>
      
      <div style="text-align: center;">
        <a href="#" class="btn btn-primary">Load More</a>
      </div>
    </div>
  </section>

  <!-- Real Database Reports Section -->
  <section style="background: white; padding: 80px 0;">
    <div class="container">
      <div class="pet-cards-container">
        <div class="section-title" style="margin-bottom: 30px;">
          <h2>Recent Pet Reports</h2>
          <p>Latest lost and found pet reports from our community</p>
        </div>
        
        <!-- Lost Pets Section -->
        <?php if (!empty($lost_pets)): ?>
          <div class="pet-cards-grid">
            <?php foreach ($lost_pets as $pet): ?>
              <div class="pet-card lost">
                <div class="pet-card-header">
                  <div class="pet-icon">
                    <i class="fas fa-search"></i>
                  </div>
                  <div class="pet-status lost">Lost</div>
                </div>
                
                <div class="pet-name">
                  <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
                </div>
                
                <div class="pet-details">
                  <div class="pet-detail-item">
                    <i class="fas fa-paw"></i>
                    <span><?php echo htmlspecialchars($pet['animal']); ?></span>
                  </div>
                  <?php if (!empty($pet['breed'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-tag"></i>
                      <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($pet['age'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-calendar"></i>
                      <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                    </div>
                  <?php endif; ?>
                </div>
                
                <div class="pet-location">
                  <div class="pet-location-label">Last Seen</div>
                  <div class="pet-location-text"><?php echo htmlspecialchars($pet['last_seen'] ?? 'Location not specified'); ?></div>
                </div>
                
                <div class="pet-date">
                  <span><i class="fas fa-clock"></i> Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?></span>
                  <?php if (!empty($pet['pet_id'])): ?>
                    <span class="pet-id">ID: <?php echo htmlspecialchars($pet['pet_id']); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-pets-message">
            <i class="fas fa-search"></i>
            <h3>No Lost Pets Reported</h3>
            <p>Be the first to report a lost pet in your area.</p>
          </div>
        <?php endif; ?>

        <!-- Found Pets Section -->
        <?php if (!empty($found_pets)): ?>
          <div class="pet-cards-grid" style="margin-top: 40px;">
            <?php foreach ($found_pets as $pet): ?>
              <div class="pet-card found">
                <div class="pet-card-header">
                  <div class="pet-icon">
                    <i class="fas fa-home"></i>
                  </div>
                  <div class="pet-status found">Found</div>
                </div>
                
                <div class="pet-name">
                  <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
                </div>
                
                <div class="pet-details">
                  <div class="pet-detail-item">
                    <i class="fas fa-paw"></i>
                    <span><?php echo htmlspecialchars($pet['animal']); ?></span>
                  </div>
                  <?php if (!empty($pet['breed'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-tag"></i>
                      <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                    </div>
                  <?php endif; ?>
                  <?php if (!empty($pet['age'])): ?>
                    <div class="pet-detail-item">
                      <i class="fas fa-calendar"></i>
                      <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                    </div>
                  <?php endif; ?>
                </div>
                
                <div class="pet-location">
                  <div class="pet-location-label">Found At</div>
                  <div class="pet-location-text"><?php echo htmlspecialchars($pet['found_at'] ?? 'Location not specified'); ?></div>
                </div>
                
                <div class="pet-date">
                  <span><i class="fas fa-clock"></i> Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?></span>
                  <?php if (!empty($pet['pet_id'])): ?>
                    <span class="pet-id">ID: <?php echo htmlspecialchars($pet['pet_id']); ?></span>
                  <?php endif; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php else: ?>
          <div class="no-pets-message" style="margin-top: 40px;">
            <i class="fas fa-home"></i>
            <h3>No Found Pets Reported</h3>
            <p>Be the first to report a found pet in your area.</p>
          </div>
        <?php endif; ?>
      </div>
      
      <div style="text-align: center; margin-top: 40px;">
        <a href="reportlost.php" class="btn btn-primary" style="margin-right: 15px;">Report Lost Pet</a>
        <a href="reportfound.php" class="btn btn-secondary">Report Found Pet</a>
      </div>
    </div>
  </section>

  <!-- Stats Section -->
  <section class="stats">
    <div class="container">
      <div class="section-title">
        <h2>Our Impact</h2>
        <p>Join thousands of pet lovers making a difference</p>
      </div>
      
      <div class="stats-grid">
        <div class="stat-item">
          <div class="stat-number">2,547</div>
          <div class="stat-text">Pets Reunited</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">5,832</div>
          <div class="stat-text">Active Reports</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">12,409</div>
          <div class="stat-text">Community Members</div>
        </div>
        <div class="stat-item">
          <div class="stat-number">98%</div>
          <div class="stat-text">Success Rate</div>
        </div>
      </div>
    </div>
  </section>

  <!-- How It Works Section -->
  <section class="how-it-works">
    <div class="container">
      <div class="section-title">
        <h2>How It Works</h2>
        <p>Simple steps to report or find a lost pet</p>
      </div>
      
      <div class="steps">
        <div class="step">
          <div class="step-number">1</div>
          <div class="step-icon">
            <i class="fas fa-clipboard-list"></i>
          </div>
          <h3>Create a Report</h3>
          <p>Provide details about the lost or found pet, including photos, location, and contact information.</p>
        </div>
        
        <div class="step">
          <div class="step-number">2</div>
          <div class="step-icon">
            <i class="fas fa-bullhorn"></i>
          </div>
          <h3>Share with Community</h3>
          <p>Your report will be visible to our community members and shared across social networks.</p>
        </div>
        
        <div class="step">
          <div class="step-number">3</div>
          <div class="step-icon">
            <i class="fas fa-heart"></i>
          </div>
          <h3>Reunite with Family</h3>
          <p>Get notifications when there's a match and help reunite pets with their loving families.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Success Stories -->
  <section class="success-stories">
    <div class="container">
      <div class="section-title">
        <h2>Success Stories</h2>
        <p>Heartwarming reunions made possible by our community</p>
      </div>
      
      <div class="stories">
        <div class="story">
          <div class="story-image" style="background-image: url('https://images.unsplash.com/photo-1583337130417-3346a1be7dee?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1064&q=80');"></div>
          <div class="story-content">
            <h3>Max's Homecoming</h3>
            <p>After 3 weeks missing, Max was reunited with his family thanks to a community member who spotted him.</p>
            <a href="#" class="btn btn-primary">Read More</a>
          </div>
        </div>
        
        <div class="story">
          <div class="story-image" style="background-image: url('https://images.unsplash.com/photo-1543852786-1cf6624b9987?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80');"></div>
          <div class="story-content">
            <h3>Luna's Journey</h3>
            <p>Luna traveled over 20 miles before being identified through our microchip partnership program.</p>
            <a href="#" class="btn btn-primary">Read More</a>
          </div>
        </div>
        
        <div class="story">
          <div class="story-image" style="background-image: url('https://images.unsplash.com/photo-1596273315325-5111575e3baf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1074&q=80');"></div>
          <div class="story-content">
            <h3>Buddy's Rescue</h3>
            <p>Found injured and scared, Buddy received medical care and was reunited after a month-long recovery.</p>
            <a href="#" class="btn btn-primary">Read More</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer>
    <div class="container">
      <div class="footer-content">
        <div class="footer-column">
          <h3>PetCareHub</h3>
          <p>Dedicated to helping pets and their owners live happier, healthier lives together.</p>
          <div class="social-links">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-pinterest"></i></a>
          </div>
        </div>
        
        <div class="footer-column">
          <h3>Quick Links</h3>
          <ul class="footer-links">
            <li><a href="#">Home</a></li>
            <li><a href="#">Services</a></li>
            <li><a href="#">Adoption</a></li>
            <li><a href="#">Lost & Found</a></li>
            <li><a href="#">Community</a></li>
          </ul>
        </div>
        
        <div class="footer-column">
          <h3>Resources</h3>
          <ul class="footer-links">
            <li><a href="#">Pet Care Tips</a></li>
            <li><a href="#">Emergency Resources</a></li>
            <li><a href="#">Local Shelters</a></li>
            <li><a href="#">Microchipping Info</a></li>
            <li><a href="#">Pet Insurance</a></li>
          </ul>
        </div>
        
        <div class="footer-column">
          <h3>Contact Us</h3>
          <p>123 Pet Care Avenue<br>New York, NY 10001</p>
          <p>Email: info@petcarehub.com<br>Phone: (555) 123-4567</p>
        </div>
      </div>
      
      <div class="copyright">
        <p>&copy; 2023 PetCareHub. All rights reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Report Pet Modal -->
  <div class="modal" id="report-modal">
    <div class="modal-content">
      <span class="close-modal">&times;</span>
      <h2>Report a Lost or Found Pet</h2>
      
      <form id="pet-report-form">
        <div class="form-group">
          <label>Report Type</label>
          <select class="form-control" id="report-type" name="report_type" required>
            <option value="">Select Type</option>
            <option value="lost">I Lost a Pet</option>
            <option value="found">I Found a Pet</option>
          </select>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="pet-name">Pet Name</label>
            <input type="text" class="form-control" id="pet-name" name="pet_name">
          </div>
          
          <div class="form-group">
            <label for="pet-type">Pet Type</label>
            <select class="form-control" id="pet-type" name="pet_type" required>
              <option value="">Select Type</option>
              <option value="dog">Dog</option>
              <option value="cat">Cat</option>
              <option value="bird">Bird</option>
              <option value="rabbit">Rabbit</option>
              <option value="other">Other</option>
            </select>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="breed">Breed</label>
            <input type="text" class="form-control" id="breed" name="breed">
          </div>
          
          <div class="form-group">
            <label for="color">Color/Markings</label>
            <input type="text" class="form-control" id="color" name="color" required>
          </div>
        </div>
        
        <div class="form-row">
          <div class="form-group" id="last-seen-group">
            <label for="last-seen-date">Last Seen Date</label>
            <input type="date" class="form-control" id="last-seen-date" name="last_seen_date">
          </div>
          
          <div class="form-group" id="found-date-group" style="display: none;">
            <label for="found-date">Found Date</label>
            <input type="date" class="form-control" id="found-date" name="found_date">
          </div>
          
          <div class="form-group">
            <label for="location">Location</label>
            <input type="text" class="form-control" id="location" name="location" required placeholder="Street, Neighborhood, City">
          </div>
        </div>
        
        <div class="form-group">
          <label for="description">Description</label>
          <textarea class="form-control" id="description" name="description" rows="4" required placeholder="Describe your pet or the pet you found, including any distinctive features"></textarea>
        </div>
        
        <div class="form-group">
          <label for="pet-image">Upload Photo</label>
          <input type="file" class="form-control" id="pet-image" name="pet_image" accept="image/*">
        </div>
        
        <div class="form-row">
          <div class="form-group">
            <label for="contact-email">Contact Email</label>
            <input type="email" class="form-control" id="contact-email" name="contact_email" required>
          </div>
          
          <div class="form-group">
            <label for="contact-phone">Contact Phone</label>
            <input type="tel" class="form-control" id="contact-phone" name="contact_phone">
          </div>
        </div>
        
        <div class="form-group">
          <button type="submit" class="btn btn-primary btn-lg">Submit Report</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    // Sample data for demonstration
    const petReports = [
      {
        id: 1,
        type: 'lost',
        name: 'Max',
        petType: 'dog',
        breed: 'Golden Retriever',
        color: 'Golden',
        date: '2023-06-15',
        location: 'Central Park, New York',
        description: 'Friendly golden retriever answers to Max. Last seen wearing a blue collar with contact info. He is microchipped and may be nervous around strangers.',
        image: 'https://images.unsplash.com/photo-1552053831-71594a27632d?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=662&q=80',
        contact: { email: 'owner@example.com', phone: '(555) 123-4567' },
        status: 'lost'
      },
      {
        id: 2,
        type: 'found',
        name: 'Unknown',
        petType: 'cat',
        breed: 'Tabby',
        color: 'Orange and white',
        date: '2023-06-18',
        location: 'Maple Street, Brooklyn',
        description: 'Found this friendly tabby cat near Maple Street. No collar but very affectionate. Appears to be well cared for and is comfortable around people.',
        image: 'https://images.unsplash.com/photo-1514888286974-6c03e2ca1dba?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=686&q=80',
        contact: { email: 'finder@example.com', phone: '(555) 987-6543' },
        status: 'not_reunited'
      },
      {
        id: 3,
        type: 'lost',
        name: 'Bella',
        petType: 'dog',
        breed: 'French Bulldog',
        color: 'Brindle',
        date: '2023-06-20',
        location: 'Downtown, Queens',
        description: 'Small French Bulldog answers to Bella. Microchipped but no collar. Very shy and may hide if approached. Reward offered for safe return.',
        image: 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
        contact: { email: 'bellaowner@example.com', phone: '(555) 456-7890' },
        status: 'reunited'
      },
      {
        id: 4,
        type: 'found',
        name: 'Unknown',
        petType: 'rabbit',
        breed: 'Lop',
        color: 'White with gray spots',
        date: '2023-06-22',
        location: 'Prospect Park, Brooklyn',
        description: 'Found this domesticated rabbit near the park entrance. Seems well cared for but scared. Currently being fostered until owner is found.',
        image: 'https://images.unsplash.com/photo-1585110396000-c9ffd4e4b308?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=687&q=80',
        contact: { email: 'rabbitsaver@example.com', phone: '(555) 234-5678' },
        status: 'not_reunited'
      },
      {
        id: 5,
        type: 'lost',
        name: 'Charlie',
        petType: 'bird',
        breed: 'Cockatiel',
        color: 'Gray with yellow head',
        date: '2023-06-25',
        location: 'Astoria, Queens',
        description: 'Friendly cockatiel answers to Charlie. Can say "pretty bird" and whistles. May land on shoulders if comfortable. Last seen near 30th Ave.',
        image: 'https://images.unsplash.com/photo-1522926193341-e9ffd686c60f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1170&q=80',
        contact: { email: 'charlieowner@example.com', phone: '(555) 765-4321' },
        status: 'lost'
      },
      {
        id: 6,
        type: 'found',
        name: 'Unknown',
        petType: 'dog',
        breed: 'Chihuahua Mix',
        color: 'Tan and white',
        date: '2023-06-26',
        location: 'Upper East Side, Manhattan',
        description: 'Found this small dog wandering near Central Park. Appears to be older, with some gray around the muzzle. No collar but very sweet temperament.',
        image: 'https://images.unsplash.com/photo-1608744882201-52a7f7f3dd60?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=761&q=80',
        contact: { email: 'goodsamaritan@example.com', phone: '(555) 876-5432' },
        status: 'reunited'
      }
    ];

    // Function to display pet reports
    function displayPets(filter = 'all') {
      const container = document.getElementById('pets-container');
      container.innerHTML = '';
      
      const filteredPets = petReports.filter(pet => {
        if (filter === 'all') return true;
        if (filter === 'lost') return pet.type === 'lost' && pet.status !== 'reunited';
        if (filter === 'found') return pet.type === 'found' && pet.status !== 'reunited';
        if (filter === 'reunited') return pet.status === 'reunited';
        return true;
      });
      
      if (filteredPets.length === 0) {
        container.innerHTML = '<div class="text-center" style="grid-column: 1 / -1; padding: 40px;"><h3>No pets found</h3><p>There are currently no reports matching your criteria.</p></div>';
        return;
      }
      
      filteredPets.forEach(pet => {
        const statusClass = pet.status === 'lost' ? 'status-lost' : 
                           pet.status === 'reunited' ? 'status-reunited' : 'status-found';
        const statusText = pet.status === 'lost' ? 'Lost' : 
                          pet.status === 'reunited' ? 'Reunited' : 'Found';
        
        const petCard = document.createElement('div');
        petCard.className = 'pet-card';
        petCard.innerHTML = `
          <div class="pet-image" style="background-image: url('${pet.image}')">
            <div class="pet-status ${statusClass}">${statusText}</div>
          </div>
          <div class="pet-details">
            <h3 class="pet-name">${pet.name}</h3>
            <div class="pet-meta">
              <span><i class="fas fa-paw"></i> ${pet.petType}</span>
              <span><i class="fas fa-map-marker-alt"></i> ${pet.location}</span>
              <span><i class="far fa-calendar"></i> ${new Date(pet.date).toLocaleDateString()}</span>
            </div>
            <p class="pet-description">${pet.description}</p>
            <div class="pet-contact">
              <div class="contact-info">
                <strong>Contact:</strong>
                ${pet.contact.email}<br>
                ${pet.contact.phone ? pet.contact.phone : ''}
              </div>
              <a href="#" class="btn btn-primary">View Details</a>
            </div>
          </div>
        `;
        container.appendChild(petCard);
      });
    }

    // Initialize the page
    document.addEventListener('DOMContentLoaded', function() {
      // Display all pets initially
      displayPets();
      
      // Filter buttons functionality
      document.querySelectorAll('.filter-btn').forEach(button => {
        button.addEventListener('click', function() {
          document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active'));
          this.classList.add('active');
          displayPets(this.dataset.filter);
        });
      });
      
      // Modal functionality
      const modal = document.getElementById('report-modal');
      const reportBtn = document.getElementById('report-pet-btn');
      const closeModal = document.querySelector('.close-modal');
      const reportType = document.getElementById('report-type');
      const lastSeenGroup = document.getElementById('last-seen-group');
      const foundDateGroup = document.getElementById('found-date-group');
      
      reportBtn.addEventListener('click', function() {
        modal.style.display = 'block';
      });
      
      closeModal.addEventListener('click', function() {
        modal.style.display = 'none';
      });
      
      window.addEventListener('click', function(event) {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      });
      
      // Toggle date fields based on report type
      reportType.addEventListener('change', function() {
        if (this.value === 'lost') {
          lastSeenGroup.style.display = 'block';
          foundDateGroup.style.display = 'none';
          document.getElementById('pet-name').setAttribute('required', 'required');
        } else if (this.value === 'found') {
          lastSeenGroup.style.display = 'none';
          foundDateGroup.style.display = 'block';
          document.getElementById('pet-name').removeAttribute('required');
        } else {
          lastSeenGroup.style.display = 'none';
          foundDateGroup.style.display = 'none';
        }
      });
      
      // Form submission
      document.getElementById('pet-report-form').addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Thank you for your report! In a real application, this would be saved to our database.');
        modal.style.display = 'none';
        // Here you would typically send the form data to your server
      });
    });
  </script>
</body>
</html>