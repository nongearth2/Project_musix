<?php
ob_start();
include '../config/connect.inc.php';

?>

<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../Logo/LogoRSZ.png" type="image/x-icon">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../style/Register_style.css">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form name="form-register" method="POST" action="">
                                    <h2 class="fw-bold mb-2 text-uppercase">สมัครสมาชิก</h2>
                                    <p class="text-white-50 mb-5">สร้างข้อมูลเพื่อเข้าสู่ระบบ Relaxing using music</p>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="member_name" class="form-control form-control-lg"
                                            placeholder="ชื่อ-นามสกุล" required />
                                        <div id="nameError" class="text-danger"></div>
                                    </div>
                                    <div class="form-outline form-white mb-4">
                                        <input type="email" name="member_email" class="form-control form-control-lg"
                                            placeholder="Email" required />
                                        <div id="emailError" class="text-danger"></div>
                                    </div>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="member_username" class="form-control form-control-lg"
                                            placeholder="Username" required />
                                        <div id="usernameError" class="text-danger"></div>
                                    </div>

                                    <div class="form-outline form-white mb-4 position-relative">
                                        <input type="password" id="member_password" name="member_password"
                                            class="form-control form-control-lg" placeholder="Password" required />
                                        <!-- ไอคอนลูกตาทางขวา -->
                                        <i class="fas fa-eye toggle-password" data-target="member_password"></i>
                                        <div id="passwordError" class="text-danger"></div>
                                        <small class="form-text"
                                            style="color: white;">กรุณากรอกรหัสผ่านให้มีความยาวอย่างน้อย 8
                                            ตัวอักษร</small>
                                    </div>

                                    <div class="form-outline form-white mb-4 position-relative">
                                        <input type="password" id="confirm_password" name="confirm_password"
                                            class="form-control form-control-lg" placeholder="ยืนยันรหัสผ่าน"
                                            required />
                                        <!-- ไอคอนลูกตาทางขวา -->
                                        <i class="fas fa-eye toggle-password" data-target="confirm_password"></i>
                                        <div id="confirmPasswordError" class="text-danger"></div>
                                        <small class="form-text"
                                            style="color: white;">กรุณายืนยันรหัสผ่านให้ตรงกับรหัส</small>
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit"
                                        name="submit">สมัครสมาชิก</button>
                                </form>
                                <div>
                                    <p class="mb-0">มีบัญชีอยู่แล้ว? <a href="../auth/Login.php"
                                            class="text-white-50 fw-bold">ล็อกอิน เข้าสู่ระบบ</a></p>
                                </div>
                            </div>

                            <?php
                        function showError($title, $message) {
                            echo "<script>
                                    Swal.fire({
                                        icon: 'error',
                                        title: '$title',
                                        text: '$message',
                                        confirmButtonText: 'ตกลง'
                                    });
                                  </script>";
                            exit();
                        }
                        
                        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                            $member_name = $_POST['member_name'];
                            $member_email = $_POST['member_email'];
                            $member_username = $_POST['member_username'];
                            $member_password = $_POST['member_password'];
                            $confirm_password = $_POST['confirm_password'];
                        
                            // ตรวจสอบข้อมูลที่ซ้ำซ้อน
                            $checks = [
                                ["SELECT * FROM member WHERE member_name='$member_name'", 'ชื่อสมาชิกนี้ถูกใช้แล้ว กรุณาใช้ชื่อใหม่!'],
                                ["SELECT * FROM member WHERE member_username='$member_username'", 'ชื่อ Username นี้ถูกใช้แล้ว กรุณาใช้ Username ใหม่!'],
                                ["SELECT * FROM member WHERE member_email='$member_email'", 'Email นี้ถูกใช้ไปแล้ว กรุณาใช้อีเมลใหม่!']
                            ];
                        
                            foreach ($checks as $check) {
                                [$query, $errorMessage] = $check;
                                $result = mysqli_query($link, $query);
                                if (mysqli_num_rows($result) > 0) {
                                    showError('เกิดข้อผิดพลาด', $errorMessage);
                                }
                            }
                        
                            // ตรวจสอบความถูกต้องของรหัสผ่าน
                            if (strlen($member_password) < 8) {
                                showError('เกิดข้อผิดพลาด', 'กรุณากรอกรหัสผ่านอย่างน้อย 8 ตัว!');
                            }
                        
                            if ($member_password !== $confirm_password) {
                                showError('เกิดข้อผิดพลาด', 'รหัสผ่านไม่ตรงกัน กรุณากรอกใหม่!');
                            }
                        
                            // เพิ่มข้อมูลสมาชิกใหม่
                            $default_profile_picture = 'default_picture.png';
                            $background_color = '#000000';
                            $font_color = '#FFFFFF';
                        
                            $sql = "INSERT INTO member (member_name, member_email, member_username, member_password, profile_picture, status_id, background_color, font_color) 
                                    VALUES ('$member_name', '$member_email', '$member_username', '$member_password', '$default_profile_picture', 3, '$background_color', '$font_color')";
                        
                            if (mysqli_query($link, $sql)) {
                                $member_id = mysqli_insert_id($link);
                                header("Location: ../auth/Login.php?register=success&member_id=$member_id");
                                exit();
                            } else {
                                showError('ลงทะเบียนไม่สำเร็จ', mysqli_error($link));
                            }
                        }          
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>

    <script>
    document.querySelector('form[name="form-register"]').addEventListener('submit', function(event) {
        const emailInput = document.querySelector('input[name="member_email"]');
        const emailPattern = /^[^\s@]+@(gmail\.com|hotmail\.com|yahoo\.com)$/i;

        if (!emailPattern.test(emailInput.value)) {
            event.preventDefault(); // ยกเลิกการส่งฟอร์ม
            Swal.fire({
                icon: 'error',
                title: 'รูปแบบอีเมลไม่ถูกต้อง',
                text: 'กรุณากรอกอีเมลที่เป็นโดเมนสากล เช่น gmail.com, hotmail.com หรือ yahoo.com เท่านั้น',
            });
        }
    });
    document.querySelectorAll(".toggle-password").forEach(item => {
        item.addEventListener("click", function() {
            const targetId = this.getAttribute("data-target");
            const passwordField = document.getElementById(targetId);
            const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
            passwordField.setAttribute("type", type);

            // Toggle between fa-eye and fa-eye-slash icons
            this.classList.toggle("fa-eye");
            this.classList.toggle("fa-eye-slash");
        });
    });
    </script>

</body>

</html>