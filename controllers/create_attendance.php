<?php
session_start();
include "../config/db.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course = $_POST['course'];
    $year = intval($_POST['year']);
    $section = $_POST['section'];
    $attendance_date = $_POST['attendance_date'];
    $created_by = $_SESSION['user_id']; // assuming you store teacher's ID in session

    // Insert into attendance header
    $stmt = $conn->prepare("INSERT INTO attendance (course_code, year_level, section, attendance_date, created_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $course, $year, $section, $attendance_date, $created_by);
    $stmt->execute();
    $attendance_id = $stmt->insert_id;
    $stmt->close();

    // Get all registered students in the same year & section
   $students = $conn->query("SELECT id_number FROM users WHERE year_level=$year AND section='$section'");
    
    while ($student = $students->fetch_assoc()) {
        $sid = $conn->real_escape_string($student['id_number']);
        $conn->query("INSERT INTO attendance_details (attendance_id, student_id, status) VALUES ($attendance_id, '$sid', '-')");
    }


    $_SESSION['success'] = "Attendance created with " . $students->num_rows . " students.";
    header("Location: ../views/view_attendance.php?id=$attendance_id");
    exit;
}
?>
