<?php
// Start session to manage login, cart, etc.
// session_start();
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <!-- for dynamic seo?? -->
        <title><?php echo isset($pageTitle)? $pageTitle: "Online Stationery Store" ?></title>
        <meta name="description" content="<?php isset($metaDescription)? $metaDescription: "Buy stationery products at best price" ?>"> 
        <link rel="stylesheet" href="style.css"> 
    </head>
    <body>

    <!-- Header / Navbar -->
    <header>
        <nav class="container">
            <div class="logo"><a href="index.php">Stationery Store</a></div>
            <div class="nav-right">
                <a href="index.php">Home</a>
                <!-- <a href="view_cart.php">Cart</a> -->
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="order_history.php">Orders</a>
                    <a href="view_cart.php">My Cart</a>
                    <a href="logout.php">Logout</a>
                <?php else: ?>
                    <a href="login.php">Login</a>
                    <a href="register.php">Register</a>
                <?php endif; ?> <!-- end of if -->
            </div>
        </nav>
    </header>
