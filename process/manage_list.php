<?php
include '../config/connect.inc.php';

// ตรวจสอบว่าผู้ใช้ได้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['member_id'])) {
    die("Please log in to manage your lists.");
}

$member_id = $_SESSION['member_id'];

// ตรวจสอบการแก้ไขหรือเพิ่มลิสต์
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = ''; // เพื่อเก็บสถานะการทำงาน

    // ตรวจสอบลิสต์ที่ต้องการแก้ไข
    if (isset($_POST['list_names'])) {
        foreach ($_POST['list_names'] as $list_id => $list_name) {
            // อัปเดตชื่อของลิสต์
            if (!empty($list_name)) {
                $list_name = mysqli_real_escape_string($link, $list_name);
                $query = "UPDATE list_category SET List_name='$list_name' WHERE List_id='$list_id' AND member_id='$member_id'";
                mysqli_query($link, $query);
                $action = 'edit'; // ตั้งค่าเป็นการแก้ไข
            }
        }
    }

    // ตรวจสอบการเพิ่มลิสต์ใหม่
    if (!empty($_POST['new_list_name'])) {
        $new_list_name = mysqli_real_escape_string($link, $_POST['new_list_name']);
        $query = "INSERT INTO list_category (List_name, member_id) VALUES ('$new_list_name', '$member_id')";
        mysqli_query($link, $query);
        $action = 'add'; // ตั้งค่าเป็นการเพิ่ม
    }

    // ส่งค่า action ไปยังหน้า Library.php
    if ($action === 'edit') {
        $_SESSION['alert_message'] = 'เปลี่ยนชื่อลิสต์เรียบร้อยแล้ว';
    } elseif ($action === 'add') {
        $_SESSION['alert_message'] = 'เพิ่มลิสต์สำเร็จแล้ว';
    }

    // เมื่อดำเนินการเสร็จแล้วให้รีเฟรชหน้า
    header('Location: ../page/EditLibrary.php');
    exit();
}
?>