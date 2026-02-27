<?php
include "../config/db.php";

$message = "";

if (isset($_POST['register'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    if ($email === "admin@gmail.com" && $password === "admin") {
        $role = "admin";
    } else {
        $role = "user";
    }

    // 🔎 Check if email already exists
    $check_stmt = mysqli_prepare($conn,
        "SELECT id FROM users WHERE email=?"
    );

    mysqli_stmt_bind_param($check_stmt, "s", $email);
    mysqli_stmt_execute($check_stmt);
    $result = mysqli_stmt_get_result($check_stmt);

    if (mysqli_num_rows($result) > 0) {

        $message = "Email already registered. Please login.";

    } else {

        $stmt = mysqli_prepare($conn,
            "INSERT INTO users (name, email, password, role)
             VALUES (?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param($stmt, "ssss",
            $name, $email, $hashed_password, $role
        );

        if (mysqli_stmt_execute($stmt)) {
            $message = "Registration successful! Please login.";
        } else {
            $message = "Registration failed!";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Register</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Register</h2>
<?php if($message) echo "<p class='text-danger'>$message</p>"; ?>

<form method="post" class="w-50">
<input class="form-control mb-2" name="name" placeholder="Full Name" required>
<input class="form-control mb-2" type="email" name="email" placeholder="Email" required>
<input class="form-control mb-2" type="password" name="password" placeholder="Password" required>
<button class="btn btn-primary" name="register">Register</button>
</form>

</body>
</html>