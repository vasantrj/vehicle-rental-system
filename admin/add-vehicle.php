<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

$success='';$error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name  = mysqli_real_escape_string($conn,$_POST['name']);
  $brand = mysqli_real_escape_string($conn,$_POST['brand']);
  $price = intval($_POST['price']);
  if(empty($_FILES['image']['name'])){ $error="Please upload a vehicle image."; }
  else {
    $ext = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if(!in_array($ext,$allowed)){ $error="Invalid image format."; }
    else {
      $new = 'veh_'.uniqid().'.'.$ext;
      if(move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$new)){
        $stmt=mysqli_prepare($conn,"INSERT INTO vehicles(name,brand,price_per_day,image,status) VALUES(?,?,?,?,'available')");
        mysqli_stmt_bind_param($stmt,'ssis',$name,$brand,$price,$new);
        mysqli_stmt_execute($stmt);
        header("Location: manage-vehicles.php?added=1"); exit();
      } else { $error="Upload failed. Check folder permissions."; }
    }
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Add Vehicle — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Fleet</div>
    <h1>Add New Vehicle</h1>
    <div class="sub">Add a vehicle to your rental fleet</div>
  </div>
  <?php if($error): ?><div class="auth-error mb-4"><i class="fa-solid fa-triangle-exclamation me-2"></i><?= $error ?></div><?php endif; ?>
  <div class="row justify-content-center mb-5">
    <div class="col-md-7">
      <div class="profile-card">
        <!-- Image Preview -->
        <div id="previewWrap" style="display:none;margin-bottom:20px">
          <img id="imgPreview" style="width:100%;max-height:220px;object-fit:cover;border-radius:var(--r2);border:1px solid var(--b1)">
        </div>
        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="input-label">Vehicle Name</label>
              <input type="text" name="name" class="input-field" placeholder="e.g. Swift Dzire" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Brand</label>
              <input type="text" name="brand" class="input-field" placeholder="e.g. Maruti" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Price Per Day (₹)</label>
              <input type="number" name="price" class="input-field" placeholder="e.g. 1500" min="1" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Vehicle Image</label>
              <input type="file" name="image" class="input-field" accept="image/*" required style="padding:9px" id="imgInput">
            </div>
          </div>
          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-plus me-2"></i>Add Vehicle</button>
            <a href="manage-vehicles.php" class="btn btn-ghost btn-md"><i class="fa-solid fa-arrow-left me-2"></i>Back</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php include '../includes/admin_footer.php'; ?>
<?php include '../includes/scripts.php'; ?>
<script>
document.getElementById('imgInput').addEventListener('change',function(){
  const f=this.files[0]; if(!f) return;
  const r=new FileReader();
  r.onload=e=>{
    document.getElementById('imgPreview').src=e.target.result;
    document.getElementById('previewWrap').style.display='block';
  };
  r.readAsDataURL(f);
});
</script>
</body></html>
