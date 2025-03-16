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

                <button type="submit" class="btn btn-danger btn-disabled" id="deleteButton" style=" margin-bottom: 100px;" disabled>
                    <i class="fa-solid fa-trash" style="color: #000000; "></i> ลบเสียงเพลง
                </button>

            </form>
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
 

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        let currentAudio = null; // ตัวแปรสำหรับเก็บออบเจ็กต์เสียงปัจจุบัน
        let currentButton = null; // ตัวแปรสำหรับเก็บปุ่มที่กำลังเล่นอยู่

        document.querySelectorAll('.play-button').forEach(button => {
            const soundImage = button.querySelector('img'); // สมมติว่ามี <img> ภายในปุ่ม

            // ตรวจสอบสถานะของเสียงและปรับภาพตามสถานะ
            const workingStatus = parseInt(button.getAttribute('data-status'), 10);
            if (workingStatus === 0) {
                // หาก working_status = 0 ให้ทำให้ภาพจาง
                soundImage.classList.add('faded');
                soundImage.alt = 'เสียงนี้ถูกปิดใช้งาน';
                soundImage.title = 'เสียงนี้ถูกปิดใช้งาน';
            }

            button.addEventListener('click', function() {
                // หากมีเสียงที่กำลังเล่นอยู่ ให้หยุดมันก่อน
                if (currentAudio && !currentAudio.paused) {
                    currentAudio.pause();
                    currentAudio.currentTime = 0; // รีเซ็ตเวลาเล่นเป็น 0
                    if (currentButton) {
                        // เปลี่ยนภาพกลับไปยังภาพเล่น
                        currentButton.querySelector('img').classList.remove('playing');
                    }
                }

                // ตรวจสอบสถานะของเสียง
                const workingStatus = parseInt(this.getAttribute('data-status'), 10);
                if (workingStatus === 0) {
                    // แสดง SweetAlert เมื่อสถานะการทำงานคือ 0 (ปิดใช้งาน)
                    Swal.fire({
                        icon: 'warning',
                        title: 'เสียงนี้ถูกปิดการใช้งาน',
                        text: 'คุณไม่สามารถเล่นเสียงนี้ได้',
                        confirmButtonText: 'ตกลง'
                    });
                    return;
                }

                // สร้างออบเจ็กต์เสียงใหม่และเล่น
                if (currentAudio !== this.audio) {
                    currentAudio = new Audio(this.getAttribute('data-src'));
                    currentAudio.loop = false; // ไม่ให้เสียงเล่นลูป
                    currentAudio.play();
                    currentButton = this; // เก็บปุ่มที่กำลังเล่นอยู่
                    this.audio = currentAudio; // เก็บออบเจ็กต์เสียงในปุ่ม

                    // เปลี่ยนภาพในปุ่มเป็นภาพที่แสดงว่าเสียงกำลังเล่น
                    soundImage.classList.add('playing');

                    // เชื่อมโยงสไลเดอร์ระดับเสียงกับเสียง
                    const volumeSlider = this.parentElement.querySelector('.volume-slider');
                    volumeSlider.addEventListener('input', () => {
                        currentAudio.volume = volumeSlider.value;
                    });

                    currentAudio.addEventListener('ended', () => {
                        currentAudio = null; // ไม่มีเสียงเล่นอยู่
                        currentButton = null;
                        // เปลี่ยนภาพกลับไปยังภาพเล่น
                        soundImage.classList.remove('playing');
                    });
                } else {
                    currentAudio = null; // ไม่มีเสียงเล่นอยู่
                    currentButton = null;
                    // เปลี่ยนภาพกลับไปยังภาพเล่น
                    soundImage.classList.remove('playing');
                }
            });
        });
    });

    // ฟังก์ชันลบรายการ
    function deleteList(listId) {
        // เก็บ listId เพื่อใช้งานเมื่อลบลิสต์
        selectedListId = listId;

        // แสดงป็อปอัปยืนยัน
        $('#confirmDeleteModal').modal('show');
    }

    // เมื่อกดยืนยันการลบลิสต์
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        if (selectedListId !== null) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../process/manage_del_list.php';

            const inputAction = document.createElement('input');
            inputAction.type = 'hidden';
            inputAction.name = 'action';
            inputAction.value = 'delete';
            form.appendChild(inputAction);

            const inputListId = document.createElement('input');
            inputListId.type = 'hidden';
            inputListId.name = 'list_id';
            inputListId.value = selectedListId;
            form.appendChild(inputListId);

            document.body.appendChild(form);
            form.submit();
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
    document.addEventListener("DOMContentLoaded", function() {
        // เลือก checkbox ทั้งหมดและปุ่มลบ
        const checkboxes = document.querySelectorAll('input[name="audio_ids[]"]');
        const deleteButton = document.getElementById("deleteButton");

        // ฟังก์ชันเปิด/ปิดปุ่ม
        function toggleDeleteButton() {
            // ตรวจสอบว่ามี checkbox ใดถูกเลือกหรือไม่
            const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

            // เปิดหรือปิดการใช้งานปุ่ม
            deleteButton.disabled = !isChecked;

            // เปลี่ยนคลาสของปุ่มเพื่อแสดงสถานะ
            if (isChecked) {
                deleteButton.classList.remove("btn-disabled");
            } else {
                deleteButton.classList.add("btn-disabled");
            }
        }

        // เพิ่ม event listener ให้ checkbox ทุกตัว
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener("change", toggleDeleteButton);
        });
    });
    </script>




</center>

</body>

</html>
<?php
include('../include/footer.php');
?>
