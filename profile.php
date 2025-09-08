<?php
session_start();
include 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$stmt = $conn->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();
$stmt->close();

// Fetch user's vet appointments
$stmt = $conn->prepare("
    SELECT a.id, a.pet_id, a.appointment_date, a.appointment_time, a.reason, a.status, p.name as pet_name, p.type as pet_type
    FROM appointments a
    JOIN pets p ON a.pet_id = p.id
    WHERE a.user_id = ?
    ORDER BY a.appointment_date DESC, a.appointment_time DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$appointments_result = $stmt->get_result();
$appointments = $appointments_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch user's pets
$stmt = $conn->prepare("SELECT id, name, type as pet_type, breed, age FROM pets WHERE user_id = ? ORDER BY name ASC");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$pets_result = $stmt->get_result();
$pets = $pets_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Fetch user's photographer bookings
$stmt = $conn->prepare("
    SELECT b.id, b.pet_name, b.pet_type, b.session_type, b.booking_date, b.booking_time, b.duration_hours, b.special_requests, p.name as photographer_name
    FROM bookings b
    JOIN photographers p ON b.photographer_id = p.id
    WHERE b.user_id = ?
    ORDER BY b.booking_date DESC, b.booking_time DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$bookings_result = $stmt->get_result();
$bookings = $bookings_result->fetch_all(MYSQLI_ASSOC);
$stmt->close();

// Calculate statistics
$total_appointments = count($appointments);
$upcoming_appointments = 0;
$completed_appointments = 0;
$current_date = date('Y-m-d');

foreach ($appointments as $appointment) {
    if ($appointment['appointment_date'] >= $current_date) {
        $upcoming_appointments++;
    } else {
        $completed_appointments++;
    }
}

$total_pets = count($pets);
$total_bookings = count($bookings);
$upcoming_bookings = 0;
$completed_bookings = 0;

foreach ($bookings as $booking) {
    if ($booking['booking_date'] >= $current_date) {
        $upcoming_bookings++;
    } else {
        $completed_bookings++;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile - Bhapa</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --primary: #4361ee;
  --secondary: #3a0ca3;
  --accent: #ff6f61;
  --success: #4cc9f0;
  --warning: #f39c12;
  --light: #f8f9fa;
  --dark: #212529;
  --gray: #6c757d;
  --shadow: 0 4px 20px rgba(0,0,0,0.1);
  --transition: all 0.3s ease;
}

* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

body {
  font-family: 'Inter', sans-serif;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: var(--dark);
  line-height: 1.6;
  min-height: 100vh;
}

/* Navbar */
.navbar {
  background: white;
  box-shadow: var(--shadow);
  position: sticky;
  top: 0;
  z-index: 1000;
  margin-bottom: 20px;
}

.navbar-content {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px 20px;
  max-width: 1200px;
  margin: 0 auto;
}

.navbar-logo {
  font-size: 1.8rem;
  font-weight: 700;
  color: var(--primary);
  text-decoration: none;
  display: flex;
  align-items: center;
  font-family: 'Playfair Display', serif;
}

.navbar-logo i {
  margin-right: 10px;
  color: var(--accent);
}

.navbar-nav {
  display: flex;
  align-items: center;
}

.navbar-nav a {
  color: var(--dark);
  text-decoration: none;
  font-weight: 500;
  font-size: 1rem;
  transition: var(--transition);
  padding: 8px 16px;
  border-radius: 8px;
}

.navbar-nav a:hover {
  color: var(--primary);
  background: rgba(67, 97, 238, 0.1);
}

.container {
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
}

/* Header */
.profile-header {
  background: white;
  border-radius: 20px;
  padding: 40px;
  margin-bottom: 30px;
  box-shadow: var(--shadow);
  position: relative;
  overflow: hidden;
}

.profile-header::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 4px;
  background: linear-gradient(90deg, var(--primary), var(--accent));
}

.profile-info {
  display: flex;
  align-items: center;
  gap: 30px;
  margin-bottom: 30px;
}

.profile-avatar {
  width: 120px;
  height: 120px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 3rem;
  color: white;
  font-weight: 700;
  position: relative;
  box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
}

.profile-avatar::after {
  content: '';
  position: absolute;
  inset: -4px;
  border-radius: 50%;
  background: linear-gradient(135deg, var(--primary), var(--accent));
  z-index: -1;
  opacity: 0.3;
}

.profile-details h1 {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 10px;
  font-family: 'Playfair Display', serif;
}

.profile-details p {
  color: var(--gray);
  font-size: 1.1rem;
  margin-bottom: 5px;
}

.profile-details .email {
  display: flex;
  align-items: center;
  gap: 10px;
  color: var(--primary);
  font-weight: 500;
}

/* Stats Cards */
.stats-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 20px;
  margin-bottom: 30px;
}

.stat-card {
  background: white;
  padding: 25px;
  border-radius: 15px;
  text-align: center;
  box-shadow: var(--shadow);
  transition: var(--transition);
  position: relative;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 8px 30px rgba(0,0,0,0.15);
}

.stat-card::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  height: 3px;
  background: var(--primary);
}

.stat-card.upcoming::before {
  background: var(--success);
}

.stat-card.completed::before {
  background: var(--warning);
}

.stat-icon {
  font-size: 2.5rem;
  margin-bottom: 15px;
  color: var(--primary);
}

.stat-card.upcoming .stat-icon {
  color: var(--success);
}

.stat-card.completed .stat-icon {
  color: var(--warning);
}

.stat-number {
  font-size: 2.5rem;
  font-weight: 700;
  color: var(--dark);
  margin-bottom: 5px;
}

.stat-label {
  color: var(--gray);
  font-weight: 500;
  text-transform: uppercase;
  font-size: 0.9rem;
  letter-spacing: 1px;
}

/* Quick Actions */
.quick-actions {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
}

.action-btn {
  padding: 12px 24px;
  border: none;
  border-radius: 25px;
  font-weight: 600;
  cursor: pointer;
  transition: var(--transition);
  text-decoration: none;
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 0.9rem;
}

.action-btn-primary {
  background: var(--primary);
  color: white;
}

.action-btn-primary:hover {
  background: var(--secondary);
  transform: translateY(-2px);
}

.action-btn-secondary {
  background: transparent;
  color: var(--primary);
  border: 2px solid var(--primary);
}

.action-btn-secondary:hover {
  background: var(--primary);
  color: white;
}

/* Bookings Section */
.bookings-section {
  background: white;
  border-radius: 20px;
  padding: 40px;
  box-shadow: var(--shadow);
}

.section-title {
  display: flex;
  align-items: center;
  gap: 15px;
  margin-bottom: 30px;
}

.section-title h2 {
  font-size: 2rem;
  font-weight: 600;
  color: var(--dark);
  font-family: 'Playfair Display', serif;
}

.section-title i {
  color: var(--primary);
  font-size: 1.5rem;
}

/* Booking Cards */
.bookings-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
  gap: 25px;
}

.booking-card {
  background: #f8f9fa;
  border-radius: 15px;
  padding: 25px;
  transition: var(--transition);
  border-left: 4px solid var(--primary);
  position: relative;
}

.booking-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.booking-card.upcoming {
  border-left-color: var(--success);
  background: rgba(76, 201, 240, 0.05);
}

.booking-card.completed {
  border-left-color: var(--warning);
  background: rgba(243, 156, 18, 0.05);
}

.booking-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-start;
  margin-bottom: 20px;
}

.booking-status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.8rem;
  font-weight: 600;
  text-transform: uppercase;
}

.booking-status.upcoming {
  background: rgba(76, 201, 240, 0.2);
  color: var(--success);
}

.booking-status.completed {
  background: rgba(243, 156, 18, 0.2);
  color: var(--warning);
}

.booking-details {
  margin-bottom: 20px;
}

.booking-detail {
  display: flex;
  align-items: center;
  gap: 10px;
  margin-bottom: 10px;
  color: var(--gray);
}

.booking-detail i {
  width: 20px;
  color: var(--primary);
}

.booking-detail strong {
  color: var(--dark);
  font-weight: 600;
}

.booking-pet {
  font-size: 1.2rem;
  font-weight: 600;
  color: var(--dark);
  margin-bottom: 15px;
}

.booking-photographer {
  font-size: 1.1rem;
  color: var(--primary);
  font-weight: 500;
}

/* Empty State */
.empty-state {
  text-align: center;
  padding: 60px 20px;
  color: var(--gray);
}

.empty-state i {
  font-size: 4rem;
  margin-bottom: 20px;
  opacity: 0.5;
}

.empty-state h3 {
  font-size: 1.5rem;
  margin-bottom: 10px;
  color: var(--primary);
}

.empty-state p {
  margin: 0;
  font-size: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
  .profile-info {
    flex-direction: column;
    text-align: center;
  }
  
  .profile-details h1 {
    font-size: 2rem;
  }
  
  .stats-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  
  .bookings-grid {
    grid-template-columns: 1fr;
  }
  
  .quick-actions {
    justify-content: center;
  }
  
  .container {
    padding: 15px;
  }
  
  .profile-header,
  .bookings-section {
    padding: 25px;
  }
}

@media (max-width: 480px) {
  .stats-grid {
    grid-template-columns: 1fr;
  }
  
  .profile-avatar {
    width: 100px;
    height: 100px;
    font-size: 2.5rem;
  }
}
</style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar">
  <div class="navbar-content">
    <a href="index.php" class="navbar-logo">
      <i class="fas fa-paw"></i>Bhapa
    </a>
    <div class="navbar-nav">
      <a href="index.php">Home</a>
    </div>
  </div>
</nav>

<div class="container">
  <!-- Profile Header -->
  <div class="profile-header">
    <div class="profile-info">
      <div class="profile-avatar">
        <?php echo strtoupper(substr($user['name'], 0, 1)); ?>
      </div>
      <div class="profile-details">
        <h1><?php echo htmlspecialchars($user['name']); ?></h1>
        <div class="email">
          <i class="fas fa-envelope"></i>
          <span><?php echo htmlspecialchars($user['email']); ?></span>
        </div>
      </div>
    </div>

    <!-- Stats Cards -->
    <div class="stats-grid">
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-paw"></i>
        </div>
        <div class="stat-number"><?php echo $total_pets; ?></div>
        <div class="stat-label">My Pets</div>
      </div>
      
      <div class="stat-card upcoming">
        <div class="stat-icon">
          <i class="fas fa-calendar-alt"></i>
        </div>
        <div class="stat-number"><?php echo $upcoming_appointments; ?></div>
        <div class="stat-label">Upcoming Vet Visits</div>
      </div>
      
      <div class="stat-card completed">
        <div class="stat-icon">
          <i class="fas fa-stethoscope"></i>
        </div>
        <div class="stat-number"><?php echo $completed_appointments; ?></div>
        <div class="stat-label">Completed Vet Visits</div>
      </div>
      
      <div class="stat-card">
        <div class="stat-icon">
          <i class="fas fa-camera"></i>
        </div>
        <div class="stat-number"><?php echo $total_bookings; ?></div>
        <div class="stat-label">Photo Sessions</div>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="quick-actions">
      <a href="vet.php" class="action-btn action-btn-primary">
        <i class="fas fa-stethoscope"></i>
        Book Vet Appointment
      </a>
      <a href="photography.php" class="action-btn action-btn-primary">
        <i class="fas fa-camera"></i>
        Book Photo Session
      </a>
      <a href="mypets.php" class="action-btn action-btn-secondary">
        <i class="fas fa-paw"></i>
        My Pets
      </a>
      <a href="marketplace.php" class="action-btn action-btn-secondary">
        <i class="fas fa-shopping-cart"></i>
        Marketplace
      </a>
    </div>
  </div>

  <!-- Appointments Section -->
  <div class="bookings-section">
    <div class="section-title">
      <i class="fas fa-stethoscope"></i>
      <h2>My Vet Appointments</h2>
    </div>

    <?php if(count($appointments) > 0): ?>
      <div class="bookings-grid">
        <?php foreach($appointments as $appointment): ?>
          <?php 
          $is_upcoming = $appointment['appointment_date'] >= $current_date;
          $card_class = $is_upcoming ? 'upcoming' : 'completed';
          $status_class = $is_upcoming ? 'upcoming' : 'completed';
          $status_text = $is_upcoming ? 'Upcoming' : 'Completed';
          ?>
          <div class="booking-card <?php echo $card_class; ?>">
            <div class="booking-header">
              <div class="booking-status <?php echo $status_class; ?>">
                <?php echo $status_text; ?>
              </div>
            </div>
            
            <div class="booking-pet">
              <i class="fas fa-paw"></i>
              <?php echo htmlspecialchars($appointment['pet_name']); ?>
            </div>
            
            <div class="booking-details">
              <div class="booking-detail">
                <i class="fas fa-paw"></i>
                <span><strong>Pet Type:</strong> <?php echo htmlspecialchars($appointment['pet_type']); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-calendar"></i>
                <span><strong>Date:</strong> <?php echo date('M j, Y', strtotime($appointment['appointment_date'])); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-clock"></i>
                <span><strong>Time:</strong> <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-stethoscope"></i>
                <span><strong>Reason:</strong> <?php echo htmlspecialchars($appointment['reason']); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-info-circle"></i>
                <span><strong>Status:</strong> <?php echo htmlspecialchars($appointment['status']); ?></span>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-calendar-plus"></i>
        <h3>No Appointments Yet</h3>
        <p>Book your first vet appointment to get started!</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Pets Section -->
  <div class="bookings-section">
    <div class="section-title">
      <i class="fas fa-paw"></i>
      <h2>My Pets</h2>
    </div>

    <?php if(count($pets) > 0): ?>
      <div class="bookings-grid">
        <?php foreach($pets as $pet): ?>
          <div class="booking-card">
            <div class="booking-pet">
              <i class="fas fa-paw"></i>
              <?php echo htmlspecialchars($pet['name']); ?>
            </div>
            
            <div class="booking-details">
              <div class="booking-detail">
                <i class="fas fa-paw"></i>
                <span><strong>Type:</strong> <?php echo htmlspecialchars($pet['pet_type']); ?></span>
              </div>
              <?php if (!empty($pet['breed'])): ?>
                <div class="booking-detail">
                  <i class="fas fa-tag"></i>
                  <span><strong>Breed:</strong> <?php echo htmlspecialchars($pet['breed']); ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($pet['age'])): ?>
                <div class="booking-detail">
                  <i class="fas fa-calendar"></i>
                  <span><strong>Age:</strong> <?php echo htmlspecialchars($pet['age']); ?> years old</span>
                </div>
              <?php endif; ?>
            </div>
          </div>
            <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-paw"></i>
        <h3>No Pets Added Yet</h3>
        <p>Add your first pet to get started!</p>
      </div>
    <?php endif; ?>
  </div>

  <!-- Photographer Bookings Section -->
  <div class="bookings-section">
    <div class="section-title">
      <i class="fas fa-camera"></i>
      <h2>My Photo Sessions</h2>
    </div>

    <?php if(count($bookings) > 0): ?>
      <div class="bookings-grid">
        <?php foreach($bookings as $booking): ?>
          <?php 
          $is_upcoming = $booking['booking_date'] >= $current_date;
          $card_class = $is_upcoming ? 'upcoming' : 'completed';
          $status_class = $is_upcoming ? 'upcoming' : 'completed';
          $status_text = $is_upcoming ? 'Upcoming' : 'Completed';
          ?>
          <div class="booking-card <?php echo $card_class; ?>">
            <div class="booking-header">
              <div class="booking-status <?php echo $status_class; ?>">
                <?php echo $status_text; ?>
              </div>
            </div>
            
            <div class="booking-pet">
              <i class="fas fa-paw"></i>
              <?php echo htmlspecialchars($booking['pet_name']); ?>
            </div>
            
            <div class="booking-photographer">
              <i class="fas fa-camera"></i>
              <?php echo htmlspecialchars($booking['photographer_name']); ?>
            </div>
            
            <div class="booking-details">
              <div class="booking-detail">
                <i class="fas fa-paw"></i>
                <span><strong>Pet Type:</strong> <?php echo htmlspecialchars($booking['pet_type']); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-calendar"></i>
                <span><strong>Date:</strong> <?php echo date('M j, Y', strtotime($booking['booking_date'])); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-clock"></i>
                <span><strong>Time:</strong> <?php echo date('g:i A', strtotime($booking['booking_time'])); ?></span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-hourglass-half"></i>
                <span><strong>Duration:</strong> <?php echo htmlspecialchars($booking['duration_hours']); ?> hours</span>
              </div>
              <div class="booking-detail">
                <i class="fas fa-tag"></i>
                <span><strong>Session:</strong> <?php echo htmlspecialchars($booking['session_type']); ?></span>
              </div>
              <?php if (!empty($booking['special_requests'])): ?>
                <div class="booking-detail">
                  <i class="fas fa-comment"></i>
                  <span><strong>Notes:</strong> <?php echo htmlspecialchars($booking['special_requests']); ?></span>
                </div>
              <?php endif; ?>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
        <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-camera"></i>
        <h3>No Photo Sessions Yet</h3>
        <p>Book your first pet photography session!</p>
      </div>
        <?php endif; ?>
  </div>
</div>

<script>
// Add smooth animations
document.addEventListener('DOMContentLoaded', function() {
  // Animate stats cards
  const statCards = document.querySelectorAll('.stat-card');
  statCards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 100);
  });

  // Animate booking cards
  const bookingCards = document.querySelectorAll('.booking-card');
  bookingCards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, (index * 100) + 300);
  });
});
</script>

</body>
</html>