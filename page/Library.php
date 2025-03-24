<?php include ('../include/header.php'); ?>
<center>

    <?php
        if (!isset($_SESSION['member_id'])) {
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
            exit(); // หยุดการทำงานของสคริปต์ต่อหลังจากแจ้งเตือนและเปลี่ยนหน้า
        } 
        else {
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
                                title: "คุณไม่มีสิทธิ์เข้าใช้คลัง",
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
    <!-- เมนูทั้งหมด-->
    <nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="Show_music.php">
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
                    <!-- Dropdown จัดการเสียง -->
                    <?php if ($arr_menu['status_id'] == 1 || $arr_menu['status_id'] == 2) { ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="manageSoundDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            จัดการคลังเสียง
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="manageSoundDropdown">
                            <li><a class="dropdown-item" href="../page/EditLibrary.php">แก้ไขลิสต์เสียง</a></li>
                            <li><a class="dropdown-item" href="../page/Soundmix.php">มิกส์เสียง</a></li>
                        </ul>
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
                            <li><a class="dropdown-item" id="logoutLink" href="Logout.php">ออกจากระบบ</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <main>
        <h1>คลัง</h1>

        <!-- แสดงปุ่มหมวดหมู่ -->
        <div class="container">
            <label for="categoryButtons" class="form-label label-category">หมวดหมู่</label>
            <div class="category-buttons" id="categoryButtons"></div>
        </div>


        <div class="category-buttons" id="categoryButtons"></div>
        </div>
        <div id="audioControls"></div>
        <center>
            <div class="custom-flex-container">
                <button id="playListBtn" class="btn-startMusic"><i class="fa-solid fa-play"></i>
                    เริ่มเล่นเสียงในลิสต์</button>
                <button id="stopListBtn" class="btn-stopMusic"><i class="fa-solid fa-pause"></i>
                    หยุดเล่นเสียงในลิสต์</button>
                <div class="d-flex align-items-center">
                    <label class="slider">
                        <input type="range" class="level" id="volumeControl" min="0" max="100" step="1" value="50" />
                        <i class="fa-solid fa-volume-high icon-volum" id="volumeIcon"></i>
                    </label>
                    <span id="volumePercentage" class="ms-2">50%</span>
                </div>
            </div>


        </center>
    </main>
    <div id="scrollToTopButton" class="scroll-to-top-btn">
        <button onclick="scrollToTop()">
            <i class="fas fa-arrow-up"></i> <!-- ไอคอนลูกศรขึ้น -->
        </button>
    </div>

    <?php
// ตรวจสอบการเชื่อมต่อฐานข้อมูล
if (!$link) {
    die("Connection failed: " . mysqli_connect_error());
}

// ดึงข้อมูลหมวดหมู่จากฐานข้อมูล
$id = $_SESSION['member_id'];
$select_List = $link->query("SELECT List_id, List_name FROM list_category WHERE member_id='$id' ");
if (!$select_List) {
    die("Error executing query: " . mysqli_error($link));
}

$categories = [];
while ($row = $select_List->fetch_assoc()) {
    $categories[$row['List_id']] = $row['List_name'];
}

// ดึงข้อมูลเพลงจากฐานข้อมูล
$select_somgmix = $link->query("SELECT songmix_upload.songfile_id, songmix_upload.List_id, songmix_upload.soundmix_id, songmix_upload.member_id, upload_soundmix.soundmix_id, upload_soundmix.sound_file, upload_soundmix.soundmix_img, upload_soundmix.working_status 
                                FROM songmix_upload 
                                JOIN upload_soundmix ON songmix_upload.soundmix_id = upload_soundmix.soundmix_id 
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
        'id' => $fetch_song['soundmix_id'],
        'src' => '../music/'. $fetch_song['sound_file'],
        'image' => '../image/'. $fetch_song['soundmix_img'],
        'working_status' => $fetch_song['working_status']
    ];
}
?>
    <script>
    // ส่งข้อมูลหมวดหมู่และเพลงไปยัง JavaScript
    const audioFiles = <?php echo json_encode($songs); ?>;
    const categories = <?php echo json_encode($categories); ?>;
    </script>

</center>
</body>
<script src="../scripts/Library.js"></script>

</html>
<?php
include('../include/footer.php');
?>