<?php
include 'db.php';
include 'navbar.php';

// Check if user is logged in, otherwise redirect to login
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['email'];

// Fetch user's pets from database
$pets_query = "SELECT * FROM pets WHERE user_id = $user_id";
$pets_result = $conn->query($pets_query);

// Fetch user's appointments from database
$appointments_query = "SELECT a.*, v.name as vet_name, v.clinic_name 
                       FROM appointments a 
                       JOIN vets v ON a.vet_id = v.id 
                       WHERE a.user_id = $user_id 
                       ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$appointments_result = $conn->query($appointments_query);

// Fetch available veterinarians
$vets_query = "SELECT * FROM vets ORDER BY name";
$vets_result = $conn->query($vets_query);

// Handle form submission for new appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['book_appointment'])) {
    $pet_id = $_POST['pet_id'];
    $vet_id = $_POST['vet_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $reason = $_POST['reason'];
    
    // Insert new appointment
    $insert_query = "INSERT INTO appointments (user_id, pet_id, vet_id, appointment_date, appointment_time, reason, status) 
                     VALUES ($user_id, $pet_id, $vet_id, '$appointment_date', '$appointment_time', '$reason', 'scheduled')";
    
    if ($conn->query($insert_query)) {
        $success_message = "Appointment booked successfully!";
        // Refresh appointments
        $appointments_result = $conn->query($appointments_query);
    } else {
        $error_message = "Error booking appointment: " . $conn->error;
    }
}

// Handle appointment cancellation
if (isset($_GET['cancel_id'])) {
    $cancel_id = $_GET['cancel_id'];
    $cancel_query = "UPDATE appointments SET status = 'cancelled' WHERE id = $cancel_id AND user_id = $user_id";
    
    if ($conn->query($cancel_query)) {
        $success_message = "Appointment cancelled successfully!";
        // Refresh appointments
        $appointments_result = $conn->query($appointments_query);
    } else {
        $error_message = "Error cancelling appointment: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Veterinary Services</title>
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
            --danger: #dc3545;
            --warning: #f0ad4e;
            --info: #5bc0de;
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

        .container {
            width: 100%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
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

        section {
            padding: 80px 0;
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
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.4);
        }
        
        .btn-sm {
            padding: 10px 20px;
            font-size: 0.9rem;
        }

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }

        input, select, textarea {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: var(--transition);
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(24, 188, 156, 0.2);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 0.9em;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        thead tr {
            background-color: var(--primary);
            color: white;
            text-align: left;
        }

        th, td {
            padding: 15px 18px;
        }

        tbody tr {
            border-bottom: 1px solid #ddd;
            background-color: white;
        }

        tbody tr:nth-of-type(even) {
            background-color: #f8f9fa;
        }

        tbody tr:last-of-type {
            border-bottom: 2px solid var(--primary);
        }

        .status-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .status-scheduled {
            background-color: #e0f7ea;
            color: #2e7d32;
        }

        .status-completed {
            background-color: #e3f2fd;
            color: #1565c0;
        }

        .status-cancelled {
            background-color: #ffebee;
            color: #c62828;
        }

        .status-pending {
            background-color: #fff3e0;
            color: #ef6c00;
        }

        .message {
            padding: 15px 20px;
            border-radius: 8px;
            margin-bottom: 25px;
            text-align: center;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .message i {
            margin-right: 10px;
        }

        .success {
            background-color: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border: 1px solid rgba(39, 174, 96, 0.2);
        }

        .error {
            background-color: rgba(231, 76, 60, 0.1);
            color: var(--accent);
            border: 1px solid rgba(231, 76, 60, 0.2);
        }

        .vet-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
            margin-bottom: 25px;
        }

        .vet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .vet-header {
            background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
            color: white;
            padding: 20px;
            display: flex;
            align-items: center;
        }

        .vet-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.8rem;
        }

        .vet-info {
            padding: 25px;
        }

        .vet-name {
            font-size: 1.4rem;
            margin-bottom: 5px;
            font-family: 'Playfair Display', serif;
        }

        .vet-specialty {
            color: var(--gray);
            margin-bottom: 15px;
            font-weight: 500;
        }

        .vet-details {
            margin-bottom: 15px;
        }

        .vet-details p {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
        }

        .vet-details i {
            margin-right: 10px;
            color: var(--secondary);
            width: 20px;
        }

        .tab-container {
            margin: 40px 0;
        }

        .tabs {
            display: flex;
            margin-bottom: 30px;
            flex-wrap: wrap;
            border-bottom: 1px solid #eee;
        }

        .tab {
            padding: 15px 30px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            margin-right: 5px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            color: var(--gray);
            transition: var(--transition);
        }

        .tab.active {
            color: var(--primary);
            border-bottom: 3px solid var(--secondary);
        }

        .tab:hover {
            color: var(--primary);
        }

        .tab-content {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .tab-content.active {
            display: block;
        }

        .booking-details {
            background-color: #f8f9fa;
            padding: 25px;
            border-radius: 12px;
            margin-top: 20px;
        }

        .booking-details h3 {
            color: var(--primary);
            margin-bottom: 15px;
            font-size: 1.5rem;
        }

        .booking-details ul {
            margin-left: 20px;
            margin-bottom: 20px;
        }

        .booking-details li {
            margin-bottom: 10px;
        }

        .pet-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
            margin-bottom: 25px;
        }

        .pet-item {
            background: white;
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
        }

        .pet-item:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .pet-avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 2.5rem;
            color: white;
        }

        .pet-name {
            font-size: 1.3rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
        }

        .pet-type {
            color: var(--gray);
            margin-bottom: 15px;
            font-weight: 500;
        }

        .pet-details {
            margin-bottom: 15px;
        }

        .pet-details div {
            margin-bottom: 5px;
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
            
            .tabs {
                flex-direction: column;
            }
            
            .tab {
                width: 100%;
                text-align: center;
                margin-bottom: 5px;
            }
            
            .pet-list {
                grid-template-columns: 1fr;
            }
        }

        .animate {
            opacity: 0;
            transform: translateY(20px);
            transition: opacity 0.8s ease-out, transform 0.8s ease-out;
        }

        .animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
    </style>
</head>
<body>
    <section class="section-light">
        <div class="container">
            <!-- Display success/error messages -->
            <?php if (isset($success_message)): ?>
                <div class="message success animate">
                    <i class="fas fa-check-circle"></i> <?php echo $success_message; ?>
                </div>
            <?php endif; ?>
            
            <?php if (isset($error_message)): ?>
                <div class="message error animate">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <div class="tab-container">
                <div class="tabs">
                    <button class="tab active" onclick="openTab('booking')"><i class="fas fa-calendar-check"></i> Book Consultation</button>
                    <button class="tab" onclick="openTab('nearby')"><i class="fas fa-map-marker-alt"></i> Nearby Vets</button>
                    <button class="tab" onclick="openTab('appointments')"><i class="fas fa-calendar-alt"></i> My Appointments</button>
                   
                </div>

                <!-- Booking Tab -->
                <div id="booking" class="tab-content active">
                    <div class="grid">
                        <div>
                            <div class="card animate">
                                <h2><i class="fas fa-calendar-check"></i> Book a Consultation</h2>
                                <form method="POST" action="">
                                    <div class="form-group">
                                        <label for="pet_id">Select Pet</label>
                                        <select id="pet_id" name="pet_id" required>
                                            <option value="">Select a pet</option>
                                            <?php while ($pet = $pets_result->fetch_assoc()): ?>
                                                <option value="<?php echo $pet['id']; ?>">
                                                    <?php echo htmlspecialchars($pet['name'] . ' (' . $pet['type'] . ')'); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="vet_id">Select Veterinarian</label>
                                        <select id="vet_id" name="vet_id" required>
                                            <option value="">Select a veterinarian</option>
                                            <?php 
                                            // Reset pointer for vets result
                                            $vets_result->data_seek(0);
                                            while ($vet = $vets_result->fetch_assoc()): ?>
                                                <option value="<?php echo $vet['id']; ?>">
                                                    <?php echo htmlspecialchars('Dr. ' . $vet['name'] . ' - ' . $vet['clinic_name']); ?>
                                                </option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointment_date">Date</label>
                                        <input type="date" id="appointment_date" name="appointment_date" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="appointment_time">Time</label>
                                        <select id="appointment_time" name="appointment_time" required>
                                            <option value="">Select time</option>
                                            <option value="09:00:00">9:00 AM</option>
                                            <option value="10:00:00">10:00 AM</option>
                                            <option value="11:00:00">11:00 AM</option>
                                            <option value="13:00:00">1:00 PM</option>
                                            <option value="14:00:00">2:00 PM</option>
                                            <option value="15:00:00">3:00 PM</option>
                                            <option value="16:00:00">4:00 PM</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="reason">Reason for Visit</label>
                                        <textarea id="reason" name="reason" rows="3" required placeholder="Describe the reason for the consultation"></textarea>
                                    </div>
                                    <button type="submit" name="book_appointment" class="btn-primary"><i class="fas fa-calendar-plus"></i> Book Appointment</button>
                                </form>
                            </div>
                        </div>
                        <div>
                            <div class="card animate">
                                <h2><i class="fas fa-info-circle"></i> Booking Information</h2>
                                <div class="booking-details">
                                    <h3>What to Expect</h3>
                                    <p>When you book a consultation with our veterinarians, you can expect:</p>
                                    <ul>
                                        <li>Thorough examination of your pet</li>
                                        <li>Professional diagnosis and treatment plan</li>
                                        <li>Discussion of preventive care</li>
                                        <li>Time for all your questions</li>
                                    </ul>
                                    
                                    <h3>Emergency Services</h3>
                                    <p>For emergency cases, please call directly:</p>
                                    <p><strong>Emergency Hotline: 1-800-PET-CARE</strong></p>
                                    
                                    <h3>Preparation Tips</h3>
                                    <ul>
                                        <li>Bring any medical records for your pet</li>
                                        <li>Note down any symptoms or behaviors you're concerned about</li>
                                        <li>Bring a list of any medications your pet is taking</li>
                                        <li>If possible, bring a fresh stool sample</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Nearby Vets Tab -->
                <div id="nearby" class="tab-content">
                    <div class="card animate">
                        <h2><i class="fas fa-map-marker-alt"></i> Nearby Veterinarians</h2>
                        <p>Based on your location, we found these veterinary clinics nearby:</p>
                        
                        <?php
                        // Reset pointer for vets result
                        $vets_result->data_seek(0);
                        while ($vet = $vets_result->fetch_assoc()): 
                        ?>
                        <div class="vet-card">
                            <div class="vet-header">
                                <div class="vet-icon">
                                    <i class="fas fa-clinic-medical"></i>
                                </div>
                                <div>
                                    <h3 class="vet-name"><?php echo htmlspecialchars($vet['clinic_name']); ?></h3>
                                    <p class="vet-specialty">Dr. <?php echo htmlspecialchars($vet['name']); ?> - <?php echo htmlspecialchars($vet['specialty']); ?></p>
                                </div>
                            </div>
                            <div class="vet-info">
                                <div class="vet-details">
                                    <p><i class="fas fa-phone"></i> <?php echo htmlspecialchars($vet['phone']); ?></p>
                                    <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($vet['address']); ?></p>
                                    <p><i class="fas fa-clock"></i> <?php echo htmlspecialchars($vet['hours']); ?></p>
                                </div>
                                <button class="btn-primary btn-sm" onclick="openTab('booking')">Book Appointment</button>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>

                <!-- Appointments Tab -->
                <div id="appointments" class="tab-content">
                    <div class="card animate">
                        <h2><i class="fas fa-calendar-alt"></i> My Appointments</h2>
                        <?php if ($appointments_result->num_rows > 0): ?>
                        <table>
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Veterinarian</th>
                                    <th>Pet</th>
                                    <th>Reason</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($appointment = $appointments_result->fetch_assoc()): 
                                    // Get pet name for this appointment
                                    $pet_id = $appointment['pet_id'];
                                    $pet_query = "SELECT name FROM pets WHERE id = $pet_id";
                                    $pet_result = $conn->query($pet_query);
                                    $pet = $pet_result->fetch_assoc();
                                ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($appointment['appointment_date']); ?></td>
                                    <td><?php echo htmlspecialchars(date('g:i A', strtotime($appointment['appointment_time']))); ?></td>
                                    <td>Dr. <?php echo htmlspecialchars($appointment['vet_name']); ?></td>
                                    <td><?php echo htmlspecialchars($pet['name']); ?></td>
                                    <td><?php echo htmlspecialchars($appointment['reason']); ?></td>
                                    <td>
                                        <span class="status-badge status-<?php echo htmlspecialchars($appointment['status']); ?>">
                                            <?php echo ucfirst(htmlspecialchars($appointment['status'])); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($appointment['status'] == 'scheduled'): ?>
                                        <a href="?cancel_id=<?php echo $appointment['id']; ?>" class="btn-danger btn-sm" 
                                           onclick="return confirm('Are you sure you want to cancel this appointment?')">Cancel</a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                        <?php else: ?>
                        <p>You don't have any appointments yet.</p>
                        <?php endif; ?>
                    </div>
                </div>

               
                
            </div>
        </div>
    </section>

    <script>
        // Tab navigation
        function openTab(tabName) {
            const tabs = document.getElementsByClassName('tab-content');
            for (let i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
            }
            
            const tabButtons = document.getElementsByClassName('tab');
            for (let i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove('active');
            }
            
            document.getElementById(tabName).classList.add('active');
            event.currentTarget.classList.add('active');
            
            // Trigger animations for content in the active tab
            setTimeout(() => {
                const animateElements = document.getElementById(tabName).querySelectorAll('.animate');
                animateElements.forEach(el => {
                    el.classList.add('visible');
                });
            }, 100);
        }
        
        // Set default date to tomorrow
        const tomorrow = new Date();
        tomorrow.setDate(tomorrow.getDate() + 1);
        document.getElementById('appointment_date').value = tomorrow.toISOString().split('T')[0];
        
        // Initialize animations
        document.addEventListener('DOMContentLoaded', function() {
            // Animate elements in the active tab
            const animateElements = document.querySelectorAll('.animate');
            animateElements.forEach(el => {
                if (el.closest('.tab-content.active')) {
                    setTimeout(() => {
                        el.classList.add('visible');
                    }, 100);
                }
            });
            
            // Add intersection observer for elements that come into view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            document.querySelectorAll('.animate').forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>