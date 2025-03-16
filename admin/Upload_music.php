<?php
include ('../include/header.php');
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
<?php
     if (!isset($_SESSION['member_id'])) {
        echo '<script>
                document.addEventListener("DOMContentLoaded", function() {
                    Swal.fire({
                        icon: "warning",
                        title: "กรุณาเข้าสู่ระบบก่อน",
                        confirmButtonText: "ตกลง"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = "../auth/Login.php"; // เปลี่ยนเส้นทางไปที่หน้า Login
                        }
                    });
                });
              </script>';
    exit(); // หยุดการทำงานของสคริปต์ต่อหลังจากแจ้งเตือนและเปลี่ยนหน้า
    }
    else {
        $id = $_SESSION['member_id'];
        $sql_profile = "SELECT * FROM member WHERE member_id='$id'";
        $sql_menu = "SELECT status_id FROM member WHERE member_id='$id'";
        $result_profile = mysqli_query($link, $sql_profile);
        $result_menu = mysqli_query($link, $sql_menu);
        $arr_profile = mysqli_fetch_array($result_profile);
        $arr_menu = mysqli_fetch_array($result_menu);
?>
<!-- เมนูโปรไฟล์ -->
<nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="Show_music.php">
            <img src="../Logo/LogoRSZ.png" width="50" height="50" alt="logo">
            RelaxSoundZone
        </a>

        <!-- โชวสามขีด  การแสดงผลให้เป็นในโทรศัพท์ -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- เมนู -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../page/Show_music.php">หน้าแรก</a>
                </li>
                <!-- Dropdown จัดการเสียง -->
                <?php if ($arr_menu['status_id'] == 1) { ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="manageSoundDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        การจัดการเสียง
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="manageSoundDropdown">
                        <li><a class="dropdown-item" href="../admin/Upload_music.php">อัพโหลดเสียงใหม่</a></li>
                        <li><a class="dropdown-item" href="../admin/Usage_control_music.php">เปิด/ปิดการใช้งานเสียง</a>
                        </li>
                    </ul>
                </li>
                <?php } ?>

                <!-- เมนูเพิ่มเติม -->
                <?php if ($arr_menu['status_id'] == 1 || $arr_menu['status_id'] == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../page/Library.php">คลัง</a>
                </li>
                <?php } ?>
                <!-- Dropdown โปรไฟล์ -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="profileDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo '../profile_pics/' . htmlspecialchars($arr_profile['profile_picture']); ?>"
                            alt="Profile Picture" class="profile-pic">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                        <li><a class="dropdown-item" href="../page/Edit_profile.php">โปรไฟล์ของฉัน</a></li>
                        <li><a class="dropdown-item" href="#" id="logoutLink">ออกจากระบบ</a>
                        </li>
                    </ul>
                </li>


            </ul>
        </div>
    </div>
</nav>



<?php
    }
    ?>

<?php


if($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
}


$member_id = $_SESSION['member_id'];
$sql = "SELECT status_id FROM member WHERE member_id = ?";
$stmt = $link->prepare($sql);

if (!$stmt) {
    // แสดงข้อผิดพลาดของ SQL ถ้า prepare ล้มเหลว
    die("SQL Error: " . $link->error);
}

$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['status_id'] != 1) { // ถ้าไม่ใช่ Admin
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "warning",
                    title: "สำหรับผู้ดูแลเท่านั้น!!!",
                    confirmButtonText: "ตกลง"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../page/Show_music.php"; // เปลี่ยนเส้นทางไปที่หน้า Login
                    }
                });
            });
          </script>';
    exit();
}

if(isset($_POST['submit'])){
    $category_id = $_POST['category_id']; 
    $soundmix_img = $_FILES['soundmix_img']['name'];
    $sound_file = $_FILES['sound_file']['name'];
    $user_type = $_POST['user_type']; 

    $soundmix_img_tmp_name = $_FILES['soundmix_img']['tmp_name'];
    $sound_file_tmp_name = $_FILES['sound_file']['tmp_name'];

    $soundmix_img_folder = '../image/'.$soundmix_img;
    $sound_file_folder = '../music/'.$sound_file;

    $working_status = '1';

    if(move_uploaded_file($soundmix_img_tmp_name, $soundmix_img_folder) && move_uploaded_file($sound_file_tmp_name, $sound_file_folder)){
        $sql = "INSERT INTO upload_soundmix (category_id, soundmix_img, sound_file, user_type,working_status) VALUES (?, ?, ?, ?,'$working_status')";
        $stmt = $link->prepare($sql);
        $stmt->bind_param("isss", $category_id, $soundmix_img, $sound_file, $user_type); 
        
        if($stmt->execute()) {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'อัพโหลดสำเร็จ!',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../admin/Upload_music.php'; // เปลี่ยนเส้นทางไปที่หน้า Show_music
                            }
                        });
                    });
                  </script>";
        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด: {$stmt->error}',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../admin/Upload_music.php'; // เปลี่ยนเส้นทางไปที่หน้า Upload_music
                            }
                        });
                    });
                  </script>";
        }
        } else {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'การอัพโหลดไฟล์ล้มเหลว!',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = '../admin/Upload_music.php'; // เปลี่ยนเส้นทางไปที่หน้า Upload_music
                            }
                        });
                    });
                  </script>";
        }
    }        
?>

<main>
    <div class="container">
        <div class="cardUpload">
            <h3 class="text-upload mb-4">Upload Music by Admin</h3>
            <form action="../admin/Upload_music.php" method="post" enctype="multipart/form-data">

                <!-- หมวดหมู่ -->
                <div class="form-floating mb-3">
                    <select name="category_id" class="form-select" id="category_id" required>
                        <?php 
                    $sqlcategory = "SELECT * FROM category;";
                    $resultcategory = mysqli_query($link, $sqlcategory);
                    while($arrcategory = mysqli_fetch_array($resultcategory)){
                    ?>
                        <option value="<?php echo $arrcategory['category_id']; ?>">
                            <?php echo $arrcategory['category_name']; ?></option>
                        <?php
                    }
                    ?>
                    </select>
                    <label for="category_id">เลือกหมวดหมู่</label>
                </div>

                <!-- รูปภาพ -->
                <div class="form-floating mb-3">
                    <input type="file" name="soundmix_img" id="soundmix_img" class="form-control" accept="image/*">
                    <label for="soundmix_img">อัพโหลดรูปภาพ ไฟล์ลองรับ .jpeg .png .gif</label>
                </div>

                <!-- เสียง -->
                <div class="form-floating mb-3">
                    <input type="file" name="sound_file" id="sound_file" class="form-control" required accept="audio/*">
                    <label for="sound_file">อัพโหลดเสียง ไฟล์ลองรับ MP3, AAC, WAV, AIFF</label>
                </div>

                <!-- สถานะผู้ใช้ -->
                <div class="mb-3">
                    <label for="user_type">กำหนดสถานะผู้ใช้:</label><br>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="user_type" value="Premium" required>
                        <label class="form-check-label text-status" for="user_type">Premium</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="user_type" value="normal" required>
                        <label class="form-check-label text-status" for="user_type">ผู้ใช้ธรรมดา</label>
                    </div>
                </div>

                <!-- ปุ่มอัพโหลด -->
                <div class="d-grid">
                    <button type="submit" class="btn btn-upload" name="submit"><i
                            class="fa-solid fa-cloud-arrow-up"></i> อัพโหลดเสียง</button>

                </div>
            </form>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
// เพิ่ม Event Listener ให้กับปุ่ม "ออกจากระบบ"
document.getElementById('logoutLink').addEventListener('click', function(event) {
    event.preventDefault(); // หยุดการทำงานปกติของลิงก์

    Swal.fire({
        title: 'คุณแน่ใจหรือไม่?',
        text: 'คุณต้องการออกจากระบบใช่หรือไม่?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'ใช่, ออกจากระบบ',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            // ถ้าผู้ใช้ยืนยัน ให้นำไปยังหน้า Logout.php
            window.location.href = '../page/Logout.php?logout=success';
        }
    });
});
</script>
</body>

</html>