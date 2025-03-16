<?php
include '../config/connect.inc.php';

// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// ตรวจสอบว่ามีการส่งข้อมูลโพสต์มา
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['audio_ids'])) {
    $audio_ids = $_POST['audio_ids'];

    // ตรวจสอบว่ามีการเลือกเสียง
    if (!empty($audio_ids) && is_array($audio_ids)) {
        foreach ($audio_ids as $audio_id) {
            // ลบข้อมูลจากฐานข้อมูล
            $stmt = $link->prepare("DELETE FROM songmix_upload WHERE songfile_id = ?");
            $stmt->bind_param('i', $audio_id);
            
            if ($stmt->execute()) {
                // ลบไฟล์จากเซิร์ฟเวอร์
                $stmt = $link->prepare("SELECT soundmix_id FROM songmix_upload WHERE songfile_id = ?");
                $stmt->bind_param('i', $audio_id);
                $stmt->execute();
                $stmt->bind_result($filename);
                $stmt->fetch();

                if ($filename) {
                    $file_path = 'music/' . $filename;
                    if (file_exists($file_path)) {
                        unlink($file_path);
                    }
                }
            }
        }
        // ส่งข้อมูลว่า ลบสำเร็จ
        $_SESSION['delete_sound_success'] = true;
        header('Location: ../page/EditLibrary.php'); // เปลี่ยนเส้นทางไปที่หน้า EditLibrary
        exit();
    } 
}
?>