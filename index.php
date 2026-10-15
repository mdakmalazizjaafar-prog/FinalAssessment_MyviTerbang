<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Grades</title>
</head>
<body>

<h1> Student Grading System </h1>

<h2> Student Records </h2>
<a href="add.php"> Add New Result </a>
<br>
<br>

<input type="text" name="search" placeholder="Search student name, matric number, subject name or code subject">
<input type="submit" name="search" value="Search">

<a href="index.php"> Show All </a>

<table border="1">
    <tr> 
        <th>No. </th>
        <th>Student Name</th>
        <th>Matric No. </th>
        <th>Subject </th>
        <th>Test 1 (20)</th>
        <th>Test 2 (30)</th>
        <th>Final (50)</th>
        <th>Total (100)</th>
        <th>Grade </th>
        <th>Actions </th>
    </tr>
    
</body>
</html>