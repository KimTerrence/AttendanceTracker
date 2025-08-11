<?php
session_start();
include "../config/db.php"; // DB connection

// Check if admin is logged in
if (empty($_SESSION['first_name']) || empty($_SESSION['last_name'])) {
    header("Location: ../index.php");
    exit;
}

$fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
$idnum = $_SESSION['user_id'];

// Fetch all students (role = 'student')
$sql_students = "SELECT id_number, first_name, last_name, year_level, section FROM users WHERE role = '0'";
$result_students = $conn->query($sql_students);

// Fetch all teachers (role = 'teacher')
$sql_teachers = "SELECT id_number, first_name, last_name FROM users WHERE role = '1'";
$result_teachers = $conn->query($sql_teachers);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Admin - Manage Users</title>
<style>
    /* Reset and base */
    * {
        box-sizing: border-box;
    }
    body {
        margin: 0;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background-color: #f9fafb;
        color: #333;
    }

    /* Navbar */
    nav {
        height: 60px;
        width: 100%;
        background-color: #14213d;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 0 2rem;
        box-shadow: 0 3px 8px rgba(20, 33, 61, 0.3);
        position: sticky;
        top: 0;
        z-index: 100;
        color: #fff;
    }
    .logo {
        font-size: 1.8rem;
        letter-spacing: 1.3px;
        user-select: none;
        font-weight: 700;
    }
    nav > div {
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .logoutBtn {
        background-color: #fca311;
        color: #14213d;
        padding: 8px 18px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(252, 163, 17, 0.6);
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        user-select: none;
    }
    .logoutBtn:hover {
        background-color: #d48806;
        box-shadow: 0 4px 10px rgba(212, 136, 6, 0.8);
    }

    /* Container holding both tables side-by-side */
    .container {
        max-width: 1200px;
        margin: 2rem auto 4rem;
        padding: 0 1rem;
        display: flex;
        gap: 2rem;
        flex-wrap: wrap;
        justify-content: center;
    }

    /* Each table wrapper */
    .table-wrapper {
        flex: 1 1 480px; /* flexible, min width ~480px */
        background: white;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .table-wrapper h2 {
        background-color: #14213d;
        color: #fff;
        margin: 0;
        padding: 1rem 1.5rem;
        font-size: 1.25rem;
        user-select: none;
    }

    /* Table */
    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
    }
    thead th {
        background-color: #14213d;
        color: #ffffff;
        padding: 14px 16px;
        font-size: 1rem;
        text-align: left;
    }
    tbody td {
        padding: 12px 16px;
        font-size: 0.95rem;
        border-bottom: 1px solid #e5e7eb;
    }
    tbody tr:last-child td {
        border-bottom: none;
    }
    tbody tr:hover {
        background-color: #f9fafb;
        transition: background-color 0.2s ease-in-out;
    }

    /* Block button */
    .btn-block {
        background-color: #dc3545;
        color: #fff;
        padding: 6px 12px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        box-shadow: 0 2px 6px rgba(220, 53, 69, 0.4);
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        display: inline-block;
    }
    .btn-block:hover {
        background-color: #b02a37;
        box-shadow: 0 4px 12px rgba(176, 42, 55, 0.5);
    }

    .table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background-color: #14213d;
    padding: 1rem 1.5rem;
    border-top-left-radius: 10px;
    border-top-right-radius: 10px;
    user-select: none;
}

.table-header h2 {
    margin: 0;
    color: #fff;
    font-size: 1.25rem;
}

.btn-add {
     background-color: #fca311;
        color: #14213d;
        padding: 8px 18px;
        font-weight: 600;
        text-decoration: none;
        border-radius: 6px;
        box-shadow: 0 2px 6px rgba(252, 163, 17, 0.6);
        transition: background-color 0.25s ease, box-shadow 0.25s ease;
        user-select: none;
}

.btn-add:hover {
        background-color: #d48806;
        box-shadow: 0 4px 10px rgba(212, 136, 6, 0.8);
}

</style>
</head>
<body>
<nav>
    <p class="logo">Attendance Tracker</p>
    <div>
        <p><?php echo htmlspecialchars($fullname); ?></p>
        <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
    </div>
</nav>

<div class="container">
    <!-- Student List -->
    <div class="table-wrapper">
         <div class="table-header">
        <h2>Student List</h2>
        <a href="../controllers/add_teacher.php" class="btn-add">Add Teacher</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Full Name</th>
                    <th>Year - Section</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_students && $result_students->num_rows > 0): ?>
                    <?php while ($row = $result_students->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_level'] . ' ' . $row['section']); ?></td>
                            <td>
                                <a href="../controllers/block_user.php?id=<?php echo $row['id_number']; ?>"
                                   class="btn-block"
                                   onclick="return confirm('Block this student?');">
                                   Block
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center;">No students found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Teacher List -->
    <div class="table-wrapper">
        <div class="table-header">
            <h2>Teacher List</h2>
            <a href="../controllers/add_teacher.php" class="btn-add">Add Teacher</a>
        </div>
        <table>
            <thead>
                <tr>
                    <th>ID Number</th>
                    <th>Full Name</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_teachers && $result_teachers->num_rows > 0): ?>
                    <?php while ($row = $result_teachers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                            <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                            <td>
                                <a href="../controllers/block_user.php?id=<?php echo $row['id_number']; ?>"
                                   class="btn-block"
                                   onclick="return confirm('Block this teacher?');">
                                   Block
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="3" style="text-align:center;">No teachers found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
</body>
</html>
