<?php
include '../config/connect.inc.php';

if (isset($_SESSION['member_id'])) {
    echo "SORRY , YOU'RE LOGGED IN";
    exit();
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
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="../Logo/LogoRSZ.png" type="image/x-icon">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="../style/Login_style.css">
</head>

<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark text-white" style="border-radius: 1rem;">
                        <div class="card-body p-5 text-center">
                            <div class="mb-md-5 mt-md-4 pb-5">
                                <form name="form-Login" method="POST" action="">
                                    <h2 class="fw-bold mb-2 text-uppercase">เข้าสู่ระบบ</h2>
                                    <p class="text-white-50 mb-5">กรุณาป้อนข้อมูลเข้าสู่ระบบและรหัสผ่านของคุณ!</p>

                                    <div class="form-outline form-white mb-4">
                                        <input type="text" name="member_username" class="form-control form-control-lg"
                                            placeholder="Username" required />
                                    </div>

                                    <div class="form-outline form-white mb-4 position-relative">
                                        <input type="password" id="member_password" name="member_password"
                                            class="form-control form-control-lg" placeholder="Password" required />
                                        <!-- ไอคอนลูกตาทางขวา -->
                                        <i class="fas fa-eye toggle-password" data-target="member_password"></i>
                                        <div id="passwordError" class="text-danger"></div>
                                    </div>

                                    <button class="btn btn-outline-light btn-lg px-5" type="submit"
                                        name="submit">เข้าสู่ระบบ</button>
                                </form>
                                <div>
                                    <p class="mb-0" style="padding:25px;">ยังไม่มีบัญชี? <a href="../auth/Register.php"
                                            class="text-white-50 fw-bold">สมัครสมาชิก</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $member_username = $_POST['member_username'];
    $member_password = $_POST['member_password'];

    // ตรวจสอบชื่อผู้ใช้และรหัสผ่านในฐานข้อมูล
    $sql = "SELECT * FROM member WHERE member_username = ? AND member_password = ?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'ss', $member_username, $member_password);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) === 0) {
        echo '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>';
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'ข้อมูลไม่ถูกต้อง',
                    text: 'กรุณาลองใหม่อีกครั้ง!',
                });
              </script>";
    } else {
        $arr = mysqli_fetch_array($result);
        $_SESSION['member_id'] = $arr['member_id'];
        header('Location: ../page/Show_music.php?login=success');
        exit();
    }
}

?>
<script>
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