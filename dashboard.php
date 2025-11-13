<?php
// var_dump($user);
// exit;

$require_admin = true;
include 'auth.php';
include 'db.php';
include 'header.php';

//var_dump($_SESSION); // for debugging only
?>


<div style="padding: 20px;">
    <h1> Admin Dashboard</h1>
    <!-- <p>Welcome, <?php //echo $_SESSION['name']; ?>!</p> -->

    <?//= htmlspecialchars($_SESSION['name'] ?? 'Welcome,Admin!'); ?> <!-- htmlspecialchars => protect from XXS(cross site scripting) -->

    <?php echo "<p>Welcome, " . htmlspecialchars($_SESSION['name'] ?? 'Admin') . "!</p>"; ?>



    <div style="display: flex; gap: 20px; margin-top: 30px;">
        <?php
        // Count total products
        $product_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM products"))['total'];

        // Count total categories
        $category_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM categories"))['total'];

        // Count total orders
        $order_count = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders"))['total'];
        ?>

        <!-- Products -->
        <div style="flex: 1; background: #f9f9f9; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2>Products</h2>
            <p><?php echo $product_count; ?> products</p>
            <a href="add_product.php" class="btn">Add Product</a>
        </div>

        <!-- Categories -->
        <div style="flex: 1; background: #f9f9f9; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2>Categories</h2>
            <p><?php echo $category_count; ?> categories</p>
            <a href="manage_categories.php" class="btn">Manage Categories</a>
        </div>

        <!-- Orders -->
        <div style="flex: 1; background: #f9f9f9; border-radius: 10px; padding: 20px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
            <h2>Orders</h2>
            <p><?php echo $order_count; ?> total orders</p>
            <a href="view_orders.php" class="btn">View Orders</a>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>
