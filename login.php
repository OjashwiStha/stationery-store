<?php
session_start();
include 'db.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST["email"]);
    $password = $_POST["password"];

    $query = "SELECT * FROM users WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if ($user = mysqli_fetch_assoc($result)) {
        // var_dump($user);
        // exit;

        if (password_verify($password, $user['password'])) {
            // SET SESSION VARIABLES HERE
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            // Redirect based on role
            if ($user['role'] === 'admin') {
                header("Location: dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit();
        } else {
            $error = "❌ Invalid email or password.";
        }
    } else {
        $error = "❌ User not found.";
    }
}
?>


<?php include 'header.php'; ?>
<div class="auth-container">
    <h2>Login</h2>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="POST" onsubmit="return validateLoginForm()">
        <input type="email" name="email" placeholder="Email" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit" class="btn">Login</button>
    </form>
</div>

<script>
function validateLoginForm() {
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="password"]').value.trim();


    const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;

    if (email === "" || password === "") {
        alert("Please fill in all fields.");
        return false;
    }

    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        return false;
    }

    if (password.length < 6) {
        alert("Password should be at least 6 characters.");
        return false;
    }

    return true; 
}
</script>

<?php include 'footer.php'; ?>
