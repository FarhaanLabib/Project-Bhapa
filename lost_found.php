<?php
// lostandfound.php
include 'db.php';

// Fetch Lost pets
$lostPets = [];
$result = $conn->query("SELECT * FROM lost ORDER BY date_reported DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $lostPets[] = $row;
    }
}

// Fetch Found pets
$foundPets = [];
$result = $conn->query("SELECT * FROM found ORDER BY date_reported DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $foundPets[] = $row;
    }
}

$conn->close();
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
    .section {
      margin: 40px auto;
      max-width: 1000px;
      text-align: left;
    }
    .section h2 {
      margin-bottom: 20px;
      color: #333;
    }
    .card {
      background: #fff;
      padding: 20px;
      border-radius: 12px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .card h3 {
      margin-top: 0;
      color: orange;
    }
    .card p {
      margin: 5px 0;
    }
    .empty {
      color: #777;
      font-style: italic;
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

  <!-- Lost Pets Section -->
  <div class="section">
    <h2>🐶 Lost Pets</h2>
    <?php if (count($lostPets) > 0): ?>
      <?php foreach ($lostPets as $pet): ?>
        <div class="card">
          <h3><?= htmlspecialchars($pet['animal']) ?> - <?= htmlspecialchars($pet['name'] ?? 'Unknown') ?></h3>
          <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed'] ?? 'Unknown') ?></p>
          <p><strong>Age:</strong> <?= htmlspecialchars($pet['age'] ?? 'Unknown') ?></p>
          <p><strong>Last Seen:</strong> <?= htmlspecialchars($pet['last_seen']) ?></p>
          <p><strong>Date Reported:</strong> <?= htmlspecialchars($pet['date_reported']) ?></p>
          <?php if (!empty($pet['pet_id'])): ?>
            <p><strong>Pet ID:</strong> <?= htmlspecialchars($pet['pet_id']) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty">No lost pets reported yet.</p>
    <?php endif; ?>
  </div>

  <!-- Found Pets Section -->
  <div class="section">
    <h2>🐾 Found Pets</h2>
    <?php if (count($foundPets) > 0): ?>
      <?php foreach ($foundPets as $pet): ?>
        <div class="card">
          <h3><?= htmlspecialchars($pet['animal']) ?> - <?= htmlspecialchars($pet['name'] ?? 'Unknown') ?></h3>
          <p><strong>Breed:</strong> <?= htmlspecialchars($pet['breed'] ?? 'Unknown') ?></p>
          <p><strong>Age:</strong> <?= htmlspecialchars($pet['age'] ?? 'Unknown') ?></p>
          <p><strong>Found At:</strong> <?= htmlspecialchars($pet['found_at']) ?></p>
          <p><strong>Date Reported:</strong> <?= htmlspecialchars($pet['date_reported']) ?></p>
          <?php if (!empty($pet['pet_id'])): ?>
            <p><strong>Pet ID:</strong> <?= htmlspecialchars($pet['pet_id']) ?></p>
          <?php endif; ?>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p class="empty">No pets reported as found yet.</p>
    <?php endif; ?>
  </div>
</main>

</body>
</html>
