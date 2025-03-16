<nav>
  <ul>
    <li><a href="index.html">Home</a></li>
    <?php if (isset($_SESSION['isLoggedIn']) && $_SESSION['isLoggedIn']): ?>
      <!-- User is logged in -->
      <li><span>Welcome, <?php echo htmlspecialchars($_SESSION['userName']); ?>!</span></li>
      <li><a href="profile.php">Profile</a></li>
      <li><a href="logout.php">Logout</a></li>
    <?php else: ?>
      <!-- User is not logged in -->
      <li><a href="login.php">Login</a></li>
      <li><a href="register.php">Register</a></li>
    <?php endif; ?>
  </ul>
</nav>