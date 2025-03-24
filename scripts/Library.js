document.addEventListener('DOMContentLoaded', function() {
    const volumeControl = document.getElementById('volumeControl');
    const volumeIcon = document.getElementById('volumeIcon');
    const volumePercentage = document.getElementById('volumePercentage');
    let audioElements = [];
    let currentCategory = null;
    let isMuted = false;

    // ฟังก์ชันในการอัพเดตไอคอนเมื่อปรับระดับเสียง
    volumeControl.addEventListener('input', function() {
        const volume = volumeControl.value;

        if (volume == 0) {
            volumeIcon.classList.remove('fa-volume-high', 'fa-volume-medium', 'fa-volume-low');
            volumeIcon.classList.add('fa-volume-xmark');
            isMuted = true;  // ตั้งค่าปิดเสียง
        } else if (volume <= 30) {
            volumeIcon.classList.remove('fa-volume-high', 'fa-volume-medium', 'fa-volume-xmark');
            volumeIcon.classList.add('fa-volume-low');
            isMuted = false;
        } else if (volume <= 70) {
            volumeIcon.classList.remove('fa-volume-high', 'fa-volume-medium', 'fa-volume-xmark');
            volumeIcon.classList.add('fa-volume-low');
            isMuted = false;
        } else {
            volumeIcon.classList.remove('fa-volume-medium', 'fa-volume-low', 'fa-volume-xmark');
            volumeIcon.classList.add('fa-volume-high');
            isMuted = false;
        }

        updateVolumeDisplay(volume);
        audioElements.forEach(audio => {
            audio.volume = volume / 100;
        });
    });

    // ฟังก์ชันสำหรับคลิกไอคอนเพื่อปิด/เปิดเสียง
volumeIcon.addEventListener('click', function() {
    if (isMuted) {
        // หากเสียงปิดอยู่ ให้เปิดเสียงและตั้งค่าเป็น 50%
        volumeControl.value = 50;
        volumeIcon.classList.remove('fa-volume-xmark');
        volumeIcon.classList.add('fa-volume-high');
        isMuted = false;

        // ตั้งค่าเสียงให้กับไฟล์เสียงทุกไฟล์ใน audioElements
        audioElements.forEach(audio => {
            audio.volume = 0.5;  // เสียงที่ตั้งไว้ 50%
        });
    } else {
        // หากเสียงเปิดอยู่ ให้ปิดเสียง
        volumeControl.value = 0;
        volumeIcon.classList.remove('fa-volume-high', 'fa-volume-medium', 'fa-volume-low');
        volumeIcon.classList.add('fa-volume-xmark');
        isMuted = true;

        // ตั้งค่าเสียงให้กับไฟล์เสียงทุกไฟล์ใน audioElements
        audioElements.forEach(audio => {
            audio.volume = 0;  // ปิดเสียง
        });
    }
});


    // ฟังก์ชันอัปเดตการแสดงผลระดับเสียง
    function updateVolumeDisplay(volume) {
        volumePercentage.textContent = `${Math.round(volume)}%`;
    }

    function updateAudioElements() {
        audioElements.forEach(audio => {
            audio.pause();
        });
        audioElements = [];

        if (currentCategory && audioFiles[currentCategory]) {
            audioFiles[currentCategory].forEach(file => {
                if (file.working_status === '1') {
                    const audio = new Audio(file.src);
                    audio.loop = true;

                    // ใช้ค่าระดับเสียงที่กำหนดไว้ใน slider ของแต่ละเสียง
                    const volumeSlider = document.querySelector(`#volume-slider-${file.id}`);
                    if (volumeSlider) {
                        audio.volume = volumeSlider.value / 100; 
                    } else {
                        audio.volume = 0.5; // ถ้าไม่เจอ slider ใช้ค่าเริ่มต้น 50%
                    }
                    
                    audioElements.push(audio);
                }
            });
        }
    }

    // เล่นไฟล์เสียงทั้งหมดในหมวดหมู่ปัจจุบันที่มี working_status = 1
    const playListBtn = document.getElementById('playListBtn');
    playListBtn.addEventListener('click', () => {
        updateAudioElements();
        audioElements.forEach(audio => {
            audio.play();
        });
    });

    // หยุดไฟล์เสียงทั้งหมดในหมวดหมู่ปัจจุบัน
    const stopListBtn = document.getElementById('stopListBtn');
    stopListBtn.addEventListener('click', () => {
        audioElements.forEach(audio => {
            audio.pause();
            audio.currentTime = 0; // รีเซ็ตการเล่นใหม่
        });
    });

    // การตั้งค่าระดับเสียงเริ่มต้น
    window.onload = function () {
        const initialVolume = 100; // 100%
        volumeControl.value = initialVolume;
        updateVolumeDisplay(initialVolume);
        audioElements.forEach(audio => {
            audio.volume = initialVolume / 100;
        });
    };

    function showSongsByCategory(category) {
        currentCategory = category;
        updateAudioElements();
    
        const audioControls = document.getElementById('audioControls');
        audioControls.innerHTML = ''; // ล้างการควบคุมที่มีอยู่
    
        if (audioFiles[category]) {
            audioFiles[category].forEach(file => {
                const soundItem = document.createElement('div');
                soundItem.className = 'sound-item';
    
                // สร้างภาพของเสียง
                const soundImage = document.createElement('img');
                soundImage.src = file.image || 'default-image.png'; // กำหนดภาพเริ่มต้น
                soundImage.alt = file.id;
                soundImage.className = 'sound-image';
    
                // ถ้า working_status = 0 (ปิดใช้งาน)
                if (file.working_status === '0') {
                    soundImage.classList.add('disabled-sound');
                    soundImage.alt = 'เสียงนี้ถูกปิดใช้งาน';
                    soundImage.title = 'เสียงนี้ถูกปิดใช้งาน';
                    soundItem.classList.add('disabled'); // เพิ่มคลาส disabled ให้กับ sound-item
    
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
    
                // สร้างตัวควบคุมระดับเสียง
                const volumeSlider = document.createElement('input');
                volumeSlider.type = 'range';
                volumeSlider.id = `volume-slider-${file.id}`;
                volumeSlider.className = 'volume-slider';
                volumeSlider.min = 0;
                volumeSlider.max = 100;
                volumeSlider.value = 50; // ค่าเริ่มต้นที่ 50%
                volumeSlider.oninput = () => adjustVolume(file.id, volumeSlider.value);
    
                // แสดงเปอร์เซ็นต์ระดับเสียง
                const volumePercentage = document.createElement('span');
                volumePercentage.className = 'volume-percentage';
                volumePercentage.innerHTML = ` ${volumeSlider.value}%`;
    
                volumeSlider.addEventListener('input', function () {
                    volumePercentage.innerHTML = `${volumeSlider.value}%`; // อัปเดตเปอร์เซ็นต์เสียงทันที
                });
    
                // สร้างตัวเสียง (audio element)
                const audioElement = document.createElement('audio');
                audioElement.id = file.id;
                audioElement.src = file.src;
                audioElement.loop = true;
    
                // เพิ่ม element ต่างๆ เข้าไปใน soundItem
                soundItem.appendChild(soundImage);
                soundItem.appendChild(volumeSlider);
                soundItem.appendChild(volumePercentage);
                soundItem.appendChild(audioElement);
    
                // เพิ่ม soundItem เข้าไปใน audioControls
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

    function adjustVolume(audioId, value) {
        const audio = document.getElementById(audioId);
        audio.volume = value / 100;
    }

    function displayCategoryButtons() {
        const categoryButtons = document.getElementById('categoryButtons');
        categoryButtons.innerHTML = '';
    
        for (const [id, name] of Object.entries(categories)) {
            const button = document.createElement('button');
            button.classList.add('category-button'); // เพิ่มคลาสปุ่ม
            button.innerHTML = name;
    
            button.onclick = () => {
                // ลบคลาส active จากปุ่มทั้งหมด
                document.querySelectorAll('.category-button').forEach(btn => btn.classList.remove('active'));
    
                // เพิ่มคลาส active ให้ปุ่มที่ถูกคลิก
                button.classList.add('active');
    
                // แสดงเพลงของหมวดหมู่ที่เลือก
                showSongsByCategory(id);
    
                // หยุดเสียงทั้งหมดเมื่อเปลี่ยนหมวดหมู่
                document.querySelectorAll('audio').forEach(audio => {
                    audio.pause();
                    audio.currentTime = 0;
                });
            };
    
            categoryButtons.appendChild(button);
        }
    }
    
    // แสดงปุ่มหมวดหมู่และแสดงเพลงหมวดหมู่แรกเมื่อโหลดหน้า
    window.onload = function () {
        displayCategoryButtons();
    
        // เลือกหมวดหมู่แรกและทำให้ active
        const firstCategoryId = Object.keys(categories)[0];
        if (firstCategoryId) {
            showSongsByCategory(firstCategoryId);
            const firstButton = document.querySelector('.category-button');
            if (firstButton) firstButton.classList.add('active');
        }
    };
    
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
