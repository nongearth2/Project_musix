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
    } else {
        // ดึงข้อมูลจาก session
        $id = $_SESSION['member_id'];
    
        // ดึงข้อมูลสมาชิกจากฐานข้อมูล
        $sql_profile = "SELECT * FROM member WHERE member_id = '$id'";
        $sql_menu = "SELECT status_id FROM member WHERE member_id = '$id'";
        $result_profile = mysqli_query($link, $sql_profile);
        $result_menu = mysqli_query($link, $sql_menu);
    
        if ($result_profile && $result_menu) {
            $arr_profile = mysqli_fetch_array($result_profile);
            $arr_menu = mysqli_fetch_array($result_menu);
    
            // หากสถานะเป็น 3 (ผู้ใช้ปกติ)
            if ($arr_menu['status_id'] == 3) {
                echo '<script type="text/javascript">
                        Swal.fire({
                            icon: "info",
                            title: "คุณไม่มีสิทธิ์บันทึกเสียง",
                            text: "กรุณาสมัคร Premium ก่อน",
                            confirmButtonText: "สมัคร Premium",
                            showCancelButton: true,
                            cancelButtonText: "ยกเลิก"
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location.href = "../page/Edit_profile.php"; // เปลี่ยนเส้นทางไปที่หน้า Upgrade Premium
                            }
                        });
                      </script>';
                exit(); // หยุดการทำงานของสคริปต์
            }
        } else {
            // กรณีเกิดข้อผิดพลาดในการเชื่อมต่อฐานข้อมูล
            echo '<script type="text/javascript">
                    Swal.fire({
                        icon: "error",
                        title: "เกิดข้อผิดพลาด",
                        text: "ไม่สามารถโหลดข้อมูลสมาชิกได้",
                        confirmButtonText: "ตกลง"
                    });
                  </script>';
            exit();
        }
    }
    ?>
</center>
<!-- เมนูโปรไฟล์ -->
<nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="Show_music.php">
            <img src="../Logo/LogoRSZ.png" width="40" height="40" alt="logo">
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

                <!-- เมนูเพิ่มเติม -->
                <?php if ($arr_menu['status_id'] == 1 || $arr_menu['status_id'] == 2) { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../page/Library.php">คลัง</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../page/EditLibrary.php">เพิ่มลิสต์ใหม่</a>
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
                        <li><a class="dropdown-item" id="logoutLink" href="../page/Logout.php">ออกจากระบบ</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>

</head>

<!-- คำสั่ง php -->
<?php

if ($link->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $link->connect_error);
}



if (isset($_POST['submit'])) {
    // ตรวจสอบว่ามีข้อมูล selected_files และ list_id ถูกส่งมาหรือไม่
    if (isset($_POST['selected_files']) && is_array($_POST['selected_files']) && isset($_POST['list_id'])) {
        $selected_files = $_POST['selected_files'];
        $list_id = $_POST['list_id'];
        $member_id = $_SESSION['member_id']; // ตรวจสอบว่า member_id ถูกเก็บในเซสชัน

        // เตรียมการสร้าง Placeholders
        $placeholders = implode(',', array_fill(0, count($selected_files), '(?, ?, ?)'));

        // SQL Query
        $sql = "INSERT INTO songmix_upload (soundmix_id, List_id, member_id) VALUES " . $placeholders;

        // เตรียมคำสั่ง SQL
        $stmt = $link->prepare($sql);

        // ตรวจสอบข้อผิดพลาดในการเตรียมคำสั่ง
        if (!$stmt) {
            die("การเตรียมคำสั่ง SQL ล้มเหลว: " . $link->error);
        }

        // เตรียมค่าพารามิเตอร์
        $params = [];
        foreach ($selected_files as $file_id) {
            $params[] = $file_id;
            $params[] = $list_id;
            $params[] = $member_id;
        }

        // ผูกพารามิเตอร์
        $stmt->bind_param(str_repeat('iis', count($selected_files)), ...$params);

        // ตรวจสอบการ Execute
        if ($stmt->execute()) {
            echo "<script>
                    Swal.fire({
                        icon: 'success',
                        title: 'การอัพโหลดไฟล์สำเร็จ!',
                        confirmButtonText: 'ตกลง',
                        willClose: () => {
                            window.location.href='Library.php';
                        }
                    });
                  </script>";
        } else {
            echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'เกิดข้อผิดพลาด: {$stmt->error}',
                        confirmButtonText: 'ตกลง',
                        willClose: () => {
                            window.location.href='Library.php';
                        }
                    });
                  </script>";
        }
    } else {
        echo "<script>
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาเลือกไฟล์และลิสต์!',
                    confirmButtonText: 'ตกลง',
                    willClose: () => {
                        window.location.href='../page/Library.php';
                    }
                });
              </script>";
    }
}

// ดึงข้อมูลไฟล์เสียงจากฐานข้อมูล upload_soundmix ที่เปิดใช้งาน (working_status = 1)
$sql = "SELECT * FROM upload_soundmix WHERE working_status = 1 ORDER BY category_id" ;
$select_songs = $link->query($sql);

$songs = [];
while ($fetch_song = $select_songs->fetch_assoc()) {
    $songs[] = [
        'id' => $fetch_song['soundmix_id'],
        'src' => '../music/' . $fetch_song['sound_file'],
        'image' => '../image/' . $fetch_song['soundmix_img'],
        'working_status' => $fetch_song['working_status']
    ];
}

$id=$_SESSION['member_id'];
// ดึงลิสต์จากฐานข้อมูล list_category
$sql = "SELECT * FROM list_category WHERE member_id='$id'";
$select_lists = $link->query($sql);

$lists = [];
while ($fetch_list = $select_lists->fetch_assoc()) {
    $lists[] = [
        'id' => $fetch_list['List_id'],
        'name' => $fetch_list['List_name']
    ];
}
?>

<!-- -------- php -->
<main>

    <body>
        <div class="container" style="margin-bottom: 100px;">
            <div class="cardMix">
                <h3 class="textMix-header mb-4 text-center">มิกส์เสียงลงคลัง</h3>

                <form action="../page/Soundmix.php" method="post">
                    <!-- เลือกลิสต์ -->
                    <div class="mb-3">
                        <label class="LabelMix" for="list_id">เลือกลิสต์:</label>
                        <?php if (!empty($lists)) { ?>
                        <select name="list_id" id="list_id" class="form-select" required>
                            <?php foreach ($lists as $list) { ?>
                            <option value="<?php echo htmlspecialchars($list['id']); ?>">
                                <?php echo htmlspecialchars($list['name']); ?></option>
                            <?php } ?>
                        </select>
                        <?php } else { ?>
                        <p class="text-danger">เพิ่มลิสต์หรือสร้างลิสต์ก่อน</p>
                        <?php } ?>
                    </div>

                    <!-- แสดงไฟล์เสียง -->
                    <div class="soundMix-list">
                        <?php foreach ($songs as $song) { ?>
                        <div class="soundMix-item">
                            <label class="star-container">
                                <input type="checkbox" name="selected_files[]"
                                    value="<?php echo htmlspecialchars($song['id']); ?>" class="form-check-input" />
                                <span class="star" title="เลือกเสียง"></span>
                            </label>
                            <img src="<?php echo htmlspecialchars($song['image']); ?>" alt="Cover Image"
                                class="soundMix-image" data-src="<?php echo htmlspecialchars($song['src']); ?>">
                            <audio class="hidden" data-loop="<?php echo htmlspecialchars($song['loop']); ?>">
                                <source src="<?php echo htmlspecialchars($song['src']); ?>" type="audio/mpeg">
                                Your browser does not support the audio element.
                            </audio>
                            <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1"
                                title="Volume">
                        </div>
                        <?php } ?>
                    </div>

                    <!-- ปุ่มรวมเสียงที่เลือก -->
                    <div class="d-gridMix mt-4">
                        <button type="submit" class="btn-SoundMix btn-disabled" name="submit" id="submitButton"
                            disabled>
                            <i class="fa-solid fa-plus"></i> รวมเสียงที่เลือก
                        </button>
                    </div>
                </form>
                <!-- ปุ่มที่จะแสดงหลังเลื่อน -->

            </div>
            <div id="scrollToTopButton" class="scroll-to-top-btn">
                <button onclick="scrollToTop()">
                    <i class="fas fa-arrow-up"></i> <!-- ไอคอนลูกศรขึ้น -->
                </button>
            </div>
        </div>


</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../scripts/Soundmix.js "></script>

</body>

</html>
<?php
include('../include/footer.php');
?>