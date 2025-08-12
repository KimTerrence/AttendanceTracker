<?php
session_start();
include "../config/db.php";

try {
    if (!empty($_SESSION['first_name']) || !empty($_SESSION['last_name'])) {
        $fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
        $current_user_id = $_SESSION['user_id']; // must be set on login
    } else {
        header("Location: ../index.php");
        exit;
    }
} catch (Exception $err) {
    echo $err;
}

// If search query exists
$searchTerm = '';
$sql = "SELECT a.id, a.course_code, a.year_level, a.section, a.attendance_date, ad.status
        FROM attendance a
        INNER JOIN attendance_details ad ON a.id = ad.attendance_id
        WHERE ad.student_id = ?";

if (!empty($_GET['q'])) {
    $searchTerm = trim($_GET['q']);
    $sql .= " AND (a.course_code LIKE ? OR a.section LIKE ? OR a.year_level LIKE ?)";
}

$sql .= " ORDER BY a.created_at DESC";
$stmt = $conn->prepare($sql);

if (!empty($searchTerm)) {
    $like = "%" . $searchTerm . "%";
    $stmt->bind_param("isss", $current_user_id, $like, $like, $like);
} else {
    $stmt->bind_param("i", $current_user_id);
}

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
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        th {
            background: #14213d;
            color: #fff;
            text-align: left;
            padding: 12px;
            font-size: 16px;
        }
        td {
            padding: 12px;
            border-bottom: 1px solid #ddd;
            font-size: 15px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f1f1f1;
            transition: background 0.2s ease-in-out;
        }
        a.view-link {
            color: #fca311;
            text-decoration: none;
            font-weight: bold;
        }
        a.view-link:hover {
            text-decoration: underline;
        }
         .row-present {
        background-color: #d4edda !important; /* light green */
        }
        .row-absent {
            background-color: #f8d7da !important; /* light red */
        }
        .row-late {
            background-color: #fff3cd !important; /* light yellow */
        }
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
        <div class="top-bar">
            <form method="GET" class="search-form">
                <input type="search" id="search" name="q" placeholder="Search classes"
                    class="search-input" value="<?= htmlspecialchars($searchTerm) ?>" />
                <button type="submit" class="search-btn">Search</button>
            </form>
        </div>

        <!-- Attendance List -->
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
                        <?php
                            $status = strtolower($row['status']);
                            $row_class = '';
                            if ($status === 'present') {
                                $row_class = 'row-present';
                            } elseif ($status === 'absent') {
                                $row_class = 'row-absent';
                            } elseif ($status === 'late') {
                                $row_class = 'row-late';
                            }
                        ?>
                        <tr class="<?= $row_class; ?>">
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