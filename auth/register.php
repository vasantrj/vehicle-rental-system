<?php
include "../config/db.php";

$message = "";

if (isset($_POST['register'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    if ($email === "admin@gmail.com" && $password === "admin") {
        $role = "admin";
    } else {
        $role = "user";
    }

    $query = "INSERT INTO users (name, email, password, role)
              VALUES ('$name', '$email', '$password', '$role')";

    if (mysqli_query($conn, $query)) {
        $message = "Registration successful! You can login now.";
    } else {
        $message = "Registration failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register - Vehicle Rental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 bg-white p-4 shadow rounded">
            <h3 class="text-center mb-3">Register</h3>

            <?php if($message) echo "<p class='text-success text-center'>$message</p>"; ?>

            <form method="post">
                <input class="form-control mb-3" type="text" name="name" placeholder="Full Name" required>
                <input class="form-control mb-3" type="email" name="email" placeholder="Email" required>
                <input class="form-control mb-3" type="password" name="password" placeholder="Password" required>
                <button class="btn btn-success w-100" name="register">Register</button>
            </form>

            <p class="text-center mt-3">
                Already have account? <a href="login.php">Login</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>