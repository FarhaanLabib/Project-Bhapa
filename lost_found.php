<?php
include 'db.php';

// Handle delete requests
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    if ($_POST['action'] == 'delete_lost' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM lost_pets WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    } elseif ($_POST['action'] == 'delete_found' && isset($_POST['id'])) {
        $id = (int)$_POST['id'];
        $stmt = $conn->prepare("DELETE FROM found WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
    }
}

// Initialize arrays
$lost_pets = [];
$found_pets = [];

// Fetch lost pets
$lost_query = "SELECT * FROM lost_pets ORDER BY id DESC LIMIT 10";
$lost_result = $conn->query($lost_query);
if ($lost_result && $lost_result->num_rows > 0) {
    while ($row = $lost_result->fetch_assoc()) {
        $lost_pets[] = $row;
    }
}

// Fetch found pets
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
  <title>Bhapa - Lost & Found</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
      background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
      color: var(--dark);
      line-height: 1.6;
      min-height: 100vh;
    }

    .container {
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }

    /* Header */
    header {
      background: white;
      box-shadow: var(--shadow);
      position: sticky;
      top: 0;
      z-index: 1000;
    }

    .header-content {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 20px 0;
    }

    .logo {
      font-size: 2rem;
      font-weight: 700;
      color: var(--primary);
      text-decoration: none;
      display: flex;
      align-items: center;
    }

    .logo i {
      margin-right: 10px;
      color: var(--accent);
    }

    nav {
      display: flex;
      gap: 30px;
    }

    nav a {
      color: var(--dark);
      text-decoration: none;
      font-weight: 500;
      font-size: 1rem;
      transition: var(--transition);
      position: relative;
      padding: 10px 0;
    }

    nav a:hover {
      color: var(--primary);
    }

    nav a:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: var(--transition);
    }

    nav a:hover:after {
      width: 100%;
    }

    /* Hero Section */
    .hero {
      text-align: center;
      padding: 80px 0;
      background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
      color: white;
      margin-bottom: 60px;
    }

    .hero h1 {
      font-size: 3.5rem;
      font-weight: 700;
      margin-bottom: 20px;
      opacity: 0;
      animation: fadeInUp 1s ease forwards;
    }

    .hero p {
      font-size: 1.2rem;
      opacity: 0.9;
      max-width: 600px;
      margin: 0 auto;
      opacity: 0;
      animation: fadeInUp 1s ease 0.3s forwards;
    }

    @keyframes fadeInUp {
      to {
        opacity: 1;
        transform: translateY(0);
      }
      from {
        opacity: 0;
        transform: translateY(30px);
      }
    }

    /* Cards Section */
    .cards-section {
      margin-bottom: 80px;
    }

    .section-title {
      text-align: center;
      margin-bottom: 50px;
    }

    .section-title h2 {
      font-size: 2.5rem;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 15px;
    }

    .section-title p {
      font-size: 1.1rem;
      color: var(--gray);
    }

    .cards-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
      gap: 30px;
      margin-bottom: 40px;
    }

    .pet-card {
      background: white;
      border-radius: 20px;
      padding: 30px;
      box-shadow: var(--shadow);
      transition: var(--transition);
      position: relative;
      overflow: hidden;
    }

    .pet-card:hover {
      transform: translateY(-10px);
      box-shadow: 0 8px 30px rgba(0,0,0,0.15);
    }

    .pet-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--primary), var(--accent));
    }

    .card-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 20px;
    }

    .pet-type {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .pet-type i {
      font-size: 1.5rem;
      color: var(--primary);
    }

    .status-badge {
      padding: 8px 16px;
      border-radius: 20px;
      font-size: 0.9rem;
      font-weight: 600;
      text-transform: uppercase;
    }

    .status-lost {
      background: rgba(255, 107, 97, 0.1);
      color: var(--accent);
    }

    .status-found {
      background: rgba(76, 201, 240, 0.1);
      color: var(--success);
    }

    .pet-name {
      font-size: 1.8rem;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 15px;
    }

    .pet-details {
      margin-bottom: 20px;
    }

    .detail-item {
      display: flex;
      align-items: center;
      gap: 10px;
      margin-bottom: 10px;
      color: var(--gray);
    }

    .detail-item i {
      width: 20px;
      color: var(--primary);
    }

    .pet-location {
      background: rgba(67, 97, 238, 0.05);
      padding: 15px;
      border-radius: 10px;
      margin-bottom: 20px;
    }

    .location-label {
      font-size: 0.9rem;
      color: var(--gray);
      margin-bottom: 5px;
    }

    .location-text {
      font-weight: 500;
      color: var(--dark);
    }

    .card-footer {
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .date-info {
      font-size: 0.9rem;
      color: var(--gray);
    }

    .action-btn {
      background: var(--success);
      color: white;
      border: none;
      padding: 12px 24px;
      border-radius: 25px;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      font-size: 0.9rem;
    }

    .action-btn:hover {
      background: #3ba8c4;
      transform: scale(1.05);
    }

    .action-btn.delete {
      background: var(--accent);
    }

    .action-btn.delete:hover {
      background: #e55a4a;
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
    }

    /* Responsive */
    @media (max-width: 768px) {
      .header-content {
        flex-direction: column;
        gap: 20px;
      }

      nav {
        gap: 20px;
      }

      .hero h1 {
        font-size: 2.5rem;
      }

      .cards-grid {
        grid-template-columns: 1fr;
        gap: 20px;
      }

      .pet-card {
        padding: 20px;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="container">
    <div class="header-content">
      <a href="index.php" class="logo">
        <i class="fas fa-paw"></i>Bhapa
      </a>
      <nav>
        <a href="index.php">HOME</a>
        <a href="reportfound.php">REPORT FOUND</a>
        <a href="reportlost.php">REPORT LOST</a>
      </nav>
    </div>
  </div>
</header>

<section class="hero">
  <div class="container">
    <h1>Lost & Found Pets</h1>
    <p>Help reunite lost pets with their families. Report lost or found pets in your community.</p>
  </div>
</section>

<section class="cards-section">
  <div class="container">
    <div class="section-title">
      <h2>Recent Reports</h2>
      <p>Latest lost and found pet reports from our community</p>
    </div>

    <!-- Lost Pets -->
    <?php if (!empty($lost_pets)): ?>
      <div class="cards-grid">
        <?php foreach ($lost_pets as $pet): ?>
          <div class="pet-card">
            <div class="card-header">
              <div class="pet-type">
                <i class="fas fa-search"></i>
                <span class="status-badge status-lost">Lost</span>
              </div>
            </div>
            
            <div class="pet-name">
              <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
            </div>
            
            <div class="pet-details">
              <div class="detail-item">
                <i class="fas fa-paw"></i>
                <span><?php echo htmlspecialchars($pet['animal']); ?></span>
              </div>
              <?php if (!empty($pet['breed'])): ?>
                <div class="detail-item">
                  <i class="fas fa-tag"></i>
                  <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($pet['age'])): ?>
                <div class="detail-item">
                  <i class="fas fa-calendar"></i>
                  <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                </div>
              <?php endif; ?>
            </div>
            
            <div class="pet-location">
              <div class="location-label">Last Seen</div>
              <div class="location-text"><?php echo htmlspecialchars($pet['lost_at'] ?? 'Location not specified'); ?></div>
            </div>
            
            <div class="card-footer">
              <div class="date-info">
                <i class="fas fa-clock"></i>
                Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?>
              </div>
              <form method="POST" style="display: inline;" onsubmit="return confirm('Mark this pet as found? This will remove it from the lost pets list.');">
                <input type="hidden" name="action" value="delete_lost">
                <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">
                <button type="submit" class="action-btn">Found</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-search"></i>
        <h3>No Lost Pets Reported</h3>
        <p>Be the first to report a lost pet in your community.</p>
      </div>
    <?php endif; ?>

    <!-- Found Pets -->
    <?php if (!empty($found_pets)): ?>
      <div class="cards-grid">
        <?php foreach ($found_pets as $pet): ?>
          <div class="pet-card">
            <div class="card-header">
              <div class="pet-type">
                <i class="fas fa-heart"></i>
                <span class="status-badge status-found">Found</span>
              </div>
            </div>
            
            <div class="pet-name">
              <?php echo !empty($pet['name']) ? htmlspecialchars($pet['name']) : 'Unknown Name'; ?>
            </div>
            
            <div class="pet-details">
              <div class="detail-item">
                <i class="fas fa-paw"></i>
                <span><?php echo htmlspecialchars($pet['animal']); ?></span>
              </div>
              <?php if (!empty($pet['breed'])): ?>
                <div class="detail-item">
                  <i class="fas fa-tag"></i>
                  <span><?php echo htmlspecialchars($pet['breed']); ?></span>
                </div>
              <?php endif; ?>
              <?php if (!empty($pet['age'])): ?>
                <div class="detail-item">
                  <i class="fas fa-calendar"></i>
                  <span><?php echo htmlspecialchars($pet['age']); ?> years old</span>
                </div>
              <?php endif; ?>
            </div>
            
            <div class="pet-location">
              <div class="location-label">Found At</div>
              <div class="location-text"><?php echo htmlspecialchars($pet['found_at'] ?? 'Location not specified'); ?></div>
            </div>
            
            <div class="card-footer">
              <div class="date-info">
                <i class="fas fa-clock"></i>
                Reported: <?php echo isset($pet['date_reported']) ? date('M j, Y', strtotime($pet['date_reported'])) : 'Recently'; ?>
              </div>
              <form method="POST" style="display: inline;" onsubmit="return confirm('Mark this pet as reunited? This will remove it from the found pets list.');">
                <input type="hidden" name="action" value="delete_found">
                <input type="hidden" name="id" value="<?php echo $pet['id']; ?>">
                <button type="submit" class="action-btn">Reunited</button>
              </form>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    <?php else: ?>
      <div class="empty-state">
        <i class="fas fa-heart"></i>
        <h3>No Found Pets Reported</h3>
        <p>Help reunite lost pets by reporting any found animals.</p>
      </div>
    <?php endif; ?>
  </div>
</section>

<script>
// Add smooth scrolling and animations
document.addEventListener('DOMContentLoaded', function() {
  // Add fade-in animation to cards
  const cards = document.querySelectorAll('.pet-card');
  cards.forEach((card, index) => {
    card.style.opacity = '0';
    card.style.transform = 'translateY(30px)';
    
    setTimeout(() => {
      card.style.transition = 'all 0.6s ease';
      card.style.opacity = '1';
      card.style.transform = 'translateY(0)';
    }, index * 100);
  });
});
</script>

</body>
</html>