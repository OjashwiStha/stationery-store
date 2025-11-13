<?php
// session_start();
include 'db.php';
include 'auth.php';
include 'header.php';

echo "<h2 style='text-align:center;'>Your Cart</h2>";

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p style='text-align:center;'>Your cart is empty.</p>";
    include 'includes/footer.php';
    exit;
}

$total = 0;
?>

<table class="cart-table">
    <tr>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Action</th>
    </tr>

<?php
foreach ($_SESSION['cart'] as $product_id => $qty) {
    $sql = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $sql);
    $product = mysqli_fetch_assoc($result);

    $subtotal = $product['price'] * $qty;
    $total += $subtotal;
    ?>
    <tr>
        <td><?php echo $product['name']; ?></td>
        <td>NRP<?php echo number_format($product['price'], 2); ?></td>
        <td><?php echo $qty; ?></td>
        <td>NRP<?php echo number_format($subtotal, 2); ?></td>
        <td><a href="remove_from_cart.php?id=<?php echo $product_id; ?>">Remove</a></td>
    </tr>
<?php } ?>

<tr>
    <td colspan="3" style="text-align:right;"><strong>Total:</strong></td>
    <td colspan="2"><strong>NRP<?php echo number_format($total, 2); ?></strong></td>
</tr>
</table>

<p style="text-align:center; margin-top: 20px;">
    <a href="checkout.php" class="btn">Proceed to Checkout</a>
</p>

<?php include 'footer.php'; ?>







