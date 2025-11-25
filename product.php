<?php
session_start();
include 'db.php';
include 'header.php';

// Check if product ID is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $product_id = $_GET['id'];

    // Fetch product details from DB
    $sql = "SELECT p.*, c.name AS category_name 
            FROM products p 
            LEFT JOIN categories c ON p.category_id = c.id
            WHERE p.id = $product_id";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) === 1) {
        $product = mysqli_fetch_assoc($result);
    } else {
        echo "<p>Product not found.</p>";
        include 'footer.php';
        exit;
    }
} else {
    echo "<p>Invalid product ID.</p>";
    include 'footer.php';
    exit;
}
?>

<div class="product-details">
    <div class="product-img">
        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $product['name'] . "- " .$product['description'] ?>">
    </div>
    <div class="product-info">
        <h2><?php echo $product['name']; ?></h2>
        <p><strong>Category:</strong> <?php echo $product['category_name']; ?></p>
        <p><strong>Price:</strong> NRP<?php echo number_format($product['price'], 2); ?></p>
        <p><strong>Description:</strong></p>
        <p><?php echo $product['description']; ?></p>

        <form action="cart.php" method="POST">
            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
            <label for="qty">Quantity:</label>
            <input type="number" name="qty" value="1" min="1" required>
            <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
        </form>
    </div>
</div>

<!-- Recommendation -->
<h3 style="padding-left:300px"> You May Also Like</h3>
<div class="product-grid">
<?php
$cat_id = $product['category_id'];
$rec_q = mysqli_query($conn, "SELECT * FROM products WHERE category_id = $cat_id AND id != $product_id LIMIT 4");
while ($rec = mysqli_fetch_assoc($rec_q)) {
?>
    <div class="product-card">
        <img src="uploads/<?php echo $product['image']; ?>" alt="<?php echo $rec['name'] . "- " .$rec['description'] ?>">
        <h4><?php echo $rec['name']; ?></h4>
        <p>NRP<?php echo number_format($rec['price'], 2); ?></p>
        <a href="product.php?id=<?php echo $rec['id']; ?>" class="btn">View</a>
    </div>
<?php } ?>
</div>


<?php include 'footer.php'; ?>
