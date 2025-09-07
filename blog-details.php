<?php
include 'db.php';
include 'navbar.php';

// Check if blog ID is provided
if (!isset($_GET['id'])) {
    header("Location: blog.php");
    exit();
}

$blog_id = (int)$_GET['id'];

// Fetch blog post
$blog_query = "SELECT b.*, u.name 
               FROM blogs b 
               JOIN users u ON b.user_id = u.id 
               WHERE b.id = $blog_id AND b.status = 'published'";
$blog_result = $conn->query($blog_query);

if ($blog_result->num_rows === 0) {
    header("Location: blog.php");
    exit();
}

$blog = $blog_result->fetch_assoc();

// Increment view count
$conn->query("UPDATE blogs SET views = views + 1 WHERE id = $blog_id");

// Fetch comments for this blog
$comments_query = "SELECT bc.*, u.name 
                   FROM blog_comments bc 
                   JOIN users u ON bc.user_id = u.id 
                   WHERE bc.blog_id = $blog_id 
                   ORDER BY bc.created_at DESC";
$comments_result = $conn->query($comments_query);

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_comment'])) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];
    $comment = $conn->real_escape_string($_POST['comment']);
    
    $insert_comment = "INSERT INTO blog_comments (blog_id, user_id, comment) 
                       VALUES ($blog_id, $user_id, '$comment')";
    
    if ($conn->query($insert_comment)) {
        $success_message = "Comment added successfully!";
        // Refresh comments
        $comments_result = $conn->query($comments_query);
    } else {
        $error_message = "Error adding comment: " . $conn->error;
    }
}

// Fetch related blogs
$related_query = "SELECT * FROM blogs 
                  WHERE category = '{$blog['category']}' 
                  AND id != $blog_id 
                  AND status = 'published' 
                  ORDER BY created_at DESC 
                  LIMIT 3";
$related_result = $conn->query($related_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($blog['title']); ?> - PetCareHub Blog</title>
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
        
        .blog-header {
            text-align: center;
            margin-bottom: 40px;
        }
        
        .blog-meta {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
            font-size: 0.9rem;
            color: var(--gray);
            flex-wrap: wrap;
        }
        
        .blog-meta span {
            margin: 0 15px;
            display: flex;
            align-items: center;
        }
        
        .blog-meta i {
            margin-right: 5px;
        }
        
        .blog-category {
            display: inline-block;
            background: var(--secondary);
            color: white;
            padding: 8px 20px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 20px;
        }
        
        .blog-image {
            height: 500px;
            background-size: cover;
            background-position: center;
            border-radius: 16px;
            margin-bottom: 40px;
            box-shadow: var(--shadow);
            position: relative;
            overflow: hidden;
        }
        
        .blog-content {
            margin-bottom: 50px;
            font-size: 1.1rem;
            line-height: 1.9;
        }
        
        .blog-content p {
            margin-bottom: 25px;
        }
        
        .blog-content h2, .blog-content h3 {
            margin-top: 40px;
            margin-bottom: 20px;
            color: var(--primary);
        }
        
        .blog-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 30px;
            border-top: 1px solid var(--light);
            margin-top: 50px;
            flex-wrap: wrap;
        }
        
        .blog-tags {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .blog-tags span {
            margin-right: 10px;
            font-weight: 600;
        }
        
        .tag {
            display: inline-block;
            background: var(--light);
            color: var(--dark);
            padding: 6px 15px;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-right: 8px;
        }
        
        .blog-share {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .blog-share span {
            margin-right: 10px;
            font-weight: 600;
        }
        
        .share-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--light);
            color: var(--dark);
            margin-left: 10px;
            transition: var(--transition);
            text-decoration: none;
        }
        
        .share-btn:hover {
            background: var(--secondary);
            color: white;
            transform: translateY(-3px);
        }

        .comments-section {
            margin-top: 60px;
        }
        
        .comment-form {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: var(--shadow);
            margin-bottom: 40px;
        }
        
        .comment-form h3 {
            margin-bottom: 20px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: var(--dark);
        }
        
        textarea {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5eb;
            border-radius: 8px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: var(--transition);
            min-height: 150px;
            resize: vertical;
        }
        
        textarea:focus {
            outline: none;
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(24, 188, 156, 0.2);
        }
        
        .btn {
            display: inline-block;
            padding: 14px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: var(--transition);
            border: none;
            background: var(--secondary);
            color: white;
        }
        
        .btn:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }
        
        .comment-list {
            margin-top: 40px;
        }
        
        .comment {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: var(--shadow);
            margin-bottom: 25px;
            transition: var(--transition);
        }
        
        .comment:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        
        .comment-header {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
        }
        
        .comment-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--secondary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 1.2rem;
            margin-right: 15px;
        }
        
        .comment-author {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--primary);
        }
        
        .comment-date {
            font-size: 0.9rem;
            color: var(--gray);
        }
        
        .comment-content {
            color: #555;
            line-height: 1.6;
            font-size: 1.05rem;
        }

        .related-posts {
            margin-top: 80px;
        }
        
        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 30px;
            margin-top: 30px;
        }
        
        .related-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }
        
        .related-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }
        
        .related-image {
            height: 200px;
            background-size: cover;
            background-position: center;
            position: relative;
        }
        
        .related-category {
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
        
        .related-content {
            padding: 25px;
        }
        
        .related-title {
            font-size: 1.4rem;
            margin-bottom: 15px;
            font-family: 'Playfair Display', serif;
            color: var(--primary);
        }
        
        .related-link {
            display: inline-flex;
            align-items: center;
            color: var(--secondary);
            font-weight: 600;
            text-decoration: none;
            transition: var(--transition);
        }
        
        .related-link i {
            margin-left: 8px;
            transition: var(--transition);
        }
        
        .related-link:hover {
            color: var(--primary);
        }
        
        .related-link:hover i {
            transform: translateX(5px);
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
            
            .blog-image {
                height: 300px;
            }
            
            .blog-footer {
                flex-direction: column;
                align-items: flex-start;
            }
            
            .blog-share {
                margin-top: 20px;
            }
            
            .related-grid {
                grid-template-columns: 1fr;
            }
            
            .blog-meta span {
                margin: 5px 15px;
            }
        }
    </style>
</head>
<body>
    <!-- Blog Detail Section -->
    <section class="section-light">
        <div class="container">
            <div class="blog-header animate">
                <div class="blog-category"><?php echo htmlspecialchars($blog['category']); ?></div>
                <h1><?php echo htmlspecialchars($blog['title']); ?></h1>
                <div class="blog-meta">
                    <span><i class="far fa-user"></i> By <?php echo htmlspecialchars($blog['name']); ?></span>
                    <span><i class="far fa-calendar"></i> <?php echo date('F j, Y', strtotime($blog['created_at'])); ?></span>
                    <span><i class="far fa-clock"></i> <?php echo $blog['read_time']; ?> min read</span>
                    <span><i class="far fa-eye"></i> <?php echo $blog['views'] + 1; ?> views</span>
                </div>
            </div>

            <div class="blog-image animate" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                <?php if (!empty($blog['image_url'])): ?>
                    <img src="<?php echo $blog['image_url']; ?>" alt="<?php echo htmlspecialchars($blog['title']); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 16px;">
                <?php endif; ?>
            </div>

            <div class="blog-content animate">
                <?php echo nl2br(htmlspecialchars($blog['content'])); ?>
            </div>

            <div class="blog-footer animate">
                <div class="blog-tags">
                    <span>Tags:</span>
                    <div>
                        <span class="tag"><?php echo htmlspecialchars($blog['category']); ?></span>
                        <span class="tag">Pet Care</span>
                        <span class="tag">Tips</span>
                    </div>
                </div>
                <div class="blog-share">
                    <span>Share:</span>
                    <a href="#" class="share-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="share-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="share-btn"><i class="fab fa-linkedin-in"></i></a>
                    <a href="#" class="share-btn"><i class="fab fa-pinterest-p"></i></a>
                </div>
            </div>

            <!-- Comments Section -->
            <div class="comments-section">
                <h2 class="animate">Comments (<?php echo $comments_result->num_rows; ?>)</h2>
                
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

                <!-- Comment Form -->
                <?php if (isset($_SESSION['user_id'])): ?>
                <div class="comment-form animate">
                    <h3>Leave a Comment</h3>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="comment">Your Comment</label>
                            <textarea id="comment" name="comment" required placeholder="Share your thoughts..."></textarea>
                        </div>
                        <button type="submit" name="submit_comment" class="btn">Post Comment</button>
                    </form>
                </div>
                <?php else: ?>
                <div class="comment-form animate">
                    <p>Please <a href="login.php" style="color: var(--secondary); font-weight: 600;">login</a> to leave a comment.</p>
                </div>
                <?php endif; ?>

                <!-- Comment List -->
                <div class="comment-list">
                    <?php if ($comments_result->num_rows > 0): ?>
                        <?php while ($comment = $comments_result->fetch_assoc()): ?>
                            <div class="comment animate">
                                <div class="comment-header">
                                    <div class="comment-avatar">
                                        <?php 
                                        $name = $comment['name'];
                                        $name_parts = explode(' ', $name);
                                        if (count($name_parts) >= 2) {
                                            echo strtoupper($name_parts[0][0] . $name_parts[1][0]);
                                        } else {
                                            echo strtoupper(substr($name, 0, 2));
                                        }
                                        ?>
                                    </div>
                                    <div>
                                        <div class="comment-author"><?php echo htmlspecialchars($comment['name']); ?></div>
                                        <div class="comment-date"><?php echo date('F j, Y \a\t g:i a', strtotime($comment['created_at'])); ?></div>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <?php echo nl2br(htmlspecialchars($comment['comment'])); ?>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <p class="animate">No comments yet. Be the first to comment!</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Related Posts -->
            <?php if ($related_result->num_rows > 0): ?>
            <div class="related-posts">
                <h2 class="animate">Related Articles</h2>
                <div class="related-grid">
                    <?php while ($related = $related_result->fetch_assoc()): ?>
                        <div class="related-card animate">
                            <div class="related-image" style="background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                                <?php if (!empty($related['image_url'])): ?>
                                    <img src="<?php echo $related['image_url']; ?>" alt="<?php echo htmlspecialchars($related['title']); ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php endif; ?>
                                <?php if (!empty($related['category'])): ?>
                                    <div class="related-category"><?php echo htmlspecialchars($related['category']); ?></div>
                                <?php endif; ?>
                            </div>
                            <div class="related-content">
                                <h3 class="related-title"><?php echo htmlspecialchars($related['title']); ?></h3>
                                <a href="blog-details.php?id=<?php echo $related['id']; ?>" class="related-link">Read More <i class="fas fa-arrow-right"></i></a>
                            </div>
                        </div>
                    <?php endwhile; ?>
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
        });
    </script>
</body>
</html>