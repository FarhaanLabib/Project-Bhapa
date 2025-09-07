<?php
include 'db.php';
include 'navbar.php';

// Initialize variables
$message = '';
$medications = [];

// Handle form submission for adding new medication
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_medication'])) {
    $pet_name = $_POST['pet_name'] ?? '';
    $medication_name = $_POST['medication_name'] ?? '';
    $dosage = $_POST['dosage'] ?? '';
    $frequency = $_POST['frequency'] ?? '';
    $start_date = $_POST['start_date'] ?? '';
    $end_date = $_POST['end_date'] ?? '';
    $notes = $_POST['notes'] ?? '';
    $status = 'Active'; // Default status
    
    // Basic validation
    if (empty($pet_name) || empty($medication_name) || empty($dosage) || empty($frequency) || empty($start_date)) {
        $message = "Please fill in all required fields!";
    } else {
        $stmt = $conn->prepare("INSERT INTO medications (pet_name, medication_name, dosage, frequency, start_date, end_date, notes, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssssss", $pet_name, $medication_name, $dosage, $frequency, $start_date, $end_date, $notes, $status);
            if ($stmt->execute()) {
                $message = "success";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $message = "Database error: " . $conn->error;
        }
    }
}

// Handle medication deletion
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM medications WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $delete_id);
        if ($stmt->execute()) {
            $message = "deleted";
        } else {
            $message = "Error deleting medication: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Handle status update
if (isset($_GET['update_status'])) {
    $update_id = $_GET['update_id'];
    $new_status = $_GET['new_status'];
    
    $stmt = $conn->prepare("UPDATE medications SET status = ? WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("si", $new_status, $update_id);
        if ($stmt->execute()) {
            $message = "status_updated";
        } else {
            $message = "Error updating status: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Fetch all medications from the database
$result = $conn->query("SELECT * FROM medications ORDER BY start_date DESC");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $medications[] = $row;
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
  <title>PetCareHub - Medication Tracking</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* Pet-themed color scheme */
    :root {
      --primary: #3a7ca5;    /* Pet blue */
      --secondary: #5cb85c;  /* Healthy green */
      --accent: #f0ad4e;     /* Warning orange */
      --light: #f8f9fa;
      --dark: #212529;
      --gray: #6c757d;
      --success: #5cb85c;
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
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      color: var(--dark);
      line-height: 1.6;
    }

    body {
      display: flex;
      flex-direction: column;
      min-height: 100vh;
    }

    h1, h2, h3, h4 {
      font-weight: 700;
      line-height: 1.2;
      margin-bottom: 1rem;
    }
    
    h1 {
      font-size: 2.8rem;
    }
    
    h2 {
      font-size: 2.2rem;
      position: relative;
      padding-bottom: 15px;
    }
    
    h2:after {
      content: '';
      position: absolute;
      bottom: 0;
      left: 0;
      width: 60px;
      height: 4px;
      background: var(--accent);
      border-radius: 2px;
    }
    
    h3 {
      font-size: 1.6rem;
    }
    
    p {
      margin-bottom: 1rem;
      color: #555;
    }
    
    .text-center {
      text-align: center;
    }
    
    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 20px;
    }
    
    section {
      padding: 70px 0;
    }
    
    .section-light {
      background: white;
    }
    
    .btn {
      display: inline-block;
      padding: 12px 28px;
      border-radius: 30px;
      text-decoration: none;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
      text-align: center;
    }
    
    .btn-primary {
      background: var(--primary);
      color: white;
    }
    
    .btn-primary:hover {
      background: #2c6185;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-accent {
      background: var(--accent);
      color: white;
    }
    
    .btn-accent:hover {
      background: #ec971f;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-danger {
      background: #dc3545;
      color: white;
    }
    
    .btn-danger:hover {
      background: #c82333;
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .btn-sm {
      padding: 6px 12px;
      font-size: 0.875rem;
    }
    
    /* Status badges */
    .status-badge {
      display: inline-block;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 0.85rem;
      font-weight: 600;
    }
    
    .status-active {
      background-color: #e0f7ea;
      color: #2e7d32;
    }
    
    .status-completed {
      background-color: #e3f2fd;
      color: #1565c0;
    }
    
    .status-missed {
      background-color: #ffebee;
      color: #c62828;
    }
    
    .status-pending {
      background-color: #fff3e0;
      color: #ef6c00;
    }
    
    /* Hero Sections */
    .hero {
      background: linear-gradient(135deg, var(--primary) 0%, #2c6185 100%);
      color: white;
      padding: 120px 0 80px;
      text-align: center;
      position: relative;
      overflow: hidden;
    }
    
    .hero:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQPSIxMDAlIj48ZGVmcz48cGF0dGVybiBpZD0icGF0dGVybiIgd2lkdGg9IjQwIiBoZWlnaHQ9IjQwIiBwYXR0ZXJuVW5pdHM9InVzZXJTcGFjZU9uVXNlIiBwYXR0ZXJuVHJhbnNmb3JtPSJyb3RhdGUoNDUpIj48Y2lyY2xlIGN4PSIyMCIgY3k9IjIwIiByPSIxLjUiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNSkiLz48L3BhdHRlcm4+PC9kZWZzPjxyZWN0IHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiIGZpbGw9InVybCgjcGF0dGVybikiLz48L3N2Zz4=');
      opacity: 0.3;
    }
    
    .hero-content {
      position: relative;
      z-index: 2;
      max-width: 800px;
      margin: 0 auto;
    }
    
    .hero h1 {
      margin: 0 auto 20px;
    }
    
    .hero p {
      font-size: 1.2rem;
      max-width: 600px;
      margin: 0 auto 30px;
      color: rgba(255,255,255,0.85);
    }
    
    /* Form Styles */
    .form-container {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: var(--shadow);
      margin-bottom: 40px;
    }
    
    .form-group {
      margin-bottom: 20px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 8px;
      font-weight: 500;
    }
    
    .form-control {
      width: 100%;
      padding: 12px 15px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
    }
    
    .form-control:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(58, 124, 165, 0.2);
    }
    
    textarea.form-control {
      min-height: 100px;
      resize: vertical;
    }
    
    /* Medication List Styles */
    .medication-list {
      margin-top: 40px;
    }
    
    .medication-card {
      background: white;
      border-radius: 12px;
      padding: 20px;
      margin-bottom: 20px;
      box-shadow: var(--shadow);
      border-left: 5px solid var(--primary);
    }
    
    .medication-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }
    
    .medication-actions {
      display: flex;
      gap: 10px;
    }
    
    .medication-details {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      gap: 15px;
    }
    
    .detail-item {
      margin-bottom: 10px;
    }
    
    .detail-label {
      font-weight: 600;
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    /* Message Styles */
    .message-container {
      margin-bottom: 20px;
      padding: 15px;
      border-radius: 8px;
      text-align: center;
      font-weight: 500;
    }
    
    .success-message {
      background-color: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }
    
    .error-message {
      background-color: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
    
    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 40px;
      background: white;
      border-radius: 12px;
      box-shadow: var(--shadow);
    }
    
    .empty-state i {
      font-size: 4rem;
      color: #ddd;
      margin-bottom: 20px;
    }
    
    /* TEE Table Styles */
    .tee-table {
      width: 100%;
      border-collapse: collapse;
      margin: 25px 0;
      font-size: 0.9em;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }
    
    .tee-table thead tr {
      background-color: var(--primary);
      color: white;
      text-align: left;
    }
    
    .tee-table th,
    .tee-table td {
      padding: 12px 15px;
    }
    
    .tee-table tbody tr {
      border-bottom: 1px solid #ddd;
      background-color: white;
    }
    
    .tee-table tbody tr:nth-of-type(even) {
      background-color: #f8f9fa;
    }
    
    .tee-table tbody tr:last-of-type {
      border-bottom: 2px solid var(--primary);
    }
    
    /* Responsive */
    @media (max-width: 768px) {
      h1 {
        font-size: 2.3rem;
      }
      
      h2 {
        font-size: 1.8rem;
      }
      
      section {
        padding: 50px 0;
      }
      
      .medication-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
      }
      
      .medication-actions {
        width: 100%;
        justify-content: flex-end;
      }
      
      .tee-table {
        display: block;
        overflow-x: auto;
      }
    }
  </style>
</head>
<body>
  <!-- Medication Tracking Page -->
  <main id="medication">
    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-content">
        <h1>Medication Tracking</h1>
        <p>Keep track of your pet's medications, dosages, and schedules all in one place</p>
      </div>
    </section>

    <!-- Medication Form Section -->
    <section class="section-light">
      <div class="container">
        <h2>Add New Medication</h2>
        
        <!-- Message Container -->
        <?php if (!empty($message)): ?>
          <div class="message-container <?php echo ($message == 'success') ? 'success-message' : (($message == 'deleted' || $message == 'status_updated') ? 'success-message' : 'error-message'); ?>">
            <?php if ($message == 'success') { 
              echo '<i class="fas fa-check-circle"></i> Medication added successfully!';
            } elseif ($message == 'deleted') {
              echo '<i class="fas fa-check-circle"></i> Medication deleted successfully!';
            } elseif ($message == 'status_updated') {
              echo '<i class="fas fa-check-circle"></i> Medication status updated successfully!';
            } else {
              echo '<i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($message);
            } ?>
          </div>
        <?php endif; ?>
        
        <div class="form-container">
          <form method="POST">
            <div class="form-group">
              <label for="pet_name">Pet Name *</label>
              <input type="text" id="pet_name" name="pet_name" class="form-control" placeholder="Enter pet name" required>
            </div>
            
            <div class="form-group">
              <label for="medication_name">Medication Name *</label>
              <input type="text" id="medication_name" name="medication_name" class="form-control" placeholder="Enter medication name" required>
            </div>
            
            <div class="form-group">
              <label for="dosage">Dosage *</label>
              <input type="text" id="dosage" name="dosage" class="form-control" placeholder="e.g., 5mg, 1 tablet" required>
            </div>
            
            <div class="form-group">
              <label for="frequency">Frequency *</label>
              <select id="frequency" name="frequency" class="form-control" required>
                <option value="">Select frequency</option>
                <option value="Once daily">Once daily</option>
                <option value="Twice daily">Twice daily</option>
                <option value="Three times daily">Three times daily</option>
                <option value="Every other day">Every other day</option>
                <option value="Weekly">Weekly</option>
                <option value="As needed">As needed</option>
                <option value="Other">Other</option>
              </select>
            </div>
            
            <div class="form-group">
              <label for="start_date">Start Date *</label>
              <input type="date" id="start_date" name="start_date" class="form-control" required>
            </div>
            
            <div class="form-group">
              <label for="end_date">End Date (if applicable)</label>
              <input type="date" id="end_date" name="end_date" class="form-control">
            </div>
            
            <div class="form-group">
              <label for="notes">Notes</label>
              <textarea id="notes" name="notes" class="form-control" placeholder="Any special instructions or notes"></textarea>
            </div>
            
            <button type="submit" name="add_medication" class="btn btn-primary">Add Medication</button>
          </form>
        </div>
        
        <!-- Medication List -->
        <div class="medication-list">
          <h2>Current Medications</h2>
          
          <?php if (empty($medications)): ?>
            <div class="empty-state">
              <i class="fas fa-pills"></i>
              <h3>No medications added yet</h3>
              <p>Add a medication using the form above to get started</p>
            </div>
          <?php else: ?>
            <?php foreach ($medications as $med): ?>
              <div class="medication-card">
                <div class="medication-header">
                  <h3><?php echo htmlspecialchars($med['pet_name']); ?> - <?php echo htmlspecialchars($med['medication_name']); ?></h3>
                  <div class="medication-actions">
                    <!-- Status Dropdown -->
                    <div class="status-selector">
                      <form method="GET" style="display: inline;">
                        <input type="hidden" name="update_id" value="<?php echo $med['id']; ?>">
                        <select name="new_status" onchange="this.form.submit()" class="form-control" style="display: inline-block; width: auto; padding: 6px 12px;">
                          <option value="Active" <?php echo ($med['status'] == 'Active') ? 'selected' : ''; ?>>Active</option>
                          <option value="Completed" <?php echo ($med['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                          <option value="Missed" <?php echo ($med['status'] == 'Missed') ? 'selected' : ''; ?>>Missed</option>
                          <option value="Pending" <?php echo ($med['status'] == 'Pending') ? 'selected' : ''; ?>>Pending</option>
                        </select>
                        <input type="hidden" name="update_status" value="1">
                      </form>
                    </div>
                    
                    <a href="?delete_id=<?php echo $med['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this medication?')">Delete</a>
                  </div>
                </div>
                
                <div class="medication-details">
                  <div class="detail-item">
                    <div class="detail-label">Status</div>
                    <span class="status-badge status-<?php echo strtolower($med['status']); ?>">
                      <?php echo htmlspecialchars($med['status']); ?>
                    </span>
                  </div>
                  
                  <div class="detail-item">
                    <div class="detail-label">Dosage</div>
                    <div><?php echo htmlspecialchars($med['dosage']); ?></div>
                  </div>
                  
                  <div class="detail-item">
                    <div class="detail-label">Frequency</div>
                    <div><?php echo htmlspecialchars($med['frequency']); ?></div>
                  </div>
                  
                  <div class="detail-item">
                    <div class="detail-label">Start Date</div>
                    <div><?php echo date('M j, Y', strtotime($med['start_date'])); ?></div>
                  </div>
                  
                  <?php if (!empty($med['end_date'])): ?>
                    <div class="detail-item">
                      <div class="detail-label">End Date</div>
                      <div><?php echo date('M j, Y', strtotime($med['end_date'])); ?></div>
                    </div>
                  <?php endif; ?>
                </div>
                
                <?php if (!empty($med['notes'])): ?>
                  <div class="detail-item">
                    <div class="detail-label">Notes</div>
                    <div><?php echo htmlspecialchars($med['notes']); ?></div>
                  </div>
                <?php endif; ?>
              </div>
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        
        <!-- TEE Table -->
        <div class="tee-table-container">
          <h2>Medication Status Overview (TEE Table)</h2>
          <table class="tee-table">
            <thead>
              <tr>
                <th>Pet Name</th>
                <th>Medication</th>
                <th>Dosage</th>
                <th>Frequency</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($medications)): ?>
                <tr>
                  <td colspan="7" style="text-align: center;">No medications to display</td>
                </tr>
              <?php else: ?>
                <?php foreach ($medications as $med): ?>
                  <tr>
                    <td><?php echo htmlspecialchars($med['pet_name']); ?></td>
                    <td><?php echo htmlspecialchars($med['medication_name']); ?></td>
                    <td><?php echo htmlspecialchars($med['dosage']); ?></td>
                    <td><?php echo htmlspecialchars($med['frequency']); ?></td>
                    <td><?php echo date('M j, Y', strtotime($med['start_date'])); ?></td>
                    <td><?php echo !empty($med['end_date']) ? date('M j, Y', strtotime($med['end_date'])) : 'N/A'; ?></td>
                    <td>
                      <span class="status-badge status-<?php echo strtolower($med['status']); ?>">
                        <?php echo htmlspecialchars($med['status']); ?>
                      </span>
                    </td>
                  </tr>
                <?php endforeach; ?>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </section>
  </main>

  <script>
    // Set today's date as the default for start date
    document.addEventListener('DOMContentLoaded', function() {
      const today = new Date().toISOString().split('T')[0];
      document.getElementById('start_date').value = today;
      
      // Add confirmation for delete actions
      const deleteButtons = document.querySelectorAll('.btn-danger');
      deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
          if (!confirm('Are you sure you want to delete this medication?')) {
            e.preventDefault();
          }
        });
      });
    });
  </script>
</body>
</html>