<?php
session_start();
$root='../';
include '../config/db.php';
if(!isset($_SESSION['role'])||$_SESSION['role']!=='admin'){ header("Location: ../auth/login.php"); exit(); }

$id = intval($_GET['id'] ?? 0);
if(!$id){ header("Location: manage-vehicles.php"); exit(); }
$vehicle = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM vehicles WHERE id=$id"));
if(!$vehicle){ header("Location: manage-vehicles.php"); exit(); }

$success=''; $error='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $name   = mysqli_real_escape_string($conn,$_POST['name']);
  $brand  = mysqli_real_escape_string($conn,$_POST['brand']);
  $price  = intval($_POST['price']);
  $status = $_POST['status']==='booked' ? 'booked' : 'available';
  $imgName = $vehicle['image'];

  if(!empty($_FILES['image']['name'])){
    $ext = strtolower(pathinfo($_FILES['image']['name'],PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','gif','webp'];
    if(!in_array($ext,$allowed)){
      $error = "Invalid image format. Use JPG, PNG, or WebP.";
    } else {
      $newImg = 'veh_'.uniqid().'.'.$ext;
      if(move_uploaded_file($_FILES['image']['tmp_name'],'../assets/images/'.$newImg)){
        $defaults=['swift.jpg','creta.jpg','re.jpg','hero-car.jpg'];
        if($imgName && !in_array($imgName,$defaults)) @unlink('../assets/images/'.$imgName);
        $imgName = $newImg;
      } else {
        $error = "Failed to upload image. Check folder permissions.";
      }
    }
  }

  if(!$error){
    mysqli_query($conn,"UPDATE vehicles SET name='$name',brand='$brand',price_per_day=$price,image='$imgName',status='$status' WHERE id=$id");
    header("Location: manage-vehicles.php?updated=1"); exit();
  }
}
?>
<!DOCTYPE html><html lang="en"><head>
<?php include '../includes/head.php'; ?>
<title>Edit Vehicle — Admin</title>
</head><body>
<!-- BG -->
<div class="dash-bg" aria-hidden="true"><div class="dash-bg-blob1"></div><div class="dash-bg-blob2"></div><div class="dash-bg-blob3"></div><div class="dash-bg-grid"></div></div>
<?php include '../includes/navbar_admin.php'; ?>
<div class="page-wrap">
  <div class="dash-header">
    <div class="eyebrow">Fleet</div>
    <h1>Edit Vehicle</h1>
    <div class="sub">Update details for: <strong><?= htmlspecialchars($vehicle['name']) ?></strong></div>
  </div>
  <?php if($error): ?><div class="auth-error mb-4"><?= $error ?></div><?php endif; ?>
  <div class="row justify-content-center mb-5">
    <div class="col-md-8">
      <div class="profile-card">
        <!-- Current image preview -->
        <div class="mb-4 text-center">
          <img src="../assets/images/<?= htmlspecialchars($vehicle['image']) ?>"
               alt="Current" id="imgPreview"
               style="max-height:220px;border-radius:var(--r2);border:1px solid var(--b1);object-fit:cover;width:100%">
          <div style="color:var(--t3);font-size:.75rem;margin-top:8px">Current image — upload new to replace</div>
        </div>
        <form method="POST" enctype="multipart/form-data">
          <div class="row g-3">
            <div class="col-md-6">
              <label class="input-label">Vehicle Name</label>
              <input type="text" name="name" class="input-field" value="<?= htmlspecialchars($vehicle['name']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Brand</label>
              <input type="text" name="brand" class="input-field" value="<?= htmlspecialchars($vehicle['brand']) ?>" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Price Per Day (₹)</label>
              <input type="number" name="price" class="input-field" value="<?= $vehicle['price_per_day'] ?>" min="1" required>
            </div>
            <div class="col-md-6">
              <label class="input-label">Status</label>
              <select name="status" class="input-field">
                <option value="available" <?= $vehicle['status']==='available'?'selected':'' ?>>Available</option>
                <option value="booked"    <?= $vehicle['status']==='booked'?'selected':'' ?>>Booked</option>
              </select>
            </div>
            <div class="col-12">
              <label class="input-label">Replace Image (optional)</label>
              <input type="file" name="image" class="input-field" accept="image/*" style="padding:9px" id="imgInput">
            </div>
          </div>
          <div class="d-flex gap-3 mt-4">
            <button type="submit" class="btn btn-primary btn-md"><i class="fa-solid fa-floppy-disk me-2"></i>Save Changes</button>
            <a href="manage-vehicles.php" class="btn btn-ghost btn-md"><i class="fa-solid fa-arrow-left me-2"></i>Cancel</a>
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
  r.onload=e=>document.getElementById('imgPreview').src=e.target.result;
  r.readAsDataURL(f);
});
</script>
</body></html>
