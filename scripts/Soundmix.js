document.addEventListener('DOMContentLoaded', () => {
    let currentAudios = []; // อาร์เรย์เพื่อติดตามวัตถุเสียงทั้งหมด
    document.querySelectorAll('.soundMix-image').forEach(image => {
        image.addEventListener('click', function() {
            const audio = this.nextElementSibling; //องค์ประกอบเสียง
            const isPlaying = !audio.paused;

            if (isPlaying) {
                audio.pause();
                this.classList.remove('playing');
            } else {
                audio.play();
                this.classList.add('playing');
            }

            if (!currentAudios.includes(audio)) {
                currentAudios.push(audio);
            }
        });
    });

    document.querySelectorAll('.volume-slider').forEach(slider => {
        slider.addEventListener('input', function() {
            const audio = this.parentElement.querySelector('audio');
            if (audio) {
                audio.volume = this.value;
            }
        });
    });

    document.querySelectorAll('.form-check-input').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const isChecked = this.checked;
            const audio = this.parentElement.querySelector('audio');
            if (audio) {
                if (isChecked) {
                    audio.play();
                    currentAudios.push(audio);
                } else {
                    audio.pause();
                    currentAudios = currentAudios.filter(a => a !== audio);
                }
            }
        });
    });

    // จัดการฟังก์ชันลูป
    document.querySelectorAll('audio').forEach(audio => {
        const loop = audio.getAttribute('data-loop') === '1';
        audio.loop = loop;
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
document.addEventListener("DOMContentLoaded", function() {
    // Get the checkbox inputs and submit button
    const checkboxes = document.querySelectorAll('input[name="selected_files[]"]');
    const submitButton = document.getElementById('submitButton');

    // Function to check if at least one checkbox is selected
    function toggleSubmitButton() {
        // Check if any checkbox is selected
        const isChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);

        // Enable or disable the submit button based on checkbox selection
        submitButton.disabled = !isChecked;

        // Add or remove a class for visual indication
        if (isChecked) {
            submitButton.classList.remove('btn-disabled');
        } else {
            submitButton.classList.add('btn-disabled');
        }
    }

    // Initial state of the button when the page loads
    toggleSubmitButton();

    // Add event listeners to the checkboxes to trigger toggleSubmitButton function
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleSubmitButton);
    });
});


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