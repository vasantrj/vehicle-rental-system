<?php
session_start();
include "../config/db.php";
require "../mail_helper.php";

if (!isset($_SESSION['role']) || $_SESSION['role'] !== "user") {
    header("Location: ../auth/login.php");
    exit();
}

if (!isset($_POST['booking_id'])) {
    die("Invalid request.");
}

$booking_id = intval($_POST['booking_id']);

// Simulate 80% success
$is_success = (rand(1,10) <= 8);

if ($is_success) {

    $stmt = mysqli_prepare($conn, "
        SELECT b.total_price, b.start_date, b.end_date,
               u.email, u.name,
               v.name AS vehicle_name
        FROM bookings b
        JOIN users u ON b.user_id = u.id
        JOIN vehicles v ON b.vehicle_id = v.id
        WHERE b.id=?
    ");

    mysqli_stmt_bind_param($stmt, "i", $booking_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $data = mysqli_fetch_assoc($result);

    if ($data) {

        $update = mysqli_prepare($conn,
            "UPDATE bookings 
             SET status='approved', payment_status='paid' 
             WHERE id=?"
        );
        mysqli_stmt_bind_param($update, "i", $booking_id);
        mysqli_stmt_execute($update);

        sendBookingEmail(
            $data['email'],
            $data['name'],
            $data['vehicle_name'],
            $data['start_date'],
            $data['end_date'],
            $data['total_price']
        );
    }

    header("Location: payment_result.php?status=success");
    exit();

} else {

    $update = mysqli_prepare($conn,
        "UPDATE bookings SET payment_status='failed' WHERE id=?"
    );
    mysqli_stmt_bind_param($update, "i", $booking_id);
    mysqli_stmt_execute($update);

    header("Location: payment_result.php?status=failed");
    exit();
}