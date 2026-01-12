<?php
session_start();
require '../config/database.php';

if (!isset($_SESSION['user_id']) || !isset($_GET['date'])) {
    exit;
}

$user_id = $_SESSION['user_id'];
$date = $_GET['date']; // Format: YYYY-MM-DD

// UPDATE QUERY: Hapus "AND status = 'pending'" agar tugas selesai tetap terambil
// Kita urutkan berdasarkan status (Pending duluan) lalu jam
$query = "SELECT * FROM tasks 
          WHERE user_id = '$user_id' 
          AND DATE(due_date) = '$date' 
          ORDER BY status DESC, due_date ASC";

$result = mysqli_query($conn, $query);

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
        default:
            return 'secondary';
    }
}

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $badgeColor = getCategoryColor($row['category']);
        $time = date('H:i', strtotime($row['due_date']));

        // --- LOGIKA STATUS SELESAI ---
        $is_completed = ($row['status'] == 'completed');

        // 1. Opacity Rendah jika selesai
        $cardClass = $is_completed ? 'opacity-50' : '';

        // 2. Coret Judul jika selesai
        $textClass = $is_completed ? 'text-decoration-line-through text-muted' : '';

        // 3. Tombol Aksi (Bisa Undo)
        $action = $is_completed ? 'uncomplete' : 'complete';
        $btnLabel = $is_completed ? 'Mark as Pending' : 'Mark Done';
        $btnIcon = $is_completed ? 'fa-undo' : 'fa-check';
        $btnColor = $is_completed ? 'text-warning' : 'text-success';
?>
        <div class="card border-0 shadow-sm mb-3 rounded-4 animate-up <?= $cardClass ?>">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <span class="badge bg-light text-<?= $badgeColor ?> mb-2">
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
<?php
    }
} else {
    // Tampilan Kosong
    echo '
    <div class="text-center py-5 text-muted fade-in">
        <i class="fas fa-calendar-day fs-1 mb-3 opacity-25"></i>
        <p>No tasks found on this date.</p>
    </div>';
}
?>