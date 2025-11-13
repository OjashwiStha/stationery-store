<?php
// session_start();
include 'auth.php';

if (isset($_GET['id'])) {
    $product_id = $_GET['id'];
    unset($_SESSION['cart'][$product_id]);
}

header('Location: view_cart.php');
exit;
