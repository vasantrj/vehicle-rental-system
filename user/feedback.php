<?php

session_start();
include "../config/db.php";

if(!isset($_SESSION['user_id'])){
header("Location: ../auth/login.php");
exit();
}

$user_id=$_SESSION['user_id'];

$success="";

if($_SERVER["REQUEST_METHOD"]=="POST"){

$message=$_POST['message'];
$rating=$_POST['rating'];

$stmt=mysqli_prepare($conn,"
INSERT INTO feedback(user_id,name,email,message)
SELECT id,name,email,?
FROM users
WHERE id=?
");

mysqli_stmt_bind_param($stmt,"si",$message,$user_id);
mysqli_stmt_execute($stmt);

$success="Thank you for your feedback ❤️";

}

?>

<?php include "../includes/navbar_user.php"; ?>

<!DOCTYPE html>
<html>
<head>

<title>Feedback</title>

<link rel="stylesheet" href="../assets/style.css">

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>

.header{
background:linear-gradient(120deg,#ff7a18,#ff4e00);
padding:30px;
color:white;
border-radius:12px;
margin-top:20px;
}

.feedback-card{
max-width:700px;
margin:auto;
margin-top:40px;
padding:30px;
border-radius:15px;
box-shadow:0 10px 30px rgba(0,0,0,0.15);
background:white;
}

textarea{
resize:none;
border-radius:10px !important;
}

.star{
font-size:28px;
cursor:pointer;
color:#ccc;
}

.star.active{
color:#f59e0b;
}

</style>

</head>

<body>

<div class="container">

<!-- HEADER -->

<div class="header">

<h2>Give Feedback</h2>

<p>Help us improve your vehicle rental experience</p>

</div>

<!-- SUCCESS MESSAGE -->

<?php if($success!=""){ ?>

<div class="alert alert-success mt-4 text-center">

<?= $success ?>

</div>

<?php } ?>

<!-- FEEDBACK CARD -->

<div class="feedback-card">

<form method="post">

<label class="mb-2"><strong>Your Experience</strong></label>

<div class="mb-3">

<span class="star" onclick="rate(1)">★</span>
<span class="star" onclick="rate(2)">★</span>
<span class="star" onclick="rate(3)">★</span>
<span class="star" onclick="rate(4)">★</span>
<span class="star" onclick="rate(5)">★</span>

<input type="hidden" name="rating" id="rating" required>

</div>

<label class="mb-2"><strong>Your Feedback</strong></label>

<textarea name="message" class="form-control" rows="5" placeholder="Tell us about your experience..." required></textarea>

<button class="btn btn-primary mt-3">

Submit Feedback

</button>

</form>

</div>

</div>

<script>

/* STAR RATING */

function rate(stars){

document.getElementById("rating").value=stars;

let starElements=document.querySelectorAll(".star");

starElements.forEach((star,index)=>{

if(index < stars){
star.classList.add("active");
}else{
star.classList.remove("active");
}

});

}

</script>

</body>
</html>