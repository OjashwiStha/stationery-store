<?php
$require_admin = true;
include 'auth.php';
include 'db.php';

if (!isset($_GET['id'])) {
    die("❌ No product ID provided.");
}

$id = (int) $_GET['id'];

// Optional: Remove product image from uploads folder
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT image FROM products WHERE id = $id"));
if ($product && $product['image']) {
    $image_path = "../uploads/" . $product['image'];
    if (file_exists($image_path)) {
        unlink($image_path); // delete the image file
    }
}

// Delete product from database
$delete_query = "DELETE FROM products WHERE id = $id";
if (mysqli_query($conn, $delete_query)) {
    header("Location: dashboard.php?msg=deleted");
    exit();
} else {
    echo "❌ Error deleting product: " . mysqli_error($conn);
}
?>
