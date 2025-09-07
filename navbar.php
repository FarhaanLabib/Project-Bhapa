<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect logged-in users away from signup/login pages
$current_page = basename($_SERVER['PHP_SELF']);
$restricted_pages = ['signup.php', 'login.php'];

if (isset($_SESSION['user']) && in_array($current_page, $restricted_pages)) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Bhapa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary: #4361ee;
      --secondary: #3a0ca3;
      --accent: #ff6f61;
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --success: #4cc9f0;
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
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      color: var(--dark);
      line-height: 1.6;
    }

    header {
      background: white; 
      color: var(--dark); 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      padding: 15px 5%;
      position: sticky;
      top: 0;
      z-index: 1000;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }

    .logo {
      font-size: 1.8rem;
      font-weight: 800;
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
      align-items: center;
    }

    nav a {
      color: var(--dark);
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      margin-left: 25px;
      transition: var(--transition);
      position: relative;
    }

    nav a:hover {
      color: var(--primary);
    }

    nav a:after {
      content: '';
      position: absolute;
      bottom: -5px;
      left: 0;
      width: 0;
      height: 2px;
      background: var(--primary);
      transition: var(--transition);
    }

    nav a:hover:after {
      width: 100%;
    }

    nav a.active {
      color: var(--primary);
    }

    nav a.active:after {
      width: 100%;
    }

    /* Dropdown */
    .dropdown {
      position: relative;
      display: inline-block;
      
    }

    .dropdown-content {
      display: none;
      position: absolute;
      background: #fff;
      min-width: 250px;
      box-shadow: var(--shadow);
      border-radius: 8px;
      z-index: 1000;
    }

    .dropdown-content a {
      display: block;
      padding: 10px 15px;
      color: var(--dark);
      text-decoration: none;
      transition: var(--transition);
    }

    .dropdown-content a:hover {
      background: #f8f9fa;
      color: var(--primary);
    }

    .dropdown.show .dropdown-content {
      display: block;
    }

    #userCircle {
      width: 42px;
      height: 42px;
      background: var(--primary);
      color: white;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 18px;
      cursor: pointer;
      user-select: none;
      box-shadow: var(--shadow);
      position: relative;
      transition: var(--transition);
      margin-left: 20px;
    }

    #userCircle:hover {
      transform: scale(1.05);
    }

    #dropdownMenu {
      display: none;
      position: absolute;
      top: 60px;
      right: 0;
      background: #fff;
      border-radius: 8px;
      box-shadow: var(--shadow);
      padding: 10px 0;
      min-width: 180px;
      text-align: left;
      z-index: 1000;
    }

    #dropdownMenu.show {
      display: block;
    }

    #dropdownMenu a {
      display: block;
      padding: 10px 20px;
      color: var(--dark);
      text-decoration: none;
      transition: var(--transition);
      font-weight: 500;
    }

    #dropdownMenu a:hover {
      background: #f8f9fa;
      color: var(--primary);
    }

    #dropdownMenu button {
      background: transparent;
      border: none;
      color: #e63946;
      padding: 10px 20px;
      cursor: pointer;
      font-weight: 600;
      font-size: 14px;
      width: 100%;
      text-align: left;
      transition: var(--transition);
    }

    #dropdownMenu button:hover {
      background: #f8f9fa;
      color: #b32c37;
    }
  </style>
</head>
<body>

<header>
  <a href="index.php" class="logo"><i class="fas fa-calendar-alt"></i>Bhapa </a>
  <nav>
    <a href="index.php">Home</a>
 
    <a href="about.php">About</a>
    
 

    <!-- Medication Dropdown -->
    <div class="dropdown">
      <a href="javascript:void(0)" onclick="toggleMedication()">Medication <i class="fas fa-caret-down"></i></a>
      <div id="medicationDropdown" class="dropdown-content">
        <a href="medicationBlog.php">Medication Blog</a>
        <a href="medicationTracking.php">Medication Tracking</a>
      </div>
    </div>
    <a href="lost_found.php">Lost & Found</a>

    <a href="photography.php">Photography</a>
    

     <?php if (isset($_SESSION['user'])): ?>
         <a href="vet.php">Vet</a>
    <a href="mypets.php">My Pets</a>
    <a href="blog.php">Blogs</a>
    <a href="marketplace.php">Marketplace</a>
     <a href="contact.php">Contact</a>
    <?php else: ?>
      <a href="signup.php">Signup</a>
      <a href="login.php">Login</a>
    <?php endif; ?>
  </nav>

  <?php if (isset($_SESSION['user'])): ?>
    <?php $firstLetter = strtoupper(substr($_SESSION['user'], 0, 1)); ?>
    <div id="userCircle" onclick="toggleDropdown()" title="User Menu"><?php echo $firstLetter; ?>
      <div id="dropdownMenu">
        <a href="profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
        <a href="mypets.php"><i class="fas fa-ticket-alt"></i> My Pets</a>
        <a href="#"><i class="fas fa-cog"></i> Settings</a>
        <form method="post" action="logout.php">
          <button type="submit"><i class="fas fa-sign-out-alt"></i> Logout</button>
        </form>
      </div>
    </div>
  <?php endif; ?>
</header>

<script>
  function toggleDropdown() {
    const dropdown = document.getElementById('dropdownMenu');
    dropdown.classList.toggle('show');
  }

  function toggleMedication() {
    const menu = document.querySelector('.dropdown');
    menu.classList.toggle('show');
  }

  // Close dropdowns if clicking outside
  document.addEventListener('click', function (e) {
    const circle = document.getElementById('userCircle');
    const userDropdown = document.getElementById('dropdownMenu');
    const medDropdown = document.querySelector('.dropdown');

    if (circle && !circle.contains(e.target)) {
      userDropdown.classList.remove('show');
    }
    if (medDropdown && !medDropdown.contains(e.target)) {
      medDropdown.classList.remove('show');
    }
  });
</script>

</body>
</html>
