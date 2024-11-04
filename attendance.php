<?php
include 'config.php';
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// Fetch events for the dropdown
$events_query = "SELECT id, event_name FROM events";
$events_result = $conn->query($events_query);

// Check if the form is submitted to fetch cadets
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['event_id'])) {
    $event_id = $_POST['event_id'];

    // Fetch cadets for the selected event
    $cadets_query = "SELECT cadet_id, cadet_name, cadet_rank, location FROM cadets";
    $cadets_result = $conn->query($cadets_query);
}

// Mark attendance when the button is clicked
if (isset($_POST['mark_attendance'])) {
    $cadet_id = $_POST['cadet_id'];
    $event_id = $_POST['event_id'];

    // Insert attendance into the database
    $insert_query = "INSERT INTO attendance (cadet_id, event_id, attended) VALUES (?, ?, TRUE)";
    $insert_stmt = $conn->prepare($insert_query);
    $insert_stmt->bind_param("si", $cadet_id, $event_id);

    if ($insert_stmt->execute()) {
        $success_message = "Attendance marked successfully for Cadet ID: $cadet_id.";
    } else {
        $error_message = "Error marking attendance: " . $insert_stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Management</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Mark Attendance</h2>

        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
        <?php elseif (isset($error_message)): ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
        <?php endif; ?>

        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="event_id" class="form-label">Select Event</label>
                <select name="event_id" id="event_id" class="form-select" required>
                    <option value="">Choose an event...</option>
                    <?php while ($event = $events_result->fetch_assoc()): ?>
                        <option value="<?php echo $event['id']; ?>"><?php echo htmlspecialchars($event['event_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Fetch Cadets</button>
        </form>

        <?php if (isset($cadets_result) && $cadets_result->num_rows > 0): ?>
            <h3>Cadets for Event ID: <?php echo htmlspecialchars($event_id); ?></h3>
            <table class="table">
                <thead>
                    <tr>
                        <th>Cadet ID</th>
                        <th>Cadet Name</th>
                        <th>Cadet Rank</th>
                        <th>Location</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($cadet = $cadets_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cadet['cadet_id']); ?></td>
                            <td><?php echo htmlspecialchars($cadet['cadet_name']); ?></td>
                            <td><?php echo htmlspecialchars($cadet['cadet_rank']); ?></td>
                            <td><?php echo htmlspecialchars($cadet['location']); ?></td>
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="cadet_id" value="<?php echo $cadet['cadet_id']; ?>">
                                    <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
                                    <button type="submit" name="mark_attendance" class="btn btn-success">Mark Attendance</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No cadets found for this event.</p>
        <?php endif; ?>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
