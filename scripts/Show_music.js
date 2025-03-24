function showSongsByCategory(category) {
    const audioControls = document.getElementById('audioControls');
    audioControls.innerHTML = ''; // ลบปุ่มที่ไม่ได้ใช้ในหน้าที่เลือกออก

    if (audioFiles[category]) {
        const enabledFiles = audioFiles[category].filter(file => file.working_status === '1');
        const disabledFiles = audioFiles[category].filter(file => file.working_status === '0');
        const sortedFiles = [...enabledFiles, ...disabledFiles];

        sortedFiles.forEach(file => {
            const soundItem = document.createElement('div');
            soundItem.className = 'sound-item';

            // เพิ่มคลาส disabled หาก working_status = 0
            if (file.working_status === '0') {
                soundItem.classList.add('disabled');
            }

            const soundImage = document.createElement('img');
            soundImage.src = file.image;
            soundImage.alt = file.id;
            soundImage.className = 'sound-image';

            if (file.working_status === '0') {
                soundImage.classList.add('disabled-sound');
                soundImage.alt = 'เสียงนี้ถูกปิดใช้งาน';
                soundImage.title = 'เสียงนี้ถูกปิดใช้งาน';
                soundImage.onclick = () => {
                    Swal.fire({
                        icon: 'warning',
                        title: 'ขออภัยไม่สามารถเล่นเสียงนี้ได้',
                        text: 'เสียงนี้ถูกปิดการใช้งาน'
                    });
                };
            } else {
                soundImage.classList.remove('disabled-sound');
                soundImage.onclick = () => togglePlay(file.id, soundItem);
            }

            const volumeSlider = document.createElement('input');
            volumeSlider.type = 'range';
            volumeSlider.className = 'volume-slider';
            volumeSlider.min = 0;
            volumeSlider.max = 100;
            volumeSlider.value = 50;
            volumeSlider.oninput = () => adjustVolume(file.id, volumeSlider.value);

            const volumePercentage = document.createElement('span');
            volumePercentage.className = 'volume-percentage';
            volumePercentage.innerHTML = '';

            const audioElement = document.createElement('audio');
            audioElement.id = file.id;
            audioElement.src = file.src;
            audioElement.loop = true;

            soundItem.appendChild(soundImage);
            soundItem.appendChild(volumeSlider);
            soundItem.appendChild(volumePercentage);
            soundItem.appendChild(audioElement);

            audioControls.appendChild(soundItem);
        });
    }
}



function togglePlay(audioId, soundItem) {
    var audio = document.getElementById(audioId);
    var isPlaying = !audio.paused; // ตรวจสอบว่าเสียงกำลังเล่นอยู่หรือไม่

    // หากเสียงกำลังเล่นอยู่
    if (isPlaying) {
        audio.pause(); // หยุดเสียง
        audio.currentTime = 0; // รีเซ็ตเวลาเสียง
        soundItem.classList.remove('playing'); // ลบคลาส playing เมื่อหยุดเสียง
        soundItem.classList.remove('selected'); // ลบคลาส selected เมื่อหยุดเสียง
    } else {
        audio.play(); // เล่นเสียง
        soundItem.classList.add('playing'); // เพิ่มคลาส playing เมื่อเสียงกำลังเล่น
        soundItem.classList.add('selected'); // เพิ่มคลาส selected เพื่อแสดงว่าเสียงนี้ถูกเลือก
    }
}

function adjustVolume(audioId, volume) {
    var audio = document.getElementById(audioId);
    audio.volume = volume / 100;

    // อัพเดตเปอร์เซ็นต์
    const soundItem = document.querySelector(`#${audioId}`).parentElement;
    const volumePercentage = soundItem.querySelector('.volume-percentage');
    volumePercentage.innerHTML = `${volume}%`;
}
function displayCategoryButtons() {
    const categoryButtons = document.getElementById('categoryButtons');
    categoryButtons.innerHTML = '';

    for (const [id, name] of Object.entries(categories)) {
        const button = document.createElement('button');
        button.innerHTML = name;
        button.classList.add('category-button'); // เพิ่มคลาสสำหรับสไตล์
        button.onclick = () => {
            showSongsByCategory(id);
            setActiveCategory(id); // กำหนดให้ปุ่มที่เลือกมีเอฟเฟกต์ hover ค้างไว้
        };
        categoryButtons.appendChild(button);
    }
}

// ฟังก์ชันกำหนดปุ่มหมวดหมู่ที่ถูกเลือก
function setActiveCategory(selectedId) {
    const buttons = document.querySelectorAll('.category-button');
    buttons.forEach(button => {
        if (button.innerHTML === categories[selectedId]) {
            button.classList.add('active'); // เพิ่มคลาส active
        } else {
            button.classList.remove('active'); // ลบคลาสออกจากปุ่มที่ไม่ได้ถูกเลือก
        }
    });
}

// แสดงปุ่มหมวดหมู่และเพลงทั้งหมดเมื่อโหลดหน้า
window.onload = function () {
    displayCategoryButtons();
    const firstCategoryId = Object.keys(categories)[0];
    if (firstCategoryId) {
        showSongsByCategory(firstCategoryId);
        setActiveCategory(firstCategoryId);
    }
};

/*!
* Start Bootstrap - Business Casual v7.0.9 (https://startbootstrap.com/theme/business-casual)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-business-casual/blob/master/LICENSE)
*/
// Highlights current date on contact page
window.addEventListener('DOMContentLoaded', event => {
    const listHoursArray = document.body.querySelectorAll('.list-hours li');
    listHoursArray[new Date().getDay()].classList.add(('today'));
})

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



