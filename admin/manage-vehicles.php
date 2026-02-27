<?php
session_start();
include "../config/db.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "admin") {
    header("Location: ../auth/login.php");
    exit();
}

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare($conn,
        "DELETE FROM vehicles WHERE id=?"
    );
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

$result = mysqli_query($conn, "SELECT * FROM vehicles");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Vehicles</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<h2>Manage Vehicles</h2>

<table class="table table-bordered">
<tr>
<th>ID</th>
<th>Name</th>
<th>Brand</th>
<th>Price</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>
<tr>
<td><?= $row['id'] ?></td>
<td><?= htmlspecialchars($row['name']) ?></td>
<td><?= htmlspecialchars($row['brand']) ?></td>
<td>₹<?= $row['price_per_day'] ?></td>
<td><?= $row['status'] ?></td>
<td>
<a class="btn btn-danger btn-sm" href="?delete=<?= $row['id'] ?>">Delete</a>
</td>
</tr>
<?php } ?>

</table>

</body>
</html>