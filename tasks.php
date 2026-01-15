<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// --- LOGIC PHP ---
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$cat    = isset($_GET['cat']) ? $_GET['cat'] : 'All';
$sort   = isset($_GET['sort']) ? $_GET['sort'] : 'date_asc';

// Cek apakah user sedang melakukan filter/pencarian?
$is_filter_active = (!empty($search) || $cat != 'All');

$query = "SELECT * FROM tasks 
          WHERE user_id = '$user_id' 
          AND (status = 'pending' OR (status = 'completed' AND DATE(completed_at) = CURDATE()))";

if (!empty($search)) {
    $query .= " AND title LIKE '%$search%'";
}
if ($cat != 'All') {
    $cat = mysqli_real_escape_string($conn, $cat);
    $query .= " AND category = '$cat'";
}

switch ($sort) {
    case 'alpha_asc':
        $query .= " ORDER BY title ASC";
        break;
    case 'alpha_desc':
        $query .= " ORDER BY title DESC";
        break;
    case 'date_desc':
        $query .= " ORDER BY due_date DESC";
        break;
    default:
        $query .= " ORDER BY due_date ASC";
}

$result = mysqli_query($conn, $query);

// Grouping
$overdue_tasks = [];
$today_tasks   = [];
$completed_tasks = [];
$today_date = date('Y-m-d');

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] == 'completed') {
        $completed_tasks[] = $row;
    } else {
        if (!empty($row['due_date']) && $row['due_date'] < $today_date) {
            $overdue_tasks[] = $row;
        } else {
            $today_tasks[] = $row;
        }
    }
}

function getCatColor($cat)
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">Hi, <?= htmlspecialchars($full_name) ?>!</h2>
                <p class="text-secondary small">Make today count!</p>
            </div>
        </div>

        <form action="" method="GET" class="row g-2 mb-4">
            <div class="col-md-3">
                <select name="cat" class="form-select border border-primary text-secondary" onchange="this.form.submit()">
                    <option value="All">All Categories</option>
                    <option value="Personal" <?= $cat == 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Work" <?= $cat == 'Work' ? 'selected' : '' ?>>Work</option>
                    <option value="Study" <?= $cat == 'Study' ? 'selected' : '' ?>>Study</option>
                    <option value="Health" <?= $cat == 'Health' ? 'selected' : '' ?>>Health</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group  border border-primary rounded-1 overflow-hidden bg-white">
                    <input type="text" name="q" class="form-control border-0 shadow-none" placeholder="Search tasks..." value="<?= htmlspecialchars($search) ?>">
                    <span class="input-group-text bg-white border-0 ps-3"><i class="fas fa-search text-muted"></i></span>
                </div>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select border border-primary text-secondary" onchange="this.form.submit()">
                    <option value="date_asc" <?= $sort == 'date_asc' ? 'selected' : '' ?>>📅 Date (Ascending)</option>
                    <option value="date_desc" <?= $sort == 'date_desc' ? 'selected' : '' ?>>📅 Date (Descending)</option>
                    <option value="alpha_asc" <?= $sort == 'alpha_asc' ? 'selected' : '' ?>>🔤 A - Z</option>
                    <option value="alpha_desc" <?= $sort == 'alpha_desc' ? 'selected' : '' ?>>🔤 Z - A</option>
                </select>
            </div>
        </form>

        <?php if (empty($overdue_tasks) && empty($today_tasks) && empty($completed_tasks)): ?>

            <div class="text-center py-5">
                <?php if ($is_filter_active): ?>
                    <div class="mb-3">
                        <i class="fas fa-search fs-1 text-muted opacity-50"></i>
                    </div>
                    <h5 class="fw-bold text-muted">No results found</h5>
                    <p class="small text-muted">We couldn't find any tasks matching your search.</p>
                    <a href="tasks.php" class="btn btn-outline-primary btn-sm rounded-pill px-4">Clear Filters</a>

                <?php else: ?>
                    <img src="assets/img/empty_tasks.svg" style="width: 150px; opacity: 0.6;" onerror="this.style.display='none'">
                    <h5 class="fw-bold mt-3 text-muted">Don't let the day slip away...</h5>
                    <p class="small text-muted">Start by adding a new task!</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

        <div class="vstack gap-4">

            <?php if (!empty($overdue_tasks)): ?>
                <div>
                    <h6 class="fw-bold text-danger mb-3 ps-2 border-start border-4 border-danger">&nbsp; Overdue Tasks</h6>
                    <div class="row g-3"> <?php foreach ($overdue_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($today_tasks)): ?>
                <div>
                    <h6 class="fw-bold text-primary mb-3 ps-2 border-start border-4 border-primary">&nbsp; Active Tasks</h6>
                    <div class="row g-3"> <?php foreach ($today_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($completed_tasks)): ?>
                <div>
                    <h6 class="fw-bold text-success mb-3 ps-2 border-start border-4 border-success opacity-75">&nbsp; Completed Today</h6>
                    <div class="row g-3 opacity-75"> <?php foreach ($completed_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

        </div>

        <div class="text-center mt-5 mb-5">
            <a href="history.php" class="text-decoration-none text-muted small opacity-50 fw-bold">
                Check all history <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

    </div>

    <button class="btn-fab" title="Add Task" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="fas fa-plus"></i>
    </button>
</main>

<?php
include 'views/modal_add_task.php';
include 'includes/footer.php';
?>