<?php
include '../config/connect.inc.php';

// เช็คว่ามีการอัปเดตสถานะผู้ใช้
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_SESSION['member_id'])) {
    $id = $_SESSION['member_id'];
    $data = json_decode(file_get_contents('php://input'), true);
    $package_id = $data['package_id']; // รับ package_id จากข้อมูล JSON

    // ตรวจสอบว่า status_id เป็น 2 หรือไม่
    $check_status_query = "SELECT status_id FROM member WHERE member_id = '$id'";
    $status_result = mysqli_query($link, $check_status_query);
    $status_row = mysqli_fetch_assoc($status_result);
    
    if ($status_row['status_id'] == 2) {
        // ถ้า status_id เป็น 2 ให้เปลี่ยนเฉพาะ package_id
        $update_package_query = "UPDATE member SET package_id = '$package_id' WHERE member_id = '$id'";
    } else {
        // ถ้า status_id ไม่เป็น 2 ให้เปลี่ยนทั้ง status_id และ package_id
        $update_package_query = "UPDATE member SET status_id = 2, package_id = '$package_id' WHERE member_id = '$id' AND status_id = 3";
    }

    // ทำการอัปเดต
    if (mysqli_query($link, $update_package_query)) {
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => mysqli_error($link)]);
    }
    exit(); // หยุดการทำงานของสคริปต์ที่นี่
}