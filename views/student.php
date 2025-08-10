<?php
session_start();
include "../config/db.php";

try {
    if (!empty($_SESSION['first_name']) || !empty($_SESSION['last_name'])) {
        $fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
        $current_user_id = $_SESSION['user_id']; // make sure this is set on login
    } else {
        header("Location: ../index.php");
        exit;
    }
} catch (Exception $err) {
    echo $err;
}

$sql = "SELECT a.id, a.course_code, a.year_level, a.section, a.attendance_date, ad.status
        FROM attendance a
        INNER JOIN attendance_details ad ON a.id = ad.attendance_id
        WHERE ad.student_id = ?
        ORDER BY a.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $current_user_id);
$stmt->execute();
$attendance_list = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Student</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background-color: #f9fafb;
            color: #333;
        }
        nav {
            height: 60px;
            width: 100%;
            background-color: #14213d; /* deep navy blue */
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            box-shadow: 0 3px 8px rgba(20, 33, 61, 0.3);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        nav div{
            display:flex; 
            align-items: center;
            gap:1rem;
            color:#fca311;
        }
        .logo {
            font-size: 1.8rem;
            color: #ffffff;
            letter-spacing: 1.3px;
            user-select: none;
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
        .logoutBtn:hover,
        .logoutBtn:focus {
            background-color: #d48806;
            box-shadow: 0 4px 10px rgba(212, 136, 6, 0.8);
            outline: none;
        }
        .logoutBtn:active {
            background-color: #b06b04;
            box-shadow: none;
        }
      table {
            width: 90%;
            margin: 2rem auto;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: left;
        }
        th {
            background: #14213d;
            color: #fff;
        }
        tr:hover {
            background-color: #f1f1f1;
        }
        a.view-link {
            color: #fca311;
            text-decoration: none;
            font-weight: bold;
        }
        a.view-link:hover {
            text-decoration: underline;
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
        <div>
            <form action="search_results.php" method="GET" style="max-width: 400px; margin: 2rem auto;">
                <div style="position: relative;">
                    <input
                        type="search"
                        id="search"
                        name="q"
                        placeholder="Search classes"
                        style="
                            width: 100%;
                            padding: 0.75rem 3rem 0.75rem 1rem;
                            border: 1.8px solid #ccc;
                            border-radius: 8px;
                            font-size: 1rem;
                        "
                        required
                    />
                    <button
                        type="submit"
                        aria-label="Search"
                        style="
                            position: absolute;
                            right: 0.25rem;
                            top: 50%;
                            transform: translateY(-50%);
                            background-color: #fca311;
                            border: none;
                            padding: 0.5rem 1rem;
                            border-radius: 6px;
                            color: #14213d;
                            font-weight: 700;
                            cursor: pointer;
                        "
                    >Search</button>
                </div>
            </form>
        </div>

        <!-- Attendance List -->
        <h2 style="text-align:center;">My Attendance Records</h2>
        <table>
            <thead>
                <tr>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Section</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($attendance_list->num_rows > 0): ?>
                    <?php while ($row = $attendance_list->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row['course_code']); ?></td>
                            <td><?= htmlspecialchars($row['year_level']); ?></td>
                            <td><?= htmlspecialchars($row['section']); ?></td>
                            <td><?= htmlspecialchars($row['attendance_date']); ?></td>
                            <td><?= htmlspecialchars($row['status']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" style="text-align:center;">No attendance records found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>