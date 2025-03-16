<?php
include '../config/connect.inc.php';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // ลบลิสต์ที่เลือก
    if (isset($_POST['action']) && $_POST['action'] === 'delete' && isset($_POST['list_id'])) {
        $list_id = intval($_POST['list_id']);
        
        // เริ่ม transaction
        mysqli_begin_transaction($link);
        
        try {
            // ลบไฟล์เสียงที่อยู่ในลิสต์นี้
            $sql_delete_songs = "DELETE FROM songmix_upload WHERE List_id = $list_id";
            if ($link->query($sql_delete_songs) === FALSE) {
                throw new Exception('เกิดข้อผิดพลาดในการลบไฟล์เสียง: ' . $link->error);
            }

            // ลบลิสต์ในตาราง list_category
            $sql_delete_list = "DELETE FROM list_category WHERE List_id = $list_id";
            if ($link->query($sql_delete_list) === FALSE) {
                throw new Exception('เกิดข้อผิดพลาดในการลบลิสต์: ' . $link->error);
            }

            // Commit transaction
            mysqli_commit($link);

            // ส่งข้อมูลว่า ลบสำเร็จ
            $_SESSION['delete_success'] = true;
            header('Location: ../page/EditLibrary.php');
            exit();
        } catch (Exception $e) {
            // Rollback transaction
            mysqli_rollback($link);
            echo $e->getMessage();
        }
    }
    // เมื่อดำเนินการเสร็จแล้วให้รีเฟรชหน้า
    header('Location: ../page/EditLibrary.php');
    exit();
}
?>