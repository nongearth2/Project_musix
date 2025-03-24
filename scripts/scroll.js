window.onscroll = function() {
    var scrollButton = document.getElementById("scrollToTopButton");
    
    // ตรวจสอบว่าเลื่อนถึงด้านล่างสุดของหน้าแล้วหรือยัง
    if (document.documentElement.scrollTop + window.innerHeight >= document.documentElement.scrollHeight) {
        scrollButton.classList.add("show");
    } else {
        scrollButton.classList.remove("show");
    }
};

// ฟังก์ชันเลื่อนกลับไปที่ด้านบน
function scrollToTop() {
    window.scrollTo({ top: 0, behavior: 'smooth' });
}