<?php include 'db.php'; ?>
<?php include 'navbar.php'; ?>

<?php
$message = ''; // Initialize message

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = $_POST['fullname'] ?? '';
    $email = $_POST['email'] ?? '';
    $subject = $_POST['subject'] ?? '';
    $messageContent = $_POST['message'] ?? '';
    
    // Basic validation
    if (empty($fullname) || empty($email) || empty($subject) || empty($messageContent)) {
        $message = "All fields are required!";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address";
    } else {
        $stmt = $conn->prepare("INSERT INTO contacts (fullname, email, subject, message) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            $message = "Database error: " . $conn->error;
        } else {
            $stmt->bind_param("ssss", $fullname, $email, $subject, $messageContent);
            if ($stmt->execute()) {
                $message = "success";
            } else {
                $message = "Error: " . $stmt->error;
            }
            $stmt->close();
        }
    }
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>PetCareHub - Contact Us</title>
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
      scroll-behavior: smooth;
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
    
    .section-title {
      margin-bottom: 2.5rem;
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
    
    .section-gray {
      background: #f8f9fa;
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
    
    .btn-lg {
      padding: 15px 40px;
      font-size: 1.1rem;
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
      background: url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIxMDAlIiBoZWlnaHQ9IjEwMCUiPjxkZWZzPjxwYXR0ZXJuIGlkPSJwYXR0ZXJuIiB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHBhdHRlcm5Vbml0cz0idXNlclNwYWNlT25Vc2UiIHBhdHRlcm5UcmFuc2Zvcm09InJvdGF0ZSg0NSkiPjxjaXJjbGUgY3g9IjIwIiBjeT0iMjAiIHI9IjEuNSIgZmlsbD0icmdiYSgyNTUsMjU1LDI1NSwwLjA1KSIvPjwvcGF0dGVybj48L2RlZnM+PHJlY3Qgd2lkdGg9IjEwMCUiIGhlaWdodD0iMTAwJSIgZmlsbD0idXJsKCNwYXR0ZXJuKSIvPjwvc3ZnPg==');
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
    
    /* Contact Page Specific Styles */
    .contact-section {
      padding: 80px 0;
    }
    
    .contact-container {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 40px;
    }
    
    .contact-info h2 {
      margin-bottom: 25px;
    }
    
    .contact-details {
      margin-top: 30px;
    }
    
    .contact-item {
      display: flex;
      align-items: flex-start;
      margin-bottom: 25px;
    }
    
    .contact-icon {
      width: 50px;
      height: 50px;
      border-radius: 50%;
      background: rgba(58, 124, 165, 0.1);
      display: flex;
      align-items: center;
      justify-content: center;
      margin-right: 20px;
      flex-shrink: 0;
    }
    
    .contact-icon i {
      font-size: 1.5rem;
      color: var(--primary);
    }
    
    .contact-form {
      background: white;
      border-radius: 12px;
      padding: 30px;
      box-shadow: var(--shadow);
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
      min-height: 150px;
      resize: vertical;
    }
    
    .map-section {
      height: 400px;
      background: #f8f9fa;
    }
    
    .map-container {
      height: 100%;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: var(--shadow);
    }
    
    .map-container iframe {
      width: 100%;
      height: 100%;
      border: none;
    }
    
    .faq-section {
      padding: 80px 0;
      background: #f8f9fa;
    }
    
    .faq-container {
      max-width: 800px;
      margin: 0 auto;
    }
    
    .faq-item {
      background: white;
      border-radius: 12px;
      margin-bottom: 20px;
      box-shadow: var(--shadow);
      overflow: hidden;
    }
    
    .faq-question {
      padding: 20px;
      font-size: 1.2rem;
      font-weight: 600;
      cursor: pointer;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .faq-answer {
      padding: 0 20px 20px;
      color: #555;
      display: none;
    }
    
    .faq-question i {
      transition: var(--transition);
    }
    
    .faq-question.active i {
      transform: rotate(180deg);
    }
    
    /* Responsive */
    @media (max-width: 992px) {
      .contact-container {
        grid-template-columns: 1fr;
      }
    }
    
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
    }
    
    /* Message Styles */
    .message-container {
      margin-top: 20px;
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
    
    /* Form Field Validation */
    .error-field {
      border-color: #f8d7da !important;
      box-shadow: 0 0 0 3px rgba(248, 215, 218, 0.5) !important;
    }
  </style>
</head>
<body>
  <!-- Contact Us Page -->
  <main id="contact">
    <!-- Contact Hero -->
    <section class="hero">
      <div class="hero-content">
        <h1>We're Here to Help</h1>
        <p>Have questions about pet care, adoption, or our services? Our team is ready to assist you and your furry friends</p>
      </div>
    </section>

    <!-- Contact Section -->
    <section class="contact-section section-light">
      <div class="container">
        <div class="contact-container">
          <div class="contact-info">
            <h2>Contact Information</h2>
            <p>Whether you need help with pet care, have questions about adoption, or want to provide feedback, we're here for you and your pets.</p>
            <div class="contact-details">
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="fas fa-map-marker-alt"></i>
                </div>
                <div>
                  <h3>Our Office</h3>
                  <p>123 Pet Care Avenue, Suite 200<br>San Francisco, CA 94107</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="fas fa-phone"></i>
                </div>
                <div>
                  <h3>Phone</h3>
                  <p>+1 (415) 555-PETS<br>Mon-Fri, 9am-6pm PST</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="fas fa-envelope"></i>
                </div>
                <div>
                  <h3>Email</h3>
                  <p>support@petcarehub.com<br>adoptions@petcarehub.com</p>
                </div>
              </div>
              
              <div class="contact-item">
                <div class="contact-icon">
                  <i class="fas fa-clock"></i>
                </div>
                <div>
                  <h3>Emergency Support</h3>
                  <p>Available 24/7 for urgent pet care questions and adoption emergencies</p>
                </div>
              </div>
            </div>
          </div>
          
          <div class="contact-form">
            <h2>Send Us a Message</h2>
            <form method="POST">
              <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="fullname" class="form-control <?php echo (!empty($message) && $message != 'success' && empty($fullname)) ? 'error-field' : ''; ?>" placeholder="Enter your name" value="<?php echo htmlspecialchars($fullname ?? ''); ?>">
              </div>
              
              <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control <?php echo (!empty($message) && $message != 'success' && (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL))) ? 'error-field' : ''; ?>" placeholder="Enter your email" value="<?php echo htmlspecialchars($email ?? ''); ?>">
              </div>
              
              <div class="form-group">
                <label for="subject">Subject</label>
                <select id="subject" name="subject" class="form-control <?php echo (!empty($message) && $message != 'success' && empty($subject)) ? 'error-field' : ''; ?>">
                  <option value="">Select a subject</option>
                  <option value="General Inquiry" <?php echo (isset($subject) && $subject == 'General Inquiry') ? 'selected' : ''; ?>>General Inquiry</option>
                  <option value="Adoption Questions" <?php echo (isset($subject) && $subject == 'Adoption Questions') ? 'selected' : ''; ?>>Adoption Questions</option>
                  <option value="Medication Support" <?php echo (isset($subject) && $subject == 'Medication Support') ? 'selected' : ''; ?>>Medication Support</option>
                  <option value="Technical Issues" <?php echo (isset($subject) && $subject == 'Technical Issues') ? 'selected' : ''; ?>>Technical Issues</option>
                  <option value="Partnership" <?php echo (isset($subject) && $subject == 'Partnership') ? 'selected' : ''; ?>>Partnership</option>
                  <option value="Other" <?php echo (isset($subject) && $subject == 'Other') ? 'selected' : ''; ?>>Other</option>
                </select>
              </div>
              
              <div class="form-group">
                <label for="message">Message</label>
                <textarea id="message" name="message" class="form-control <?php echo (!empty($message) && $message != 'success' && empty($messageContent)) ? 'error-field' : ''; ?>" placeholder="How can we help you and your pet?"><?php echo htmlspecialchars($messageContent ?? ''); ?></textarea>
              </div>
              
              <button type="submit" class="btn btn-primary" style="width: 100%;">Send Message</button>
              
              <!-- Message Container -->
              <?php if (!empty($message)): ?>
                <div class="message-container <?php echo ($message == 'success') ? 'success-message' : 'error-message'; ?>">
                  <?php if ($message == 'success') { 
                    echo '<i class="fas fa-check-circle"></i> Your message has been sent successfully! We\'ll get back to you soon.';
                  } else {
                    echo '<i class="fas fa-exclamation-circle"></i> ' . htmlspecialchars($message);
                  } ?>
                </div>
              <?php endif; ?>
            </form>
          </div>
        </div>
      </div>
    </section>

    <!-- Map Section -->
    <section class="map-section">
      <div class="container">
        <div class="map-container">
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d100940.14245968236!2d-122.43760000000003!3d37.75769999999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x80859a6d00690021%3A0x4a501367f076adff!2sSan%20Francisco%2C%20CA!5e0!3m2!1sen!2sus!4v1688652499999!5m2!1sen!2sus" allowfullscreen="" loading="lazy"></iframe>
        </div>
      </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
      <div class="container">
        <h2 class="text-center">Frequently Asked Questions</h2>
        <p class="text-center" style="max-width: 700px; margin: 0 auto 40px;">Find answers to common questions about PetCareHub</p>
        
        <div class="faq-container">
          <div class="faq-item">
            <div class="faq-question">
              How does the medication tracking feature work?
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Our medication tracking system allows you to input your pet's medications, set reminders for doses, and track administration history. You'll receive notifications via email or app alerts to ensure your pet never misses a dose.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              How can I adopt a pet through PetCareHub?
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>We partner with verified shelters and rescue organizations. You can browse available pets on our platform, submit adoption applications, and schedule meet-and-greet sessions—all through our secure system.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Is my pet's health information secure?
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>Absolutely. We use industry-standard encryption and security practices to protect all your pet's health information. Your data is never shared with third parties without your explicit consent.</p>
            </div>
          </div>
          
          <div class="faq-item">
            <div class="faq-question">
              Do you offer emergency veterinary advice?
              <i class="fas fa-chevron-down"></i>
            </div>
            <div class="faq-answer">
              <p>While we provide general pet care information, we always recommend contacting your veterinarian directly for emergencies. Our 24/7 support line can help you locate emergency vet services in your area.</p>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>

  <script>
    // FAQ functionality
    document.querySelectorAll('.faq-question').forEach(question => {
      question.addEventListener('click', () => {
        const answer = question.nextElementSibling;
        const isActive = question.classList.contains('active');
        
        // Close all open answers
        document.querySelectorAll('.faq-question').forEach(q => {
          q.classList.remove('active');
          q.nextElementSibling.style.display = 'none';
        });
        
        // If this wasn't active, open it
        if (!isActive) {
          question.classList.add('active');
          answer.style.display = 'block';
        }
      });
    });
    
    // Highlight error fields on page load
    document.addEventListener('DOMContentLoaded', function() {
      const errorFields = document.querySelectorAll('.error-field');
      if (errorFields.length > 0) {
        errorFields[0].focus();
      }
    });
  </script>
</body>
</html>