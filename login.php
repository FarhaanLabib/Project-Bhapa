
<?php

include 'config.php';
include 'db.php';
include 'navbar.php';


$loginError = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    $stmt = $conn->prepare("SELECT id, email, name, password FROM users WHERE email = ?");
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows === 1) {
            $stmt->bind_result($id, $emailFromDb, $name, $hashedPassword);
            $stmt->fetch();

            if (password_verify($password, $hashedPassword)) {
                $_SESSION['user_id'] = $id;
                $_SESSION['email'] = $emailFromDb;
                $_SESSION['user'] = $name;

                echo "<script>window.location.href = 'index.php';</script>";
                exit;
            } else {
                $loginError = 'Incorrect email or password.';
            }
        } else {
            $loginError = 'Incorrect email or password.';
        }

        $stmt->close();
    } else {
        $loginError = 'Something went wrong. Please try again later.';
    }
}
?>
  
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Bhapa</title>
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
     
      display: flex;
      flex-direction: column;
    }

    .login-container {
      display: flex;
     
    }
    
    .login-illustration {
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
    
    .login-illustration:before {
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
    
    .features {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 20px;
      margin-top: 40px;
    }
    
    .feature-item {
      background: rgba(255, 255, 255, 0.15);
      backdrop-filter: blur(10px);
      border-radius: 12px;
      padding: 20px;
      width: 180px;
      text-align: center;
    }
    
    .feature-item i {
      font-size: 2rem;
      margin-bottom: 15px;
    }
    
    .login-form-section {
      flex: 1;
      display: flex;
      justify-content: center;
      align-items: center;
      padding: 40px;
      background: white;
    }
    
    .login-form-container {
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
    
    .login-form-container h2 {
      font-size: 2rem;
      margin-bottom: 30px;
      color: var(--dark);
    }
    
    .social-login {
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
    
    .options {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 25px;
    }
    
    .remember {
      display: flex;
      align-items: center;
    }
    
    .remember input {
      margin-right: 8px;
    }
    
    .forgot-password {
      color: var(--primary);
      text-decoration: none;
      font-size: 0.9rem;
      transition: var(--transition);
    }
    
    .forgot-password:hover {
      text-decoration: underline;
    }
    
    .btn-login {
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
    
    .btn-login:hover {
      background: var(--secondary);
      transform: translateY(-3px);
      box-shadow: var(--shadow);
    }
    
    .error {
      background: #ffebee;
      color: #c62828;
      padding: 12px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
      font-size: 0.9rem;
    }
    
    .signup-link {
      margin-top: 25px;
      font-size: 0.95rem;
      color: var(--gray);
    }
    
    .signup-link a {
      color: var(--primary);
      text-decoration: none;
      font-weight: 600;
      margin-left: 5px;
      transition: var(--transition);
    }
    
    .signup-link a:hover {
      text-decoration: underline;
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .login-container {
        flex-direction: column;
      }
      
      .login-illustration {
        padding: 60px 30px;
      }
      
      .login-form-section {
        padding: 60px 30px;
      }
    }
    
    @media (max-width: 576px) {
      .features {
        flex-direction: column;
        align-items: center;
      }
      
      .feature-item {
        width: 100%;
      }
    }
  </style>
</head>
<body>
 
  <div class="login-container">
    <div class="login-illustration">
      <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>EventHub - Welcome Back</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #f7f9ff;
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
      color: #3a57e8; /* EventHub theme blue */
      margin-bottom: 15px;
    }
    .illustration-content p {
      font-size: 1.1rem;
      color: #666;
      margin-bottom: 40px;
    }
    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 25px;
    }
    .feature-item {
      background: #f0f4ff;
      border-radius: 15px;
      padding: 25px 15px;
      transition: transform 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .feature-item i {
      font-size: 2rem;
      color: #3a57e8;
      margin-bottom: 12px;
    }
    .feature-item h3 {
      font-size: 1.1rem;
      color: #444;
      font-weight: 600;
    }
    /* Decorative icons in background */
    body::before {
      content: "🎟️";
      font-size: 80px;
      color: rgba(58,87,232,0.08);
      position: absolute;
      top: 30px;
      left: 30px;
    }
    body::after {
      content: "🎉";
      font-size: 100px;
      color: rgba(58,87,232,0.06);
      position: absolute;
      bottom: 40px;
      right: 50px;
    }
  </style>
</head>
<body>

  <!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Bhapa - Pet Login</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background: #fdfcfb;
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
      color: #ff7b54; /* Warm pet orange */
      margin-bottom: 15px;
    }
    .illustration-content p {
      font-size: 1.1rem;
      color: #666;
      margin-bottom: 40px;
    }
    .features {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
      gap: 25px;
    }
    .feature-item {
      background: #fff6f2;
      border-radius: 15px;
      padding: 25px 15px;
      transition: transform 0.3s, box-shadow 0.3s;
      box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .feature-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }
    .feature-item i {
      font-size: 2rem;
      color: #ff7b54;
      margin-bottom: 12px;
    }
    .feature-item h3 {
      font-size: 1.1rem;
      color: #444;
      font-weight: 600;
    }
    /* Decorative pawprints */
    body::before {
      content: "🐾";
      font-size: 80px;
      color: rgba(255,123,84,0.08);
      position: absolute;
      top: 30px;
      left: 30px;
    }
    body::after {
      content: "🐶";
      font-size: 90px;
      color: rgba(255,123,84,0.06);
      position: absolute;
      bottom: 40px;
      right: 50px;
    }
  </style>
</head>
<body>

  <div class="illustration-content">
    <h1>Welcome Back to Bhapa 🐾</h1>
    <p>Sign in to find your furry friends, track lost pets, and explore pet adoption opportunities near you.</p>
    
    <div class="features">
      <div class="feature-item">
        <i class="fas fa-paw"></i>
        <h3>Track Pets</h3>
      </div>
      <div class="feature-item">
        <i class="fas fa-heart"></i>
        <h3>Adopt & Care</h3>
      </div>
      <div class="feature-item">
        <i class="fas fa-bell"></i>
        <h3>Get Alerts</h3>
      </div>
      <div class="feature-item">
        <i class="fas fa-users"></i>
        <h3>Pet Community</h3>
      </div>
    </div>
  </div>

</body>
</html>


</body>
</html>

    </div>
    
    <div class="login-form-section">
      <div class="login-form-container">
       
        <h2>Sign In to Your Account</h2>
        
        <div class="social-login">
          <button class="social-btn google">
            <i class="fab fa-google"></i>
          </button>
          <button class="social-btn facebook">
            <i class="fab fa-facebook-f"></i>
          </button>
          <button class="social-btn twitter">
            <i class="fab fa-twitter"></i>
          </button>
        </div>
        
        <div class="divider">
          <div class="divider-line"></div>
          <div class="divider-text">or continue with email</div>
          <div class="divider-line"></div>
        </div>
        
        <!-- PHP error message would go here -->
        <div class="error" style="display: none;">Incorrect email or password. Please try again.</div>
        
        <form method="post" id="loginForm">
          <div class="form-group">
            <div class="input-with-icon">
              <i class="fas fa-envelope input-icon"></i>
              <input type="email" name="email" class="form-control" placeholder="Email address" required>
            </div>
          </div>
          
          <div class="form-group">
            <div class="input-with-icon">
              <i class="fas fa-lock input-icon"></i>
              <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>
          </div>
          
          <div class="options">
            <div class="remember">
              <input type="checkbox" id="remember" name="remember">
              <label for="remember">Remember me</label>
            </div>
            <a href="#" class="forgot-password">Forgot password?</a>
          </div>
          
          <button type="submit" class="btn-login">Sign In</button>
        </form>
        
        <div class="signup-link">
          Don't have an account? <a href="signup.php">Create account</a>
        </div>
      </div>
    </div>
  </div>

<?php if (!empty($loginError)): ?>
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const errorBox = document.querySelector('.error');
    errorBox.textContent = <?= json_encode($loginError) ?>;
    errorBox.style.display = 'block';
    const form = document.getElementById('loginForm');
    form.classList.add('shake');
    setTimeout(() => form.classList.remove('shake'), 500);
  });
</script>
<?php endif; ?>
</body>
</html>