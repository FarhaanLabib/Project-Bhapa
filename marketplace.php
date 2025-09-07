<?php
session_start();

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle "Add to Cart"
if (isset($_GET['add_to_cart'])) {
    $product_id = intval($_GET['add_to_cart']);
    
    // Check if product already exists in cart
    $found = false;
    foreach ($_SESSION['cart'] as &$item) {
        if ($item['id'] == $product_id) {
            $item['quantity'] += 1;
            $found = true;
            break;
        }
    }
    
    // If not found, add new item with quantity 1
    if (!$found) {
        $_SESSION['cart'][] = [
            'id' => $product_id,
            'quantity' => 1
        ];
    }
    
    header("Location: marketplace.php");
    exit;
}

// Demo products
$products = [
    ['id'=>1,'product_name'=>'Premium Dog Food','Description'=>'High-protein kibble for active dogs','Pet_type'=>'Dog','Category'=>'Food','Brand'=>'Pedigree','breedspecific'=>'German Shepherd','image_url'=>'https://placedog.net/500/280?id=21','price'=>29.99],
    ['id'=>2,'product_name'=>'Interactive Cat Toy','Description'=>'Feather teaser wand for playful cats','Pet_type'=>'Cat','Category'=>'Toys','Brand'=>'MeowPlay','breedspecific'=>'Persian Cat','image_url'=>'https://placekitten.com/400/250','price'=>12.99],
    ['id'=>3,'product_name'=>'Spacious Bird Cage','Description'=>'Sturdy cage with perches and feeding cups','Pet_type'=>'Bird','Category'=>'Housing','Brand'=>'FeatherHome','breedspecific'=>'Parakeet','image_url'=>'https://placebear.com/400/250','price'=>89.99],
    ['id'=>4,'product_name'=>'Cozy Cat Bed','Description'=>'Soft plush round bed for comfy naps','Pet_type'=>'Cat','Category'=>'Accessories','Brand'=>'PurrNest','breedspecific'=>'Maine Coon','image_url'=>'https://placekitten.com/401/251','price'=>34.99],
    ['id'=>5,'product_name'=>'Durable Dog Leash','Description'=>'Nylon leash with padded handle','Pet_type'=>'Dog','Category'=>'Accessories','Brand'=>'PawGear','breedspecific'=>'Labrador','image_url'=>'https://placedog.net/500/280?id=55','price'=>19.99],
    ['id'=>6,'product_name'=>'Bird Swing','Description'=>'Fun swing for parrots and small birds','Pet_type'=>'Bird','Category'=>'Toys','Brand'=>'FeatherFun','breedspecific'=>'Cockatiel','image_url'=>'https://placebear.com/401/251','price'=>15.99]
];

// Calculate total items in cart (FIXED VERSION)
$cart_count = 0;
if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        if (isset($item['quantity'])) {
            $cart_count += $item['quantity'];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Pet Marketplace</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
:root {
  --primary: #2c3e50;    /* Deep blue */
  --secondary: #18bc9c;  /* Teal */
  --accent: #e74c3c;     /* Coral */
  --light: #ecf0f1;      /* Light gray */
  --dark: #2c3e50;       /* Dark blue */
  --gray: #95a5a6;       /* Medium gray */
  --success: #27ae60;    /* Green */
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

h1, h2, h3, h4, h5 {
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 1rem;
}

h1 {
  font-size: 2.8rem;
  font-family: 'Playfair Display', serif;
  font-weight: 700;
}

h2 {
  font-size: 2.2rem;
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
  font-size: 1.5rem;
}

p {
  margin-bottom: 1rem;
  color: #555;
  font-size: 1rem;
  line-height: 1.6;
}

.text-center {
  text-align: center;
}

.section-title {
  margin-bottom: 3rem;
}

.section-title.text-center h2:after {
  left: 50%;
  transform: translateX(-50%);
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

.btn {
  display: inline-block;
  padding: 14px 28px;
  border-radius: 50px;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
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

.btn-secondary {
  background: transparent;
  color: var(--primary);
  border: 2px solid var(--primary);
}

.btn-secondary:hover {
  background: var(--primary);
  color: white;
  transform: translateY(-3px);
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

.btn-sm {
  padding: 10px 20px;
  font-size: 0.8rem;
}

/* Header Styles */
header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  background: #fff;
  padding: 16px 28px;
  font-weight: 600;
  border-bottom: 1px solid #eee;
  position: sticky;
  top: 0;
  z-index: 1000;
  box-shadow: var(--shadow);
}

.logo {
  font-size: 24px;
  font-weight: 700;
  color: var(--primary);
  font-family: 'Playfair Display', serif;
}

.logo i {
  color: var(--secondary);
  margin-right: 8px;
}

nav {
  display: flex;
  align-items: center;
  gap: 20px;
}

nav a {
  text-decoration: none;
  font-weight: 600;
  padding: 8px 16px;
  border-radius: 8px;
  color: var(--primary);
  transition: var(--transition);
}

nav a:hover {
  color: var(--secondary);
}

.cart-box {
  background: var(--light);
  padding: 10px 16px;
  border-radius: 50px;
  display: flex;
  align-items: center;
  gap: 8px;
  font-weight: 600;
  transition: var(--transition);
}

.cart-box:hover {
  background: var(--secondary);
  color: white;
}

/* Hero Section */
.market-hero {
  background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
  color: white;
  padding: 80px 0;
  text-align: center;
}

.market-hero h1 {
  margin-bottom: 20px;
}

.market-hero p {
  max-width: 700px;
  margin: 0 auto 30px;
  color: rgba(255,255,255,0.9);
  font-size: 1.2rem;
}

/* Product Grid */
.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
  gap: 30px;
  padding: 40px 0;
}

.product-card {
  background: white;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: var(--shadow);
  transition: var(--transition);
  position: relative;
}

.product-card:hover {
  transform: translateY(-10px);
  box-shadow: 0 20px 40px rgba(0,0,0,0.15);
}

.product-image {
  height: 200px;
  overflow: hidden;
}

.product-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: var(--transition);
}

.product-card:hover .product-image img {
  transform: scale(1.05);
}

.product-content {
  padding: 25px;
}

.product-title {
  font-size: 1.3rem;
  margin-bottom: 10px;
  color: var(--primary);
}

.product-price {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--secondary);
  margin-bottom: 15px;
}

.product-details {
  margin-bottom: 20px;
}

.product-detail {
  display: flex;
  margin-bottom: 8px;
  font-size: 0.9rem;
}

.detail-label {
  font-weight: 600;
  min-width: 120px;
  color: var(--dark);
}

.detail-value {
  color: #666;
}

.product-action {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 20px;
}

.quantity-controls {
  display: flex;
  align-items: center;
  background: var(--light);
  border-radius: 50px;
  overflow: hidden;
}

.quantity-btn {
  background: none;
  border: none;
  width: 36px;
  height: 36px;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  font-size: 1.2rem;
  transition: var(--transition);
}

.quantity-btn:hover {
  background: var(--secondary);
  color: white;
}

.quantity-display {
  width: 40px;
  text-align: center;
  font-weight: 600;
}

/* Filter Section */
.filter-section {
  background: var(--light);
  padding: 30px 0;
}

.filter-container {
  display: flex;
  flex-wrap: wrap;
  gap: 15px;
  justify-content: center;
}

.filter-group {
  display: flex;
  flex-direction: column;
  min-width: 200px;
}

.filter-label {
  font-weight: 600;
  margin-bottom: 8px;
  color: var(--dark);
}

.filter-select {
  padding: 12px 16px;
  border-radius: 8px;
  border: 1px solid #ddd;
  background: white;
  font-family: 'Montserrat', sans-serif;
}

/* Responsive Design */
@media (max-width: 992px) {
  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  }
}

@media (max-width: 768px) {
  header {
    flex-direction: column;
    padding: 15px;
    gap: 15px;
  }
  
  .products-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .market-hero {
    padding: 60px 0;
  }
  
  .market-hero h1 {
    font-size: 2.2rem;
  }
  
  .filter-container {
    flex-direction: column;
  }
}

/* Animation */
@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.animate {
  animation: fadeInUp 0.8s ease-out forwards;
}

.delay-1 {
  animation-delay: 0.2s;
}

.delay-2 {
  animation-delay: 0.4s;
}
</style>
</head>
<body>

<header>
  <div class="logo"><i class="fas fa-paw"></i> Pet Marketplace</div>
  <nav>
    <a href="index.php">Home</a>
    <a href="cart.php" class="cart-box">
      <i class="fas fa-shopping-cart"></i>
      <span>Cart: <?php echo $cart_count; ?> item(s)</span>
    </a>
  </nav>
</header>

<section class="market-hero">
  <div class="container">
    <h1>Premium Pet Products</h1>
    <p>Discover high-quality supplies for your beloved pets. From food to toys, we have everything you need to keep your furry friends happy and healthy.</p>
    <a href="#products" class="btn btn-primary">Shop Now</a>
  </div>
</section>

<section class="filter-section">
  <div class="container">
    <div class="filter-container">
      <div class="filter-group">
        <label class="filter-label">Pet Type</label>
        <select class="filter-select">
          <option>All Pets</option>
          <option>Dogs</option>
          <option>Cats</option>
          <option>Birds</option>
          <option>Other</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label class="filter-label">Category</label>
        <select class="filter-select">
          <option>All Categories</option>
          <option>Food</option>
          <option>Toys</option>
          <option>Accessories</option>
          <option>Housing</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label class="filter-label">Brand</label>
        <select class="filter-select">
          <option>All Brands</option>
          <option>Pedigree</option>
          <option>MeowPlay</option>
          <option>FeatherHome</option>
          <option>PurrNest</option>
          <option>PawGear</option>
        </select>
      </div>
      
      <div class="filter-group">
        <label class="filter-label">Sort By</label>
        <select class="filter-select">
          <option>Featured</option>
          <option>Price: Low to High</option>
          <option>Price: High to Low</option>
          <option>Newest First</option>
        </select>
      </div>
    </div>
  </div>
</section>

<section id="products">
  <div class="container">
    <div class="products-grid">
      <?php foreach($products as $product): ?>
      <div class="product-card animate">
        <div class="product-image">
          <img src="<?php echo $product['image_url']; ?>" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
        </div>
        <div class="product-content">
          <h3 class="product-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
          <div class="product-price">$<?php echo number_format($product['price'], 2); ?></div>
          
          <div class="product-details">
            <div class="product-detail">
              <span class="detail-label">Description:</span>
              <span class="detail-value"><?php echo htmlspecialchars($product['Description']); ?></span>
            </div>
            <div class="product-detail">
              <span class="detail-label">Pet Type:</span>
              <span class="detail-value"><?php echo htmlspecialchars($product['Pet_type']); ?></span>
            </div>
            <div class="product-detail">
              <span class="detail-label">Category:</span>
              <span class="detail-value"><?php echo htmlspecialchars($product['Category']); ?></span>
            </div>
            <div class="product-detail">
              <span class="detail-label">Brand:</span>
              <span class="detail-value"><?php echo htmlspecialchars($product['Brand']); ?></span>
            </div>
            <div class="product-detail">
              <span class="detail-label">Breed Specific:</span>
              <span class="detail-value"><?php echo htmlspecialchars($product['breedspecific']); ?></span>
            </div>
          </div>
          
          <div class="product-action">
            <div class="quantity-controls">
              <button class="quantity-btn">-</button>
              <div class="quantity-display">1</div>
              <button class="quantity-btn">+</button>
            </div>
            <a href="?add_to_cart=<?php echo $product['id']; ?>" class="btn btn-primary btn-sm">Add to Cart</a>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<script>
// Quantity controls functionality
document.querySelectorAll('.quantity-controls').forEach(control => {
  const minusBtn = control.querySelector('.quantity-btn:first-child');
  const plusBtn = control.querySelector('.quantity-btn:last-child');
  const display = control.querySelector('.quantity-display');
  
  let quantity = 1;
  
  minusBtn.addEventListener('click', () => {
    if (quantity > 1) {
      quantity--;
      display.textContent = quantity;
    }
  });
  
  plusBtn.addEventListener('click', () => {
    quantity++;
    display.textContent = quantity;
  });
});

// Animation on scroll
document.addEventListener('DOMContentLoaded', function() {
  const animatedElements = document.querySelectorAll('.animate');
  
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.style.opacity = '1';
        entry.target.style.transform = 'translateY(0)';
      }
    });
  }, {
    threshold: 0.1
  });
  
  animatedElements.forEach(element => {
    element.style.opacity = '0';
    element.style.transform = 'translateY(40px)';
    element.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
    observer.observe(element);
  });
});
</script>

</body>
</html>