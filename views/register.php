<?php 
session_start();
$errors = $_SESSION['register_errors'] ?? [];
$formData = $_SESSION['form_data'] ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Register</title>
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
    background-color: #14213d; /* dark navy */
    padding: 3rem 4rem;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    width:620px;
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
    color: #ffffff; /* white text */
    margin-bottom: 1rem;
  }
  /* Input groups for two-row layout */
  .input-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
  }
  .input-group {
    display: flex;
    flex-direction: column;
  }
  label {
    color: #fca311; /* amber accent for labels */
    font-weight: 600;
    margin-bottom: 0.3rem;
    user-select: none;
  }
  input[type="text"],
  input[type="email"],
  input[type="password"] {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    background-color: #fafafa;
    color: #222;
    transition: border-color 0.3s ease;
    width: 100%;
    box-sizing: border-box;
  }
  input[type="text"]:focus,
  input[type="email"]:focus,
  input[type="password"]:focus {
    border-color: #14213d; /* navy focus */
    background-color: #fff;
    outline: none;
  }
  button {
    padding: 0.75rem 1rem;
    font-size: 1rem;
    border: none;
    border-radius: 8px;
    background-color: #fca311; /* amber background */
    color: #14213d; /* navy text */
    font-weight: 700;
    cursor: pointer;
    box-shadow: 0 4px 10px rgba(252, 163, 17, 0.6);
    transition: background-color 0.25s ease, box-shadow 0.25s ease;
    user-select: none;
    margin-top:1.75rem;
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
  .login-link {
    text-align: center;
    font-size: 0.9rem;
    color: #ffffff;
    margin-top: 1rem;
    user-select: none;
  }
  .login-link a {
    color: #fca311;
    text-decoration: none;
  }
  .login-link a:hover,
  .login-link a:focus {
    text-decoration: underline;
    outline: none;
  }
  select {
    padding: 0.70rem 1rem;
    font-size: 1rem;
    border: 1.8px solid #ccc;
    border-radius: 8px;
    background-color: #fafafa;
    color: #222;
    transition: border-color 0.3s ease;
    width: 100%;
    box-sizing: border-box;
}
  .alert {
    background-color: #f8d7da;
    border: 1.8px solid #f5c2c7;
    color: #842029;
    border-radius: 8px;
    padding: 1rem 1.5rem;
    margin-bottom: 1.5rem;
    font-weight: 600;
  }
</style>
</head>
<body>
  <div class="container">

    <?php if (!empty($errors)): ?>
      <div class="alert" role="alert" tabindex="0">
        <ul style="margin-left:1.2rem;">
          <?php foreach ($errors as $error): ?>
            <li><?php echo htmlspecialchars($error); ?></li>
          <?php endforeach; ?>
        </ul>
      </div>
    <?php 
      // Clear errors after displaying
      unset($_SESSION['register_errors']);
    endif; 
    ?>

    <form action="../controllers/register.php" method="POST">
      <p class="title">Register</p>
      <div class="input-row">
        <div class="input-group">
          <label for="fname">First Name</label>
          <input 
            type="text" 
            id="fname" 
            name="fname" 
            placeholder="Your first name" 
            autocomplete="name" 
            required 
          
          />
        </div>
        <div class="input-group">
          <label for="lname">Last Name</label>
          <input 
            type="text" 
            id="lname" 
            name="lname" 
            placeholder="Your last name" 
            autocomplete="name" 
            required 
          />
        </div>
        <div class="input-group">
          <label for="idnum">ID Number</label>
          <input 
            type="text" 
            id="idnum" 
            name="idnum" 
            placeholder="ex 12-34567" 
            autocomplete="email" 
            required 
          />
        </div>
        <div class="input-group">
          <label for="yearLevel">Year Level</label>
          <select id="yearLevel" name="yearLevel" required>
            <option value="1" >1st Year</option>
            <option value="2" >2nd Year</option>
            <option value="3" >3rd Year</option>
            <option value="4" >4th Year</option>
          </select>
        </div>
      </div>

      <div class="input-row">
        <div class="input-group">
          <label for="section">Section</label>
          <select id="section" name="section" required>
            <option value="A">Section A</option>
            <option value="B">Section B</option>
            <option value="C">Section C</option>
            <option value="D">Section D</option>
          </select>
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" placeholder="Create password" minlength="6" autocomplete="new-password" required />
        </div>
        <div class="input-group">
          <button type="submit">Register</button>
        </div>
        <div class="input-group">
          <label for="confirmPassword">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm password" minlength="6" autocomplete="new-password" required />
        </div>
      </div>
           
      <p class="login-link">
        Already have an account? <a href="../index.php">Login</a>
      </p>
    </form>
  </div>
</body>
</html>
