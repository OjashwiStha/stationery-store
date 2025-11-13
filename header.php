<?php
// Start session to manage login, cart, etc.
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <title>Online Stationery Store</title>
      <link rel="stylesheet" href="style.css"> 
  </head>
  <body>

  <!-- Header / Navbar -->
  <header>
      <div class="container">
          <h1><a href="index.php">Stationery Store</a></h1>
          <nav>
              <a href="index.php">Home</a>
              <!-- <a href="view_cart.php">Cart</a> -->
              <?php if (isset($_SESSION['user_id'])): ?>
                  <a href="order_history.php">Orders</a>
                  <a href="logout.php">Logout</a>
              <?php else: ?>
                  <a href="login.php">Login</a>
                  <a href="register.php">Register</a>
              <?php endif; ?> <!-- end of if --> 
          </nav>
      </div>
  </header>
