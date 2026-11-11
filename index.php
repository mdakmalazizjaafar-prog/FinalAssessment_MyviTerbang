
<?php



session_start();

if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

include 'db.php';

$search = "";

if (isset($_GET['search'])) {
    $search = trim($_GET['search']);
}

if ($search != "") {

    $stmt = $conn->prepare("
        SELECT * FROM results
        WHERE student_name LIKE ?
        OR matric_num LIKE ?
        OR subject_name LIKE ?
    ");

    $keyword = "%" . $search . "%";

    $stmt->bind_param("sss", $keyword, $keyword, $keyword);

} else {

    $stmt = $conn->prepare("SELECT * FROM results");
}

$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>

    <link rel="stylesheet" href="css/style.css">
    
</head>

<a href="logout.php">Logout</a>

<body>
<div class="container">

<h1>Student Grading System</h1> 
<br>

<h2>Student Records</h2>

<a href="add.php">Add New Result</a>

<br><br>

<form method="GET" action="index.php">

    <input
        type="text"
        name="search"
        placeholder="Search student name, matric number or subject"
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <input type="submit" value="Search">

    <a href="index.php">Show All</a>

</form>

<br>

<table border="1">

    <tr>
        <th>No.</th>
        <th>Student Name</th>
        <th>Matric No.</th>
        <th>Subject</th>
        <th>Test 1 (20)</th>
        <th>Test 2 (30)</th>
        <th>Final (50)</th>
        <th>Total (100)</th>
        <th>Grade</th>
        <th>Actions</th>
    </tr>

<?php

$no = 1;

if ($result->num_rows > 0) {

    while ($row = $result->fetch_assoc()) {

        echo "<tr>";

        echo "<td>" . $no++ . "</td>";
        echo "<td>" . htmlspecialchars($row['student_name']) . "</td>";
        echo "<td>" . htmlspecialchars($row['matric_num']) . "</td>";
        echo "<td>" . htmlspecialchars($row['subject_name']) . "</td>";

        echo "<td>" . number_format($row['test_1'], 2) . "</td>";
        echo "<td>" . number_format($row['test_2'], 2) . "</td>";
        echo "<td>" . number_format($row['final_exam'], 2) . "</td>";
        echo "<td>" . number_format($row['total_marks'], 2) . "</td>";

        echo "<td>" . htmlspecialchars($row['grade']) . "</td>";

        echo "<td>
            <a href='edit.php?id=" . $row['result'] . "'>Edit</a> |
            <a href='delete.php?id=" . $row['result'] . "'onclick=\"return confirm('Are you sure you want to delete this student record?');\">Delete</a>
        </td>";

        echo "</tr>";
    }

} else {

    echo "<tr>";
    echo "<td colspan='10' style='text-align:center;'>";

    if ($search != "") {
        echo "No records found for \"" . htmlspecialchars($search) . "\".";
    } else {
        echo "No student records available.";
    }

    echo "</td>";
    echo "</tr>";

}

?>

</table>

</div>
</body>
</html>
