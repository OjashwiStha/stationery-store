<?php
// session_start();
include 'db.php';
include 'auth.php';
include 'header.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center;'>Please <a href='login.php'>login</a> to view your order history.</p>";
    include 'footer.php';
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch orders for this user
$sql = "SELECT * FROM orders WHERE user_id = $user_id ORDER BY created_at DESC";
$result = mysqli_query($conn, $sql);

echo "<h2 style='text-align:center;'> Your Order History</h2>";

if (mysqli_num_rows($result) == 0) {
    echo "<p style='text-align:center;'>No orders found.</p>";
    include 'footer.php';
    exit;
}
?>

<table class="cart-table">
    <tr>
        <th>Order ID</th>
        <th>Date</th>
        <th>Total (NRP)</th>
        <th>Payment</th>
        <th>Action</th>
    </tr>

<?php while ($order = mysqli_fetch_assoc($result)) { ?>
    <tr>
        <td>#<?php echo $order['id']; ?></td>
        <td><?php echo date('d M Y, h:i A', strtotime($order['created_at'])); ?></td>
        <td><?php echo number_format($order['total_price'], 2); ?></td>
        <td><?php echo $order['payment_mode']; ?></td>
        <td><a href="view_invoice.php?id=<?php echo $order['id']; ?>">View Invoice</a></td>
    </tr>
<?php } ?>
</table>

<?php include 'footer.php'; ?>
