<?php
session_start();
include "../config/db.php"; // database connection

// Check if admin is logged in
if (empty($_SESSION['first_name']) || empty($_SESSION['last_name'])) {
    header("Location: ../index.php");
    exit;
}

// Get user ID from query string
if (isset($_GET['id'])) {
    $user_id = intval($_GET['id']);

    // Prevent admin from blocking themselves
    if ($user_id === intval($_SESSION['user_id'])) {
        $_SESSION['block_user_message'] = "You cannot block your own account.";
        header("Location: ../views/admin_dashboard.php");
        exit;
    }

    // Block user by updating their status
    $sql = "UPDATE users SET status = 'blocked' WHERE id_number = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);

    if ($stmt->execute()) {
        $_SESSION['block_user_message'] = "User with ID $user_id has been blocked successfully.";
    } else {
        $_SESSION['block_user_message'] = "Failed to block user. Please try again.";
    }

    $stmt->close();
} else {
    $_SESSION['block_user_message'] = "Invalid request.";
}

// Redirect back to admin page
header("Location: ../views/admin.php");
exit;
