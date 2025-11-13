<?php
$require_admin = true;
include 'auth.php';
include 'db.php';
include 'header.php';

// Handle form submission
$message = "";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float) $_POST['price'];
    $category_id = (int) $_POST['category_id'];
    $image = "";

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $image_name = time() . "_" . basename($_FILES['image']['name']);
        $upload_path = "../uploads/" . $image_name;
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image = $image_name;
        }
    }

    // Insert product
    $sql = "INSERT INTO products (name, description, price, category_id, image)
            VALUES ('$name', '$description', $price, $category_id, '$image')";
    if (mysqli_query($conn, $sql)) {
        $message = "✅ Product added successfully!";
    } else {
        $message = "❌ Error: " . mysqli_error($conn);
    }
}
?>

<div style="padding: 20px;">
    <h2> Add New Product</h2>
    <?php if ($message) echo "<p style='color: green;'>$message</p>"; ?>

    <form method="POST" enctype="multipart/form-data" style="max-width: 500px;">
        <label>Product Name</label><br>
        <input type="text" name="name" required><br><br>

        <label>Description</label><br>
        <textarea name="description" required></textarea><br><br>

        <label>Price (NRP)</label><br>
        <input type="number" name="price" step="0.01" required><br><br>

        <label>Category</label><br>
        <select name="category_id" required>
            <option value="">Select Category</option>
            <?php
            $cat_q = mysqli_query($conn, "SELECT * FROM categories");
            while ($cat = mysqli_fetch_assoc($cat_q)) {
                echo "<option value='{$cat['id']}'>{$cat['name']}</option>";
            }
            ?>
        </select><br><br>

        <label>Product Image</label><br>
        <input type="file" name="image" accept="image/*"><br><br>

        <button type="submit" class="btn">Add Product</button>
    </form>
</div>

<?php include 'footer.php'; ?>
