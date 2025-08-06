<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Login Page</title>
        <style>
            *{
                padding:0;
                margin:0;
                font-family: Arial, Helvetica, sans-serif;
            }
            .container{
                height:100vh;
                display:flex;
                align-items:center;
                justify-content:center;
                background-color:#F2F2F2;
            }
            form{
                display:flex;
                flex-direction:column;
                gap:1vh;
                background-color:#EAE4D5;
                padding: 3vw;
                width: 20vw;
                border-radius:10px;
            }
            input{
                height:2vw;
                padding-left:1vw;
                border-radius:5px;
                border:1px solid gray;
            }
            button{
                height:2vw;
                border-radius:5px;
                background-color:#000000;
                color:white;
            }
            p{
                text-align:center;
                font-size:2vw;
                font-weight:bold;
                padding: 1vw;
            }
        </style>
    </head>
<body>

    <div class="container">
        <form action="./controllers/login.php" method="POST">
            <p>Login</p>
            <input type="text" name="email" placeholder="ID number" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
    </div>

</body>
</html>
