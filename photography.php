<?php
include 'db.php';
include 'navbar.php';

// Handle booking form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_photographer'])) {
    $photographer_id = $_POST['photographer_id'];
    $user_id = $_SESSION['user_id'] ?? 0; // Assuming you have user authentication
    $pet_name = $_POST['pet_name'];
    $pet_type = $_POST['pet_type'];
    $session_type = $_POST['session_type'];
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];
    $duration_hours = $_POST['duration_hours'];
    $special_requests = $_POST['special_requests'];
    
    $stmt = $conn->prepare("INSERT INTO bookings (photographer_id, user_id, pet_name, pet_type, session_type, booking_date, booking_time, duration_hours, special_requests) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisssssis", $photographer_id, $user_id, $pet_name, $pet_type, $session_type, $booking_date, $booking_time, $duration_hours, $special_requests);
    
    if ($stmt->execute()) {
        $booking_success = "Your booking request has been submitted successfully!";
    } else {
        $booking_error = "There was an error processing your booking. Please try again.";
    }
}

// Fetch all photographers
$photographers = [];
$result = $conn->query("SELECT * FROM photographers WHERE is_available = 1 ORDER BY rating DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $photographers[] = $row;
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
  <title>Pet Photography - Book Professional Pet Photographers</title>
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
      background: #ffffff;
      color: var(--dark);
      line-height: 1.6;
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

    /* Hero Section */
    .photography-hero {
      background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
      color: white;
      padding: 160px 0 100px;
      position: relative;
      overflow: hidden;
      min-height: 60vh;
      display: flex;
      align-items: center;
    }
    
    .photography-hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" preserveAspectRatio="none"><path d="M0,0 L100,0 L100,100 Z" fill="rgba(255,255,255,0.05)"/></svg>');
      background-size: 100% 100%;
    }
    
    .photography-hero-content {
      max-width: 800px;
      position: relative;
      z-index: 2;
      text-align: center;
      margin: 0 auto;
    }
    
    .photography-hero h1 {
      margin-bottom: 24px;
      font-size: 4.2rem;
      line-height: 1.1;
    }
    
    .photography-hero p {
      font-size: 1.3rem;
      margin-bottom: 40px;
      opacity: 0.9;
      font-weight: 400;
    }
    
    .photography-hero-pattern {
      position: absolute;
      bottom: -100px;
      left: -100px;
      width: 400px;
      height: 400px;
      background: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200"><rect fill="rgba(24, 188, 156, 0.1)" width="200" height="200"/><path d="M0,0 L200,200" stroke="rgba(24, 188, 156, 0.2)" stroke-width="2"/></svg>');
      z-index: 0;
    }

    /* Photographer Cards */
    .photographers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 40px;
      margin-top: 50px;
    }
    
    .photographer-card {
      background: white;
      border-radius: 16px;
      overflow: hidden;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
    }
    
    .photographer-card:hover {
      transform: translateY(-15px);
      box-shadow: 0 20px 40px rgba(0,0,0,0.15);
    }
    
    .photographer-image {
      height: 280px;
      background-size: cover;
      background-position: center;
      position: relative;
    }
    
    .photographer-image:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 100%;
      height: 50%;
      background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
    }
    
    .photographer-specialty {
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
    
    .photographer-info {
      padding: 30px;
    }
    
    .photographer-name-rating {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .photographer-name {
      font-size: 1.5rem;
      font-family: 'Playfair Display', serif;
      margin: 0;
    }
    
    .photographer-rating {
      display: flex;
      align-items: center;
      color: #f39c12;
      font-weight: 600;
    }
    
    .photographer-rating i {
      margin-right: 5px;
    }
    
    .photographer-meta {
      display: flex;
      justify-content: space-between;
      margin-bottom: 20px;
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .photographer-meta span {
      display: flex;
      align-items: center;
    }
    
    .photographer-meta i {
      margin-right: 5px;
    }
    
    .photographer-description {
      margin-bottom: 25px;
      color: #555;
    }
    
    .photographer-actions {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .photographer-rate {
      font-weight: 700;
      font-size: 1.2rem;
      color: var(--primary);
    }
    
    /* Booking Modal */
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
      padding: 20px;
    }
    
    .modal-content {
      background: white;
      border-radius: 16px;
      max-width: 600px;
      margin: 50px auto;
      padding: 40px;
      position: relative;
      box-shadow: 0 20px 60px rgba(0,0,0,0.3);
      animation: modalFadeIn 0.5s;
    }
    
    @keyframes modalFadeIn {
      from { opacity: 0; transform: translateY(-50px); }
      to { opacity: 1; transform: translateY(0); }
    }
    
    .close-modal {
      position: absolute;
      top: 20px;
      right: 20px;
      font-size: 1.5rem;
      cursor: pointer;
      color: var(--gray);
      transition: var(--transition);
    }
    
    .close-modal:hover {
      color: var(--accent);
    }
    
    .modal-title {
      margin-bottom: 30px;
      text-align: center;
    }
    
    .form-group {
      margin-bottom: 25px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 600;
      color: var(--dark);
    }
    
    .form-control {
      width: 100%;
      padding: 15px 20px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      font-family: 'Montserrat', sans-serif;
      transition: var(--transition);
    }
    
    .form-control:focus {
      outline: none;
      border-color: var(--secondary);
      box-shadow: 0 0 0 3px rgba(24, 188, 156, 0.2);
    }
    
    .alert {
      padding: 15px 20px;
      border-radius: 8px;
      margin-bottom: 25px;
    }
    
    .alert-success {
      background: rgba(39, 174, 96, 0.1);
      color: var(--success);
      border: 1px solid rgba(39, 174, 96, 0.2);
    }
    
    .alert-error {
      background: rgba(231, 76, 60, 0.1);
      color: var(--accent);
      border: 1px solid rgba(231, 76, 60, 0.2);
    }
    
    /* Responsive Design */
    @media (max-width: 992px) {
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
      
      .photography-hero {
        padding: 120px 0 80px;
        min-height: auto;
      }
      
      .photographers-grid {
        grid-template-columns: 1fr;
      }
      
      .modal-content {
        padding: 30px 20px;
        margin: 20px auto;
      }
    }
  </style>
</head>
<body>
  <!-- Hero Section -->
  <section class="photography-hero">
    <div class="container">
      <div class="photography-hero-content">
        <h1 class="animate">Capture Precious Moments</h1>
        <p class="animate delay-1">Book professional pet photographers to create lasting memories of your furry friends. Our photographers specialize in capturing your pet's unique personality.</p>
      </div>
    </div>
    <div class="photography-hero-pattern"></div>
  </section>

  <!-- Photographers Section -->
  <section class="section-light">
    <div class="container">
      <div class="section-title text-center">
        <h2>Our Photographers</h2>
        <p>Choose from our talented team of professional pet photographers</p>
      </div>
      
      <div class="photographers-grid">
        <?php if (!empty($photographers)): ?>
          <?php foreach ($photographers as $photographer): ?>
            <div class="photographer-card animate">
              <div class="photographer-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <?php if (!empty($photographer['profile_picture'])): ?>
                  <img src="<?php echo $photographer['profile_picture']; ?>" alt="<?php echo htmlspecialchars($photographer['name']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
                <div class="photographer-specialty"><?php echo htmlspecialchars($photographer['specialty']); ?></div>
              </div>
              <div class="photographer-info">
                <div class="photographer-name-rating">
                  <h3 class="photographer-name"><?php echo htmlspecialchars($photographer['name']); ?></h3>
                  <div class="photographer-rating">
                    <i class="fas fa-star"></i> <?php echo $photographer['rating']; ?>
                  </div>
                </div>
                <div class="photographer-meta">
                  <span><i class="fas fa-briefcase"></i> <?php echo $photographer['experience_years']; ?> years experience</span>
                  <span><i class="fas fa-camera"></i> Pet Photography</span>
                </div>
                <p class="photographer-description"><?php echo htmlspecialchars($photographer['description']); ?></p>
                <div class="photographer-actions">
                  <div class="photographer-rate">$<?php echo $photographer['hourly_rate']; ?>/hour</div>
                  <button class="btn btn-primary" onclick="openBookingModal(<?php echo $photographer['id']; ?>, '<?php echo htmlspecialchars($photographer['name']); ?>')">Book Now</button>
                </div>
              </div>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <div class="text-center" style="grid-column: 1 / -1;">
            <p>No photographers available at the moment. Please check back later.</p>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </section>

  <!-- Booking Modal -->
  <div id="bookingModal" class="modal">
    <div class="modal-content">
      <span class="close-modal" onclick="closeBookingModal()">&times;</span>
      <div class="modal-title">
        <h2>Book a Photographer</h2>
        <p id="photographerName">Complete the form below to request a booking</p>
      </div>
      
      <?php if (isset($booking_success)): ?>
        <div class="alert alert-success"><?php echo $booking_success; ?></div>
      <?php endif; ?>
      
      <?php if (isset($booking_error)): ?>
        <div class="alert alert-error"><?php echo $booking_error; ?></div>
      <?php endif; ?>
      
      <form id="bookingForm" method="POST" action="">
        <input type="hidden" id="photographerId" name="photographer_id" value="">
        
        <div class="form-group">
          <label for="petName">Pet's Name</label>
          <input type="text" id="petName" name="pet_name" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="petType">Pet Type</label>
          <select id="petType" name="pet_type" class="form-control" required>
            <option value="">Select Pet Type</option>
            <option value="Dog">Dog</option>
            <option value="Cat">Cat</option>
            <option value="Bird">Bird</option>
            <option value="Reptile">Reptile</option>
            <option value="Small Mammal">Small Mammal</option>
            <option value="Other">Other</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="sessionType">Session Type</label>
          <select id="sessionType" name="session_type" class="form-control" required>
            <option value="">Select Session Type</option>
            <option value="Studio Portrait">Studio Portrait</option>
            <option value="Outdoor Session">Outdoor Session</option>
            <option value="Action Shots">Action Shots</option>
            <option value="Family Portrait with Pet">Family Portrait with Pet</option>
            <option value="Custom Session">Custom Session</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="bookingDate">Preferred Date</label>
          <input type="date" id="bookingDate" name="booking_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
        </div>
        
        <div class="form-group">
          <label for="bookingTime">Preferred Time</label>
          <input type="time" id="bookingTime" name="booking_time" class="form-control" required>
        </div>
        
        <div class="form-group">
          <label for="durationHours">Session Duration (hours)</label>
          <select id="durationHours" name="duration_hours" class="form-control" required>
            <option value="1">1 hour</option>
            <option value="2">2 hours</option>
            <option value="3">3 hours</option>
            <option value="4">4 hours</option>
          </select>
        </div>
        
        <div class="form-group">
          <label for="specialRequests">Special Requests</label>
          <textarea id="specialRequests" name="special_requests" class="form-control" rows="4"></textarea>
        </div>
        
        <button type="submit" name="book_photographer" class="btn btn-primary btn-lg" style="width: 100%;">Submit Booking Request</button>
      </form>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    // Open booking modal
    function openBookingModal(photographerId, photographerName) {
      document.getElementById('photographerId').value = photographerId;
      document.getElementById('photographerName').textContent = 'Booking with ' + photographerName;
      document.getElementById('bookingModal').style.display = 'block';
      document.body.style.overflow = 'hidden';
    }
    
    // Close booking modal
    function closeBookingModal() {
      document.getElementById('bookingModal').style.display = 'none';
      document.body.style.overflow = 'auto';
    }
    
    // Close modal when clicking outside
    window.onclick = function(event) {
      const modal = document.getElementById('bookingModal');
      if (event.target === modal) {
        closeBookingModal();
      }
    };
    
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
      
      // Set minimum date to today
      const today = new Date();
      const dd = String(today.getDate()).padStart(2, '0');
      const mm = String(today.getMonth() + 1).padStart(2, '0');
      const yyyy = today.getFullYear();
      document.getElementById('bookingDate').min = yyyy + '-' + mm + '-' + dd;
    });
  </script>
</body>
</html>