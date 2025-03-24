<?php
include ('../include/header.php');
$packages_query = "SELECT * FROM package ";
$result = mysqli_query($link, $packages_query);
$packages = mysqli_fetch_all($result, MYSQLI_ASSOC);
?>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="../Logo/LogoRSZ.png" type="image/x-icon">
<title>Edit Profile</title>

</head>

<body>
    <?php
    
    if (!isset($_SESSION['member_id'])) {
        echo '<script>
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
        exit(); // หยุดการทำงานของสคริปต์เพื่อป้องกันโค้ดส่วนที่เหลือไม่ให้ทำงาน
    } else {
            $id = $_SESSION['member_id'];
            $sql_profile = "SELECT * FROM member WHERE member_id='$id'";
            $sql_menu = "SELECT status_id FROM member WHERE member_id='$id'";
            $sql_package = "SELECT p.package_detail , p.package_time
                FROM package p 
                INNER JOIN member m ON p.package_id = m.package_id 
                WHERE m.member_id = '$id'";


            $result_profile = mysqli_query($link, $sql_profile);
            $result_menu = mysqli_query($link, $sql_menu);
            $result_package = mysqli_query($link, $sql_package); // ดำเนินการ SQL สำหรับแพ็กเกจ

            $arr_profile = mysqli_fetch_array($result_profile);
            $arr_menu = mysqli_fetch_array($result_menu);
            $arr_package = mysqli_fetch_array($result_package); // แพ็กเกจที่เลือก

            
    ?>
    <section class="h-100 gradient-custom-2">
        <nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="Show_music.php">
                    <img src="../Logo/LogoRSZ.png" width="40" height="40" alt="logo"> RelaxSoundZone
                </a>
                <!-- โชวสามขีด  การแสดงผลให้เป็นในโทรศัพท์ -->
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent"
                    aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation"
                    id="navbarToggleBtn">
                    <i class="fas fa-bars"></i> <!-- ไอคอนสามขีด (เปิดเมนู) -->
                </button>
                <div class="collapse navbar-collapse" id="navbarContent">
                    <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="Show_music.php">หน้าแรก</a></li>
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
                        <?php if ($arr_menu['status_id'] == 1 || $arr_menu['status_id'] == 2) { ?>
                        <li class="nav-item"><a class="nav-link" href="Library.php">คลัง</a></li>
                        <?php } ?>
                        <li class="nav-item"><a class="nav-link" id="logoutLink" href="../Logout.php">ออกจากระบบ</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center" style="padding-top: 80px; margin-bottom: 100px;">
                <div class="col col-lg-9 col-xl-8">
                    <div class="card">
                        <h4 class="profile-title">ข้อมูลโปรไฟล์</h4>
                        <div class="rounded-top text-white d-flex flex-row" id="background-container"
                            style="background-color: <?php echo htmlspecialchars($arr_profile['background_color']); ?>; height: 200px;">
                            <button type="button" class="btn btn-custom-color" data-bs-toggle="modal"
                                data-bs-target="#colorPickerModal" style="position: absolute; top: 5px; right: 10px;"
                                title="เปลียนสีพื้นหลัง">
                                <i class="fas fa-palette"></i>
                            </button>
                            <div class="ms-4 mt-5 d-flex flex-column" style="width: 150px;">
                                <img src="<?php echo '../profile_pics/' . htmlspecialchars($arr_profile['profile_picture']); ?>"
                                    alt="Profile Picture" class="img-fluid img-thumbnail mt-4 mb-2"
                                    style="width: 150px; z-index: 1">
                                <button type="button" class="btn btn-custom" data-bs-toggle="modal"
                                    data-bs-target="#uploadModal" style="z-index: 1;">เปลี่ยนรูปโปรไฟล์</button>
                            </div>
                            <div class="ms-3" style="margin-top: 130px;">
                                <h5 style="color: <?php echo htmlspecialchars($arr_profile['font_color']); ?>;">
                                    <?php echo htmlspecialchars($arr_profile['member_name']); ?></h5>
                                <button type="button" class="btn btn-edit" data-bs-toggle="modal"
                                    data-bs-target="#editNameModal" style="z-index: 1; border: none; background: none;"
                                    title="แก้ไขชื้อ">
                                    <i class="fas fa-pencil-alt" style="font-size: 1.5rem;color:gray;"></i>
                                </button>
                            </div>

                            <!-- ข้อความสำหรับผู้ใช้พรีเมียม -->
                            <?php if ($arr_profile['status_id'] == 2): ?>
                            <?php 
                                // ตรวจสอบว่าแพ็กเกจถูกซื้อหรือไม่
                                $packageBought = isset($arr_package['package_detail']) && !empty($arr_package['package_time']) && $arr_package['package_time'] > 0;
                                ?>

                            <?php if ($packageBought): ?>
                            <!-- แสดงเวลาที่เหลือถ้าซื้อแพ็กเกจแล้ว -->
                            <div class="countdown-timer">
                                <svg class="progress-ring" width="80" height="80">
                                    <circle class="progress-ring__circle" stroke="white" stroke-width="6"
                                        fill="transparent" r="35" cx="40" cy="40" />
                                </svg>
                                <span id="remainingTime" class="countdown-text">
                                    <?php echo htmlspecialchars($arr_package['package_time']); ?>
                                </span>
                            </div>

                            <?php else: ?>
                            <!-- แจ้งเตือนถ้ายังไม่ได้ซื้อแพ็กเกจ -->
                            <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                Swal.fire({
                                    title: 'คุณยังไม่ได้ซื้อแพ็กเกจ!',
                                    text: 'กรุณาซื้อแพ็กเกจเพื่อใช้บริการ.',
                                    icon: 'warning',
                                    confirmButtonText: 'ซื้อแพ็กเกจ'
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        $('#packageModal').modal('show'); // แสดงโมดัลให้ซื้อแพ็กเกจ
                                    }
                                });
                            });
                            </script>
                            <?php endif; ?>
                            <?php endif; ?>


                        </div>
                        <div class="p-4 text-black bg-body-tertiary">
                            <div class="status-container text-center text-body">
                                <?php
        $status = $arr_profile['status_id'];
        $statusText = '';
        $backgroundColor = '';
        $buttonText = '';
        $buttonTextPremium = ''; 

        switch ($status) {
            case 1:
                $statusText = 'ผู้ดูแลระบบ';
                $backgroundColor = 'linear-gradient(to right, #FF5733, #FFC300)'; // สีแดงเข้มถึงสีส้ม สำหรับผู้ดูแลระบบ
                break;
            case 2:
                $statusText = 'สมาชิกพรีเมียม';
                $backgroundColor = 'linear-gradient(to right, #FFD700, #FFEA00)'; // สีทอง สำหรับสมาชิกพรีเมียม
                $buttonTextPremium = 'ซื้อแพ็คเกจพรีเมียม';
                break;
            case 3:
                $statusText = 'สมาชิกทั่วไป';
                $backgroundColor = 'linear-gradient(to right, #87CEFA, #00BFFF)'; // สีน้ำเงินอ่อนถึงเข้ม สำหรับสมาชิกทั่วไป
                $buttonText = 'สมัครสมาชิกพรีเมียม';
                break;
        }
        ?>
                                <p class="status-text" style="background: <?php echo $backgroundColor; ?>;">
                                    <?php echo htmlspecialchars($statusText); ?>
                                </p>

                                <div class="status-button-container">
                                    <?php if ($buttonText): ?>
                                    <button class="btn btn-primary" onclick="showPackageOptions()">
                                        <?php echo htmlspecialchars($buttonText); ?>
                                    </button>
                                    <?php endif; ?>

                                    <?php if ($buttonTextPremium): ?>
                                    <button class="btn btn-primary" onclick="showPackageOptions()">
                                        <?php echo htmlspecialchars($buttonTextPremium); ?>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4 text-black">
                            <div class="mb-5 text-body">
                                <p class="lead fw-normal mb-1">รายการคลัง</p>
                                <div class="p-4 bg-body-tertiary">
                                    <?php
                                    if ($arr_menu['status_id'] == 1 || $arr_menu['status_id'] == 2) {
                                        $sql_list_category = "SELECT * FROM list_category WHERE member_id='$id'";
                                        $result_list_category = mysqli_query($link, $sql_list_category);
                                        $list_count = 0;

                                        while ($arr_list_category = mysqli_fetch_array($result_list_category)) {
                                            $list_count++;
                                            echo '<p class="font-italic mb-1">' . htmlspecialchars($arr_list_category['List_name']) . '</p>';
                                        }
                                        echo '<p class="font-italic mb-1">จำนวนลิสต์ทั้งหมดของผู้ใช้: ' . $list_count . ' รายการ</p>';
                                            } elseif ($arr_menu['status_id'] == 3) {
                                                echo '<p class="font-italic mb-1 text-danger">หมายเหตุ: สำหรับสมาชิกพรีเมียมเท่านั้น</p>';
                                            }
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ป็อปอัพสำหรับการแก้ไขรูปโปรไฟล์ -->
    <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadModalLabel">อัปโหลดรูปโปรไฟล์ใหม่</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="../page/Edit_profile.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <label for="profile_picture">เลือกรูปโปรไฟล์ใหม่:</label>
                        <input type="file" name="profile_picture" id="profile_picture" required class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <input type="submit" value="อัปโหลด" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ป็อปอัพ สำหรับการเปลี่ยนชื่อและสีฟอนต์ -->
    <div class="modal fade" id="editNameModal" tabindex="-1" aria-labelledby="editNameModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editNameModalLabel">แก้ไขชื่อและสีฟอนต์</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="Edit_profile.php" method="post">
                    <div class="modal-body">
                        <label for="name">ชื่อ:</label>
                        <input type="text" name="new_member_name" id="name"
                            value="<?php echo htmlspecialchars($arr_profile['member_name']); ?>" required
                            class="form-control">
                        <label for="font_color">สีฟอนต์:</label>
                        <input type="color" name="font_color" id="font_color"
                            value="<?php echo htmlspecialchars($arr_profile['font_color']); ?>" class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <input type="submit" value="บันทึก" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- ป็อปอัพสำหรับเปลี่ยนสีพื้นหลัง -->
    <div class="modal fade" id="colorPickerModal" tabindex="-1" aria-labelledby="colorPickerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="colorPickerModalLabel">เลือกสีพื้นหลัง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="Edit_profile.php" method="post">
                    <div class="modal-body">
                        <label for="background_color">สีพื้นหลัง:</label>
                        <input type="color" name="background_color" id="background_color"
                            value="<?php echo htmlspecialchars($arr_profile['background_color']); ?>"
                            class="form-control">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                        <input type="submit" value="บันทึก" class="btn btn-primary">
                    </div>
                </form>
            </div>
        </div>
    </div>


    <div class="modal fade" id="packageModal" tabindex="-1" aria-labelledby="packageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="packageModalLabel">เลือกแพ็คเกจ</h5>
                    <button type="button" class="close" onclick="closeModal()" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php foreach ($packages as $package): ?>
                    <div class="package-option mb-3">
                        <h6><?php echo htmlspecialchars($package['package_detail']); ?></h6>
                        <button class="btn btn-outline-primary btn-package"
                            onclick="purchasePackage('<?php echo htmlspecialchars($package['package_id']); ?>')">ซื้อแพ็คเกจ</button>
                    </div>
                    <?php endforeach; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">ปิด</button>
                </div>
            </div>
        </div>
    </div>



    <!-- ป็อปอัพสำหรับ QR Code -->
    <div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrCodeModalLabel">QR Code สำหรับการชำระเงิน</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <p class="alert alert-info">กรุณาสแกน QR Code เพื่อทำการชำระเงิน</p> <!-- เพิ่มข้อความแจ้งเตือน -->
                    <img id="qrCodeImage" src="" alt="QR Code" class="img-fluid">
                    <div id="countdown" class="mt-3"></div> <!-- แสดงเวลานับถอยหลัง -->
                </div>
            </div>
        </div>
    </div>

    </div>
    <?php }?>

    <?php
    // ตรวจสอบว่ามีการส่งข้อมูลจากฟอร์มหรือไม่
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_SESSION['member_id'];
    
        // ตรวจสอบการอัปโหลดรูปโปรไฟล์
        if (isset($_FILES['profile_picture'])) {
            $profilePic = $_FILES['profile_picture'];
            $target_dir = "../profile_pics/";
            $target_file = $target_dir . basename($profilePic["name"]);
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
            // ตรวจสอบว่าไฟล์ที่อัปโหลดเป็นภาพหรือไม่
            $check = getimagesize($profilePic["tmp_name"]);
            if ($check !== false) {
                // บันทึกรูปภาพ
                if (move_uploaded_file($profilePic["tmp_name"], $target_file)) {
                    // อัปเดตชื่อไฟล์รูปโปรไฟล์ในฐานข้อมูล
                    $update_query = "UPDATE member SET profile_picture = '" . basename($profilePic["name"]) . "' WHERE member_id = '$id'";
                    mysqli_query($link, $update_query);
    
                    // แจ้งเตือนเปลี่ยนรูปโปรไฟล์สำเร็จด้วย SweetAlert
                    echo "<script>
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: 'เปลี่ยนรูปโปรไฟล์สำเร็จ!',
                            confirmButtonText: 'ตกลง'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // ปิดป็อปอัพโดยไม่รีโหลดหน้า
                                window.location.href = window.location.href;
                            }
                        });
                    </script>";
                    exit(); 
                } else {
                    echo "<script>
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: 'เกิดข้อผิดพลาดในการอัปโหลดรูปโปรไฟล์',
                            confirmButtonText: 'ตกลง'
                        });
                    </script>";
                    exit(); // หยุดการดำเนินการเมื่อเกิดข้อผิดพลาด
                }
            } else {
                echo "<script>
                    Swal.fire({
                        icon: 'error',
                        title: 'ไฟล์ไม่ถูกต้อง!',
                        text: 'ไฟล์ที่อัปโหลดไม่ใช่รูปภาพ.',
                        confirmButtonText: 'ตกลง'
                    });
                </script>";
                exit(); // หยุดการดำเนินการเมื่อไฟล์ไม่ถูกต้อง
            }
        }
    
    
        
        // เปลียนสีพื้นหลัง
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['background_color'])) {
            $id = $_SESSION['member_id'];
            $background_color = mysqli_real_escape_string($link, $_POST['background_color']);
        
            // อัปเดตสีพื้นหลังในฐานข้อมูล
            $update_query = "UPDATE member SET background_color = '$background_color' WHERE member_id = '$id'";
if (mysqli_query($link, $update_query)) {
    echo "<script>
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ!',
            text: 'เปลี่ยนสีสำเร็จ!',
            confirmButtonText: 'ตกลง'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'Edit_profile.php'; // ไปยังหน้า Edit_profile.php
            }
        });
    </script>";
} else {
    echo "<script>
        Swal.fire({
            icon: 'error',
            title: 'เกิดข้อผิดพลาด!',
            text: 'ไม่สามารถเปลี่ยนสีได้',
            confirmButtonText: 'ตกลง'
        });
    </script>";
}

        }
    
        // ตรวจสอบการเปลี่ยนชื่อและสีฟอนต์
if (isset($_POST['new_member_name']) || isset($_POST['font_color'])) {
    $update_query_parts = [];

    if (isset($_POST['new_member_name'])) {
        $new_member_name = $_POST['new_member_name'];
        $update_query_parts[] = "member_name = '" . mysqli_real_escape_string($link, $new_member_name) . "'";
    }

    if (isset($_POST['font_color'])) {
        $font_color = $_POST['font_color'];
        $update_query_parts[] = "font_color = '" . mysqli_real_escape_string($link, $font_color) . "'";
    }

    // อัปเดตฐานข้อมูล
    if (!empty($update_query_parts)) {
        $update_query = "UPDATE member SET " . implode(', ', $update_query_parts) . " WHERE member_id = '$id'";
        if (mysqli_query($link, $update_query)) {
            echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'สำเร็จ!',
                    text: 'การเปลี่ยนแปลงสำเร็จ!',
                    confirmButtonText: 'ตกลง'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = 'Edit_profile.php'; // ไปยังหน้า Edit_profile.php
                    }
                });
            </script>";
        } else {
            echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'เกิดข้อผิดพลาด!',
                    text: 'ไม่สามารถเปลี่ยนแปลงข้อมูลได้',
                    confirmButtonText: 'ตกลง'
                });
            </script>";
        }
        exit();
    }
}
    }
    ?>
    <script>
    function showPackageOptions() {
        $('#packageModal').modal('show');
    }

    function closeModal() {
        $('#packageModal').modal('hide');
        $('#qrCodeModal').modal('hide');
    }

    let alertShown = false; // ตัวแปรสำหรับตรวจสอบว่า SweetAlert ถูกแสดงหรือยัง

    function purchasePackage(packageId) {
        const qrCodeImage = document.getElementById('qrCodeImage');
        qrCodeImage.src = `../image/qr_code.png`; // เปลี่ยนที่อยู่ให้ชี้ไปยังโฟลเดอร์ image
        $('#qrCodeModal').modal('show');

        let countdownTime = 5; // เวลานับถอยหลัง 5 วินาที
        const countdownDisplay = document.getElementById('countdown');
        countdownDisplay.textContent = `เวลาที่เหลือ: ${countdownTime} วินาที`;

        const countdownInterval = setInterval(() => {
            countdownTime--;
            countdownDisplay.textContent = `เวลาที่เหลือ: ${countdownTime} วินาที`;

            if (countdownTime <= 0) {
                clearInterval(countdownInterval);
                closeModal();
                if (!alertShown) { // ตรวจสอบว่า SweetAlert ยังไม่แสดง
                    alertShown = true; // ตั้งให้แสดงแล้ว
                    Swal.fire({
                        icon: 'success',
                        title: 'ชำระเงินเรียบร้อยแล้ว!',
                        text: 'คุณได้สมัครสมาชิก Premium เรียบร้อยแล้ว!',
                        confirmButtonText: 'ตกลง'
                    }).then(() => {
                        // อัปเดตสถานะสมาชิกในฐานข้อมูลที่นี่
                        upgradeToPremium(packageId); // ฟังก์ชันในการอัปเดตสถานะ
                    });
                }
            }
        }, 1000); // ตั้งเวลาทุก 1 วินาที
    }

    function upgradeToPremium(packageId) {
        fetch('../process/upgrade_to_premium.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    package_id: packageId
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    location.reload(); // รีเฟรชหน้า
                } else {
                    Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถอัปเดตสถานะได้: ' + data.message, 'error');
                }
            })
            .catch(error => {
                console.error('เกิดข้อผิดพลาด:', error);
                Swal.fire('เกิดข้อผิดพลาด!', 'มีปัญหาในการส่งข้อมูล.', 'error');
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const remainingTimeElement = document.getElementById('remainingTime');
        const circle = document.querySelector('.progress-ring__circle');

        const initialTime = parseInt(remainingTimeElement.innerText);
        let timeLeft = initialTime;
        let alertShown = false;
        const totalCircleLength = 220;

        const countdownInterval = setInterval(() => {
            if (timeLeft <= 15 && timeLeft > 0 && !alertShown) {
                alertShown = true;
                Swal.fire({
                    title: 'เวลาใกล้หมดแล้ว!',
                    text: `เวลาที่เหลือ: ${timeLeft} วินาที\nต้องการซื้อแพ็กเกจเพิ่มอีกหรือไม่?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'ซื้อแพ็กเกจ',
                    cancelButtonText: 'ปิดหน้า'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#packageModal').modal('show');
                    } else {
                        window.close();
                    }
                });
            }

            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                updateUserStatus(); // อัปเดตเป็นสมาชิกทั่วไป
                return;
            }

            timeLeft--;
            remainingTimeElement.innerText = timeLeft;

            // คำนวณค่าความยาวของ stroke-dashoffset ให้ลดลง
            const progress = (timeLeft / initialTime) * totalCircleLength;
            circle.style.strokeDashoffset = totalCircleLength - progress;
        }, 1000);

        function updateUserStatus() {
            fetch('../process/update_status.php', { // ชื่อไฟล์ที่อัปเดต status_id
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        status_id: 3
                    }) // ส่ง status_id ที่จะเปลี่ยน
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        Swal.fire('หมดอายุสมาชิก', 'สถานะของคุณได้ถูกเปลี่ยนเป็น Normal เรียบร้อยแล้ว!',
                            'info');
                        location.reload(); // รีเฟรชหน้า
                    } else {
                        Swal.fire('เกิดข้อผิดพลาด!', 'ไม่สามารถอัปเดตสถานะได้.', 'error');
                    }
                });
        }

    });

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
                window.location.href = 'Logout.php?logout=success';
            }
        });
    });
    </script>

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
</body>
<?php
include('../include/footer.php');
?>
</html>
