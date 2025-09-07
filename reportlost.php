<?php
include 'db.php'; // database connection

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $animal = $_POST['animal'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $age = $_POST['age'] ?? '';
    $name = $_POST['name'] ?? '';
    $lastseen = $_POST['lastseen'] ?? '';
    $datereported = $_POST['datereported'] ?? '';
    $petid = $_POST['petid'] ?? '';

    if (empty($animal) || empty($lastseen) || empty($datereported)) {
        $message = "Please fill in all required fields!";
    } else {
        $stmt = $conn->prepare("INSERT INTO lost (animal, breed, age, name, last_seen, date_reported, pet_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssss", $animal, $breed, $age, $name, $lastseen, $datereported, $petid);

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
  <title>Report Lost Pet</title>
  <style>
    body { font-family: Segoe UI, sans-serif; background: #f9f9f9; margin:0; }
    header { display:flex; justify-content:space-between; align-items:center; background:#fff; padding:15px 30px; box-shadow:0 2px 5px rgba(0,0,0,0.1); }
    nav a { margin-left:20px; text-decoration:none; color:black; font-weight:600; }
    nav a:hover { color:orange; }
    .card { max-width:600px; margin:40px auto; background:#fff; padding:20px; border-radius:12px; box-shadow:0 4px 10px rgba(0,0,0,0.1); }
    .form-group { margin-bottom:15px; }
    .form-group label { display:block; margin-bottom:5px; font-weight:600; }
    .form-group input {
      width:100%; padding:10px; border:1px solid #ddd; border-radius:8px;
    }
    button { padding:10px 20px; background:orange; border:none; border-radius:8px; color:white; font-weight:bold; cursor:pointer; }
    button:hover { background:darkorange; }
    .msg-success { padding:10px; background:#d4edda; color:#155724; border-radius:8px; margin-bottom:15px; }
    .msg-error { padding:10px; background:#f8d7da; color:#721c24; border-radius:8px; margin-bottom:15px; }
  </style>
</head>
<body>

<header>
  <div class="logo">🐾 Lost & Found</div>
  <nav>
    <a href="lostandfound.php">Home</a>
    <a href="reportlost.php">Report Lost</a>
    <a href="reportfound.php">Report Found</a>
  </nav>
</header>

<div class="card">
  <h2>Report Lost Pet</h2>

  <?php if (!empty($message)): ?>
    <?php if ($message == 'success'): ?>
      <div class="msg-success">✅ Reported Successfully!</div>
    <?php else: ?>
      <div class="msg-error"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
  <?php endif; ?>

  <form method="POST">
    <div class="form-group">
      <label>Animal</label>
      <input type="text" name="animal" placeholder="Dog, Cat..." required>
    </div>
    <div class="form-group">
      <label>Breed</label>
      <input type="text" name="breed">
    </div>
    <div class="form-group">
      <label>Age</label>
      <input type="text" name="age">
    </div>
    <div class="form-group">
      <label>Pet Name</label>
      <input type="text" name="name">
    </div>
    <div class="form-group">
      <label>Last Seen</label>
      <input type="text" name="lastseen" placeholder="Location" required>
    </div>
    <div class="form-group">
      <label>Date Reported</label>
      <input type="date" name="datereported" required>
    </div>
    <div class="form-group">
      <label>Pet ID (if registered)</label>
      <input type="text" name="petid">
    </div>
    <button type="submit">Submit Lost Report</button>
  </form>
</div>

</body>
</html>
