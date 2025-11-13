<?php
// session_start();
include 'db.php';
include 'auth.php'; 
include 'header.php'; 

// Check if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "<p style='text-align:center;'>Your cart is empty.</p>";
    include 'footer.php';
    exit;
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo "<p style='text-align:center;'>Please <a href='login.php'>login</a> to checkout.</p>";
    include 'footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name    = mysqli_real_escape_string($conn, $_POST['name']);
    $email   = mysqli_real_escape_string($conn, $_POST['email']);
    $phone   = mysqli_real_escape_string($conn, $_POST['phone']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $payment_mode = mysqli_real_escape_string($conn, $_POST['payment_mode']);
    $user_id = $_SESSION['user_id'];
    $total_price = 0;
    
    // Calculate total
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product_q = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
        $product = mysqli_fetch_assoc($product_q);
        $total_price += $product['price'] * $qty;
    }
    
    // Insert into orders table
    $sql = "INSERT INTO orders (user_id, customer_name, customer_email, customer_phone, customer_address, total_price, payment_mode)
            VALUES ($user_id, '$name', '$email', '$phone', '$address', $total_price, '$payment_mode')";
    mysqli_query($conn, $sql);
    $order_id = mysqli_insert_id($conn); // Get last inserted order ID
    
    // Insert each item into order_items
    foreach ($_SESSION['cart'] as $product_id => $qty) {
        $product_q = mysqli_query($conn, "SELECT price FROM products WHERE id = $product_id");
        $product = mysqli_fetch_assoc($product_q);
        $price = $product['price'];
        
        $insert_item = "INSERT INTO order_items (order_id, product_id, quantity, price)
                        VALUES ($order_id, $product_id, $qty, $price)";
        mysqli_query($conn, $insert_item);
    }
    
    // Clear cart
    unset($_SESSION['cart']);
    
    echo "<h2 style='text-align:center;'>🎉 Order Placed Successfully!</h2>";
    echo "<p style='text-align:center;'>Thank you <strong>$name</strong>.<br>Your order ID is <strong>#$order_id</strong>.</p>";
    
    include 'footer.php';
    exit;
}
?>

<h2 style="text-align:center;">🧾 Checkout</h2>

<form method="POST" action="checkout.php" style="max-width:600px; margin:auto;">
    <label>Full Name:</label><br>
    <input type="text" name="name" required><br><br>

    <label>Email:</label><br>
    <input type="email" name="email" required><br><br>

    <label>Phone:</label><br>
    <input type="text" name="phone" required><br><br>

    <label>Address:</label><br>
    <textarea name="address" required></textarea><br><br>

    <label>Payment Mode:</label><br>
    <select name="payment_mode" required>
        <option value="Cash on Delivery">Cash on Delivery</option>
        <option value="Paytm Link">Paytm (Link Only)</option>
    </select><br><br>

    <button type="submit" class="btn">Place Order</button>
</form>

<?php include 'footer.php'; ?>
