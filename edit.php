
<?php
include 'db.php';

// Check student ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die("Invalid or missing student ID.");
}

$id = (int) $_GET['id'];

// Fetch existing student record
$stmt = $conn->prepare("SELECT * FROM results WHERE result = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    die("Student record not found.");
}

// Update record
if (isset($_POST['update'])) {

    $student_name = $_POST['student_name'];
    $matric_num = $_POST['matric_num'];
    $subject_name = $_POST['subject_name'];

    $test_1 = (float) $_POST['test_1'];
    $test_2 = (float) $_POST['test_2'];
    $final_exam = (float) $_POST['final_exam'];

    // Calculate total marks automatically
    $total_marks = $test_1 + $test_2 + $final_exam;

    // Calculate grade automatically
    if ($total_marks >= 80) {
        $grade = "A";
    } elseif ($total_marks >= 65) {
        $grade = "B";
    } elseif ($total_marks >= 50) {
        $grade = "C";
    } elseif ($total_marks >= 40) {
        $grade = "D";
    } else {
        $grade = "E";
    }

    // Update database including total marks and grade
    $stmt = $conn->prepare("
        UPDATE results SET
            student_name = ?,
            matric_num = ?,
            subject_name = ?,
            test_1 = ?,
            test_2 = ?,
            final_exam = ?,
            total_marks = ?,
            grade = ?
        WHERE result = ?
    ");

    $stmt->bind_param(
        "sssddddsi",
        $student_name,
        $matric_num,
        $subject_name,
        $test_1,
        $test_2,
        $final_exam,
        $total_marks,
        $grade,
        $id
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Update failed: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Result</title>
</head>
<body>

<div class="container">

    <h2 class="title">Edit Result</h2>

    <form method="post">

        <div class="form-group">
            <label for="student_name">Student Name</label>
            <input type="text" id="student_name" name="student_name"
                value="<?php echo htmlspecialchars($row['student_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="matric_num">Matric Number</label>
            <input type="text" id="matric_num" name="matric_num"
                value="<?php echo htmlspecialchars($row['matric_num']); ?>" required>
        </div>

        <div class="form-group">
            <label for="subject_name">Subject Name</label>
            <input type="text" id="subject_name" name="subject_name"
                value="<?php echo htmlspecialchars($row['subject_name']); ?>" required>
        </div>

        <div class="form-group">
            <label for="test_1">Test 1 (20)</label>
            <input type="number" id="test_1" name="test_1"
                min="0" max="20" step="0.01"
                value="<?php echo htmlspecialchars($row['test_1']); ?>" required>
        </div>

        <div class="form-group">
            <label for="test_2">Test 2 (30)</label>
            <input type="number" id="test_2" name="test_2"
                min="0" max="30" step="0.01"
                value="<?php echo htmlspecialchars($row['test_2']); ?>" required>
        </div>

        <div class="form-group">
            <label for="final_exam">Final Exam (50)</label>
            <input type="number" id="final_exam" name="final_exam"
                min="0" max="50" step="0.01"
                value="<?php echo htmlspecialchars($row['final_exam']); ?>" required>
        </div>

        <div class="button-group">

            <button type="submit" name="update">
                Update
            </button>

            <a href="index.php">Cancel</a>

        </div>

    </form>

</div>

</body>
</html>
