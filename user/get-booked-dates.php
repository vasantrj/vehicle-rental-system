<?php

include "../config/db.php";

$vehicle_id=$_GET['vehicle_id'];

$result=mysqli_query($conn,
"SELECT start_date,end_date FROM bookings
WHERE vehicle_id=$vehicle_id AND status!='cancelled'"
);

$dates=[];

while($row=mysqli_fetch_assoc($result)){

$start=strtotime($row['start_date']);
$end=strtotime($row['end_date']);

for($i=$start;$i<=$end;$i+=86400){

$dates[]=date("Y-m-d",$i);

}

}

echo json_encode($dates);