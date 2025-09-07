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

// Fetch user's bookings with photographer name
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
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Profile</title>
<style>
body {font-family: 'Segoe UI', sans-serif; background: #f8f8f8; margin:0; padding:0;}
.container {max-width:1200px; margin:20px auto; padding:20px; background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.05);}
h1,h2 {margin-bottom:10px;}
section {margin-bottom:30px;}
table {width:100%; border-collapse:collapse;}
th, td {padding:12px; border:1px solid #ddd; text-align:left;}
th {background:#f4f4f4;}
</style>
</head>
<body>
<div class="container">
    <h1>My Profile</h1>

    <section>
        <h2>User Info</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($user['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
    </section>

    <section>
        <h2>My Booked Photographers</h2>
        <?php if(count($bookings) > 0): ?>
        <table>
            <tr>
                <th>Photographer</th>
                <th>Pet Name</th>
                <th>Pet Type</th>
                <th>Session Type</th>
                <th>Date</th>
                <th>Time</th>
                <th>Duration (hours)</th>
                <th>Special Requests</th>
            </tr>
            <?php foreach($bookings as $booking): ?>
            <tr>
                <td><?php echo htmlspecialchars($booking['photographer_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['pet_name']); ?></td>
                <td><?php echo htmlspecialchars($booking['pet_type']); ?></td>
                <td><?php echo htmlspecialchars($booking['session_type']); ?></td>
                <td><?php echo htmlspecialchars($booking['booking_date']); ?></td>
                <td><?php echo htmlspecialchars($booking['booking_time']); ?></td>
                <td><?php echo htmlspecialchars($booking['duration_hours']); ?></td>
                <td><?php echo htmlspecialchars($booking['special_requests']); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php else: ?>
            <p>You have no bookings yet.</p>
        <?php endif; ?>
    </section>
</div>
</body>
</html>
