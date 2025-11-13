<?php
include 'db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone    = mysqli_real_escape_string($conn, $_POST['phone']);
    $address  = mysqli_real_escape_string($conn, $_POST['address']);
    $role     = 'user'; // all registrations default to user

    // Check if email already exists
    $check = mysqli_query($conn, "SELECT id FROM users WHERE email = '$email'");
    if (mysqli_num_rows($check) > 0) {
        $error = "Email already registered.";
    } else {
        $sql = "INSERT INTO users (name, email, password, phone, address, role)
                VALUES ('$name', '$email', '$password', '$phone', '$address', '$role')";
        if (mysqli_query($conn, $sql)) {
            $_SESSION['user_id'] = mysqli_insert_id($conn);
            $_SESSION['role'] = $role;
            header("Location: login.php");
            exit();
        } else {
            $error = "Registration failed. Please try again.";
        }
    }
}
?>

<?php include 'header.php'; ?>
<div class="auth-container">
    <h2>Register</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" onsubmit="return validateRegisterForm()">
        <input type="text" name="name" placeholder="Full Name" required><br>
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <input type="text" name="phone" placeholder="Phone"><br>
        <textarea name="address" placeholder="Address"></textarea><br>
        <button type="submit" class="btn">Register</button>
    </form>
</div>

<script>
function validateRegisterForm() {
    const name     = document.querySelector('input[name="name"]').value.trim();
    const email    = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();
    const phone    = document.querySelector('input[name="phone"]').value.trim();
    const address  = document.querySelector('textarea[name="address"]').value.trim();

    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,}$/i;
    const phonePattern = /^[0-9]{10,15}$/; // Accepts 10 to 15 digits

    if (name === "" || email === "" || password === "" || phone === "" || address === "") {
        alert("Please fill in all the fields.");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (password.length < 6) {
        alert("Password must be at least 6 characters long.");
        return false;
    }

    if (!phonePattern.test(phone)) {
        alert("Please enter a valid phone number (digits only).");
        return false;
    }

    return true; // Everything is valid, submit the form
}
</script>

<?php include 'footer.php'; ?>
