<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>โปรไฟล์ผู้ใช้</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">
</head>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Roboto', sans-serif;
    background-color: #121212;
    color: white;
}

.profile-container {
    display: flex;
    flex-direction: row;
    padding: 20px;
}

.profile-left {
    width: 25%;
    background-color: #1d1d1d;
    border-radius: 8px;
    padding: 20px;
    margin-right: 20px;
}

.profile-header {
    text-align: center;
    margin-bottom: 20px;
}

.profile-avatar {
    width: 120px;
    height: 120px;
    border-radius: 50%;
}

.profile-name {
    font-size: 24px;
    font-weight: 500;
    margin-top: 10px;
}

.vip-badge {
    background-color: #ffcc00;
    color: black;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 12px;
    margin-left: 10px;
}

.profile-dob {
    font-size: 14px;
    color: #aaa;
}

.vip-button {
    background-color: #ffcc00;
    color: black;
    padding: 10px;
    border-radius: 20px;
    margin-top: 10px;
    width: 100%;
    border: none;
    font-size: 14px;
}

.profile-menu {
    list-style-type: none;
    margin-top: 20px;
}

.profile-menu li {
    padding: 10px;
    border-bottom: 1px solid #333;
}

.profile-menu a {
    color: #ffcc00;
    text-decoration: none;
}

.profile-right {
    width: 70%;
    background-color: #1d1d1d;
    border-radius: 8px;
    padding: 20px;
}

.profile-details,
.profile-social {
    margin-bottom: 20px;
}

.profile-item {
    margin-bottom: 15px;
}

.profile-item label {
    font-weight: 500;
    color: #aaa;
}

.profile-item p {
    font-size: 16px;
}

@media (max-width: 768px) {
    .profile-container {
        flex-direction: column;
    }

    .profile-left,
    .profile-right {
        width: 100%;
        margin-right: 0;
    }

    .profile-name {
        font-size: 20px;
    }

    .vip-button {
        font-size: 12px;
    }
}
</style>

<body>
    <div class="profile-container">
        <div class="profile-left">
            <div class="profile-header">
                <img src="avatar.png" alt="Avatar" class="profile-avatar">
                <div class="profile-info">
                    <h2 class="profile-name">EaRth LeFaCe <span class="vip-badge">VIP</span></h2>
                    <p class="profile-dob">วันหมดอายุ: 2025-1-1</p>
                    <button class="vip-button">ต่ออายุ VIP</button>
                </div>
            </div>
            <ul class="profile-menu">
                <li><a href="#">ประวัติ</a></li>
                <li><a href="#">โปรไฟล์</a></li>
                <li><a href="#">ออกจากระบบ</a></li>
            </ul>
        </div>

        <div class="profile-right">
            <div class="profile-details">
                <h3>โปรไฟล์</h3>
                <div class="profile-item">
                    <label>ชื่อเต็ม</label>
                    <p>EaRth LeFaCe</p>
                </div>
                <div class="profile-item">
                    <label>วันเกิด</label>
                    <p>-</p>
                </div>
                <div class="profile-item">
                    <label>เพศ</label>
                    <p>ชาย</p>
                </div>
                <div class="profile-item">
                    <label>Email Contact</label>
                    <p>-</p>
                </div>
            </div>

            <div class="profile-social">
                <h3>บัญชีที่เข้าสู่ระบบ</h3>
                <div class="profile-item">
                    <label>เบอร์โทรศัพท์มือถือ</label>
                    <p>-</p>
                </div>
                <div class="profile-item">
                    <label>Google</label>
                    <p> EaRth LeFaCe </p>
                </div>
                <div class="profile-item">
                    <label>WeTV ID</label>
                    <p>4526807237</p>
                </div>
            </div>
        </div>
    </div>

    <script src="scripts.js"></script>
</body>

</html>