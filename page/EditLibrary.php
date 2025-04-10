<?php
include ('../include/header.php');
?>
<center>
    <?php
if (!isset($_SESSION['member_id'])) {
    // หากไม่ได้เข้าสู่ระบบ
    echo '<script type="text/javascript">
            Swal.fire({
                icon: "warning",
                title: "กรุณาเข้าสู่ระบบก่อน",
                confirmButtonText: "ตกลง"
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = "../auth/Login.php"; // เปลี่ยนเส้นทางไปที่หน้า Login
                }
            });
          </script>';
    exit(); // หยุดการทำงานของสคริปต์
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
                        title: "คุณไม่มีสิทธิ์เข้าใช้การแก้ไขคลัง",
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

    <!-- เมนูโปรไฟล์ -->
    <nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../page/Show_music.php">
                <img src="../Logo/LogoRSZ.png" width="40" height="40" alt="logo">
                RelaxSoundZone
            </a>

            <!-- โชวสามขีด  การแสดงผลให้เป็นในโทรศัพท์ -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation" id="navbarToggleBtn">
                <i class="fas fa-bars"></i> <!-- ไอคอนสามขีด (เปิดเมนู) -->
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
                        <a class="nav-link" href="../page/Soundmix.php">เพิ่มเสียงใหม่</a>
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
                            <li><a class="dropdown-item" href="Edit_profile.php">โปรไฟล์ของฉัน</a></li>
                            <li><a class="dropdown-item" id="logoutLink" href="../Logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <?php


// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}
if (isset($_SESSION['alert_message'])) {
    echo "<script>
        Swal.fire({
            title: 'สำเร็จ!',
            text: '" . $_SESSION['alert_message'] . "',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        });
    </script>";

    // ลบ session หลังจากแสดงการแจ้งเตือน
    unset($_SESSION['alert_message']);
}



if (isset($_SESSION['delete_success'])) {
    echo "<script>
        Swal.fire({
            title: 'ลบลิสต์สำเร็จ!',
            text: 'ลิสต์ของคุณถูกลบเรียบร้อยแล้ว',
            icon: 'success',
            confirmButtonText: 'ตกลง'
        });
    </script>";

    // ลบ session หลังจากแสดงการแจ้งเตือน
    unset($_SESSION['delete_success']);
}
// ตรวจสอบว่าผู้ใช้ได้ล็อกอินอยู่หรือไม่
if (!isset($_SESSION['member_id'])) {
    echo '<script>
        alert("คุณต้องเข้าสู่ระบบก่อน");
        window.location.href = "login.php";
    </script>';
    exit;
}

$id = $_SESSION['member_id'];

// ดึงข้อมูลหมวดหมู่จากฐานข้อมูลสำหรับผู้ใช้ที่ล็อกอิน
$select_List = $link->query("SELECT List_id, List_name FROM list_category WHERE member_id='$id'");
if (!$select_List) {
    die("Error executing query: " . mysqli_error($link));
}

$categories = [];
while ($row = $select_List->fetch_assoc()) {
    $categories[$row['List_id']] = $row['List_name'];
}


// ดึงข้อมูลเพลงจากฐานข้อมูลสำหรับผู้ใช้ที่ล็อกอิน
$select_somgmix = $link->query("SELECT songmix_upload.songfile_id, songmix_upload.List_id, songmix_upload.soundmix_id, songmix_upload.member_id, 
                                        upload_soundmix.soundmix_id, upload_soundmix.sound_file, upload_soundmix.soundmix_img, upload_soundmix.working_status 
                                FROM songmix_upload 
                                JOIN upload_soundmix ON songmix_upload.soundmix_id = upload_soundmix.soundmix_id 
                                WHERE songmix_upload.member_id = '$id' 
                                ORDER BY songmix_upload.List_id");

if (!$select_somgmix) {
    die("Error executing query: " . mysqli_error($link));
}

$songs = [];
while ($fetch_song = $select_somgmix->fetch_assoc()) {
    $category = $fetch_song['List_id'];  // จัดกลุ่มตาม List_id
    if (!isset($songs[$category])) {
        $songs[$category] = [];
    }
    $songs[$category][] = [
        'id' => $fetch_song['songfile_id'],
        'src' => '../music/' . $fetch_song['sound_file'], // URL ของไฟล์เสียง
        'img' => '../image/' . $fetch_song['soundmix_img'], // URL ของภาพ
        'working_status' => $fetch_song['working_status']
    ];
}
?>


    <main>
        <h1>จัดการคลัง</h1>

        <!-- แสดงปุ่มหมวดหมู่ -->
        <div class="container">
            <div class="category-buttons" id="categoryButtons"></div>
            <button data-bs-toggle="modal" data-bs-target="#manageListModal">
                แก้ไขชื่อลิสต์/เพิ่มลิสต์/ลบลิสต์
            </button>
        </div>

        <div id="audioControls">
            <form method="post" action="../process/delete_soundLibrary.php">
                <?php foreach ($categories as $categoryId => $categoryName): ?>
                <h2><?php echo htmlspecialchars($categoryName); ?></h2>
                <?php if (isset($songs[$categoryId]) && !empty($songs[$categoryId])): ?>
                <?php foreach ($songs[$categoryId] as $file): ?>
                <div class="sound-item">
                    <button type="button" class="play-button" data-src="<?php echo htmlspecialchars($file['src']); ?>"
                        data-status="<?php echo $file['working_status']; ?>">
                        <img src="<?php echo htmlspecialchars($file['img']); ?>" alt="Play"
                            style="width: 50px; height: 50px; object-fit: cover;">
                    </button>
                    <label class="text-CheckDel">
                        <input class="custom-checkbox" type="checkbox" name="audio_ids[]" style="display: inline-block;"
                            value="<?php echo htmlspecialchars($file['id']); ?>">

                        เลือก
                    </label>
                    <input type="range" class="volume-slider" min="0" max="1" step="0.01" value="1">
                </div>
                <?php endforeach; ?>
                <?php else: ?>
                <p>ไม่มีไฟล์เสียงในหมวดหมู่นี้ กรุณาเพิ่มเสียง</p>
                <?php endif; ?>
                <?php endforeach; ?>

                <button type="submit" class="btn btn-danger btn-disabled" id="deleteButton"
                    style=" margin-bottom: 100px;" disabled>
                    <i class="fa-solid fa-trash" style="color: #000000; "></i> ลบเสียงเพลง
                </button>

            </form>
            <div id="scrollToTopButton" class="scroll-to-top-btn">
                <button onclick="scrollToTop()">
                    <i class="fas fa-arrow-up"></i> <!-- ไอคอนลูกศรขึ้น -->
                </button>
            </div>
        </div>
    </main>
    <?php
// แสดง SweetAlert ถ้ามีการแจ้งเตือนจากการลบเสียง
if (isset($_SESSION['delete_sound_success'])) {
    echo "<script>
            Swal.fire({
                title: 'สำเร็จ!',
                text: 'ลบเสียงเรียบร้อยแล้ว',
                icon: 'success',
                confirmButtonText: 'ตกลง'
            });
          </script>";
    unset($_SESSION['delete_sound_success']); // ลบค่าเซสชันหลังแสดงแล้ว
}
?>


    <!-- ป็อปอัปจัดการลิสต์ -->
    <div class="modal fade" id="manageListModal" tabindex="-1" aria-labelledby="manageListModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="manageListModalLabel">จัดการลิสต์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- ฟอร์มสำหรับแก้ไข/ลบ/เพิ่มลิสต์ -->
                    แก้ไขชื่อรายการ
                    <form id="manageListForm" action="../process/manage_list.php" method="post">
                        <!-- แสดงลิสต์ที่มีอยู่ -->
                        <?php foreach ($categories as $list_id => $list_name): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <input type="text" name="list_names[<?php echo $list_id; ?>]"
                                value="<?php echo htmlspecialchars($list_name); ?>" class="form-control" />

                            <!-- ปุ่มลบลิสต์ (แบบไม่มีฟอร์มซ้อน) -->
                            <button type="button" class="btn btn-danger btn-sm" data-bs-target="confirmDeleteModal"
                                onclick="deleteList(<?php echo $list_id; ?>)" title="ลบลิสต์">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                        <?php endforeach; ?>

                        <!-- เพิ่มลิสต์ใหม่ -->
                        <div class="form-group mt-4">
                            <label for="new_list_name">เพิ่มรายการใหม่:</label>
                            <input type="text" id="new_list_name" name="new_list_name" class="form-control"
                                placeholder="กรอกชื่อลิสต์ใหม่">
                        </div>

                        <!-- ปุ่มบันทึกการเปลี่ยนแปลง -->
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ป็อปอัพ สำหรับยืนยันการลบลิสต์ -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" role="dialog"
        aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmDeleteModalLabel">ยืนยันการลบลิสต์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    คุณแน่ใจหรือว่าต้องการลบลิสต์นี้?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-danger" id="confirmDeleteBtn">ลบลิสต์</button>
                </div>
            </div>
        </div>
    </div>
    <script src="../scripts/EditLibrary.js"></script>

</center>

</body>

</html>
<?php
include('../include/footer.php');
?>