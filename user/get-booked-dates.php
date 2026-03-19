<?php
include '../config/db.php';
$vehicle_id = intval($_GET['vehicle_id']??0);
$dates = [];
$q = mysqli_query($conn,"SELECT start_date,end_date FROM bookings WHERE vehicle_id=$vehicle_id AND status!='rejected' AND end_date>=CURDATE()");
while($row=mysqli_fetch_assoc($q)){
  $start=new DateTime($row['start_date']);
  $end  =new DateTime($row['end_date']);
  $end->modify('+1 day');
  $interval=new DateInterval('P1D');
  $range=new DatePeriod($start,$interval,$end);
  foreach($range as $d) $dates[]=$d->format('Y-m-d');
}
header('Content-Type: application/json');
echo json_encode(array_unique($dates));
