<?php
session_start();
if (empty($_SESSION['first_name']) || empty($_SESSION['last_name'])) {
    header("Location: ../index.php");
    exit;
}

$fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
$idnum = $_SESSION['user_id'];

$message = "";
if (!empty($_SESSION['add_teacher_message'])) {
    $message = $_SESSION['add_teacher_message'];
    unset($_SESSION['add_teacher_message']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Teacher</title>
<style>
    body { font-family: 'Segoe UI', sans-serif; background-color: #f9fafb; margin: 0; }
    nav {
        height: 60px; background-color: #14213d; color: #fff;
        display: flex; justify-content: space-between; align-items: center;
        padding: 0 2rem; box-shadow: 0 3px 8px rgba(20, 33, 61, 0.3);
    }
    .logo { font-size: 1.5rem; font-weight: bold; }
    .logoutBtn {
        background-color: #fca311; color: #14213d; padding: 8px 18px;
        border-radius: 6px; text-decoration: none; font-weight: 600;
    }
    .container {
        max-width: 500px; margin: 3rem auto; background: white;
        padding: 2rem; border-radius: 10px;
        box-shadow: 0 4px 12px rgba(20, 33, 61, 0.15);
    }
    h2 { text-align: center; color: #14213d; margin-bottom: 1.5rem; }
    label { display: block; margin: 0.5rem 0 0.2rem; font-weight: 600; color: #333; }
    input {
        width: 100%; padding: 0.6rem 0; margin-bottom: 1rem;
        border: 1px solid #ccc; border-radius: 6px;
    }
    .btn-submit {
        width: 100%; background-color: #fca311; color: #14213d;
        padding: 0.7rem; border: none; border-radius: 6px;
        font-size: 1rem; font-weight: bold; cursor: pointer;
        transition: background-color 0.25s;
    }
    .message { text-align: center; font-weight: bold; margin-bottom: 1rem; }
    .btn-back {
    display: inline-block;
    padding: 8px 14px;
    background-color: #fca311;
    color: #14213d;
    border-radius: 6px;
    text-decoration: none;
    font-weight: 600;
    transition: background-color 0.2s;
}

</style>
</head>
<body>
<nav>
    <span class="logo">Attendance Tracker</span>
    <div>
        <span><?php echo htmlspecialchars($idnum . " - " . $fullname); ?></span>
        <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
    </div>
</nav>

<div class="container">
    <h2>Add New Teacher</h2>

    <?php if ($message): ?>
        <div class="message"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <!-- Go Back Button -->
    <div style="margin-bottom: 1rem;">
        <a href="./admin.php" class="btn-back">← Go Back</a>
    </div>

    <form action="../controllers/add_teacher.php" method="POST">
        <label for="id_number">ID Number</label>
        <input type="text" name="id_number" id="id_number" required>

        <label for="first_name">First Name</label>
        <input type="text" name="first_name" id="first_name" required>

        <label for="last_name">Last Name</label>
        <input type="text" name="last_name" id="last_name" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit" class="btn-submit">Add Teacher</button>
           <a href="./admin.php" 
                style="display:inline-block; margin-top:10px; background:#14213d; color:white; padding:0.75rem 0; border-radius:6px; text-decoration:none; text-align:center; width:100%;">
                Go Back
                </a>
    </form>
</div>
</body>
</html>
