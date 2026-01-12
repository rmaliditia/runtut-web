// assets/js/script.js

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");

  if (calendarEl) {
    // Variabel untuk menyimpan elemen tanggal terakhir yang diklik
    var selectedDayEl = null;

    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: "dayGridMonth",
      themeSystem: "bootstrap5",
      displayEventTime: false,
      selectable: true,

      headerToolbar: {
        left: "prev",
        center: "title",
        right: "next",
      },
      height: "auto",
      contentHeight: 600,
      events: "actions/api_calendar.php",

      // --- 1. FITUR VISUAL DEFAULT (Highlight Hari Ini) ---
      datesSet: function () {
        // Cari elemen hari ini
        var todayEl = document.querySelector(".fc-day-today");
        if (todayEl) {
          // Tambahkan class selected
          todayEl.classList.add("fc-day-selected");
          // Simpan ke variabel global agar bisa dihapus saat klik tanggal lain
          selectedDayEl = todayEl;
        }
      },

      // --- 2. LOGIKA KLIK TANGGAL ---
      dateClick: function (info) {
        // Hapus highlight dari tanggal sebelumnya
        if (selectedDayEl) {
          selectedDayEl.classList.remove("fc-day-selected");
        }

        // Tambahkan highlight ke tanggal baru
        info.dayEl.classList.add("fc-day-selected");
        selectedDayEl = info.dayEl;

        // Ubah Judul di Kiri
        const dateObj = new Date(info.dateStr);
        const options = { weekday: "long", day: "numeric", month: "long" };
        document.getElementById("scheduleTitle").innerText =
          dateObj.toLocaleDateString("en-US", options);

        // Ambil Data via AJAX
        const listContainer = document.getElementById("scheduleList");
        listContainer.innerHTML =
          '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div></div>';

        fetch("actions/get_daily_schedule.php?date=" + info.dateStr)
          .then((response) => response.text())
          .then((html) => {
            listContainer.innerHTML = html;
          })
          .catch((err) => {
            console.error(err);
            listContainer.innerHTML =
              '<p class="text-danger text-center">Failed to load data.</p>';
          });
      },
    });

    calendar.render();
  }
});

// SCRIPT TOOLTIP (Update bagian ini agar lebih aman)
document.addEventListener("DOMContentLoaded", function () {
  // Cek apakah bootstrap sudah terload
  if (typeof bootstrap !== "undefined") {
    const tooltipTriggerList = document.querySelectorAll(
      '[data-bs-toggle="tooltip"]'
    );
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl)
    );
  }
});

// SCRIPT CHART.JS (Pastikan kode ini ada di paling bawah)
document.addEventListener("DOMContentLoaded", function () {
  const chartBarEl = document.getElementById("weeklyChart");
  const chartPieEl = document.getElementById("categoryChart");

  // Hanya jalankan jika elemen grafik ditemukan (halaman Progress)
  if (chartBarEl && chartPieEl) {
    fetch("actions/api_chart.php")
      .then((response) => response.json())
      .then((data) => {
        // 1. Render Bar Chart
        new Chart(chartBarEl, {
          type: "bar",
          data: {
            labels: data.bar.labels,
            datasets: [
              {
                label: "Tasks Completed",
                data: data.bar.data,
                backgroundColor: "#4379EE",
                borderRadius: 6,
                barThickness: 25,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: { beginAtZero: true, grid: { borderDash: [5, 5] } },
              x: { grid: { display: false } },
            },
          },
        });

        // 2. Render Pie Chart
        const colorMap = {
          Work: "#4379EE",
          Personal: "#FF9F43",
          Study: "#00b894",
          Health: "#ff7675",
        };
        // Fallback warna jika data kosong/kategori baru
        const bgColors = (data.pie.labels || []).map(
          (label) => colorMap[label] || "#6c5ce7"
        );

        new Chart(chartPieEl, {
          type: "doughnut",
          data: {
            labels: data.pie.labels,
            datasets: [
              {
                data: data.pie.data,
                backgroundColor: bgColors,
                borderWidth: 0,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: "70%",
            plugins: {
              legend: {
                position: "bottom",
                labels: { usePointStyle: true, padding: 20 },
              },
            },
          },
        });
      })
      .catch((error) => console.error("Gagal memuat grafik:", error));
  }
});

// --- GLOBAL DELETE MODAL HANDLER ---
document.addEventListener("DOMContentLoaded", function () {
  // Gunakan Event Delegation agar elemen AJAX (seperti di kalender) juga bisa kena
  document.body.addEventListener("click", function (e) {
    // Cari apakah yang diklik adalah tombol .btn-delete atau anak elemennya (icon)
    const deleteBtn = e.target.closest(".btn-delete");

    if (deleteBtn) {
      e.preventDefault(); // Cegah link langsung jalan

      const deleteUrl = deleteBtn.getAttribute("href"); // Ambil link hapus
      const confirmBtn = document.getElementById("confirmDeleteBtn");

      // Masukkan link ke tombol "Yes, Delete" di modal
      confirmBtn.setAttribute("href", deleteUrl);

      // Tampilkan Modal
      const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal")
      );
      deleteModal.show();
    }
  });
});
