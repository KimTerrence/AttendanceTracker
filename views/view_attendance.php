<?php
session_start();
include "../config/db.php";

if (!isset($_GET['id'])) {
    header("Location: teacher_dashboard.php");
    exit;
}

$attendance_id = intval($_GET['id']);

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
    body {
        font-family: Arial, sans-serif;
        background: #f4f6f9;
        margin: 0;
        padding: 0;
    }
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 40px;
        background: white;
        box-shadow: 0 2px 6px rgba(0,0,0,0.05);
    }
    .page-header h2 {
        margin: 0;
        color: #14213d;
    }
    .add-btn {
        background: #fca311;
        color: #14213d;
        padding: 10px 18px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: bold;
        transition: 0.3s;
    }
    .add-btn:hover {
        background: #e29500;
    }
    table {
        width: 90%;
        margin: 30px auto;
        border-collapse: separate;
        border-spacing: 0;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0,0,0,0.08);
    }
    th, td {
        padding: 14px 16px;
    }
    th {
        background: #14213d;
        color: white;
        text-align: center;
        font-weight: 600;
    }
    td {
        text-align: center;
        border-bottom: 1px solid #e5e5e5;
    }
    tr:last-child td {
        border-bottom: none;
    }
    tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    tr:hover {
        background-color: #f1f5fb;
    }
    .Present { background: #d4edda !important; }
    .Late { background: #fff3cd !important; }
    .Absent { background: #f8d7da !important; }
    .status-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 20px;
        cursor: pointer;
        margin: 0 3px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    .present-btn { background: #28a745; color: white; }
    .late-btn { background: #ffc107; color: black; }
    .absent-btn { background: #dc3545; color: white; }
    .status-btn:hover {
        transform: scale(1.05);
        opacity: 0.9;
    }
</style>

</head>
<body>

    <div class="page-header">
        <h2>Attendance Details</h2>
        <a href="add_student.php?attendance_id=<?php echo $attendance_id; ?>" class="add-btn">+ Add Student</a>
    </div>

    <table>
        <thead>
            <tr>
                <th>ID-Number</th>
                <th>Student Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?php echo $row['status']; ?>">
                    <td><?php echo htmlspecialchars($row['id_number']); ?></td>
                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                    <td>
                        <form action="../controllers/update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="detail_id" value="<?php echo $row['detail_id']; ?>">
                            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
                            <input type="hidden" name="status" value="Present">
                            <button type="submit" class="status-btn present-btn">Present</button>
                        </form>
                        <form action="../controllers/update_status.php" method="POST" style="display:inline;">
                            <input type="hidden" name="detail_id" value="<?php echo $row['detail_id']; ?>">
                            <input type="hidden" name="attendance_id" value="<?php echo $attendance_id; ?>">
                            <input type="hidden" name="status" value="Late">
                            <button type="submit" class="status-btn late-btn">Late</button>
                        </form>
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
  <div style="text-align:right; margin:20px 80px;">
    <a href="./teacher.php" 
       style="background:#14213d; color:white; padding:10px 18px; border-radius:6px; text-decoration:none; font-weight:bold;">
       ← Go Back
    </a>
</div>


</body>
</html>
