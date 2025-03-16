<?php
include '../config/connect.inc.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['member_id'])) {
    $id = $_SESSION['member_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $new_status = $data['status_id'];

    // อัปเดตสถานะผู้ใช้
    $update_status_query = "UPDATE member SET status_id = '$new_status' WHERE member_id = '$id'";
    if (mysqli_query($link, $update_status_query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($link)]);
    }
    exit();
}