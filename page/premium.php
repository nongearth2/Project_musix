<?php
include '../include/header.php';
?>

<body>
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
                            <li><a class="dropdown-item"
                                    href="../admin/Usage_control_music.php">เปิด/ปิดการใช้งานเสียง</a>
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


<body>
<section class="premium-section">

    <div class="title">สมัครแพ็กเกจ Premium</div>
    <div class="subtitle">เลือกแพ็กเกจที่เหมาะกับคุณ และฟังเสียงแบบไร้ข้อจำกัด</div>
    <div class="packages">
        <div class="package">
            <h2>Premium 30 วิ</h2>
            <ul>
                <li><i class="fas fa-check"></i> มีเสียงให้เลือกฟังมากกว่า</li>
                <li><i class="fas fa-check"></i> มีสิทธิสร้างคลัง</li>
                <li><i class="fas fa-check"></i> สามารถมิกส์เสียงได้</li>
            </ul>
            <a href="#" class="btnPremium" data-package-id="1">สมัครเลย</a>
        </div>
        <div class="package">
            <h2>Premium 60 วิ</h2>
            <ul>
                <li><i class="fas fa-check"></i> มีเสียงให้เลือกฟังมากกว่า</li>
                <li><i class="fas fa-check"></i> มีสิทธิสร้างคลัง</li>
                <li><i class="fas fa-check"></i> สามารถมิกส์เสียงได้</li>
            </ul>
            <a href="#" class="btnPremium" data-package-id="2">สมัครเลย</a>
        </div>
        <div class="package">
            <h2>Premium 120 วิ</h2>
            <ul>
                <li><i class="fas fa-check"></i> มีเสียงให้เลือกฟังมากกว่า</li>
                <li><i class="fas fa-check"></i> มีสิทธิสร้างคลัง</li>
                <li><i class="fas fa-check"></i> สามารถมิกส์เสียงได้</li>
            </ul>
            <a href="#" class="btnPremium" data-package-id="3">สมัครเลย</a>
        </div>
    </div>

</section>
<?php
include '../include/footer.php';
?>
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
document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".btnPremium");
    let selectedPackage = null;

    buttons.forEach(button => {
        button.addEventListener("click", function (event) {
            event.preventDefault();
            selectedPackage = this.getAttribute("data-package-id");

            // ตรวจสอบการเข้าสู่ระบบก่อน
            <?php if (!isset($_SESSION['member_id'])) { ?>
                Swal.fire({
                    icon: 'warning',
                    title: 'กรุณาล็อกอินก่อนสมัครสมาชิก',
                    confirmButtonText: 'ตกลง'
                }).then(() => {
                    window.location.href = '../auth/login.php'; // เปลี่ยนไปที่หน้า login
                });
            <?php } else { ?>
                // ถ้ามีการเข้าสู่ระบบแล้ว ให้แสดง QR Code
                Swal.fire({
                    title: "สแกน QR Code เพื่อชำระเงิน",
                    text: "กรุณาชำระเงินให้เสร็จสิ้นก่อนดำเนินการต่อ",
                    imageUrl: "../image/qr_code.png", // เปลี่ยนเป็น URL ของ QR Code จริง
                    imageWidth: 450,
                    imageHeight: 500,
                    confirmButtonText: "ชำระเงินสำเร็จ"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // เริ่มนับถอยหลัง 5 วินาที และอัปเดตข้อมูล
                        let timerInterval;
                        Swal.fire({
                            title: "กำลังดำเนินการ...",
                            html: "ระบบกำลังอัปเดตข้อมูล <b></b> วินาที",
                            timer: 5000,
                            timerProgressBar: true,
                            didOpen: () => {
                                Swal.showLoading();
                                const b = Swal.getHtmlContainer().querySelector("b");
                                timerInterval = setInterval(() => {
                                    b.textContent = Math.ceil(Swal.getTimerLeft() / 1000);
                                }, 1000);
                            },
                            willClose: () => {
                                clearInterval(timerInterval);
                            }
                        }).then(() => {
                            // ส่งข้อมูลไปยัง PHP เพื่ออัปเดต package_id ในฐานข้อมูล
                            fetch("../process/upgrade_to_premium.php", {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json"
                                },
                                body: JSON.stringify({ package_id: selectedPackage })
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === "success") {
                                    Swal.fire("สำเร็จ!", "ซื้อแพ็กเกจสำเร็จ", "success").then(() => {
                                        window.location.reload();
                                    });
                                } else {
                                    Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถอัปเดตแพ็กเกจได้", "error");
                                }
                            })
                            .catch(error => {
                                Swal.fire("เกิดข้อผิดพลาด!", "ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้", "error");
                            });
                        });
                    }
                });
            <?php } ?>
        });
    });
});

</script>