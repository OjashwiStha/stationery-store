<?php
$require_admin = true;
include 'auth.php';
include 'db.php';
include 'header.php';

$message = "";

// Add new category
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_category'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);

    $check = mysqli_query($conn, "SELECT * FROM categories WHERE name = '$name'");
    if (mysqli_num_rows($check) > 0) {
        $message = "Category already exists!";
    } else {
        $insert = mysqli_query($conn, "INSERT INTO categories (name) VALUES ('$name')");
        $message = $insert ? "Category added!" : "Failed to add category.";
    }
}

// Delete category
if (isset($_GET['delete'])) {
    $id = (int) $_GET['delete'];
    mysqli_query($conn, "DELETE FROM categories WHERE id = $id");
    $message = "Category deleted.";
}
?>

<div style="padding: 20px;">
    <h2> Manage Categories</h2>
    <?php if ($message) echo "<p style='color: green;'>$message</p>"; ?>

    <!-- Add Category -->
    <form method="POST" style="margin-bottom: 20px;">
        <label>Category Name:</label>
        <input type="text" name="name" required>
        <button type="submit" name="add_category" class="btn">Add Category</button><br>
    </form>
    <br>
    <!-- List of Categories -->
    <table border="1" cellpadding="10" cellspacing="0" style="width:50%; margin-top: 20px; margin-left: auto; margin-right: auto;text-align: center;
">
        <tr style="background-color:#f0f0f0;">
            <th>ID</th>
            <th>Name</th>
            <th>Actions</th>
        </tr>
        <?php
        $categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY id DESC");
        if (mysqli_num_rows($categories) > 0) {
            while ($cat = mysqli_fetch_assoc($categories)) {
                echo "<tr>";
                echo "<td>{$cat['id']}</td>";
                echo "<td>" . htmlspecialchars($cat['name']) . "</td>";
                echo "<td>
                        <a href='edit_product.php?id={$cat['id']}'> Edit</a> | 
                        <a href='manage_categories.php?delete={$cat['id']}' onclick='return confirm(\"Delete this category?\")'>Delete</a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='3'>No categories found.</td></tr>";
        }
        ?>
    </table>
</div>

<?php include 'footer.php'; ?>
