// assets/js/script.js

document.addEventListener("DOMContentLoaded", function () {
  var calendarEl = document.getElementById("calendar");

  if (calendarEl) {
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

      datesSet: function () {
        var todayEl = document.querySelector(".fc-day-today");
        if (todayEl) {
          todayEl.classList.add("fc-day-selected");
          selectedDayEl = todayEl;
        }
      },

      dateClick: function (info) {
        if (selectedDayEl) {
          selectedDayEl.classList.remove("fc-day-selected");
        }

        info.dayEl.classList.add("fc-day-selected");
        selectedDayEl = info.dayEl;

        const dateObj = new Date(info.dateStr);
        const options = { weekday: "long", day: "numeric", month: "long" };
        document.getElementById("scheduleTitle").innerText = dateObj
          .toLocaleDateString("en-US", options)
          .toUpperCase();

        const listContainer = document.getElementById("scheduleList");
        listContainer.innerHTML =
          '<div class="text-center py-4"><div class="spinner-border text-dark" role="status"></div></div>';

        fetch("actions/get_daily_schedule.php?date=" + info.dateStr)
          .then((response) => response.text())
          .then((html) => {
            listContainer.innerHTML = html;
          })
          .catch((err) => {
            console.error(err);
            listContainer.innerHTML =
              '<p class="text-danger fw-bold text-center">Failed to load data.</p>';
          });
      },
    });

    calendar.render();
  }
});

// SCRIPT TOOLTIP
document.addEventListener("DOMContentLoaded", function () {
  if (typeof bootstrap !== "undefined") {
    const tooltipTriggerList = document.querySelectorAll(
      '[data-bs-toggle="tooltip"]',
    );
    const tooltipList = [...tooltipTriggerList].map(
      (tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl),
    );
  }
});

// SCRIPT CHART.JS (NEUBRUTALISM STYLE)
document.addEventListener("DOMContentLoaded", function () {
  const chartBarEl = document.getElementById("weeklyChart");
  const chartPieEl = document.getElementById("categoryChart");

  if (chartBarEl && chartPieEl) {
    fetch("actions/api_chart.php")
      .then((response) => response.json())
      .then((data) => {
        // 1. Render Bar Chart (Biru Terang + Border Hitam Tebal)
        new Chart(chartBarEl, {
          type: "bar",
          data: {
            labels: data.bar.labels,
            datasets: [
              {
                label: "Tasks Completed",
                data: data.bar.data,
                backgroundColor: "#90a8ed",
                borderColor: "#111111",
                borderWidth: 3,
                borderRadius: 4,
                barThickness: 30,
              },
            ],
          },
          options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
              y: {
                beginAtZero: true,
                grid: { color: "#111111", borderDash: [2, 2], lineWidth: 2 },
                ticks: {
                  font: {
                    family: "'JetBrains Mono', monospace",
                    weight: "bold",
                    size: 12,
                  },
                  color: "#111",
                },
              },
              x: {
                grid: { display: false },
                ticks: {
                  font: {
                    family: "'JetBrains Mono', monospace",
                    weight: "bold",
                    size: 12,
                  },
                  color: "#111",
                },
              },
            },
          },
        });

        // 2. Render Pie Chart (Warna Neubrutalism)
        if (data.pie.data.length === 0) {
          // JIKA KOSONG: Tampilkan pesan penyemangat
          const parent = chartPieEl.parentElement;
          parent.innerHTML = `
            <div class="d-flex flex-column justify-content-center align-items-center text-center h-100 py-4 fade-in">
                <i class="fas fa-rocket text-dark mb-3" style="font-size: 3rem;"></i>
                <h4 class="fw-bold text-dark mb-2">A CANVAS AWAITS!</h4>
                <p class="text-dark fw-bold small mb-0" style="font-family: 'JetBrains Mono', monospace;">
                    Your journey starts here. Create a task and let's build some momentum!
                </p>
            </div>
          `;
        } else {
          // JIKA ADA DATA: Render grafik seperti biasa
          const colorMap = {
            Work: "#90a8ed",
            Personal: "#f8e136",
            Study: "#b1e87a",
            Health: "#ff90e8",
            None: "#6c757d", // Warna untuk kategori "None"
          };

          const bgColors = (data.pie.labels || []).map(
            (label) => colorMap[label] || "#c4a1ff",
          );

          new Chart(chartPieEl, {
            type: "doughnut",
            data: {
              labels: data.pie.labels,
              datasets: [
                {
                  data: data.pie.data,
                  backgroundColor: bgColors,
                  borderColor: "#111111",
                  borderWidth: 3,
                  hoverOffset: 4,
                },
              ],
            },
            options: {
              responsive: true,
              maintainAspectRatio: false,
              cutout: "60%",
              plugins: {
                legend: {
                  position: "bottom",
                  labels: {
                    usePointStyle: true,
                    padding: 20,
                    font: {
                      family: "'JetBrains Mono', monospace",
                      weight: "bold",
                      size: 12,
                    },
                    color: "#111",
                  },
                },
              },
            },
          });
        }
      })
      .catch((error) => console.error("Gagal memuat grafik:", error));
  }
});

// GLOBAL DELETE MODAL HANDLER
document.addEventListener("DOMContentLoaded", function () {
  document.body.addEventListener("click", function (e) {
    const deleteBtn = e.target.closest(".btn-delete");
    if (deleteBtn) {
      e.preventDefault();
      const deleteUrl = deleteBtn.getAttribute("href");
      const confirmBtn = document.getElementById("confirmDeleteBtn");
      confirmBtn.setAttribute("href", deleteUrl);
      const deleteModal = new bootstrap.Modal(
        document.getElementById("deleteModal"),
      );
      deleteModal.show();
    }
  });
});
// ========================================================
// LOGIKA ANYTIME & RECURRING TASK (MODAL)
// ========================================================
document.addEventListener("DOMContentLoaded", function () {
  const anytimeCheck = document.getElementById("anytimeCheck");
  const dueDateInput = document.getElementById("dueDateInput");
  const repeatCheck = document.getElementById("repeatCheck");
  const repeatType = document.getElementById("repeatType");
  const repeatHelp = document.getElementById("repeatHelp");

  const optWeekly = document.getElementById("optWeekly");
  const optMonthly = document.getElementById("optMonthly");
  const optYearly = document.getElementById("optYearly");

  const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
  ];
  const months = [
    "Jan",
    "Feb",
    "Mar",
    "Apr",
    "May",
    "Jun",
    "Jul",
    "Aug",
    "Sep",
    "Oct",
    "Nov",
    "Dec",
  ];

  if (dueDateInput && anytimeCheck) {
    // 1. Teks Dinamis saat Tanggal Dipilih
    dueDateInput.addEventListener("change", function () {
      if (this.value) {
        repeatCheck.disabled = false; // Buka kunci Ulangi

        let d = new Date(this.value);
        optWeekly.text = `Weekly (Every ${days[d.getDay()]})`;
        optMonthly.text = `Monthly (Every ${d.getDate()})`;
        optYearly.text = `Tahunan (Every ${months[d.getMonth()]} ${d.getDate()})`;
      } else {
        resetRepeatUI();
      }
    });

    // 2. Logika Checkbox Anytime saling kunci
    anytimeCheck.addEventListener("change", function () {
      if (this.checked) {
        dueDateInput.value = "";
        dueDateInput.disabled = true;
        dueDateInput.required = false;
        resetRepeatUI();
      } else {
        dueDateInput.disabled = false;
        dueDateInput.required = true;
      }
    });

    // 3. Tampilkan Dropdown saat Ulangi Task dicentang
    repeatCheck.addEventListener("change", function () {
      if (this.checked) {
        repeatType.classList.remove("d-none");
        repeatHelp.classList.remove("d-none");
      } else {
        repeatType.classList.add("d-none");
        repeatHelp.classList.add("d-none");
      }
    });

    // Helper Fungsi Reset UI
    function resetRepeatUI() {
      repeatCheck.disabled = true;
      repeatCheck.checked = false;
      repeatType.classList.add("d-none");
      repeatHelp.classList.add("d-none");
    }
  }
});

// ========================================================
// LOGIKA EDIT TASK MODAL
// ========================================================
document.addEventListener("DOMContentLoaded", function () {
  // Tangkap event klik pada tombol edit (menggunakan event delegation agar jalan di elemen hasil AJAX)
  document.body.addEventListener("click", function (e) {
    const editBtn = e.target.closest(".btn-edit-task");
    if (editBtn) {
      // Ambil data dari atribut HTML
      const id = editBtn.getAttribute("data-id");
      const title = editBtn.getAttribute("data-title");
      const desc = editBtn.getAttribute("data-desc");
      const cat = editBtn.getAttribute("data-cat");
      const due = editBtn.getAttribute("data-due");
      const recurring = editBtn.getAttribute("data-recurring");
      const rectype = editBtn.getAttribute("data-rectype");

      // Isi ke form modal
      document.getElementById("editTaskId").value = id;
      document.getElementById("editTaskTitle").value = title;
      document.getElementById("editTaskDesc").value = desc;
      document.getElementById("editTaskCategory").value = cat;

      const anytimeCheck = document.getElementById("editAnytimeCheck");
      const dueInput = document.getElementById("editDueDateInput");
      const repeatCheck = document.getElementById("editRepeatCheck");
      const repeatType = document.getElementById("editRepeatType");
      const repeatHelp = document.getElementById("editRepeatHelp");

      // Set Tanggal / Anytime
      if (!due) {
        anytimeCheck.checked = true;
        dueInput.value = "";
        dueInput.disabled = true;
        dueInput.required = false;

        repeatCheck.disabled = true;
        repeatCheck.checked = false;
        repeatType.classList.add("d-none");
        repeatHelp.classList.add("d-none");
      } else {
        anytimeCheck.checked = false;
        dueInput.value = due;
        dueInput.disabled = false;
        dueInput.required = true;

        repeatCheck.disabled = false;
        updateEditRepeatTexts(new Date(due)); // Update teks dinamis
      }

      // Set Recurring
      if (recurring == "1" && due) {
        repeatCheck.checked = true;
        repeatType.classList.remove("d-none");
        repeatHelp.classList.remove("d-none");
        if (rectype) repeatType.value = rectype;
      } else {
        repeatCheck.checked = false;
        repeatType.classList.add("d-none");
        repeatHelp.classList.add("d-none");
      }
    }
  });

  // Logika saling kunci di DALAM Modal Edit
  const editAnytimeCheck = document.getElementById("editAnytimeCheck");
  const editDueInput = document.getElementById("editDueDateInput");
  const editRepeatCheck = document.getElementById("editRepeatCheck");
  const editRepeatType = document.getElementById("editRepeatType");
  const editRepeatHelp = document.getElementById("editRepeatHelp");

  if (editDueInput && editAnytimeCheck) {
    editDueInput.addEventListener("change", function () {
      if (this.value) {
        editRepeatCheck.disabled = false;
        updateEditRepeatTexts(new Date(this.value));
      } else {
        editRepeatCheck.disabled = true;
        editRepeatCheck.checked = false;
        editRepeatType.classList.add("d-none");
        editRepeatHelp.classList.add("d-none");
      }
    });

    editAnytimeCheck.addEventListener("change", function () {
      if (this.checked) {
        editDueInput.value = "";
        editDueInput.disabled = true;
        editDueInput.required = false;

        editRepeatCheck.disabled = true;
        editRepeatCheck.checked = false;
        editRepeatType.classList.add("d-none");
        editRepeatHelp.classList.add("d-none");
      } else {
        editDueInput.disabled = false;
        editDueInput.required = true;
      }
    });

    editRepeatCheck.addEventListener("change", function () {
      if (this.checked) {
        editRepeatType.classList.remove("d-none");
        editRepeatHelp.classList.remove("d-none");
      } else {
        editRepeatType.classList.add("d-none");
        editRepeatHelp.classList.add("d-none");
      }
    });
  }

  function updateEditRepeatTexts(d) {
    const days = [
      "Sunday",
      "Monday",
      "Tuesday",
      "Wednesday",
      "Thursday",
      "Friday",
      "Saturday",
    ];
    const months = [
      "Jan",
      "Feb",
      "Mar",
      "Apr",
      "May",
      "Jun",
      "Jul",
      "Aug",
      "Sep",
      "Oct",
      "Nov",
      "Dec",
    ];

    document.getElementById("editOptWeekly").text =
      `Weekly (Every ${days[d.getDay()]})`;
    document.getElementById("editOptMonthly").text =
      `Monthly (Every ${d.getDate()})`;
    document.getElementById("editOptYearly").text =
      `Yearly (Every ${d.getDate()} ${months[d.getMonth()]})`;
  }
});

// ========================================================
// INISIALISASI BOOTSTRAP POPOVER & LOGIKA SHOW MORE (DYNAMIC)
// ========================================================
document.addEventListener("DOMContentLoaded", function () {
  // 1. Fungsi Inisialisasi Popover
  function initPopovers() {
    const popoverTriggerList = [].slice.call(
      document.querySelectorAll('[data-bs-toggle="popover"]'),
    );

    popoverTriggerList.forEach(function (popoverTriggerEl) {
      // Hapus trigger bawaan HTML (focus) agar kita bisa pakai 'click' sebagai toggle
      popoverTriggerEl.removeAttribute("data-bs-trigger");

      const existingPopover = bootstrap.Popover.getInstance(popoverTriggerEl);
      if (existingPopover) {
        existingPopover.dispose();
      }

      // Buat popover dengan trigger klik agar bisa di-toggle (buka-tutup)
      new bootstrap.Popover(popoverTriggerEl, {
        trigger: "click",
      });

      // UBAH TEKS: Saat Popover muncul, ganti teks menjadi 'Show less'
      popoverTriggerEl.addEventListener("show.bs.popover", function () {
        this.innerText = "Show less";
      });

      // UBAH TEKS: Saat Popover disembunyikan, kembalikan menjadi 'Show more'
      popoverTriggerEl.addEventListener("hide.bs.popover", function () {
        this.innerText = "Show more";
      });
    });
  }

  // 2. Fungsi Pendeteksi Teks Overflow
  function checkTextOverflow() {
    const descElements = document.querySelectorAll(".task-desc-text");

    descElements.forEach((el) => {
      const btn = el.nextElementSibling;

      if (btn && btn.classList.contains("btn-show-more")) {
        setTimeout(() => {
          if (el.scrollHeight > el.clientHeight) {
            btn.classList.remove("d-none");
          } else {
            btn.classList.add("d-none");
          }
        }, 50);
      }
    });
  }

  // 3. Jalankan saat halaman pertama kali dimuat
  initPopovers();
  checkTextOverflow();
  window.addEventListener("resize", checkTextOverflow);

  // 4. CCTV (MUTATION OBSERVER) UNTUK KALENDER AJAX
  const scheduleContainer = document.getElementById("scheduleList");
  if (scheduleContainer) {
    const observer = new MutationObserver(function (mutations) {
      initPopovers();
      checkTextOverflow();
    });
    observer.observe(scheduleContainer, { childList: true, subtree: true });
  }

  // 5. FITUR UX: Tutup popover otomatis jika user klik area kosong di layar
  document.addEventListener("click", function (e) {
    // Cek apakah area yang diklik adalah bagian dari kotak popover
    const isClickInsidePopover = e.target.closest(".popover");

    // Jika BUKAN popover yang diklik...
    if (!isClickInsidePopover) {
      const popoverTriggers = document.querySelectorAll(
        '[data-bs-toggle="popover"]',
      );
      popoverTriggers.forEach(function (btn) {
        // ...dan jika yang diklik juga BUKAN tombol show more-nya
        if (!btn.contains(e.target)) {
          const popoverInstance = bootstrap.Popover.getInstance(btn);
          if (popoverInstance) {
            popoverInstance.hide();
          }
        }
      });
    }
  });
});

// ========================================================
// LOGIKA KUNCI FORM UNTUK TUGAS YANG SUDAH SELESAI
// ========================================================
document.addEventListener("DOMContentLoaded", function () {
  const editModal = document.getElementById("editTaskModal");

  if (editModal) {
    editModal.addEventListener("show.bs.modal", function (event) {
      const button = event.relatedTarget;
      const status = button.getAttribute("data-status");
      const isCompleted = status === "completed";

      const titleInput = editModal.querySelector('[name="title"]');
      const dueDateInput = editModal.querySelector('[name="due_date"]');
      const categorySelect = editModal.querySelector('[name="category"]');
      const recurringSelect = editModal.querySelector('[name="is_recurring"]');

      // Fungsi Helper untuk merubah tampilan UI/UX
      const setLockedUI = (el, isSelect) => {
        if (!el) return;

        // 1. Kunci Fungsionalitas
        if (isSelect) {
          el.style.pointerEvents = isCompleted ? "none" : "auto";
        } else {
          el.readOnly = isCompleted;
        }

        // 2. Manipulasi Visual UI/UX
        if (isCompleted) {
          el.style.backgroundColor = "#e2e8f0"; // Pakai warna abu pastel
          el.style.color = "#6c757d"; // Teks menjadi abu-abu
          el.style.cursor = "not-allowed"; // Kursor berubah jadi tanda coret/dilarang
          el.style.opacity = "0.7"; // Membuatnya sedikit memudar (transparan)
          // el.style.boxShadow = "none"; // Hilangkan bayangan kotak (neo-shadow)
        } else {
          // Kembalikan ke tampilan normal jika tugas belum selesai (pending)
          el.style.backgroundColor = "";
          el.style.color = "";
          el.style.cursor = "";
          el.style.opacity = "1";
          el.style.boxShadow = "";
        }
      };

      // Terapkan fungsi ke semua input yang ingin dikunci
      setLockedUI(titleInput, false);
      setLockedUI(dueDateInput, false);
      setLockedUI(categorySelect, true);
      setLockedUI(recurringSelect, true);
    });
  }
});
