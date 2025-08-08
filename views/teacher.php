<?php
session_start();
try{
    if(!empty($_SESSION['first_name'] || $_SESSION['last_name'])){
        $fullname = $_SESSION['first_name'] . " " . $_SESSION['last_name'];
    }else{
        header("Location: ../index.php");
    }
}catch(Exeption $err){
    echo $err;
}
 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Teacher</title>
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
    </style>
</head>
<body>
    <nav>
        <p class="logo">Attendance Tracker</p>
        <div>
            <p><?php echo $fullname ?></p>
            <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
        </div>
    </nav>
    <div class="container">
    <div style="display: flex; justify-content: center; align-items: center; gap: 1rem; margin-top: 2rem; flex-wrap: wrap;">

        <!-- Search Form -->
        <form action="search_results.php" method="GET" style="display: flex; gap: 0.5rem; max-width: 400px; flex: 1;">
            <div style="position: relative; flex: 1;">
                <input
                    type="search"
                    id="search"
                    name="q"
                    placeholder="Search classes"
                    style="
                        width: 100%;
                        padding: 0.75rem 1rem;
                        border: 1.8px solid #ccc;
                        border-radius: 8px;
                        font-size: 1rem;
                        transition: border-color 0.3s ease;
                        box-sizing: border-box;
                    "
                    onfocus="this.style.borderColor='#14213d'"
                    onblur="this.style.borderColor='#ccc'"
                    required
                />
            </div>
            <button
                type="submit"
                style="
                    background-color: #fca311;
                    border: none;
                    padding: 0.75rem 1.5rem;
                    border-radius: 6px;
                    color: #14213d;
                    font-weight: 700;
                    cursor: pointer;
                    box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
                    transition: background-color 0.25s ease;
                "
                onmouseover="this.style.backgroundColor='#d48806'"
                onmouseout="this.style.backgroundColor='#fca311'"
            >
                Search
            </button>
        </form>
        <!-- Create Attendance Button -->
        <a href="create_attendance.php"
            style="
                background-color: #fca311;
                color: #14213d;
                padding: 0.75rem 1.5rem;
                font-weight: 700;
                text-decoration: none;
                border-radius: 6px;
                box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
                transition: background-color 0.25s ease;
            "
            onmouseover="this.style.backgroundColor='#d48806'"
            onmouseout="this.style.backgroundColor='#fca311'"
        >
            Create Attendance
        </a>
    </div>
</div>
</body>
</html>