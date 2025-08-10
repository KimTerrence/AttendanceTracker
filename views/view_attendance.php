<?php
session_start();
include "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: teacher_dashboard.php");
    exit;
}

$attendance_id = intval($_GET['id']);

// Fetch attendance details + student names
$sql = "SELECT ad.id AS detail_id, u.id_number, u.first_name, u.last_name, ad.status
        FROM attendance_details ad
        JOIN users u ON ad.student_id = u.id_number
        WHERE ad.attendance_id = ?
        ORDER BY u.id_number ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $attendance_id);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Attendance</title>
    <style>
        table { width: 90%; margin: 20px auto; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ccc; text-align: left; }
        th { background: #14213d; color: #fff; }
        .Present { background: #d4edda; }
        .Late { background: #fff3cd; }
        .Absent { background: #f8d7da; }
        .status-btn {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-right: 5px;
        }
        .present-btn { background: #28a745; color: white; }
        .late-btn { background: #ffc107; color: black; }
        .absent-btn { background: #dc3545; color: white; }
    </style>
</head>
<body>
    <h2 style="text-align:center;">Attendance Details</h2>
    <table>
        <thead>
            <tr>
                <th>Student Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?php echo $row['status']; ?>">
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <!-- Mark Present -->
                        <form action="../controllers/update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="detail_id" value="<?php echo $row['detail_id']; ?>">
                            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
                            <input type="hidden" name="status" value="Present">
                            <button type="submit" class="status-btn present-btn">Present</button>
                        </form>
                        <!-- Mark Late -->
                        <form action="../controllers/update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="detail_id" value="<?php echo $row['detail_id']; ?>">
                            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
                            <input type="hidden" name="status" value="Late">
                            <button type="submit" class="status-btn late-btn">Late</button>
                        </form>
                        <!-- Mark Absent -->
                        <form action="../controllers/update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="detail_id" value="<?php echo $row['detail_id']; ?>">
                            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
                            <input type="hidden" name="status" value="Absent">
                            <button type="submit" class="status-btn absent-btn">Absent</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
