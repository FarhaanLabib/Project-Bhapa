<!-- Footer -->
<style>
  footer {
    background: var(--dark);
    color: white;
    padding: 60px 0 30px;
    margin-top: auto;
  }

  .footer-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 40px;
    margin-bottom: 40px;
  }

  .footer-col h3 {
    font-size: 1.4rem;
    margin-bottom: 20px;
    position: relative;
    padding-bottom: 10px;
  }

  .footer-col h3:after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 50px;
    height: 3px;
    background: var(--accent);
  }

  .footer-links a {
    display: block;
    color: #adb5bd;
    text-decoration: none;
    margin-bottom: 10px;
    transition: var(--transition);
  }

  .footer-links a:hover {
    color: white;
    transform: translateX(5px);
  }

  .social-links {
    display: flex;
    gap: 15px;
    margin-top: 20px;
  }

  .social-links a {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 40px;
    height: 40px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
    color: white;
    text-decoration: none;
    transition: var(--transition);
  }

  .social-links a:hover {
    background: var(--accent);
    transform: translateY(-3px);
  }

  .copyright {
    text-align: center;
    padding-top: 30px;
    border-top: 1px solid rgba(255,255,255,0.1);
    color: #adb5bd;
    font-size: 0.9rem;
  }
</style>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-col">
        <h3>Bhapa</h3>
        <p>Your premier destination for discovering and booking events worldwide. Find your next unforgettable experience.</p>
        <div class="social-links">
          <a href="#"><i class="fab fa-facebook-f"></i></a>
          <a href="#"><i class="fab fa-twitter"></i></a>
          <a href="#"><i class="fab fa-instagram"></i></a>
          <a href="#"><i class="fab fa-linkedin-in"></i></a>
        </div>
      </div>

      <div class="footer-col">
        <h3>Quick Links</h3>
        <div class="footer-links">
          <a href="#">Home</a>
          <a href="#">Events</a>
          <a href="#">Categories</a>
          <a href="#">Venues</a>
          <a href="#">About Us</a>
        </div>
      </div>

      <div class="footer-col">
        <h3>Support</h3>
        <div class="footer-links">
          <a href="#">Help Center</a>
          <a href="#">FAQs</a>
          <a href="#">Contact Us</a>
          <a href="#">Privacy Policy</a>
          <a href="#">Terms of Service</a>
        </div>
      </div>

      <div class="footer-col">
        <h3>Newsletter</h3>
        <p>Subscribe to get updates on upcoming events</p>
        <div style="display: flex; margin-top: 15px;">
          <input type="email" placeholder="Your email address" style="flex: 1; padding: 12px 15px; border: none; border-radius: 30px 0 0 30px; outline: none;">
          <button style="background: var(--accent); color: white; border: none; padding: 0 20px; border-radius: 0 30px 30px 0; font-weight: 600; cursor: pointer;">Subscribe</button>
        </div>
      </div>
    </div>

    <div class="copyright">
      &copy; 2023 EventHub. All rights reserved.
    </div>
  </div>
</footer>
