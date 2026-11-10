
<?php
include 'db.php';

// Get result ID from URL
$id = $_GET['id'];

// Delete the selected result
$stmt = $conn->prepare("DELETE FROM results WHERE result = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

// Redirect back to index
header("Location: index.php");
exit();
?>
