<?php
// session_start();
include 'db.php';
include 'auth.php';

// Check if form was submitted
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $qty = $_POST['qty'];

    // If cart doesn't exist, create it
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = [];
    }

    // If product already in cart, increase quantity
    if (isset($_SESSION['cart'][$product_id])) {
        $_SESSION['cart'][$product_id] += $qty;
    } else {
        $_SESSION['cart'][$product_id] = $qty;
    }

    // Redirect back to product page or cart
    header('Location: view_cart.php');
    exit;
}
?>
