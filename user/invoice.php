<?php
session_start();
include "../config/db.php";
require('../fpdf/fpdf.php');

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("Invalid request");
}

$booking_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

// Fetch booking details
$stmt = mysqli_prepare($conn, "
    SELECT b.id, b.start_date, b.end_date, b.total_price,
           v.name AS vehicle_name,
           v.brand,
           u.name AS user_name
    FROM bookings b
    JOIN vehicles v ON b.vehicle_id = v.id
    JOIN users u ON b.user_id = u.id
    WHERE b.id=? AND b.user_id=? AND b.status='approved'
");

mysqli_stmt_bind_param($stmt, "ii", $booking_id, $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($result);

if (!$data) {
    die("Invoice not available.");
}

// Create PDF
$pdf = new FPDF();
$pdf->AddPage();

$pdf->SetFont('Arial','B',16);
$pdf->Cell(0,10,'Vehicle Rental Invoice',0,1,'C');

$pdf->Ln(10);

$pdf->SetFont('Arial','',12);

$pdf->Cell(50,10,'Invoice ID:',0,0);
$pdf->Cell(100,10,$data['id'],0,1);

$pdf->Cell(50,10,'Customer Name:',0,0);
$pdf->Cell(100,10,$data['user_name'],0,1);

$pdf->Cell(50,10,'Vehicle:',0,0);
$pdf->Cell(100,10,$data['vehicle_name']." (".$data['brand'].")",0,1);

$pdf->Cell(50,10,'Start Date:',0,0);
$pdf->Cell(100,10,$data['start_date'],0,1);

$pdf->Cell(50,10,'End Date:',0,0);
$pdf->Cell(100,10,$data['end_date'],0,1);

$pdf->Cell(50,10,'Total Amount:',0,0);
$pdf->Cell(100,10,"INR ".$data['total_price'],0,1);

$pdf->Ln(10);

$pdf->Cell(0,10,'Thank you for choosing our service!',0,1,'C');

$pdf->Output("D","Invoice_".$data['id'].".pdf");
exit();
?>