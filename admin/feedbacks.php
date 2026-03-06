<?php

session_start();
include "../config/db.php";

$result=mysqli_query($conn,"SELECT * FROM feedback ORDER BY created_at DESC");

?>

<?php include "../includes/navbar_admin.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>User Feedback</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="container mt-5">

<h3>User Feedback</h3>

<table class="table table-bordered mt-3">

<tr>

<th>User</th>
<th>Email</th>
<th>Rating</th>
<th>Message</th>
<th>Date</th>

</tr>

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<tr>

<td><?= $row['name'] ?></td>
<td><?= $row['email'] ?></td>

<td>

<?php
for($i=1;$i<=5;$i++){

if($i <= $row['rating']){

echo "⭐";

}else{

echo "☆";

}

}
?>

</td>

<td><?= $row['message'] ?></td>

<td><?= $row['created_at'] ?></td>

</tr>

<?php } ?>

</table>

</div>

</body>
</html>