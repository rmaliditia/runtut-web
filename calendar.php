<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// --- LOGIC PHP AWAL (DEFAULT: HARI INI) ---
$today_date = date('Y-m-d');

// UPDATE QUERY: Hapus filter 'pending' agar tugas selesai tetap muncul di awal
// Urutkan: Pending di atas, Selesai di bawah
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

            <div class="col-lg-4 d-flex flex-column">
                <h4 class="fw-bold mb-4" id="scheduleTitle"><?= date('l, d F') ?></h4>

                <div class="flex-grow-1 overflow-auto pe-2" id="scheduleList" style="max-height: 80vh;">

                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <?php
                            $badgeColor = getCategoryColor($row['category']);
                            $time = date('H:i', strtotime($row['due_date']));

                            // --- LOGIKA TAMPILAN STATUS SELESAI ---
                            $is_completed = ($row['status'] == 'completed');
                            $cardClass = $is_completed ? 'opacity-50' : '';
                            $textClass = $is_completed ? 'text-decoration-line-through text-muted' : '';
                            $action = $is_completed ? 'uncomplete' : 'complete';
                            $btnLabel = $is_completed ? 'Mark as Pending' : 'Mark Done';
                            $btnIcon = $is_completed ? 'fa-undo' : 'fa-check';
                            $btnColor = $is_completed ? 'text-warning' : 'text-success';
                            ?>

                            <div class="card border-0 shadow-sm mb-3 rounded-4 <?= $cardClass ?>">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <span class="badge bg-primary text-<?= $badgeColor ?> mb-2">
                                                <?= $time ?>
                                            </span>
                                            <h6 class="fw-bold mb-1 text-truncate <?= $textClass ?>" style="max-width: 200px;">
                                                <?= htmlspecialchars($row['title']) ?>
                                            </h6>
                                            <p class="text-muted small mb-0">
                                                <i class="fas fa-tag me-1 text-<?= $badgeColor ?>"></i>
                                                <?= $row['category'] ?>
                                            </p>
                                        </div>

                                        <div class="dropdown">
                                            <button class="btn btn-link text-muted p-0 no-arrow" data-bs-toggle="dropdown">
                                                <i class="fas fa-ellipsis-v"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow">
                                                <li>
                                                    <a class="dropdown-item small" href="actions/task_handler.php?action=<?= $action ?>&id=<?= $row['id'] ?>">
                                                        <i class="fas <?= $btnIcon ?> me-2 <?= $btnColor ?>"></i><?= $btnLabel ?>
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item small text-danger btn-delete" href="actions/task_handler.php?action=delete&id=<?= $row['id'] ?>">
                                <i class="fas fa-trash me-2"></i>Delete
                            </a>
                        </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>

                    <?php else: ?>
                        <div class="text-center py-5 text-primary opacity-70">
                            <i class="fas fa-mug-hot fs-1 mb-3 opacity-25"></i>
                            <p>No tasks for today.</p>
                        </div>
                    <?php endif; ?>

                </div>
            </div>

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-2 h-100">
                    <div class="card-body p-4">
                        <div id="calendar" class="text-primary"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <button class="btn-fab" title="Add Schedule" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="fas fa-plus"></i>
    </button>

</main>

<?php include 'views/modal_add_task.php'; ?>
<?php include 'includes/footer.php'; ?>