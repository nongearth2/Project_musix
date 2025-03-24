document.addEventListener('DOMContentLoaded', () => {
    let currentAudio = null; // ตัวแปรสำหรับเก็บออบเจ็กต์เสียงปัจจุบัน
    let currentButton = null; // ตัวแปรสำหรับเก็บปุ่มที่กำลังเล่นอยู่

    // ค้นหาทุกปุ่มเล่นเสียง
    document.querySelectorAll('.play-button').forEach(button => {
        const soundImage = button.querySelector('img'); // สมมติว่ามี <img> ภายในปุ่ม
        const soundItem = button.closest('.sound-item'); // หาตัวป้ายที่เก็บปุ่มเล่นเสียง

        // ตรวจสอบสถานะของเสียงและปรับภาพตามสถานะ
        const workingStatus = parseInt(button.getAttribute('data-status'), 10);
        if (workingStatus === 0) {
            // หาก working_status = 0 ให้ทำให้ภาพจาง
            soundImage.classList.add('faded'); 
            soundImage.alt = 'เสียงนี้ถูกปิดใช้งาน';
            soundImage.title = 'เสียงนี้ถูกปิดใช้งาน';
            
            // เพิ่มคลาส disabled ให้กับ sound-item เพื่อให้แสดงว่าเสียงนี้ถูกปิดใช้งาน
            soundItem.classList.add('disabled'); 
        }

        button.addEventListener('click', function () {
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