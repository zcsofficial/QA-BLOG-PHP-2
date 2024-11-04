<?php
include 'config.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NCC Journey - Home</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body>

    <!-- Navbar with Rounded Container -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm rounded-pill mt-3 mx-auto" style="max-width: 95%; padding: 10px 30px;">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="https://github.com/zcsofficial/ncc/blob/main/logo.png?raw=true" alt="Logo" height="50">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="about.php">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="https://zcsofficial.github.io/ncc/attendance.html">Attendance</a></li>
                    <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="events.php">Events</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <?php endif; ?>
                </ul>
                <!-- Profile Dropdown -->
                <div class="dropdown ms-3">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <button class="btn btn-secondary dropdown-toggle rounded-pill" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo htmlspecialchars($_SESSION['username']); ?> <i class="fas fa-user-circle"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                            <?php if ($_SESSION['role'] === 'admin'): ?>
                                <li><a class="dropdown-item" href="admin_panel.php">Admin Console</a></li>
                            <?php endif; ?>
                            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                        </ul>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary">Login</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Blog Posts Section -->
    <section class="blog-posts py-5">
        <div class="container">
            <h2 class="text-center mb-4">All Blog Posts</h2>
            <div class="row">
                <?php
                $query = "SELECT posts.id, posts.title, posts.body, users.username, posts.created_at FROM posts JOIN users ON posts.author_id = users.id ORDER BY posts.created_at DESC";
                $result = $conn->query($query);

                while ($post = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($post['title']) . '</h5>';
                    echo '<h6 class="card-subtitle mb-2 text-muted">by ' . htmlspecialchars($post['username']) . ' on ' . htmlspecialchars($post['created_at']) . '</h6>';
                    echo '<p class="card-text">' . htmlspecialchars($post['body']) . '</p>';
                    echo '<a href="post.php?id=' . $post['id'] . '" class="btn btn-primary">Read More</a>';
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
                ?>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="stats py-5 bg-light">
        <div class="container text-center">
            <div class="row">
                <div class="col-md-3">
                    <i class="fas fa-calendar-alt fa-3x mb-2"></i>
                    <h4>365</h4>
                    <p>Follow Every Day</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-plane fa-3x mb-2"></i>
                    <h4>20+</h4>
                    <p>Travels</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-users fa-3x mb-2"></i>
                    <h4>3</h4>
                    <p>Family Size</p>
                </div>
                <div class="col-md-3">
                    <i class="fas fa-dollar-sign fa-3x mb-2"></i>
                    <h4>$0</h4>
                    <p>Follow For Free</p>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
