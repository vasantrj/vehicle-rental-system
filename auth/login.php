<?php
session_start();
include "../config/db.php";

$error = "";

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Admin fixed credentials
    if ($email === "admin@gmail.com" && $password === "admin") {
        $_SESSION['role'] = "admin";
        $_SESSION['email'] = $email;
        header("Location: ../admin/dashboard.php");
        exit();
    }

    // Normal user login
    $query = "SELECT * FROM users WHERE email='$email' AND password='$password'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $_SESSION['role'] = "user";
        $_SESSION['email'] = $email;
        header("Location: ../user/dashboard.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Vehicle Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 bg-white p-4 shadow rounded">
            <h3 class="text-center mb-3">Login</h3>

            <?php if($error) echo "<p class='text-danger text-center'>$error</p>"; ?>

            <form method="post">
                <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                <button class="btn btn-primary w-100" name="login">Login</button>
            </form>

            <p class="text-center mt-3">
                Don't have account? <a href="register.php">Register</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>