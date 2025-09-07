<?php
include 'db.php';
include 'navbar.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user information from session
$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['user'] ?? 'User';

// Fetch user's pets from database
$pets_query = "SELECT * FROM pets WHERE user_id = $user_id ORDER BY name";
$pets_result = $conn->query($pets_query);

// Handle form submission for adding new pet
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_pet'])) {
    $pet_name = $conn->real_escape_string($_POST['pet_name']);
    $pet_type = $conn->real_escape_string($_POST['pet_type']);
    $pet_breed = $conn->real_escape_string($_POST['pet_breed']);
    $pet_age = $conn->real_escape_string($_POST['pet_age']);

    // Set last_visit to today
    $last_visit = date('Y-m-d');

    // Insert new pet
    $insert_query = "INSERT INTO pets (user_id, name, type, breed, age, last_visit) 
                     VALUES ($user_id, '$pet_name', '$pet_type', '$pet_breed', $pet_age, '$last_visit')";
    
    if ($conn->query($insert_query)) {
        $success_message = "Pet added successfully!";
        // Refresh pets list
        $pets_result = $conn->query($pets_query);
    } else {
        $error_message = "Error adding pet: " . $conn->error;
    }
}

// Handle pet deletion
if (isset($_GET['delete_id'])) {
    $delete_id = (int)$_GET['delete_id'];
    
    // Check if the pet belongs to the user
    $check_query = "SELECT * FROM pets WHERE id = $delete_id AND user_id = $user_id";
    $check_result = $conn->query($check_query);
    
    if ($check_result->num_rows > 0) {
        $delete_query = "DELETE FROM pets WHERE id = $delete_id";
        if ($conn->query($delete_query)) {
            $success_message = "Pet deleted successfully!";
            // Refresh pets list
            $pets_result = $conn->query($pets_query);
        } else {
            $error_message = "Error deleting pet: " . $conn->error;
        }
    } else {
        $error_message = "Pet not found or you don't have permission to delete it.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - My Pets</title>
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

        section {
            padding: 80px 0;
        }

        .section-light {
            background: white;
        }

        h1, h2, h3, h4, h5 {
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        h1 {
            font-size: 3rem;
            font-family: 'Playfair Display', serif;
        }
        
        h2 {
            font-size: 2.5rem;
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
        
        p {
            margin-bottom: 1.5rem;
            color: #555;
            font-size: 1.1rem;
            line-height: 1.8;
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

        .card {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
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

        input, select {
            width: 100%;
            padding: 15px 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: var(--transition);
        }

        input:focus, select:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(24, 188, 156, 0.2);
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

        .pet-list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .pet-card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow);
            transition: var(--transition);
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .pet-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.15);
        }

        .pet-card:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: var(--secondary);
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
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 5px;
            font-family: 'Playfair Display', serif;
        }

        .pet-details {
            color: var(--gray);
            margin-bottom: 20px;
        }

        .pet-details p {
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pet-details i {
            margin-right: 10px;
            color: var(--secondary);
            width: 20px;
        }

        .pet-actions {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            grid-column: 1 / -1;
        }

        .empty-state i {
            font-size: 4rem;
            color: var(--secondary);
            margin-bottom: 20px;
        }

        .empty-state h3 {
            color: var(--primary);
            margin-bottom: 10px;
        }

        .empty-state p {
            color: var(--gray);
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

        @media (max-width: 768px) {
            section {
                padding: 60px 0;
            }
            
            h1 {
                font-size: 2.5rem;
            }
            
            h2 {
                font-size: 2rem;
            }
            
            .pet-list {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <section class="section-light">
        <div class="container">
            <h1 class="animate">My Pets</h1>
            <p class="animate">Manage your furry family members and their information</p>

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

            <div class="grid">
                <!-- Add Pet Form -->
                <div class="card animate">
                    <h2><i class="fas fa-plus-circle"></i> Add New Pet</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="pet_name">Pet Name *</label>
                            <input type="text" id="pet_name" name="pet_name" required placeholder="Enter your pet's name">
                        </div>
                        <div class="form-group">
                            <label for="pet_type">Type *</label>
                            <select id="pet_type" name="pet_type" required>
                                <option value="">Select type</option>
                                <option value="Dog">Dog</option>
                                <option value="Cat">Cat</option>
                                <option value="Bird">Bird</option>
                                <option value="Rabbit">Rabbit</option>
                                <option value="Fish">Fish</option>
                                <option value="Hamster">Hamster</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="pet_breed">Breed</label>
                            <input type="text" id="pet_breed" name="pet_breed" placeholder="Enter breed">
                        </div>
                        <div class="form-group">
                            <label for="pet_age">Age (years)</label>
                            <input type="number" id="pet_age" name="pet_age" min="0" max="50" step="0.1" placeholder="Age in years">
                        </div>
                        <button type="submit" name="add_pet" class="btn btn-primary"><i class="fas fa-plus"></i> Add Pet</button>
                    </form>
                </div>

                <!-- Pets List -->
                <div class="card animate">
                    <h2><i class="fas fa-paw"></i> My Pets</h2>
                    <div class="pet-list">
                        <?php if ($pets_result->num_rows > 0): ?>
                            <?php while ($pet = $pets_result->fetch_assoc()): ?>
                                <div class="pet-card">
                                    <div class="pet-avatar">
                                        <i class="fas fa-paw"></i>
                                    </div>
                                    <h3 class="pet-name"><?php echo htmlspecialchars($pet['name']); ?></h3>
                                    <div class="pet-details">
                                        <p><i class="fas fa-dog"></i> <?php echo htmlspecialchars($pet['type']); ?>
                                            <?php if (!empty($pet['breed'])): ?> (<?php echo htmlspecialchars($pet['breed']); ?>)<?php endif; ?>
                                        </p>
                                        <?php if (!empty($pet['age'])): ?>
                                            <p><i class="fas fa-birthday-cake"></i> <?php echo htmlspecialchars($pet['age']); ?> years old</p>
                                        <?php endif; ?>
                                        <?php if (!empty($pet['last_visit'])): ?>
                                            <p><i class="fas fa-calendar-alt"></i> Last Visit: <?php echo htmlspecialchars($pet['last_visit']); ?></p>
                                        <?php endif; ?>
                                    </div>
                                    <div class="pet-actions">
                                        <a href="?delete_id=<?php echo $pet['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete <?php echo htmlspecialchars($pet['name']); ?>?')">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-paw"></i>
                                <h3>No pets added yet</h3>
                                <p>Add your first pet using the form on the left</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        // Animation on scroll
        document.addEventListener('DOMContentLoaded', function() {
            const animatedElements = document.querySelectorAll('.animate');
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1 });
            
            animatedElements.forEach(el => {
                observer.observe(el);
            });
        });
    </script>
</body>
</html>