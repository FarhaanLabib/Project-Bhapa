<?php
// lostandfound.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Lost & Found Pets</title>
  <style>
    body {
      margin: 0;
      font-family: Segoe UI, sans-serif;
      background: #f9f9f9;
    }
    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 30px;
      background: #fff;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }
    .logo {
      font-weight: bold;
      font-size: 20px;
    }
    nav a {
      margin-left: 20px;
      text-decoration: none;
      color: black;
      font-weight: 600;
    }
    nav a:hover {
      color: orange;
    }
    main {
      padding: 40px 20px;
      text-align: center;
    }
    .banner {
      background: url('https://place-puppy.com/900x300') no-repeat center;
      background-size: cover;
      border-radius: 12px;
      padding: 60px 20px;
      color: white;
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 40px;
    }
    .card {
      max-width: 600px;
      margin: 20px auto;
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      text-align: left;
    }
    .card h2 {
      margin-top: 0;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: 600;
    }
    .form-group input, .form-group textarea, .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
    button {
      padding: 10px 20px;
      background: orange;
      border: none;
      border-radius: 8px;
      color: white;
      font-weight: bold;
      cursor: pointer;
    }
    button:hover {
      background: darkorange;
    }
  </style>
</head>
<body>

<header>
  <div class="logo">🐾 Lost & Found</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="reportlost.php">Report Lost</a>
    <a href="reportfound.php">Report Found</a>
  </nav>
</header>

<main>
  <div class="banner">
    Help Lost Pets Find Their Way Home ❤️
  </div>
  <p>Welcome to our Lost & Found pet community. If you lost a pet, report it immediately. If you found one, help reunite it with the owner. Together we can make a difference.</p>
</main>

</body>
</html>
