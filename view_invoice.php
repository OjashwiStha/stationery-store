<?php
include 'db.php';
include 'auth.php';
include 'header.php';

// Must be logged in to view invoice
if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center;'>Please <a href='login.php'>login</a> to view your invoice.</p>";
    include 'footer.php';
    exit;
}

if (!isset($_GET['id'])) {
    echo "<p style='text-align:center;'>Invalid request. Order ID is missing.</p>";
    include 'footer.php';
    exit;
}

$order_id = (int) $_GET['id'];
$user_id = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'user';

// Admins can view any order, users can only view their own
if ($role === 'admin') {
    $order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id");
} else {
    $order_q = mysqli_query($conn, "SELECT * FROM orders WHERE id = $order_id AND user_id = $user_id");
}

if (mysqli_num_rows($order_q) === 0) {
    echo "<p style='text-align:center;'>Order not found or unauthorized access.</p>";
    include 'footer.php';
    exit;
}

$order = mysqli_fetch_assoc($order_q);

// Fetch order items
$items_q = mysqli_query($conn, "
    SELECT oi.*, p.name 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = $order_id
");
?>

<div style="max-width: 800px; margin: auto; padding: 20px;">
    <h2> Invoice - Order #<?php echo $order['id']; ?></h2>
    <p><strong>Date:</strong> <?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></p>
    <p><strong>Name:</strong> <?php echo $order['customer_name']; ?></p>
    <p><strong>Email:</strong> <?php echo $order['customer_email']; ?></p>
    <p><strong>Phone:</strong> <?php echo $order['customer_phone']; ?></p>
    <p><strong>Address:</strong> <?php echo $order['customer_address']; ?></p>
    <p><strong>Payment Mode:</strong> <?php echo $order['payment_mode']; ?></p>

    <hr>
    <h3> Products Ordered</h3>
    <table class="cart-table">
        <tr>
            <th>Product</th>
            <th>Price (NRP)</th>
            <th>Quantity</th>
            <th>Subtotal (NRP)</th>
        </tr>
        <?php
        $grand_total = 0;
        while ($item = mysqli_fetch_assoc($items_q)) {
            $subtotal = $item['price'] * $item['quantity'];
            $grand_total += $subtotal;
        ?>
        <tr>
            <td><?php echo $item['name']; ?></td>
            <td><?php echo number_format($item['price'], 2); ?></td>
            <td><?php echo $item['quantity']; ?></td>
            <td><?php echo number_format($subtotal, 2); ?></td>
        </tr>
        <?php } ?>
        <tr>
            <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
            <td><strong>NRP<?php echo number_format($grand_total, 2); ?></strong></td>
        </tr>
    </table>

    <br>
    <!-- <a href="order_history.php" class="btn"> Back to Orders</a> -->
</div>

<?php include 'footer.php'; ?>
