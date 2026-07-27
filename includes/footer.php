<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content neo-border neo-shadow text-center p-4" style="background-color: var(--neo-yellow);">
            <div class="mb-3 text-dark">
                <div class="bg-white neo-border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 3px 3px 0px var(--black);">
                    <i class="fas fa-trash-alt fs-3"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-2 text-dark">ARE YOU SURE?</h5>
            <p class="small mb-4 text-dark fw-bold">This action cannot be undone. Delete this?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                <a href="#" id="confirmDeleteBtn" class="btn btn-danger ignore-click">YES, DELETE</a>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="logoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content neo-border neo-shadow text-center p-4" style="background-color: var(--neo-yellow);">
            <div class="mb-3 text-dark">
                <div class="bg-white neo-border rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 60px; height: 60px; box-shadow: 3px 3px 0px var(--black);">
                    <i class="fas fa-sign-out-alt fs-3 ps-1"></i>
                </div>
            </div>
            <h5 class="fw-bold mb-2 text-dark">LOG OUT?</h5>
            <p class="small mb-4 text-dark fw-bold">Ready to end your current session?</p>
            <div class="d-flex gap-2 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">CANCEL</button>
                <a href="actions/auth.php?action=logout" class="btn btn-danger fw-bold ignore-click" onclick="sessionStorage.clear();">YES, LOGOUT</a>
            </div>
        </div>
    </div>
</div>

<?php include_once __DIR__ . '/../views/modal_add_task.php'; ?>

</main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script src="assets/js/script.js"></script>

<!-- ================= KUMPULAN ELEMEN AUDIO ================= -->
<audio id="soundDone" src="assets/audio/nice.mp3" preload="auto"></audio>
<audio id="soundClick" src="assets/audio/click.mp3" preload="auto"></audio>
<audio id="soundWelcome" src="assets/audio/welcome.mp3" preload="auto"></audio>
<audio id="soundBgm" src="assets/audio/bgm.mp3" loop preload="auto"></audio>
<!-- KUMPULAN AUDIO TAMBAHAN -->
<audio id="soundHover" src="assets/audio/hover.mp3" preload="auto"></audio>
<!-- <audio id="soundError" src="assets/audio/error.mp3" preload="auto"></audio>
<audio id="soundSuccess" src="assets/audio/success.mp3" preload="auto"></audio> -->
<audio id="soundModal" src="assets/audio/modal.mp3" preload="auto"></audio>
<audio id="soundModalReverse" src="assets/audio/reversed_modal.wav" preload="auto"></audio>
<audio id="soundToggle" src="assets/audio/toggle.mp3" preload="auto"></audio>
<audio id="soundTrash" src="assets/audio/trash.mp3" preload="auto"></audio>

<?php
// FITUR SUARA WELCOME (Berjalan 1x saat login)
if (isset($_SESSION['play_welcome']) && $_SESSION['play_welcome'] === true) :
?>
    <script>
        // Hapus sisa waktu lagu dari sesi pengguna sebelumnya saat baru masuk
        sessionStorage.removeItem('runtut_bgm_time');

        document.addEventListener("DOMContentLoaded", function() {
            const welcomeSound = document.getElementById('soundWelcome');
            let sliderVal = localStorage.getItem('runtut_sfx_slider') || 1.0;

            // SFX menggunakan skala 100% (1.0)
            welcomeSound.volume = parseFloat(sliderVal) * 1.0;
            setTimeout(function() {
                welcomeSound.play();
            }, 300);
        });
    </script>
<?php
    unset($_SESSION['play_welcome']);
endif;
?>

<!-- ================= LOGIKA UTAMA AUDIO ================= -->
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const bgm = document.getElementById('soundBgm');
        const sfxSlider = document.getElementById('sfxVolume');
        const bgmSlider = document.getElementById('bgmVolume');

        // 1. BATAS MAKSIMAL ASLI DI BALIK LAYAR
        const MAX_BGM = 0.3; // Mentok di 50% dari suara file asli
        const MAX_SFX = 1.0; // Mentok di 100% dari suara file asli

        // 2. AMBIL NILAI TAMPILAN SLIDER DARI MEMORI (Bukan volume asli)
        let savedSfxSlider = localStorage.getItem('runtut_sfx_slider');
        let savedBgmSlider = localStorage.getItem('runtut_bgm_slider');

        // Jika baru pertama buka, set tampilan slider ke 1.0 (100% secara visual)
        savedSfxSlider = savedSfxSlider !== null ? parseFloat(savedSfxSlider) : 1.0;
        savedBgmSlider = savedBgmSlider !== null ? parseFloat(savedBgmSlider) : 1.0;

        // Terapkan ke UI Slider di Dropdown Profil
        if (sfxSlider) sfxSlider.value = savedSfxSlider;
        if (bgmSlider) bgmSlider.value = savedBgmSlider;

        // Terapkan ke Mesin Audio (Nilai Tampilan Slider dikali Batas Maksimal)
        bgm.volume = savedBgmSlider * MAX_BGM;

        // 3. DETEKSI GESERAN SLIDER
        if (bgmSlider) {
            bgmSlider.addEventListener('input', function() {
                let displayValue = parseFloat(this.value);
                bgm.volume = displayValue * MAX_BGM; // Konversi ke volume asli yang dicekik
                localStorage.setItem('runtut_bgm_slider', displayValue);
            });
        }
        if (sfxSlider) {
            sfxSlider.addEventListener('input', function() {
                let displayValue = parseFloat(this.value);
                savedSfxSlider = displayValue;
                localStorage.setItem('runtut_sfx_slider', displayValue);
            });
        }
        // Fungsi Helper untuk memutar SFX dengan volume dinamis
        window.playSfx = function(soundId) {
            const sound = document.getElementById(soundId);
            if (sound) {
                const soundClone = sound.cloneNode();
                soundClone.volume = savedSfxSlider * MAX_SFX;
                soundClone.play().catch(() => {});
            }
        };
        // 4. LOGIKA WAKTU BGM (Gunakan sessionStorage agar mati saat web di-close)
        let savedTime = sessionStorage.getItem('runtut_bgm_time');
        if (savedTime) {
            bgm.currentTime = parseFloat(savedTime);
        }

        bgm.addEventListener('timeupdate', function() {
            sessionStorage.setItem('runtut_bgm_time', bgm.currentTime);
        });

        // Paksa BGM Putar
        bgm.play().catch(() => {
            document.addEventListener('click', function() {
                if (bgm.paused) bgm.play();
            }, {
                once: true
            });
        });

        // 5. FUNGSI SUARA TUGAS SELESAI (nice.mp3)
        window.playDoneSound = function(url) {
            const sound = document.getElementById('soundDone');
            sound.volume = savedSfxSlider * MAX_SFX; // Kalikan dengan 100%
            sound.play();
            sound.onended = function() {
                window.location.href = url;
            };
        };

        // 6. PENANGKAP KLIK GLOBAL (click.mp3)
        document.addEventListener('click', function(event) {
            if (event.target.closest('.ignore-click')) return;

            const clickSound = document.getElementById('soundClick');
            const soundClone = clickSound.cloneNode();
            soundClone.volume = savedSfxSlider * MAX_SFX; // Kalikan dengan 100%

            let targetLink = event.target.closest('a');
            if (targetLink && targetLink.href && targetLink.getAttribute('href') !== '#' && !targetLink.hasAttribute('data-bs-toggle')) {
                event.preventDefault();
                soundClone.play();
                setTimeout(function() {
                    window.location.href = targetLink.href;
                }, 80);
            } else {
                soundClone.play();
            }
        });
        // A. HOVER / FOCUS (Untuk elemen tombol, menu, dan kartu)
        document.querySelectorAll('.btn, .dropdown-item, .card, .nav-link').forEach(el => {
            el.addEventListener('mouseenter', () => playSfx('soundHover'));
        });

        // --- B. MODAL / POP-UP DIBUKA ---
        document.addEventListener('show.bs.modal', function() {
            playSfx('soundModal');
        });


        // --- C. MODAL / POP-UP DITUTUP (Reverse) ---
        // hide.bs.modal sangat canggih: ini akan mendeteksi klik tombol X, klik tombol Cancel, 
        // klik di luar kotak, hingga menekan tombol ESC di keyboard!
        document.addEventListener('hide.bs.modal', function() {
            playSfx('soundModalReverse');
        });

        // --- D. TRASH / DELETE (Klik tombol YES, DELETE) ---
        document.addEventListener('click', function(e) {
            const confirmBtn = e.target.closest('#confirmDeleteBtn');

            if (confirmBtn) {
                // 1. Tahan dulu agar tidak langsung pindah halaman/terhapus
                e.preventDefault();

                // 2. Putar suara buang sampah
                playSfx('soundTrash');

                // 3. Beri jeda agar suara selesai berbunyi, baru eksekusi link penghapusannya
                // (Ubah angka 400 sesuai dengan durasi panjang file trash.mp3 Anda dalam milidetik)
                setTimeout(function() {
                    window.location.href = confirmBtn.href;
                }, 330);
            }
        });

        // C. TOGGLE / SWITCH (Untuk checkbox seperti Anytime atau Recurring)
        document.addEventListener('change', function(e) {
            // Mengecualikan checkbox tugas utama yang sudah memakai nice.mp3
            if (e.target.matches('input[type="checkbox"], input[type="radio"]') && !e.target.closest('.ignore-click')) {
                playSfx('soundToggle');
            }
        });
    });
</script>
</body>

</html>

</body>

</html>