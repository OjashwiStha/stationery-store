<?php
$require_admin = true;
include 'auth.php';
include 'db.php';
include 'header.php';
?>

<h2> All Orders</h2>

<table border="1" cellpadding="10" cellspacing="0" style="width:100%; margin-top: 20px;">
    <tr style="background-color:#f0f0f0;">
        <th>Order ID</th>
        <th>User</th>
        <th>Total (NRP)</th>
        <th>Payment Mode</th>
        <th>Placed On</th>
        <th>Details</th>
    </tr>

    <?php
    $sql = "SELECT orders.*, users.name AS user_name 
            FROM orders 
            LEFT JOIN users ON orders.user_id = users.id 
            ORDER BY orders.created_at DESC";

    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        while ($order = mysqli_fetch_assoc($result)) {
            echo "<tr>";
            echo "<td>{$order['id']}</td>";
            echo "<td>" . htmlspecialchars($order['user_name'] ?? 'Guest') . "</td>";
            echo "<td>" . number_format($order['total_price'], 2) . "</td>";
            echo "<td>" . htmlspecialchars($order['payment_mode']) . "</td>";
            echo "<td>" . $order['created_at'] . "</td>";
            echo "<td><a href='../view_invoice.php?id={$order['id']}'>  View</a></td>";
            echo "</tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No orders found.</td></tr>";
    }
    ?>
</table>

<?php include 'footer.php'; ?>
