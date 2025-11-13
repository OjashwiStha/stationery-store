<?php
$require_admin = true;
include 'auth.php';
include 'db.php';
include 'header.php';

if (!isset($_GET['id'])) {
    echo "<p>❌ No product ID provided.</p>";
    exit();
}

$id = (int) $_GET['id'];
$product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id"));

if (!$product) {
    echo "<p>❌ Product not found.</p>";
    exit();
}

$message = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float) $_POST['price'];
    $category_id = (int) $_POST['category_id'];
    $image = $product['image']; // Keep old image by default

    // Handle new image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $upload_path = "../uploads/" . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image = $image_name;
        }
    }

    $sql = "UPDATE products SET 
            name='$name',
            description='$description',
            price=$price,
            category_id=$category_id,
            image='$image'
            WHERE id=$id";

    if (mysqli_query($conn, $sql)) {
        $message = "✅ Product updated successfully!";
        $product = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM products WHERE id = $id")); // refresh
    } else {
        $message = "❌ Error updating product: " . mysqli_error($conn);
    }
}
?>

<div style="padding: 20px;">
    <h2>✏️ Edit Product</h2>
    <?php if ($message) echo "<p style='color: green;'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data" style="max-width: 500px;">
        <label>Product Name</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($product['name']) ?>" required><br><br>

        <label>Description</label><br>
        <textarea name="description" required><?= htmlspecialchars($product['description']) ?></textarea><br><br>

        <label>Price (NRP)</label><br>
        <input type="number" step="0.01" name="price" value="<?= $product['price'] ?>" required><br><br>

        <label>Category</label><br>
        <select name="category_id" required>
            <?php
            $cat_q = mysqli_query($conn, "SELECT * FROM categories");
            while ($cat = mysqli_fetch_assoc($cat_q)) {
                $selected = ($product['category_id'] == $cat['id']) ? "selected" : "";
                echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Current Image</label><br>
        <?php if ($product['image']) echo "<img src='../uploads/{$product['image']}' width='100'>"; ?><br><br>

        <label>Change Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit" class="btn">Update Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>
