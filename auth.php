<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Optional: Check for admin role only if $require_admin is set
if (isset($require_admin) && $require_admin === true) {
    if ($_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }
}
?>
