document.addEventListener("DOMContentLoaded", function () {
    var navbarToggle = document.getElementById("navbarToggleBtn");
    var navbarContent = document.getElementById("navbarContent");

    // ตรวจจับการเปิดเมนู
    navbarContent.addEventListener("show.bs.collapse", function () {
        navbarToggle.innerHTML = '<i class="fas fa-times"></i>'; // เปลี่ยนเป็นกากบาท
    });

    // ตรวจจับการปิดเมนู
    navbarContent.addEventListener("hidden.bs.collapse", function () {
        navbarToggle.innerHTML = '<i class="fas fa-bars"></i>'; // เปลี่ยนเป็นสามขีด
    });
});