<?php
include 'db.php';
include 'navbar.php';

// Check if user is logged in

$is_logged_in = isset($_SESSION['user_id']);
$user_id = $is_logged_in ? $_SESSION['user_id'] : null;

// Initialize variables
$blogs_result = null;
$categories_result = null;
$total_blogs = 0;
$total_pages = 0;
$page = 1;

// Check database connection
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Handle blog submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_blog'])) {
    if (!$is_logged_in) {
        $error_message = "You must be logged in to submit a blog post.";
    } else {
        $title = trim($_POST['title']);
        $excerpt = trim($_POST['excerpt']);
        $content = trim($_POST['content']);
        $category = trim($_POST['category']);
        $read_time = intval($_POST['read_time']);
        
        // Validate inputs
        if (empty($title) || empty($excerpt) || empty($content) || empty($category)) {
            $error_message = "Please fill in all required fields.";
        } else {
            // Handle image upload
            $image_url = null;
            if (isset($_FILES['blog_image']) && $_FILES['blog_image']['error'] === UPLOAD_ERR_OK) {
                $upload_dir = "uploads/blogs/";
                if (!is_dir($upload_dir)) {
                    mkdir($upload_dir, 0777, true);
                }
                
                $file_extension = pathinfo($_FILES['blog_image']['name'], PATHINFO_EXTENSION);
                $file_name = uniqid() . '.' . $file_extension;
                $file_path = $upload_dir . $file_name;
                
                // Check if image file is actual image
                $check = getimagesize($_FILES['blog_image']['tmp_name']);
                if ($check !== false) {
                    if (move_uploaded_file($_FILES['blog_image']['tmp_name'], $file_path)) {
                        $image_url = $file_path;
                    } else {
                        $error_message = "Sorry, there was an error uploading your file.";
                    }
                } else {
                    $error_message = "File is not an image.";
                }
            }
            
            if (!isset($error_message)) {
                // Insert blog into database
                $status = 'published'; // Or 'draft' if you want to implement approval system
                
                $insert_query = $conn->prepare("INSERT INTO blogs (user_id, title, excerpt, content, category, read_time, image_url, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                $insert_query->bind_param("issssiss", $user_id, $title, $excerpt, $content, $category, $read_time, $image_url, $status);
                
                if ($insert_query->execute()) {
                    $success_message = "Blog post submitted successfully!";
                    // Clear form fields
                    $_POST = array();
                } else {
                    $error_message = "Error submitting blog: " . $conn->error;
                }
            }
        }
    }
}

try {
    // Pagination setup
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $per_page = 6;
    $offset = ($page > 1) ? ($page - 1) * $per_page : 0;

    // Get total number of published blogs
    $total_query = "SELECT COUNT(*) as total FROM blogs WHERE status = 'published'";
    $total_result = $conn->query($total_query);
    
    if ($total_result) {
        $total_blogs = $total_result->fetch_assoc()['total'];
        $total_pages = ceil($total_blogs / $per_page);
    } else {
        throw new Exception("Error getting blog count: " . $conn->error);
    }

    // Get blogs with pagination
    $blogs_query = "SELECT b.*, u.name 
                    FROM blogs b 
                    JOIN users u ON b.user_id = u.id 
                    WHERE b.status = 'published' 
                    ORDER BY b.created_at DESC 
                    LIMIT $offset, $per_page";
    $blogs_result = $conn->query($blogs_query);
    
    if (!$blogs_result) {
        throw new Exception("Error getting blogs: " . $conn->error);
    }

    // Get categories for filter
    $categories_query = "SELECT DISTINCT category FROM blogs WHERE category IS NOT NULL AND status = 'published'";
    $categories_result = $conn->query($categories_query);
    
    if (!$categories_result) {
        throw new Exception("Error getting categories: " . $conn->error);
    }
} catch (Exception $e) {
    $error_message = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetCareHub - Blog</title>
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

        .section-gray {
            background: #f9fafb;
        }

        h1, h2, h3, h4, h5 {
            font-weight: 700;
            line-height: 1.2;
            margin-bottom: 1rem;
        }
        
        h1 {
            font-size: 3.8rem;
            font-family: 'Playfair Display', serif;
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

        .blog-hero {
            background: linear-gradient(135deg, var(--primary) 0%, #1a2530 100%);
            color: white;
            padding: 100px 0;
            text-align: center;
            position: relative;
        }
        
        .blog-hero h1 {
            margin-bottom: 20px;
        }
        
        .blog-hero p {
            font-size: 1.3rem;
            opacity: 0.9;
            max-width: 700px;
            margin: 0 auto;
        }

        .blog-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 50px;
        }
        
        .blog-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .blog-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .blog-image {
            height: 220px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .blog-image:after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 50%;
            background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        }
        
        .blog-category {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--secondary);
            color: white;
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            z-index: 2;
        }
        
        .blog-content {
            padding: 30px;
        }
        
        .blog-meta {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .blog-meta span {
            margin-right: 15px;
            display: flex;
            align-items: center;
        }
        
        .blog-meta i {
            margin-right: 5px;
        }
        
        .blog-title {
            margin-bottom: 15px;
            font-size: 1.5rem;
            font-family: 'Playfair Display', serif;
        }
        
        .blog-excerpt {
            margin-bottom: 25px;
            color: #555;
        }
        
        .read-more {
            display: inline-flex;
            align-items: center;
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .read-more i {
            margin-left: 8px;
            transition: var(--transition);
        }
        
        .read-more:hover {
            color: var(--primary);
        }
        
        .read-more:hover i {
            transform: translateX(5px);
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 50px;
            gap: 10px;
        }
        
        .pagination a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: white;
            color: var(--dark);
            text-decoration: none;
            font-weight: 600;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .pagination a:hover, .pagination a.active {
            background: var(--secondary);
            color: white;
        }

        .sidebar {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 30px;
        }
        
        .sidebar h3 {
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--light);
        }
        
        .categories-list {
            list-style: none;
        }
        
        .categories-list li {
            margin-bottom: 10px;
        }
        
        .categories-list a {
            display: flex;
            justify-content: space-between;
            color: var(--dark);
            text-decoration: none;
            padding: 8px 0;
            transition: var(--transition);
        }
        
        .categories-list a:hover {
            color: var(--secondary);
        }
        
        .categories-list span {
            background: var(--light);
            color: var(--gray);
            padding: 2px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
        }

        .blog-layout {
            display: grid;
            grid-template-columns: 1fr 300px;
            gap: 40px;
        }
        
        .error-container {
            background: #ffebee;
            color: #c62828;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .success-container {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--gray);
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        /* Add Blog Button Styles */
        .add-blog-btn {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 12px 25px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 20px;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }
        
        .add-blog-btn:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        /* Blog Form Styles */
        .blog-form-container {
            background: white;
            border-radius: 16px;
            padding: 40px;
            box-shadow: var(--shadow);
            margin: 40px 0;
            display: none; /* Hidden by default */
        }
        
        .blog-form-container.visible {
            display: block;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-family: 'Montserrat', sans-serif;
            font-size: 1rem;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--secondary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(24, 188, 156, 0.2);
        }
        
        textarea.form-control {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-submit {
            background: var(--secondary);
            color: white;
            border: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: var(--transition);
        }
        
        .form-submit:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .login-prompt {
            text-align: center;
            padding: 40px;
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
        }
        
        .login-prompt a {
            color: var(--secondary);
            text-decoration: none;
            font-weight: 600;
        }
        
        .login-prompt a:hover {
            text-decoration: underline;
        }
        
        .close-form {
            background: var(--accent);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer;
            position: absolute;
            top: 20px;
            right: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        @media (max-width: 900px) {
            .blog-layout {
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
            
            .blog-grid {
                grid-template-columns: 1fr;
            }
            
            .blog-hero {
                padding: 80px 0;
            }
            
            .blog-form-container {
                padding: 25px;
            }
        }
    </style>
</head>
<body>
    <!-- Hero Section -->
    <section class="blog-hero">
        <div class="container">
            <h1>Pet Blogs</h1>
            <p>Discover helpful tips, stories, and advice for pet owners</p>
            <?php if ($is_logged_in): ?>
                <button id="show-blog-form" class="add-blog-btn">Write a Blog Post</button>
            <?php else: ?>
                <a href="login.php" class="add-blog-btn">Login to Write a Blog</a>
            <?php endif; ?>
        </div>
    </section>

    <!-- Add Blog Form Section (for logged in users) -->
    <?php if ($is_logged_in): ?>
    <section id="add-blog" class="section-gray" style="display: none;">
        <div class="container">
            <h2 class="text-center">Share Your Pet Story</h2>
            
            <?php if (isset($error_message)): ?>
                <div class="error-container">
                    <p><i class="fas fa-exclamation-circle"></i> <?php echo $error_message; ?></p>
                </div>
            <?php endif; ?>
            
            <?php if (isset($success_message)): ?>
                <div class="success-container">
                    <p><i class="fas fa-check-circle"></i> <?php echo $success_message; ?></p>
                </div>
            <?php endif; ?>
            
            <div class="blog-form-container" id="blog-form-container">
                <button class="close-form" id="close-form">&times;</button>
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Blog Title *</label>
                        <input type="text" id="title" name="title" class="form-control" value="<?php echo isset($_POST['title']) ? htmlspecialchars($_POST['title']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="excerpt">Short Excerpt *</label>
                        <textarea id="excerpt" name="excerpt" class="form-control" required><?php echo isset($_POST['excerpt']) ? htmlspecialchars($_POST['excerpt']) : ''; ?></textarea>
                        <small>Write a brief summary of your blog (1-2 sentences)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="content">Blog Content *</label>
                        <textarea id="content" name="content" class="form-control" required><?php echo isset($_POST['content']) ? htmlspecialchars($_POST['content']) : ''; ?></textarea>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" class="form-control" required>
                            <option value="">Select a category</option>
                            <option value="Dog Care" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Dog Care') ? 'selected' : ''; ?>>Dog Care</option>
                            <option value="Cat Care" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Cat Care') ? 'selected' : ''; ?>>Cat Care</option>
                            <option value="Training" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Training') ? 'selected' : ''; ?>>Training</option>
                            <option value="Nutrition" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Nutrition') ? 'selected' : ''; ?>>Nutrition</option>
                            <option value="Health" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Health') ? 'selected' : ''; ?>>Health</option>
                            <option value="Behavior" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Behavior') ? 'selected' : ''; ?>>Behavior</option>
                            <option value="Grooming" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Grooming') ? 'selected' : ''; ?>>Grooming</option>
                            <option value="Other" <?php echo (isset($_POST['category']) && $_POST['category'] == 'Other') ? 'selected' : ''; ?>>Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="read_time">Estimated Read Time (minutes) *</label>
                        <input type="number" id="read_time" name="read_time" class="form-control" min="1" max="60" value="<?php echo isset($_POST['read_time']) ? htmlspecialchars($_POST['read_time']) : '5'; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="blog_image">Blog Image (optional)</label>
                        <input type="file" id="blog_image" name="blog_image" class="form-control" accept="image/*">
                        <small>Recommended size: 1200x600 pixels</small>
                    </div>
                    
                    <button type="submit" name="submit_blog" class="form-submit">Submit Blog Post</button>
                </form>
            </div>
        </div>
    </section>
    <?php else: ?>
    <section class="section-gray">
        <div class="container">
            <div class="login-prompt">
                <h3>Want to share your pet story?</h3>
                <p>You need to be logged in to submit a blog post.</p>
                <p><a href="login.php">Login here</a> or <a href="register.php">create an account</a> to get started.</p>
            </div>
        </div>
    </section>
    <?php endif; ?>

    <!-- Blog Section -->
    <section class="section-light">
        <div class="container">
            <?php if (isset($error_message) && !isset($_POST['submit_blog'])): ?>
                <div class="error-container">
                    <h3><i class="fas fa-exclamation-triangle"></i> Database Error</h3>
                    <p><?php echo $error_message; ?></p>
                    <p>Please check your database connection and tables.</p>
                </div>
            <?php else: ?>
                <div class="blog-layout">
                    <div>
                        <h2>Latest Blogs</h2>
                        
                        <?php if ($blogs_result && $blogs_result->num_rows > 0): ?>
                            <div class="blog-grid">
                                <?php while ($blog = $blogs_result->fetch_assoc()): ?>
                                    <div class="blog-card animate">
                                        <div class="blog-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                            <?php if (!empty($blog['image_url'])): ?>
                                                <img src="<?php echo $blog['image_url']; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                            <?php endif; ?>
                                            <?php if (!empty($blog['category'])): ?>
                                                <div class="blog-category"><?php echo htmlspecialchars($blog['category']); ?></div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="blog-content">
                                            <div class="blog-meta">
                                                <span><i class="far fa-user"></i> <?php echo htmlspecialchars($blog['name']); ?></span>
                                                <span><i class="far fa-clock"></i> <?php echo $blog['read_time']; ?> min read</span>
                                                <span><i class="far fa-calendar"></i> <?php echo date('M j, Y', strtotime($blog['created_at'])); ?></span>
                                            </div>
                                            <h3 class="blog-title"><?php echo htmlspecialchars($blog['title']); ?></h3>
                                            <p class="blog-excerpt"><?php echo htmlspecialchars($blog['excerpt']); ?></p>
                                            <a href="blog-details.php?id=<?php echo $blog['id']; ?>" class="read-more">Read More <i class="fas fa-arrow-right"></i></a>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>

                            <!-- Pagination -->
                            <?php if ($total_pages > 1): ?>
                            <div class="pagination">
                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <a href="?page=<?php echo $i; ?>" class="<?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
                                <?php endfor; ?>
                            </div>
                            <?php endif; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <i class="fas fa-file-alt"></i>
                                <h3>No Blog Posts Yet</h3>
                                <p>Check back later for new articles and pet care tips.</p>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div>
                        <div class="sidebar">
                            <h3>Categories</h3>
                            <?php if ($categories_result && $categories_result->num_rows > 0): ?>
                                <ul class="categories-list">
                                    <?php while ($category = $categories_result->fetch_assoc()): 
                                        $count_query = $conn->query("SELECT COUNT(*) as count FROM blogs WHERE category = '{$category['category']}' AND status = 'published'");
                                        $count = $count_query ? $count_query->fetch_assoc()['count'] : 0;
                                    ?>
                                        <li>
                                            <a href="?category=<?php echo urlencode($category['category']); ?>">
                                                <?php echo htmlspecialchars($category['category']); ?>
                                                <span><?php echo $count; ?></span>
                                            </a>
                                        </li>
                                    <?php endwhile; ?>
                                </ul>
                            <?php else: ?>
                                <p>No categories found</p>
                            <?php endif; ?>
                        </div>

                        <div class="sidebar">
                            <h3>Popular Posts</h3>
                            <?php 
                            $popular_query = "SELECT * FROM blogs WHERE status = 'published' ORDER BY views DESC LIMIT 3";
                            $popular_result = $conn->query($popular_query);
                            ?>
                            <?php if ($popular_result && $popular_result->num_rows > 0): ?>
                                <?php while ($popular = $popular_result->fetch_assoc()): ?>
                                    <div style="margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #eee;">
                                        <h4 style="font-size: 1.1rem; margin-bottom: 10px;"><?php echo htmlspecialchars($popular['title']); ?></h4>
                                        <div style="font-size: 0.9rem; color: var(--gray);">
                                            <i class="far fa-eye"></i> <?php echo $popular['views']; ?> views
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <p>No popular posts found.</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
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
            
            // Character counter for excerpt
            const excerptTextarea = document.getElementById('excerpt');
            if (excerptTextarea) {
                const counter = document.createElement('div');
                counter.style.fontSize = '0.8rem';
                counter.style.textAlign = 'right';
                counter.style.marginTop = '5px';
                counter.style.color = '#95a5a6';
                excerptTextarea.parentNode.appendChild(counter);
                
                function updateCounter() {
                    const length = excerptTextarea.value.length;
                    counter.textContent = `${length}/160 characters`;
                    
                    if (length > 160) {
                        counter.style.color = '#e74c3c';
                    } else {
                        counter.style.color = '#95a5a6';
                    }
                }
                
                excerptTextarea.addEventListener('input', updateCounter);
                updateCounter();
            }
            
            // Toggle blog form visibility
            const showBlogFormBtn = document.getElementById('show-blog-form');
            const blogFormSection = document.getElementById('add-blog');
            const blogFormContainer = document.getElementById('blog-form-container');
            const closeFormBtn = document.getElementById('close-form');
            
            if (showBlogFormBtn && blogFormSection) {
                showBlogFormBtn.addEventListener('click', function() {
                    blogFormSection.style.display = 'block';
                    setTimeout(function() {
                        blogFormContainer.classList.add('visible');
                        // Scroll to form
                        blogFormSection.scrollIntoView({behavior: 'smooth'});
                    }, 10);
                });
            }
            
            if (closeFormBtn && blogFormSection) {
                closeFormBtn.addEventListener('click', function() {
                    blogFormContainer.classList.remove('visible');
                    setTimeout(function() {
                        blogFormSection.style.display = 'none';
                    }, 300);
                });
            }
            
            // If there was an error or success message, show the form
            <?php if (isset($error_message) || isset($success_message)): ?>
                if (blogFormSection) {
                    blogFormSection.style.display = 'block';
                    setTimeout(function() {
                        if (blogFormContainer) {
                            blogFormContainer.classList.add('visible');
                            blogFormSection.scrollIntoView({behavior: 'smooth'});
                        }
                    }, 10);
                }
            <?php endif; ?>
        });
    </script>
</body>
</html>