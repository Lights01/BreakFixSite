<?php
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$name, $email, $password]);
        echo "<script>alert('Registration successful!'); window.location.href='login.php';</script>";
    } catch (PDOException $e) {
        echo "<script>alert('Error: " . $e->getMessage() . "');</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register | BreakFixPro</title>
  <link href="style.css" rel="stylesheet" type="text/css" />
  <link href="auth.css" rel="stylesheet" type="text/css" />
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Poppins:wght@500;600;700&display=swap" rel="stylesheet">
  <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
  <header id="main-header">
    <div class="container">
      <div class="logo">
        <h1><a href="index.html">BreakFixPro</a></h1>
      </div>
      <nav>
        <ul>
          <li><a href="index.html">Home</a></li>
          <li><a href="login.php">Login</a></li>
        </ul>
      </nav>
    </div>
  </header>

  <div class="auth-container">
    <div class="auth-box">
      <h2>Create an Account</h2>
      <form class="auth-form" method="POST">
        <div class="form-group">
          <label for="name">Full Name</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required>
        </div>
        <div class="form-group">
          <label for="confirm-password">Confirm Password</label>
          <input type="password" id="confirm-password" required>
        </div>
        <div class="form-group terms">
          <label>
            <input type="checkbox" required>
            I agree to the <a href="#">Terms & Conditions</a>
          </label>
        </div>
        <button type="submit" class="auth-button">Register</button>
      </form>
      <p class="auth-switch">Already have an account? <a href="login.html">Login</a></p>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
