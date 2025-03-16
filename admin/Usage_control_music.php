<?php
include '../config/connect.inc.php';

if ($link->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $link->connect_error);
}


if (!isset($_SESSION['member_id'])) {
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
    echo '<script>
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    icon: "warning",
                    title: "กรุณาเข้าสู่ระบบก่อน!",
                    confirmButtonText: "ตกลง"
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "../auth/Login.php"; // เปลี่ยนเส้นทางไปที่หน้า Login
                    }
                });
            });
          </script>';
    exit();
}

$id = $_SESSION['member_id'];
// ตรวจสอบว่าเป็น Admin หรือไม่
$member_id = $_SESSION['member_id'];
$sql = "SELECT status_id FROM member WHERE member_id = ?";
$stmt = $link->prepare($sql);

if (!$stmt) {
    die("SQL Error: " . $link->error);
}

$stmt->bind_param("i", $member_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if ($user['status_id'] != 1) { // ถ้าไม่ใช่ Admin
    echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
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
if (isset($_POST['soundmix_id']) && isset($_POST['working_status'])) {
    $soundmix_id = $_POST['soundmix_id'];
    $working_status = $_POST['working_status'];

    $sql = "UPDATE upload_soundmix SET working_status = ? WHERE soundmix_id = ?";
    $stmt = $link->prepare($sql);
    $stmt->bind_param('ii', $working_status, $soundmix_id);

    if ($stmt->execute()) {
        echo json_encode(['status' => 'success', 'message' => 'อัพเดทสถานะไฟล์เสียงสำเร็จ!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'เกิดข้อผิดพลาด: ' . $stmt->error]);
    }
    exit();
}

$sqlCategory = "SELECT * FROM category";
$resultCategory = $link->query($sqlCategory);

$sql = "SELECT * FROM upload_soundmix";
$select_songs = $link->query($sql);

$songsByCategory = [];
while ($fetch_song = $select_songs->fetch_assoc()) {
    $category_id = $fetch_song['category_id'];
    $songsByCategory[$category_id][] = [
        'id' => $fetch_song['soundmix_id'],
        'image' => $fetch_song['working_status'] == 1 ? 'image/' . $fetch_song['soundmix_img'] : 'image/disable.png',
        'original_image' => '../image/' . $fetch_song['soundmix_img'],
        'working_status' => $fetch_song['working_status'],
        'audio_url' => '../music/' . $fetch_song['sound_file'] // เพิ่ม URL ของไฟล์เสียง
    ];
}

$categories = [];
while ($category = $resultCategory->fetch_assoc()) {
    $categories[$category['category_id']] = $category['category_name'];
}

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../Logo/LogoRSZ.png" type="image/x-icon">
    <title>การควบคุมการใช้งานเสียง by Admin</title>
    <link rel="stylesheet" href="../style/Usage_control_music.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
        integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-0pUGZvbkm6XF6gxjEnlmuGrJXVbNuzT9qBBavbLwCsOGabYfZo0T0to5eqruptLy" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ENjdO4Dr2bkBIFxQpeoCHuXG7iLXB1H+AvGpXld/Yh9D5Xx5eZvbz4+0l7o78Rx3" crossorigin="anonymous">
    </script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>
    <!-- เมนูโปรไฟล์ -->
    <nav class="navbar navbar-expand-lg menu navbar-background fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="../page/Show_music.php">
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
                    <!-- Dropdown จัดการเสียง -->
                    <?php 
                        $sql_menu="SELECT status_id FROM member WHERE member_id='$id'" ;
                        $result_menu=mysqli_query($link, $sql_menu); 
                        $arr_menu=mysqli_fetch_array($result_menu);
                        ?>
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
                            <?php 

                        $sql_profile = "SELECT * FROM member WHERE member_id=$member_id";
                        $result_pofile=mysqli_query($link,$sql_profile);
                        $arr_profile=mysqli_fetch_array($result_pofile);
                         ?>
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
    <main>
        <div class="container">
            <div class="card">
                <h3 class="text-center mb-4">การควบคุมการใช้งานเสียง by Admin</h3>
                <?php foreach ($categories as $category_id => $category_name) { ?>
                <div class="category-section">
                    <h4><?php echo htmlspecialchars($category_name); ?></h4>
                    <div class="sound-list">
                        <?php if (isset($songsByCategory[$category_id])) { ?>
                        <?php foreach ($songsByCategory[$category_id] as $song) { ?>
                        <div class="sound-item">
                            <img src="<?php echo htmlspecialchars($song['original_image']); ?>" alt="Cover Image"
                                class="sound-image <?php echo $song['working_status'] == 0 ? 'faded' : ''; ?>"
                                id="image-<?php echo htmlspecialchars($song['id']); ?>">

                            <?php if ($song['working_status'] == 1) { ?>
                            <i class="fas fa-play play-button" id="play-<?php echo htmlspecialchars($song['id']); ?>"
                                data-audio-url="<?php echo htmlspecialchars($song['audio_url']); ?>"></i>
                            <i class="fas fa-stop stop-button" id="stop-<?php echo htmlspecialchars($song['id']); ?>"
                                style="display:none;"></i>
                            <?php } ?>
                            <label class="toggle-switch">
                                <input type="checkbox" class="status-toggle"
                                    data-id="<?php echo htmlspecialchars($song['id']); ?>"
                                    <?php echo $song['working_status'] == 1 ? 'checked' : ''; ?>>
                                <span class="slider slider-red"></span>
                            </label>
                            <div class="volume-control">
                                <label for="volume-<?php echo htmlspecialchars($song['id']); ?>">Volume:</label>
                                <input type="range" id="volume-<?php echo htmlspecialchars($song['id']); ?>"
                                    class="volume-slider" min="0" max="100" value="50"
                                    data-id="<?php echo htmlspecialchars($song['id']); ?>">
                            </div>
                            <audio id="audio-<?php echo htmlspecialchars($song['id']); ?>"
                                src="<?php echo htmlspecialchars($song['audio_url']); ?>"></audio>
                        </div>
                        <?php } ?>
                        <?php } else { ?>
                        <p>ไม่มีไฟล์ในหมวดหมู่นี้</p>
                        <?php } ?>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        $('.status-toggle').on('change', function() {
            var soundmixId = $(this).data('id');
            var workingStatus = $(this).is(':checked') ? 1 : 0;
            var imageElement = $('#image-' + soundmixId);

            $.ajax({
                url: '../admin/Usage_control_music.php',
                method: 'POST',
                data: {
                    soundmix_id: soundmixId,
                    working_status: workingStatus
                },
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: response.message
                        });

                        // อัปเดตภาพตามสถานะการทำงาน
                        if (workingStatus === 1) {
                            // เมื่อสถานะทำงานคือ 1, ใช้ภาพปกติ
                            imageElement.attr('src', imageElement.data('original'));
                            imageElement.removeClass('faded'); // ลบคลาสที่ทำให้จาง
                        } else {
                            // เมื่อสถานะทำงานคือ 0, ทำให้ภาพจาง
                            imageElement.addClass('faded'); // เพิ่มคลาสที่ทำให้จาง
                            imageElement.attr('alt', 'เสียงนี้ถูกปิดใช้งาน');
                        }

                        // อัปเดตปุ่ม play/stop ตามสถานะ
                        if (workingStatus === 1) {
                            $('#play-' + soundmixId).show();
                        } else {
                            $('#play-' + soundmixId).hide();
                            $('#stop-' + soundmixId).hide();
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด!',
                            text: response.message
                        });
                    }
                }
            });
        });

        $('.play-button').on('click', function() {
            var soundmixId = $(this).attr('id').replace('play-', '');
            var audioElement = $('#audio-' + soundmixId);
            var stopButton = $('#stop-' + soundmixId);
            var playButton = $(this);

            audioElement[0].play();
            playButton.hide();
            stopButton.show();
        });

        $('.stop-button').on('click', function() {
            var soundmixId = $(this).attr('id').replace('stop-', '');
            var audioElement = $('#audio-' + soundmixId);
            var stopButton = $(this);
            var playButton = $('#play-' + soundmixId);

            audioElement[0].pause();
            audioElement[0].currentTime = 0;
            stopButton.hide();
            playButton.show();
        });

        $('.volume-slider').on('input', function() {
            var soundmixId = $(this).data('id');
            var audioElement = $('#audio-' + soundmixId);
            var volume = $(this).val() / 100;
            audioElement[0].volume = volume;
        });
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
                window.location.href = '../page/Logout.php?logout=success';
            }
        });
    });
    </script>

</body>

</html>