    <?php
    session_start();

    // Redirect if not logged in
    if (empty($_SESSION['first_name']) || empty($_SESSION['last_name'])) {
        header("Location: ../index.php");
        exit;
    }

    $fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];

    include "../config/db.php";
    
    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $classId = $_POST['class_id'] ?? '';
        $attendanceDate = $_POST['attendance_date'] ?? '';
        $notes = $_POST['notes'] ?? '';

        if (!empty($classId) && !empty($attendanceDate)) {
            $stmt = $conn->prepare("INSERT INTO attendance (class_id, attendance_date, notes) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $classId, $attendanceDate, $notes);

            if ($stmt->execute()) {
                $success = "Attendance record created successfully!";
            } else {
                $error = "Error creating attendance record.";
            }
            $stmt->close();
        } else {
            $error = "Please select a class and date.";
        }
    }

    $conn->close();
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Create Attendance</title>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }
            body {
                background-color: #f9fafb;
                color: #333;
            }
            nav {
                height: 60px;
                background-color: #14213d;
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0 2rem;
                color: white;
                box-shadow: 0 3px 8px rgba(20, 33, 61, 0.3);
            }
            nav p {
                font-size: 1.4rem;
                font-weight: bold;
            }
            nav div {
                display: flex;
                gap: 1rem;
                align-items: center;
            }
            .logoutBtn {
                background-color: #fca311;
                color: #14213d;
                padding: 8px 18px;
                border-radius: 6px;
                font-weight: 600;
                text-decoration: none;
                box-shadow: 0 2px 6px rgba(252, 163, 17, 0.6);
                transition: background-color 0.25s ease;
            }
            .logoutBtn:hover {
                background-color: #d48806;
            }
            .container {
                max-width: 550px;
                margin: 2rem auto;
                background: white;
                padding: 2rem;
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0,0,0,0.1);
            }
            h2 {
                color: #14213d;
                margin-bottom: 1rem;
                text-align: center;
            }
            label {
                font-weight: 600;
                margin-top: 1rem;
                display: block;
            }
            input, textarea, select {
                width: 100%;
                padding: 0.75rem;
                margin-top: 0.3rem;
                border: 1.8px solid #ccc;
                border-radius: 6px;
                font-size: 1rem;
                transition: border-color 0.25s ease;
            }
            input:focus, textarea:focus, select:focus {
                border-color: #14213d;
                outline: none;
            }
            button {
                margin-top: 1.5rem;
                background-color: #fca311;
                color: #14213d;
                padding: 0.75rem 1.5rem;
                font-weight: 700;
                border: none;
                border-radius: 6px;
                cursor: pointer;
                box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
                transition: background-color 0.25s ease;
                width: 100%;
            }
            button:hover {
                background-color: #d48806;
            }
            .message {
                margin-top: 1rem;
                padding: 0.8rem;
                border-radius: 6px;
                text-align: center;
            }
            .success {
                background-color: #d4edda;
                color: #155724;
            }
            .error {
                background-color: #f8d7da;
                color: #721c24;
            }
        </style>
    </head>
    <body>
        <nav>
            <p>Attendance Tracker</p>
            <div>
                <span><?php echo htmlspecialchars($fullname); ?></span>
                <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
            </div>
        </nav>

        <div class="container">
            <h2>Create Attendance</h2>

            <?php if (!empty($success)): ?>
                <div class="message success"><?php echo $success; ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="message error"><?php echo $error; ?></div>
            <?php endif; ?>

            <form method="POST" action="../controllers/create_attendance.php"   >
                <label for="notes">Course Code</label>
                <input type="text" id="notes" name="course" required>
                <label for="year">Year Level</label>
                <select id="year" name="year" required>
                    <option value="">-- Select Year --</option>
                    <option value="1">1st Year</option>
                    <option value="2">2nd Year</option>
                    <option value="3">3rd Year</option>
                    <option value="4">4th Year</option>
                </select>
                <label for="section">Section</label>
                <select id="section" name="section" required>
                    <option value="">-- Select Section --</option>
                    <option value="A">Section A</option>
                    <option value="B">Section B</option>
                    <option value="C">Section C</option>
                    <option value="D">Section D</option>
                </select>
                <label for="attendance_date">Date</label>
                <input type="date" id="attendance_date" name="attendance_date" required>
                <button type="submit">Save Attendance</button>
                <a href="./teacher.php" 
                style="display:inline-block; margin-top:10px; background:#14213d; color:white; padding:0.75rem 1.5rem; border-radius:6px; text-decoration:none; text-align:center; width:100%;">
                Go Back
                </a>

            </form>
        </div>
    </body>
    </html>
