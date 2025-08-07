<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Attendance Tracker</title>
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
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container {
            background-color: #14213d;
            padding: 3rem 4rem;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 420px;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        p.title {
            text-align: center;
            font-size: 1.8rem;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 1rem;
        }
        input[type="text"],
        input[type="password"] {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: 1.8px solid #ccc;
            border-radius: 8px;
            transition: border-color 0.3s ease;
            background-color: #fafafa;
            color: #222;
        }
        input[type="text"]:focus,
        input[type="password"]:focus {
            border-color: #14213d;
            background-color: #fff;
            outline: none;
        }
        button {
            padding: 0.75rem 1rem;
            font-size: 1rem;
            border: none;
            border-radius: 8px;
            background-color: #fca311; /* accent amber like logout button */
            color: #14213d;
            font-weight: 700;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
            transition: background-color 0.25s ease, box-shadow 0.25s ease;
        }
        button:hover,
        button:focus {
            background-color: #d48806;
            box-shadow: 0 6px 15px rgba(212, 136, 6, 0.8);
            outline: none;
        }
        button:active {
            background-color: #b06b04;
            box-shadow: none;
        }
        .noAcc{
            text-decoration: none;
            color:#ffffff;
            text-align:center;
        }
        .noAcc a{
            text-decoration: none;
            color:#fca311;
        }
    </style>
</head>
<body>
    <div class="container">
        <form action="./controllers/login.php" method="POST" >
            <p class="title">Login</p>
            <input type="text" name="idnum" placeholder="ID Number" required />
            <input type="password" name="password" placeholder="Password" required />
            <button type="submit">Login</button>
            <p class="noAcc">Dont have an account?<a href="./views/register.php" > Register</a></p>
        </form>
    </div>
</body>
</html>