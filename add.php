
<?php
include 'db.php';

// Save new student result
if (isset($_POST['save'])) {

    $student_name = $_POST['student_name'];
    $matric_num = $_POST['matric_num'];
    $subject_name = $_POST['subject_name'];

    $test_1 = (float) $_POST['test_1'];
    $test_2 = (float) $_POST['test_2'];
    $final_exam = (float) $_POST['final_exam'];

    // Calculate total marks
    $total_marks = $test_1 + $test_2 + $final_exam;

    // Calculate grade
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

    // Insert new record
    $stmt = $conn->prepare("
        INSERT INTO results
        (student_name, matric_num, subject_name,
        test_1, test_2, final_exam, total_marks, grade)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    $stmt->bind_param(
        "sssdddds",
        $student_name,
        $matric_num,
        $subject_name,
        $test_1,
        $test_2,
        $final_exam,
        $total_marks,
        $grade
    );

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Result</title>
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

<div class="container">

    <h2 class="title">Add New Result</h2>

    <form method="post">

        <div class="form-group">
            <label>Student Name</label>
            <input type="text" name="student_name" required>
        </div>

        <div class="form-group">
            <label>Matric Number</label>
            <input type="text" name="matric_num" required>
        </div>

        <div class="form-group">
            <label>Subject Name</label>
            <input type="text" name="subject_name" required>
        </div>

        <div class="form-group">
            <label>Test 1 (20)</label>
            <input type="number" name="test_1"
                   min="0" max="20" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Test 2 (30)</label>
            <input type="number" name="test_2"
                   min="0" max="30" step="0.01" required>
        </div>

        <div class="form-group">
            <label>Final Exam (50)</label>
            <input type="number" name="final_exam"
                   min="0" max="50" step="0.01" required>
        </div>

        <div class="button-group">

            <a href="index.php">Back</a>

            <button type="submit" name="save">
                Save Result
            </button>

        </div>

    </form>

</div>

</body>
</html>
