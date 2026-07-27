<?php

/** @var mysqli $conn */
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['date'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$date = $_GET['date']; // Format: YYYY-MM-DD

// UPDATE QUERY: Hapus "AND status = 'pending'" agar tugas selesai tetap terambil
// Kita urutkan berdasarkan status (Pending duluan) lalu jam

// PREPARED STATEMENTS
// 1. Siapkan kerangka SQL dengan tanda tanya (?)
$stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? AND DATE(due_date) = ? ORDER BY status DESC, due_date ASC");

// 2. Ikatkan data (bind) ke tanda tanya tersebut
// "is" = parameter pertama Integer (user_id), parameter kedua String (date)
$stmt->bind_param("is", $user_id, $date);

// 3. Eksekusi dan ambil hasilnya
$stmt->execute();
$result = $stmt->get_result();

// Helper function
function getCategoryColor($cat)
{
    switch ($cat) {
        case 'Work':
            return 'primary';
        case 'Personal':
            return 'warning';
        case 'Study':
            return 'success';
        case 'Health':
            return 'danger';
        case 'None':
            return 'secondary';
        default:
            return 'secondary';
    }
}

if (mysqli_num_rows($result) > 0) {
    // Buka tag Row Bootstrap
    echo '<div class="row g-3">';

    while ($row = mysqli_fetch_assoc($result)) {
        $badgeColor = getCategoryColor($row['category']);
        $time = date('H:i', strtotime($row['due_date']));
        $is_completed = ($row['status'] == 'completed');
        $cardClass = $is_completed ? 'opacity-50' : 'bg-white';
        $textClass = $is_completed ? 'text-decoration-line-through text-muted' : 'text-dark';
        $action = $is_completed ? 'uncomplete' : 'complete';
        $btnLabel = $is_completed ? 'PENDING' : 'DONE';
        $btnIcon = $is_completed ? 'fa-undo' : 'fa-check';

        $rawDesc = !empty($row['description']) ? htmlspecialchars($row['description']) : 'No description.';
        $formattedDesc = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" class="text-primary text-decoration-underline" style="position: relative; z-index: 10;">$1</a>', $rawDesc);
?>
        <!-- Pembungkus Kolom (2 kolom di mobile, 1 kolom di PC) -->
        <div class="col-12 col-sm-6 col-lg-12">
            <div class="card h-100 rounded-0 <?= $cardClass ?>">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <span class="badge bg-<?= $badgeColor ?> text-white border-0 rounded-0 fs-6"><?= $time ?></span>
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm neo-border border-2 px-2 py-0" data-bs-toggle="dropdown">
                                <i class="fas fa-ellipsis-h text-dark"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end neo-border neo-shadow rounded-0">
                                <li>
                                    <a class="dropdown-item fw-bold text-dark ignore-click" href="#" onclick="playDoneSound('actions/task_handler.php?action=<?= $action ?>&id=<?= $row['id'] ?>')">
                                        <i class="fas <?= $btnIcon ?> me-2"></i><?= $btnLabel ?>
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-dark border-2">
                                </li>
                                <li>
                                    <a class="dropdown-item fw-bold text-primary btn-edit-task" href="#"
                                        data-bs-toggle="modal" data-bs-target="#editTaskModal"
                                        data-id="<?= $row['id'] ?>"
                                        data-title="<?= htmlspecialchars($row['title'], ENT_QUOTES) ?>"
                                        data-desc="<?= htmlspecialchars($row['description'] ?? '', ENT_QUOTES) ?>"
                                        data-cat="<?= $row['category'] ?>"
                                        data-due="<?= $row['due_date'] ? date('Y-m-d\TH:i', strtotime($row['due_date'])) : '' ?>"
                                        data-recurring="<?= $row['is_recurring'] ?>"
                                        data-status="<?php echo $row['status']; ?>"
                                        data-rectype="<?= $row['recurrence_type'] ?>">
                                        <i class="fas fa-edit me-2"></i>EDIT
                                    </a>
                                </li>
                                <li>
                                    <hr class="dropdown-divider border-dark border-2">
                                </li>
                                <li>
                                    <a class="dropdown-item fw-bold text-danger btn-delete ignore-click" href="actions/task_handler.php?action=delete&id=<?= $row['id'] ?>">
                                        <i class="fas fa-trash me-2"></i>DELETE
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-2 text-truncate <?= $textClass ?>">
                        <?= htmlspecialchars($row['title']) ?>
                    </h5>

                    <!-- Deskripsi Tugas dengan Logika Show More Dinamis -->
                    <div class="mb-3">
                        <p class="small text-dark mb-0 task-desc-text" style="line-height: 1.4; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; font-weight: 500; word-break: break-word;">
                            <?= $formattedDesc ?>
                        </p>

                        <!-- Tombol disembunyikan secara default dengan d-none -->
                        <a tabindex="0" class="fw-bold text-primary text-decoration-underline btn-show-more d-none"
                            role="button"
                            data-bs-toggle="popover"
                            data-bs-trigger="focus"
                            data-bs-placement="auto"
                            data-bs-container="body"
                            data-bs-custom-class="neo-popover"
                            data-bs-content="<?= htmlspecialchars($rawDesc, ENT_QUOTES) ?>"
                            style="cursor: pointer; font-size: 0.75rem; margin-top: 2px; display: inline-block;">
                            Show more
                        </a>
                    </div>

                    <p class="fw-bold small mb-0 text-dark" style="font-family: 'JetBrains Mono', monospace;">
                        <i class="fas fa-tag me-1"></i><?= strtoupper($row['category']) ?>
                    </p>
                </div>
            </div>
        </div>
<?php
    }
    // Tutup tag Row Bootstrap
    echo '</div>';
} else {
    echo '
    <div class="text-center py-5 border border-dark border-3 bg-light neo-shadow animate-up">
        <i class="fas fa-mug-hot fs-1 mb-3 text-dark"></i>
        <p class="fw-bold text-dark">No tasks for today.</p>
    </div>';
}
?>