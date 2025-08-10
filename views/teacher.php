<?php
session_start();
include "../config/db.php"; // DB connection

try {
    if (!empty($_SESSION['first_name']) && !empty($_SESSION['last_name'])) {
        $fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
        $idnum = $_SESSION['user_id'];
    } else {
        header("Location: ../index.php");
        exit;
    }
} catch (Exception $err) {
    echo $err;
}

// Fetch attendance list
$created_by = $_SESSION['user_id']; // logged-in teacher's ID
$sql = "SELECT id, course_code, year_level, section, attendance_date 
        FROM attendance 
        WHERE created_by = $created_by
        ORDER BY attendance_date DESC";
$result = $conn->query($sql);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teacher</title>
    <style>
        * {
            padding: 0; margin: 0; box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f9fafb; color: #333;
        }
        nav {
            height: 60px; width: 100%; background-color: #14213d;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 2rem; box-shadow: 0 3px 8px rgba(20, 33, 61, 0.3);
            position: sticky; top: 0; z-index: 100;
        }
        nav div {
            display: flex; align-items: center; gap: 1rem; color: #fca311;
        }
        .logo {
            font-size: 1.8rem; color: #ffffff; letter-spacing: 1.3px; user-select: none;
        }
        .logoutBtn {
            background-color: #fca311; color: #14213d; padding: 8px 18px;
            font-weight: 600; text-decoration: none; border-radius: 6px;
            box-shadow: 0 2px 6px rgba(252, 163, 17, 0.6);
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
            user-select: none;
        }
        .logoutBtn:hover { background-color: #d48806; box-shadow: 0 4px 10px rgba(212, 136, 6, 0.8); }
        .logoutBtn:active { background-color: #b06b04; box-shadow: none; }
        
        .top-bar {
            display: flex; justify-content: center; align-items: center;
            gap: 1rem; margin-top: 2rem; flex-wrap: wrap;
        }
        .search-form {
            display: flex; gap: 0.5rem; max-width: 400px; flex: 1;
        }
        .search-input {
            width: 100%; padding: 0.75rem 1rem; border: 1.8px solid #ccc;
            border-radius: 8px; font-size: 1rem; transition: border-color 0.3s ease;
        }
        .search-input:focus { border-color: #14213d; outline: none; }
        .search-btn, .create-btn {
            background-color: #fca311; border: none; padding: 0.75rem 1.5rem;
            border-radius: 6px; color: #14213d; font-weight: 700; cursor: pointer;
            box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
            transition: background-color 0.25s ease;
            text-decoration: none; display: inline-block; text-align: center;
        }
        .search-btn:hover, .create-btn:hover { background-color: #d48806; }
        
        table {
            width: 90%; margin: 2rem auto; border-collapse: collapse;
            background: #fff; border-radius: 8px; overflow: hidden;
            box-shadow: 0 3px 8px rgba(0,0,0,0.1);
        }
        th, td {
            padding: 12px 15px; text-align: left; border-bottom: 1px solid #ddd;
        }
        th { background-color: #14213d; color: #fff; }
        tr:hover { background-color: #f1f1f1; }
        .btn-view {
            background-color: #fca311; color: #14213d; padding: 6px 12px;
            border-radius: 4px; text-decoration: none; font-weight: bold;
            display: inline-block;
        }
        .btn-view:hover { background-color: #d48806; }
    </style>
</head>
<body>
    <nav>
        <p class="logo">Attendance Tracker</p>
        <div>
            <p><?php echo $idnum . " - " . htmlspecialchars($fullname); ?></p>
            <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="top-bar">
            <!-- Search Form -->
            <form action="search_results.php" method="GET" class="search-form">
                <input type="search" id="search" name="q" placeholder="Search classes" class="search-input" required />
                <button type="submit" class="search-btn">Search</button>
            </form>
            <!-- Create Attendance Button -->
            <a href="create_attendance.php" class="create-btn">Create Attendance</a>
        </div>

        <!-- Attendance List Table -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Course</th>
                    <th>Year Level</th>
                    <th>Section</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result && $result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['id']; ?></td>
                            <td><?php echo htmlspecialchars($row['course_code']); ?></td>
                            <td><?php echo htmlspecialchars($row['year_level']); ?></td>
                            <td><?php echo htmlspecialchars($row['section']); ?></td>
                            <td><?php echo htmlspecialchars($row['attendance_date']); ?></td>
                            <td><a class="btn-view" href="view_attendance.php?id=<?php echo $row['id']; ?>">View</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No attendance records found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
