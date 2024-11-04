<?php
include 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

// Fetch Users
$users_result = $conn->query("SELECT * FROM users");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="../styles.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        #wrapper {
            display: flex;
        }
        .sidebar {
            min-width: 250px;
            background-color: #343a40;
        }
        .sidebar-heading {
            font-size: 1.5rem;
            color: #ffffff;
        }
        .list-group-item {
            background-color: #343a40;
            color: #ffffff;
        }
        .list-group-item:hover {
            background-color: #495057;
        }
        #page-content-wrapper {
            flex-grow: 1;
            padding: 20px;
        }
        .navbar {
            background-color: #ffffff;
        }
        h2 {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="bg-dark text-white sidebar" id="sidebar">
            <div class="sidebar-heading text-center py-4">
                <i class="fas fa-user-cog fa-2x"></i> Admin Panel
            </div>
            <nav class="list-group list-group-flush">
                <a href="admin_panel.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-tachometer-alt mr-2"></i> Dashboard
                </a>
                <a href="manage_blogs.php" class="list-group-item list-group-item-action">
                    <i class="fas fa-pencil-alt mr-2"></i> Manage Posts
                </a>
                <a href="manage_users.php" class="list-group-item list-group-item-action active">
                    <i class="fas fa-users mr-2"></i> Manage Users
                </a>
            </nav>
        </div>
        
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light border-bottom">
                <button class="btn btn-primary" id="menu-toggle"><i class="fas fa-bars"></i></button>
                <h1 class="ml-3">Manage Users</h1>
                <div class="ml-auto">
                    <span class="mr-2">Admin</span>
                    <a href="logout.php" class="btn btn-danger btn-sm">Logout</a>
                </div>
            </nav>
            
            <div class="container-fluid mt-4">
                <h2>Existing Users</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $user['id']; ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['role']); ?></td>
                                <td>
                                    <form action="manage_users.php" method="post" style="display:inline;">
                                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                        <button type="submit" name="delete_user" class="btn btn-danger btn-sm">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <script>
        $('#menu-toggle').click(function() {
            $('#wrapper').toggleClass('toggled');
        });
    </script>
</body>
</html>
