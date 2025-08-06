<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        *{
            padding:0;
            margin:0;
            font-family: Arial, Helvetica, sans-serif;
        }
        nav{
            height:5vw;
            width: 100%;
            background-color:#EAE4D5;
            display:flex;
            align-items:center;
            justify-content:space-between;
        }
        .logo{
            font-size:2vw;
            margin-left:2vw;
        }
        .logoutBtn{
            margin-right:2vw;
            text-decoration:none;
        }

        
    </style>
</head>
<body>
    <nav>
        <p class='logo'>Attendace Tracker</p>
        <a href="../controllers/logout.php" class="logoutBtn">Logout</a>
    </nav>
    <div class="container">

    </div>
</body>
</html>