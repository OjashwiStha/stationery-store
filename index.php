<?php
session_start();
include 'db.php';
include 'header.php';
?>

<!-- Search & Filter Form -->
<form method="GET" style="text-align:center; margin-bottom: 20px;"><br>
    <input type="text" name="search" placeholder="Search products..." value="<?php echo $_GET['search'] ?? ''; ?>">

    <select name="category">
        <option value="">All Categories</option>
        <?php
        $cat_q = mysqli_query($conn, "SELECT * FROM categories");
        while ($cat = mysqli_fetch_assoc($cat_q)) {
            $selected = (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? "selected" : "";
            echo "<option value='{$cat['id']}' $selected>{$cat['name']}</option>";
        }
        ?>
    </select>

    <!-- <select name="price_range">
        <option value="">All Prices</option>
        <option value="0-100" <?php //if ($_GET['price_range'] == '0-100') echo 'selected'; ?>>Under NRP100</option>
        <option value="100-500" <?php// if ($_GET['price_range'] == '100-500') echo 'selected'; ?>>NRP100 - NRP500</option>
        <option value="500-1000" <?php //if ($_GET['price_range'] == '500-1000') echo 'selected'; ?>>NRP500 - NRP1000</option>
    </select> -->

    <button type="submit" class="btn">Search</button>
</form><br>

<!-- 🧱 Main Layout: Products + Ads -->
<div style="display: flex; gap: 20px; align-items: flex-start; padding: 20px;">

    <!-- 🛍️ Left: Product Listings -->
    <div style="flex: 3;">
        <h2>Our Latest Products</h2><br>

        <div class="product-grid">
        <?php
        // Dynamic WHERE clause for filters
        $where = "WHERE 1";

        if (!empty($_GET['search'])) {
            $search = mysqli_real_escape_string($conn, $_GET['search']);
            $where .= " AND name LIKE '%$search%'";
        }

        if (!empty($_GET['category'])) {
            $category = (int) $_GET['category'];
            $where .= " AND category_id = $category";
        }

        if (!empty($_GET['price_range'])) {
            [$min, $max] = explode('-', $_GET['price_range']);
            $where .= " AND price BETWEEN $min AND $max";
        }

        $sql = "SELECT * FROM products $where";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<div class='product-card'>";
                echo "<img src='uploads/{$row['image']}' alt='{$row['name']}'>";
                echo "<a href='product.php?id={$row['id']}' class='product_detl'><h3>{$row['name']}</h3></a>";
                // echo "<h3>{$row['name']}</h3>";
                echo "<p>NRP" . number_format($row['price'], 2) . "</p>";
                // echo "<a href='product.php?id={$row['id']}' class='btn'>View</a>";
                echo "</div>";
            }
        } else {
            echo "<p>No products found.</p>";
        }
        ?>
        </div>
    </div>

    <!-- 📢 Right: Ads Sidebar -->
    <div style="flex: 1;">
        <h3>📢 Sponsored</h3>
        <div style="display: flex; flex-direction: column; gap: 15px;">
            <img src="assets/images/ads/ad1.jpg" alt="Ad 1" style="width: 100%; border-radius: 10px;">
            <!-- <img src="assets/images/ads/ad2.jpg" alt="Ad 2" style="width: 100%; border-radius: 10px;"> -->
            <!-- <img src="assets/images/ads/ad3.jpg" alt="Ad 3" style="width: 100%; border-radius: 10px;"> -->
        </div>

        <!-- 📢 Right: Ads Sidebar -->
        <!-- <div style="flex: 1;">
            <h3>📢 Sponsored</h3>
            <div style="display: flex; flex-direction: column; gap: 15px;">
                <?php
                // $position = 'right';
                // $sql = "SELECT * FROM ads WHERE position = '$position'";
                // $result = mysqli_query($conn, $sql);

                // if ($result && mysqli_num_rows($result) > 0) {
                //     while ($ad = mysqli_fetch_assoc($result)) {
                //         $image_path = 'uploads/' . htmlspecialchars($ad['image']);
                //         echo '<div class="ad-box">';
                //         echo '<a href="' . htmlspecialchars($ad['link']) . '" target="_blank">';
                //         echo '<img src="' . $image_path . '" alt="Advertisement" style="width:100%; border-radius:10px;">';
                //         echo '</a>';
                //         echo '</div>';
                //     }
                // } else {
                //     echo "<p>No ads to show for $position.</p>";
                // }
                ?>
            </div>
        </div> -->

    </div>

</div>

<?php include 'footer.php'; ?>

