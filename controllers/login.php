<?php
session_start();

include "../config/db.php";

// Helper function for sanitizing input
function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

// Check for POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $idnum = sanitize($_POST['idnum'] ?? '');
    $password = $_POST['password'] ?? '';

    // Basic validation
    $errors = [];
    if (!$idnum) {
        $errors[] = 'ID Number is required.';
    }
    if (!$password) {
        $errors[] = 'Password is required.';
    }

    if (empty($errors)) {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("SELECT id_number, password, first_name, last_name, role FROM users WHERE id_number = ? LIMIT 1");
        if ($stmt) {
            $stmt->bind_param('s', $idnum);
            $stmt->execute();
            $result = $stmt->get_result();
            $user = $result->fetch_assoc();

            if ($user) {
                // Verify hashed password
                if (password_verify($password, $user['password'])) {
                    // Login success: Set session variables and redirect
                    $_SESSION['user_id'] = $user['id_number'];
                    $_SESSION['first_name'] = $user['first_name'];
                    $_SESSION['last_name'] = $user['last_name'];
                    $_SESSION['logged_in'] = true;

                    switch($user['role']){
                        case 0:
                            header("location: ../views/student.php");
                            exit;
                        case 1:
                            header("location: ../views/teacher.php");
                            exit;
                        case 2:
                            header("location: ../views/admin.php");
                            exit;
                    }
                   
                } else {
                    $errors[] = 'Invalid password.';
                }
            } else {
                $errors[] = 'User with this ID Number not found.';
            }
            $stmt->close();
        } else {
            // Statement prepare failed
            $errors[] = 'Database query error.';
        }
    }

    // If errors, save them to session and redirect back to login form
    if (!empty($errors)) {
        $_SESSION['login_errors'] = $errors;
        $_SESSION['login_email'] = $idnum; // To keep input filled
        header("Location: ../index.php");
        exit;
    }
} else {
    // If not a POST request, redirect to login form
    header("Location: ../index.php");
    exit;
}

// Close DB connection
$conn->close();
?>
