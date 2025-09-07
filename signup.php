<?php 
include 'config.php'; 
include 'db.php';

$message = '';
$messageClass = '';

// Process form data if submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Validate inputs
    if (empty($name) || empty($email) || empty($password)) {
        $message = "All fields are required!";
        $messageClass = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address";
        $messageClass = "error";
    } else {
        // Check if email exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $message = "Email already registered. Please use a different email.";
            $messageClass = "error";
        } else {
            // Hash password
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert new user
            $insertStmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
            $insertStmt->bind_param("sss", $name, $email, $hashedPassword);
            
            if ($insertStmt->execute()) {
                $message = "Signup successful! Redirecting to login...";
                $messageClass = "success";
                
                // Redirect after success
                echo '<script>
                    setTimeout(function() {
                        window.location.href = "login.php";
                    }, 2000);
                </script>';
            } else {
                $message = "Error: " . $conn->error;
                $messageClass = "error";
            }
            $insertStmt->close();
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
  <title>Sign Up - Bhapa</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
    
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f4f6f8;
      color: var(--dark);
      line-height: 1.6;
    }

    .signup-container {
      display: flex;
      min-height: 100vh;
    }
    
    .signup-illustration {
      flex: 1;
      background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
      display: flex;
      flex-direction: column;
      justify-content: center;
      align-items: center;
      padding: 40px;
      color: white;
      position: relative;
      overflow: hidden;
    }
    
    .signup-illustration:before {
      content: '';
      position: absolute;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxjaXJjbGUgY3g9IjIwIiBjeT0iMjAiIHI9IjEuNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
      opacity: 0.3;
    }
    
    .illustration-content {
      position: relative;
      z-index: 2;
      max-width: 600px;
      text-align: center;
    }
    
    .illustration-content h1 {
      font-size: 2.5rem;
      margin-bottom: 20px;
    }
    
    .illustration-content p {
      font-size: 1.1rem;
      opacity: 0.9;
      margin-bottom: 30px;
    }
    
    .benefits {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 40px;
    }
    
    .benefit-item {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      width: 180px;
      text-align: center;
    }
    
    .benefit-item i {
      font-size: 2rem;
      margin-bottom: 15px;
    }
    
    .signup-form-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background: white;
    }
    
    .signup-form-container {
      width: 100%;
      max-width: 420px;
      text-align: center;
    }
    
    .logo {
      font-size: 2.5rem;
      font-weight: 700;
      color: var(--primary);
      margin-bottom: 10px;
    }
    
    .signup-form-container h2 {
      font-size: 2rem;
      margin-bottom: 10px;
      color: var(--dark);
    }
    
    .signup-form-container p {
      color: var(--gray);
      margin-bottom: 30px;
    }
    
    .social-signup {
      display: flex;
      justify-content: center;
      gap: 15px;
      margin-bottom: 30px;
    }
    
    .social-btn {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
      font-size: 1.2rem;
      cursor: pointer;
      transition: var(--transition);
      border: none;
    }
    
    .google {
      background: #DB4437;
    }
    
    .facebook {
      background: #4267B2;
    }
    
    .twitter {
      background: #1DA1F2;
    }
    
    .social-btn:hover {
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .divider {
      display: flex;
      align-items: center;
      margin: 30px 0;
    }
    
    .divider-line {
      flex: 1;
      height: 1px;
      background: #ddd;
    }
    
    .divider-text {
      padding: 0 15px;
      color: var(--gray);
      font-size: 0.9rem;
    }
    
    .form-group {
      margin-bottom: 20px;
      text-align: left;
    }
    
    .input-with-icon {
      position: relative;
    }
    
    .input-icon {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: var(--gray);
    }
    
    .form-control {
      width: 100%;
      padding: 14px 14px 14px 45px;
      border: 1px solid #ddd;
      border-radius: 8px;
      font-size: 1rem;
      transition: var(--transition);
    }
    
    .form-control:focus {
      border-color: var(--primary);
      outline: none;
      box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
    }
    
    .password-strength {
      height: 5px;
      background: #e0e0e0;
      border-radius: 5px;
      margin-top: 8px;
      overflow: hidden;
    }
    
    .strength-meter {
      height: 100%;
      width: 0;
      background: var(--accent);
      transition: var(--transition);
    }
    
    .terms {
      display: flex;
      align-items: flex-start;
      margin: 25px 0;
      text-align: left;
    }
    
    .terms input {
      margin-top: 5px;
      margin-right: 10px;
    }
    
    .terms label {
      font-size: 0.9rem;
      color: var(--gray);
    }
    
    .terms a {
      color: var(--primary);
      text-decoration: none;
    }
    
    .btn-signup {
      width: 100%;
      padding: 14px;
      background: var(--primary);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: var(--transition);
    }
    
    .btn-signup:hover {
      background: var(--secondary);
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .message {
      padding: 12px;
      border-radius: 8px;
      margin: 20px 0;
      text-align: center;
      font-size: 0.9rem;
    }
    
    .success {
      background: #e8f5e9;
      color: #2e7d32;
      border: 1px solid #c8e6c9;
    }
    
    .error {
      background: #ffebee;
      color: #c62828;
      border: 1px solid #ffcdd2;
    }
    
    .login-link {
      margin-top: 25px;
      font-size: 0.95rem;
      color: var(--gray);
    }
    
    .login-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      margin-left: 5px;
      transition: var(--transition);
    }
    
    .login-link a:hover {
      text-decoration: underline;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .signup-container {
        flex-direction: column;
      }
      
      .signup-illustration {
        padding: 60px 30px;
      }
      
      .signup-form-section {
        padding: 60px 30px;
      }
    }
    
    @media (max-width: 576px) {
      .benefits {
        flex-direction: column;
        align-items: center;
      }
      
      .benefit-item {
        width: 100%;
      }
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>
  
  <div class="signup-container">
    <div class="signup-illustration">
      <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bhapa - Pet Community</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #fffaf5;
      color: #333;
    }
    .illustration-content {
      max-width: 900px;
      margin: 60px auto;
      text-align: center;
      padding: 40px;
      background: #fff;
      border-radius: 20px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.08);
    }
    .illustration-content h1 {
      font-size: 2.5rem;
      font-weight: bold;
      color: #ff7b29; /* playful orange */
      margin-bottom: 15px;
    }
    .illustration-content p {
      font-size: 1.1rem;
      color: #666;
      margin-bottom: 40px;
    }
    .benefits {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 25px;
    }
    .benefit-item {
      background: #fff8f1;
      border-radius: 15px;
      padding: 25px 15px;
      transition: transform 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .benefit-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .benefit-item i {
      font-size: 2rem;
      color: #ff7b29;
      margin-bottom: 12px;
    }
    .benefit-item h3 {
      font-size: 1.1rem;
      color: #444;
      font-weight: 600;
    }
    /* Paw background accent */
    body::before {
      content: "🐾";
      font-size: 80px;
      color: rgba(255,123,41,0.1);
      position: absolute;
      top: 30px;
      left: 30px;
    }
    body::after {
      content: "🐾";
      font-size: 100px;
      color: rgba(255,123,41,0.08);
      position: absolute;
      bottom: 40px;
      right: 50px;
    }
  </style>
</head>
<body>

  <div class="illustration-content">
    <h1>Join the Bhapa Pet Community 🐶🐱</h1>
    <p>Create your Bhapa account to explore adorable pets, find new friends, and get personalized pet care recommendations.</p>
    
    <div class="benefits">
      <div class="benefit-item">
        <i class="fas fa-paw"></i>
        <h3>Discover Pets</h3>
      </div>
      <div class="benefit-item">
        <i class="fas fa-bone"></i>
        <h3>Easy Adoption</h3>
      </div>
      <div class="benefit-item">
        <i class="fas fa-bell"></i>
        <h3>Care Alerts</h3>
      </div>
      <div class="benefit-item">
        <i class="fas fa-users"></i>
        <h3>Connect with Pet Lovers</h3>
      </div>
    </div>
  </div>

</body>
</html>

    </div>
    
    <div class="signup-form-section">
      <div class="signup-form-container">
        <h2>Create Your Account</h2>
        
        <div class="divider">
          <div class="divider-line"></div>
          <div class="divider-text">Sign up with email</div>
          <div class="divider-line"></div>
        </div>
        
        <!-- Message Container -->
        <?php if ($message): ?>
          <div class="message <?php echo $messageClass; ?>">
            <i class="fas <?php echo $messageClass === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'; ?>"></i>
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
        
        <form method="post" id="signupForm">
          <div class="form-group">
            <div class="input-with-icon">
              <i class="fas fa-user input-icon"></i>
              <input type="text" name="name" class="form-control" placeholder="Full name" required
                     value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
            </div>
          </div>
          
          <div class="form-group">
            <div class="input-with-icon">
              <i class="fas fa-envelope input-icon"></i>
              <input type="email" name="email" class="form-control" placeholder="Email address" required
                     value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
            </div>
          </div>
          
          <div class="form-group">
            <div class="input-with-icon">
              <i class="fas fa-lock input-icon"></i>
              <input type="password" name="password" id="password" class="form-control" placeholder="Create password" required>
            </div>
            <div class="password-strength">
              <div class="strength-meter" id="strengthMeter"></div>
            </div>
          </div>
          
          <div class="terms">
            <input type="checkbox" id="terms" name="terms" required>
            <label for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
          </div>
          
          <button type="submit" class="btn-signup">Create Account</button>
        </form>
        
        <div class="login-link">
          Already have an account? <a href="login.php">Sign in</a>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Password strength indicator
    const passwordInput = document.getElementById('password');
    const strengthMeter = document.getElementById('strengthMeter');
    
    passwordInput.addEventListener('input', function() {
      const password = this.value;
      let strength = 0;
      
      // Check password length
      if (password.length >= 8) strength += 25;
      
      // Check for uppercase letters
      if (/[A-Z]/.test(password)) strength += 25;
      
      // Check for numbers
      if (/[0-9]/.test(password)) strength += 25;
      
      // Check for special characters
      if (/[^A-Za-z0-9]/.test(password)) strength += 25;
      
      // Update strength meter
      strengthMeter.style.width = strength + '%';
      
      // Update color
      if (strength < 50) {
        strengthMeter.style.background = '#ff6f61'; // red
      } else if (strength < 75) {
        strengthMeter.style.background = '#ffca28'; // yellow
      } else {
        strengthMeter.style.background = '#4caf50'; // green
      }
    });
    
    // Email availability check (simulated)
    const emailInput = document.querySelector('input[name="email"]');
    emailInput.addEventListener('blur', function() {
      // In a real application, this would be an AJAX request to the server
      // For demo purposes, we'll simulate with a list of taken emails
      const takenEmails = ['existing@example.com', 'taken@eventhub.com'];
      
      if (takenEmails.includes(this.value)) {
        // Create or update the message element
        let messageDiv = document.querySelector('.message');
        if (!messageDiv) {
          messageDiv = document.createElement('div');
          messageDiv.className = 'message error';
          const form = document.getElementById('signupForm');
          form.parentNode.insertBefore(messageDiv, form);
        }
        
        messageDiv.innerHTML = '<i class="fas fa-exclamation-circle"></i> Email already registered. Please use a different email.';
        messageDiv.className = 'message error';
      }
    });
    
    // Clear message when user starts typing in any field
    document.querySelectorAll('input').forEach(input => {
      input.addEventListener('input', function() {
        const messageDiv = document.querySelector('.message');
        if (messageDiv && messageDiv.classList.contains('error')) {
          messageDiv.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>