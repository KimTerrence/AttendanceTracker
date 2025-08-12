<?php
session_start();
include "../config/db.php"; // DB connection

// Redirect if not logged in as admin
if (empty($_SESSION['first_name']) || empty($_SESSION['last_name'])) {
    header("Location: ../index.php");
    exit;
}

$message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id_number  = trim($_POST['id_number']);
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $password   = trim($_POST['password']);
    $role       = 1;

    if (!empty($id_number) && !empty($first_name) && !empty($last_name) && !empty($password)) {
        // Check duplicate ID
        $check_sql = "SELECT id_number FROM users WHERE id_number = ?";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("i", $id_number);
        $check_stmt->execute();
        $check_stmt->store_result();

        if ($check_stmt->num_rows > 0) {
            $message = "⚠️ ID Number already exists!";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (id_number, first_name, last_name, password, role) 
                    VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("issss", $id_number, $first_name, $last_name, $hashed_password, $role);

            if ($stmt->execute()) {
                $message = "✅ Teacher added successfully!";
            } else {
                $message = "❌ Error: " . $stmt->error;
            }
        }
    } else {
        $message = "⚠️ Please fill in all fields.";
    }

    $_SESSION['add_teacher_message'] = $message;
    header("Location: ../views/add_teacher.php");
    exit;
}
?>
