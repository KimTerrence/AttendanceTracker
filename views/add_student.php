    <?php
    session_start();
    include "../config/db.php";

    if (!isset($_GET['attendance_id'])) {
        header("Location: teacher_dashboard.php");
        exit;
    }

    $attendance_id = intval($_GET['attendance_id']);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $student_id = trim($_POST['student_id']);

    // Check if student exists
    $check_sql = "SELECT id_number FROM users WHERE id_number = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $student_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Check if student already added to this attendance
        $dup_sql = "SELECT id FROM attendance_details WHERE attendance_id = ? AND student_id = ?";
        $dup_stmt = $conn->prepare($dup_sql);
        $dup_stmt->bind_param("is", $attendance_id, $student_id);
        $dup_stmt->execute();
        $dup_result = $dup_stmt->get_result();

        if ($dup_result->num_rows > 0) {
            $error = "Student already added to this attendance record.";
        } else {
            // Insert into attendance_details
            $insert_sql = "INSERT INTO attendance_details (attendance_id, student_id, status) VALUES (?, ?, '-')";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("is", $attendance_id, $student_id);
            $insert_stmt->execute();
            header("Location: view_attendance.php?id=" . $attendance_id);
            exit;
        }
    } else {
        $error = "Student ID not found.";
    }
}

    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <title>Add Student</title>
        <style>
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }
    .form-container {
        background: white;
        padding: 25px 30px;
        border-radius: 10px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        width: 350px;
    }
    h2 {
        text-align: center;
        color: #14213d;
        margin-bottom: 20px;
    }
    .form-group {
        display: flex;
        flex-direction: column;
        margin-bottom: 15px;
    }
    label {
        font-size: 14px;
        margin-bottom: 5px;
        color: #333;
    }
    input[type="text"] {
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 14px;
        box-sizing: border-box;
    }
    button {
        padding: 10px;
        background: #fca311;
        color: #14213d;
        border: none;
        border-radius: 6px;
        font-weight: bold;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.3s;
        width: 100%;
    }
    button:hover {
        background: #e29500;
    }
    .error {
        color: red;
        text-align: center;
        margin-top: 10px;
    }
</style>

<div class="form-container">
    <form method="POST">
        <div class="form-group">
            <label for="student_id">Student ID</label>
            <input type="text" id="student_id" name="student_id" placeholder="Enter Student ID" required>
        </div>
        <button type="submit">Add Student</button>
    </form>
    <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
</div>
    </body>
    </html>
