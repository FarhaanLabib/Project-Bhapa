<?php
include 'db.php'; // database connection

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal = $_POST['animal'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $age = $_POST['age'] ?? '';
    $name = $_POST['name'] ?? '';
    $foundat = $_POST['foundat'] ?? '';
    $datereported = $_POST['datereported'] ?? '';
    $petid = $_POST['petid'] ?? '';

    if (empty($animal) || empty($foundat) || empty($datereported)) {
        $message = "Please fill in all required fields!";
    } else {
        $stmt = $conn->prepare("INSERT INTO found (animal, breed, age, name, found_at, date_reported, pet_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $animal, $breed, $age, $name, $foundat, $datereported, $petid);

        if ($stmt->execute()) {
            $message = "success";
        } else {
            $message = "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bhapa - Report Found Pet</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3a0ca3;
      --accent: #ff6f61;
      --success: #4cc9f0;
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

    nav a.active:after {
      width: 100%;
    }

    /* Main Content */
    .main-content {
      padding: 60px 0;
    }

    .form-container {
      max-width: 600px;
      margin: 0 auto;
      background: white;
      border-radius: 20px;
      padding: 40px;
      box-shadow: var(--shadow);
      position: relative;
      overflow: hidden;
    }

    .form-container::before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      height: 4px;
      background: linear-gradient(90deg, var(--success), var(--primary));
    }

    .form-title {
      text-align: center;
      margin-bottom: 30px;
    }

    .form-title h2 {
      font-size: 2.2rem;
      font-weight: 600;
      color: var(--dark);
      margin-bottom: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 15px;
    }

    .form-title h2 i {
      color: var(--success);
    }

    .form-title p {
      color: var(--gray);
      font-size: 1rem;
    }

    .form-group {
      margin-bottom: 25px;
    }

    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
      color: var(--dark);
      font-size: 0.95rem;
    }

    .form-group input {
      width: 100%;
      padding: 15px 20px;
      border: 2px solid #e9ecef;
      border-radius: 12px;
      font-size: 1rem;
      transition: var(--transition);
      background: #f8f9fa;
    }

    .form-group input:focus {
      outline: none;
      border-color: var(--primary);
      background: white;
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.1);
    }

    .form-group input::placeholder {
      color: var(--gray);
    }

    .submit-btn {
      width: 100%;
      padding: 15px 20px;
      background: linear-gradient(135deg, var(--success) 0%, var(--primary) 100%);
      color: white;
      border: none;
      border-radius: 12px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
      margin-top: 10px;
    }

    .submit-btn:hover {
      transform: translateY(-2px);
      box-shadow: 0 8px 25px rgba(67, 97, 238, 0.3);
    }

    .submit-btn:active {
      transform: translateY(0);
    }

    /* Messages */
    .msg-success {
      padding: 15px 20px;
      background: rgba(76, 201, 240, 0.1);
      color: var(--success);
      border-radius: 12px;
      margin-bottom: 25px;
      border-left: 4px solid var(--success);
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .msg-error {
      padding: 15px 20px;
      background: rgba(255, 107, 97, 0.1);
      color: var(--accent);
      border-radius: 12px;
      margin-bottom: 25px;
      border-left: 4px solid var(--accent);
      display: flex;
      align-items: center;
      gap: 10px;
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

      .form-container {
        margin: 20px;
        padding: 30px 20px;
      }

      .form-title h2 {
        font-size: 1.8rem;
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
        <a href="reportfound.php" class="active">REPORT FOUND</a>
        <a href="reportlost.php">REPORT LOST</a>
      </nav>
    </div>
  </div>
</header>

<div class="main-content">
  <div class="container">
    <div class="form-container">
      <div class="form-title">
        <h2><i class="fas fa-heart"></i>Report Found Pet</h2>
        <p>Help reunite a found pet with its family</p>
      </div>

      <?php if (!empty($message)): ?>
        <?php if ($message == 'success'): ?>
          <div class="msg-success">
            <i class="fas fa-check-circle"></i>
            <span>✅ Reported Successfully!</span>
          </div>
        <?php else: ?>
          <div class="msg-error">
            <i class="fas fa-exclamation-circle"></i>
            <span><?= htmlspecialchars($message) ?></span>
          </div>
        <?php endif; ?>
      <?php endif; ?>

      <form method="POST">
        <div class="form-group">
          <label>Animal</label>
          <input type="text" name="animal" placeholder="Dog, Cat..." required>
        </div>
        <div class="form-group">
          <label>Breed</label>
          <input type="text" name="breed" placeholder="Breed (optional)">
        </div>
        <div class="form-group">
          <label>Age (if known)</label>
          <input type="text" name="age" placeholder="Age (optional)">
        </div>
        <div class="form-group">
          <label>Pet Name (if known)</label>
          <input type="text" name="name" placeholder="Pet name (optional)">
        </div>
        <div class="form-group">
          <label>Found At</label>
          <input type="text" name="foundat" placeholder="Location where you found the pet" required>
        </div>
        <div class="form-group">
          <label>Date Reported</label>
          <input type="date" name="datereported" required>
        </div>
        <div class="form-group">
          <label>Pet ID (if registered)</label>
          <input type="text" name="petid" placeholder="Pet ID (optional)">
        </div>
        <button type="submit" class="submit-btn">
          <i class="fas fa-paper-plane"></i> Submit Found Report
        </button>
      </form>
    </div>
  </div>
</div>

</body>
</html>
