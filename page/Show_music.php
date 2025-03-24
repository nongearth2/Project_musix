<?php include ('../include/header.php'); ?>

<center>
    <?php
   
    if (!isset($_SESSION['member_id'])) {
        echo '<nav class="navbar navbar-expand-lg menu navbar-background fixed-top">';
        echo '    <div class="container-fluid">';
        echo '        <a class="navbar-brand" href="Show_music.php">';
        echo '            <img src="../Logo/LogoRSZ.png" width="50" height="50" alt="logo"> RelaxSoundZone';
        echo '        </a>';
        echo '        <!-- ปุ่มเบอร์เกอร์ สำหรับหน้าจอมือถือ -->';
        echo '        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"';
        echo '            aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation" id="navbarToggleBtn">';
        echo '            <i class="fas fa-bars"></i> <!-- ไอคอนสามขีด (เปิดเมนู) -->';
        echo '        </button>';
        echo '        <div class="collapse navbar-collapse" id="navbarContent">';
        echo '            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">';
        echo '                <li class="nav-item">';
        echo '                    <a class="nav-link" href="../auth/Login.php" title="เข้าสู่ระบบ">🙎‍♂️ เข้าสู่ระบบ</a>';
        echo '                </li>';
        echo '                <li class="nav-item">';
        echo '                    <a class="nav-link" href="../auth/Register.php" title="สมัครสมาชิก">📋 สมัครสมาชิก</a>';
        echo '                </li>';
        echo '            </ul>';
        echo '        </div>';
        echo '    </div>';
        echo '</nav>';
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
</center>

<!-- เมนูโปรไฟล์ -->
<nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
    <div class="container-fluid">
        <a class="navbar-brand" href="Show_music.php">
            <img src="../Logo/LogoRSZ.png" width="50" height="50" alt="logo">
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
                        <li><a class="dropdown-item" href="Edit_profile.php">โปรไฟล์ของฉัน</a></li>
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

<main>
    <center>

        <!-- แสดงปุ่มหมวดหมู่ -->
        <label for="categoryButtons" class="form-label label-category ">หมวดหมู่</label>
        <div class="category-buttons" id="categoryButtons">
            <!-- ปุ่มหมวดหมู่จะถูกเพิ่มที่นี่โดย JavaScript -->
        </div>

        <div id="audioControls"></div>
        <div id="scrollToTopButton" class="scroll-to-top-btn">
            <button onclick="scrollToTop()">
                <i class="fas fa-arrow-up"></i> <!-- ไอคอนลูกศรขึ้น -->
            </button>
        </div>
    </center>
</main>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="../scripts/Show_music.js"></script>
<?php
    // ตรวจสอบการเชื่อมต่อฐานข้อมูล
    if (!$link) {
        die("Connection failed: " . mysqli_connect_error());
    }
    
        if (isset($_GET['register']) && $_GET['register'] == 'success') {
            echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
            echo "<script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({
                            icon: 'success',
                            title: 'สมัครสมาชิกเรียบร้อยแล้ว!',
                            confirmButtonText: 'ตกลง'
                        });
                    });
                </script>";
        }


    if (isset($_GET['login']) && $_GET['login'] == 'success') {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'เข้าสู่ระบบเรียบร้อยแล้ว!',
                        confirmButtonText: 'ตกลง'
                    });
                });
            </script>";

    }
    
    if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
                document.addEventListener('DOMContentLoaded', function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'ออกจากระบบเรียบร้อยแล้ว!',
                        confirmButtonText: 'ตกลง'
                    });
                });
              </script>";
    }

    // ตรวจสอบสถานะผู้ใช้
    if (isset($_SESSION['member_id'])) {
        $id = $_SESSION['member_id']; 
        $status_query = "SELECT status_id FROM member WHERE member_id='$id'";
        $status_result = mysqli_query($link, $status_query);
        $status_row = mysqli_fetch_assoc($status_result);
        $status_id = $status_row['status_id'];
    } else {
        // ดัก ถ้าไม่มีการล็อกอิน ให้กำหนดสถานะเป็นผู้ใช้ปกติ (status_id = 3)
        $status_id = 3;
    }

    // ดึงข้อมูลหมวดหมู่จากฐานข้อมูล
    $select_categories = $link->query("SELECT category_id, category_name FROM category");
    if (!$select_categories) {
        die("Error executing query: " . mysqli_error($link));
    }

    $categories = [];
    while ($category = $select_categories->fetch_assoc()) {
        $categories[$category['category_id']] = $category['category_name'];
    }

    // ดึงข้อมูลเพลงจากฐานข้อมูลตามสิทธิ์ผู้ใช้
    if ($status_id == 1) {
        // Admin เข้าถึงทุกไฟล์
        $select_songs = $link->query("SELECT * FROM upload_soundmix ORDER BY category_id");
    } elseif ($status_id == 2) {
        // Premium user เข้าถึงไฟล์ของ premium และ normal
        $select_songs = $link->query("SELECT * FROM upload_soundmix WHERE user_type IN ('premium', 'normal') ORDER BY category_id");
    } else{
        // ผู้ใช้ปกติ เข้าถึงไฟล์เฉพาะของผู้ใช้ปกติ
        $select_songs = $link->query("SELECT * FROM upload_soundmix WHERE user_type = 'normal' ORDER BY category_id");
    }

    if (!$select_songs) {
        die("Error executing query: " . mysqli_error($link));
    }

    $songs = [];
    while ($fetch_song = $select_songs->fetch_assoc()) {
        $category = $fetch_song['category_id'];
        if (!isset($songs[$category])) {
            $songs[$category] = [];
        }
        $songs[$category][] = [
            'id' => '../audio/' . $fetch_song['soundmix_id'],
            'src' => '../music/' . $fetch_song['sound_file'],
            'image' => '../image/' . $fetch_song['soundmix_img'],
            'working_status' => $fetch_song['working_status']
        ];
    }
    ?>

<script>
const audioFiles = <?php echo json_encode($songs); ?>;
const categories = <?php echo json_encode($categories); ?>;
</script>
</body>

</html>
<?php
include('../include/footer.php');
?>