<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

$today_date = date('Y-m-d');
$query = "SELECT * FROM tasks 
          WHERE user_id = '$user_id' 
          AND DATE(due_date) = '$today_date' 
          ORDER BY status DESC, due_date ASC";

$result = mysqli_query($conn, $query);

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

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="container-fluid p-0">

        <div class="row g-4 h-100">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-body p-4 bg-light">
                        <div id="calendar" class="text-dark fw-bold"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 d-flex flex-column">
                <div class="bg-primary neo-border p-3 mb-4">
                    <h4 class="fw-bold mb-0 text-dark text-uppercase" id="scheduleTitle"><?= date('l, d F') ?></h4>
                </div>

                <div class="flex-grow-1 overflow-auto pe-2 pb-2" id="scheduleList">

                    <!-- (Isi PHP Anda tetap sama) -->
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <div class="row g-3">

                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <?php
                                $badgeColor = getCategoryColor($row['category']);
                                $time = date('H:i', strtotime($row['due_date']));
                                $is_completed = ($row['status'] == 'completed');
                                $cardClass = $is_completed ? 'opacity-50' : 'bg-white';
                                $textClass = $is_completed ? 'text-decoration-line-through text-muted' : 'text-dark';
                                $action = $is_completed ? 'uncomplete' : 'complete';
                                $btnLabel = $is_completed ? 'PENDING' : 'DONE';
                                $btnIcon = $is_completed ? 'fa-undo' : 'fa-check';

                                // 1. Logika Pendeteksi Link pada Deskripsi
                                $rawDesc = !empty($row['description']) ? htmlspecialchars($row['description']) : 'No description.';
                                $formattedDesc = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" class="text-primary text-decoration-underline" style="position: relative; z-index: 10;">$1</a>', $rawDesc);
                                ?>

                                <div class="col-12 col-sm-6  col-lg-12">
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
                                                            <a class="dropdown-item fw-bold text-dark" href="actions/task_handler.php?action=<?= $action ?>&id=<?= $row['id'] ?>">
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
                                                            <a class="dropdown-item fw-bold text-danger btn-delete" href="actions/task_handler.php?action=delete&id=<?= $row['id'] ?>">
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
                            <?php endwhile; ?>

                            <!-- Tutup tag Row Bootstrap -->
                        </div>
                    <?php else: ?>
                        <div class="text-center py-5 border border-dark border-3 bg-light neo-shadow">
                            <i class="fas fa-mug-hot fs-1 mb-3 text-dark"></i>
                            <p class="fw-bold text-dark">No tasks for today.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

        </div>
    </div>

    <button class="btn-fab" title="Add Schedule" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="fas fa-plus"></i>
    </button>
</main>

<?php
include 'views/modal_add_task.php';
include 'views/modal_edit_task.php';
include 'includes/footer.php';
?>