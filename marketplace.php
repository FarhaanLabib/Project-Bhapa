<?php
// marketplace.php
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Pet Marketplace</title>
  <style>
    body {
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: #f8f8f8;
      color: #222;
    }

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
    }
    .logo { font-size: 22px; font-weight: 700; }

    nav a {
      margin-left: 18px;
      text-decoration: none;
      font-weight: 600;
      padding: 8px 14px;
      border-radius: 8px;
      background: #ff6600;
      color: #fff;
      transition: background .2s ease;
    }
    nav a:hover { background: #e55b00; }

    /* cards layout */
    .cards {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      padding: 36px;
      justify-content: center;
    }
    .card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.08);
      width: 300px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    .card img {
      width: 100%;
      height: 180px;
      object-fit: cover;
    }
    .card-content {
      padding: 16px;
      display: flex;
      flex-direction: column;
      gap: 6px;
    }
    .card-content h3 {
      margin: 0;
    }
    .card-content p {
      margin: 0;
      font-size: 14px;
      color: #444;
    }
    .btn {
      background: #ff6600;
      color: #fff;
      border: none;
      padding: 10px 14px;
      border-radius: 8px;
      font-weight: 600;
      text-decoration: none;
      display: inline-block;
      margin-top: 12px;
      text-align: center;
    }
    .btn:hover { background: #e55b00; }

    @media (max-width: 768px) {
      .cards {
        padding: 20px;
      }
      .card {
        width: 100%;
      }
    }
  </style>
</head>
<body>

<header>
  <div class="logo">🐾 Pet Marketplace</div>
  <nav>
    <a href="index.php">Home</a>
  </nav>
</header>

<main>
  <div class="cards">
    <!-- product 1 -->
    <div class="card">
      <img src="https://placedog.net/500/280?id=21" alt="Dog Food">
      <div class="card-content">
        <h3>Premium Dog Food</h3>
        <p><strong>Description:</strong> High-protein kibble for active dogs.</p>
        <p><strong>Pet Type:</strong> Dog</p>
        <p><strong>Category:</strong> Food</p>
        <p><strong>Brand:</strong> Pedigree</p>
        <p><strong>Breed Specific:</strong> German Shepherd</p>
        <a href="#" class="btn">Buy Now</a>
      </div>
    </div>

    <!-- product 2 -->
    <div class="card">
      <img src="https://placekitten.com/400/250" alt="Cat Toy">
      <div class="card-content">
        <h3>Interactive Cat Toy</h3>
        <p><strong>Description:</strong> Feather teaser wand for playful cats.</p>
        <p><strong>Pet Type:</strong> Cat</p>
        <p><strong>Category:</strong> Toys</p>
        <p><strong>Brand:</strong> MeowPlay</p>
        <p><strong>Breed Specific:</strong> Persian Cat</p>
        <a href="#" class="btn">Buy Now</a>
      </div>
    </div>

    <!-- product 3 -->
    <div class="card">
      <img src="https://placebear.com/400/250" alt="Bird Cage">
      <div class="card-content">
        <h3>Spacious Bird Cage</h3>
        <p><strong>Description:</strong> Sturdy cage with perches and feeding cups.</p>
        <p><strong>Pet Type:</strong> Bird</p>
        <p><strong>Category:</strong> Housing</p>
        <p><strong>Brand:</strong> FeatherHome</p>
        <p><strong>Breed Specific:</strong> Parakeet</p>
        <a href="#" class="btn">Buy Now</a>
      </div>
    </div>

    <!-- product 4 -->
    <div class="card">
      <img src="https://placekitten.com/401/251" alt="Cat Bed">
      <div class="card-content">
        <h3>Cozy Cat Bed</h3>
        <p><strong>Description:</strong> Soft plush round bed for comfy naps.</p>
        <p><strong>Pet Type:</strong> Cat</p>
        <p><strong>Category:</strong> Accessories</p>
        <p><strong>Brand:</strong> PurrNest</p>
        <p><strong>Breed Specific:</strong> Maine Coon</p>
        <a href="#" class="btn">Buy Now</a>
      </div>
    </div>

    <!-- product 5 -->
    <div class="card">
      <img src="https://placedog.net/500/280?id=55" alt="Dog Leash">
      <div class="card-content">
        <h3>Durable Dog Leash</h3>
        <p><strong>Description:</strong> Nylon leash with padded handle.</p>
        <p><strong>Pet Type:</strong> Dog</p>
        <p><strong>Category:</strong> Accessories</p>
        <p><strong>Brand:</strong> PawGear</p>
        <p><strong>Breed Specific:</strong> Labrador</p>
        <a href="#" class="btn">Buy Now</a>
      </div>
    </div>
  </div>
</main>

</body>
</html>
