<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

//  LOGIC PHP 
$search = isset($_GET['q']) ? mysqli_real_escape_string($conn, $_GET['q']) : '';
$cat    = isset($_GET['cat']) ? $_GET['cat'] : 'All';
$sort   = isset($_GET['sort']) ? $_GET['sort'] : 'date_asc';

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

$overdue_tasks = [];
$today_tasks   = [];
$completed_tasks = [];

// Gunakan waktu saat ini (detik)
$current_time = time();

while ($row = mysqli_fetch_assoc($result)) {
    if ($row['status'] == 'completed') {
        $completed_tasks[] = $row;
    } else {
        // Bandingkan secara absolut menggunakan strtotime
        if (!empty($row['due_date']) && strtotime($row['due_date']) < $current_time) {
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

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold mb-1">HI, <?= htmlspecialchars(strtoupper($full_name)) ?>!</h2>
                <p class="text-dark fw-bold mb-0" style="font-family: 'JetBrains Mono', monospace;">LET'S GET THINGS DONE.</p>
            </div>
        </div>

        <form action="" method="GET" class="row g-3 mb-5">
            <div class="col-md-3">
                <select name="cat" class="form-select fw-bold border-2" onchange="this.form.submit()">
                    <option value="All">All Categories</option>
                    <option value="Personal" <?= $cat == 'Personal' ? 'selected' : '' ?>>Personal</option>
                    <option value="Work" <?= $cat == 'Work' ? 'selected' : '' ?>>Work</option>
                    <option value="Study" <?= $cat == 'Study' ? 'selected' : '' ?>>Study</option>
                    <option value="Health" <?= $cat == 'Health' ? 'selected' : '' ?>>Health</option>
                </select>
            </div>
            <div class="col-md-4">
                <select name="sort" class="form-select fw-bold border-2" onchange="this.form.submit()">
                    <option value="date_asc" <?= $sort == 'date_asc' ? 'selected' : '' ?>>Date (Ascending)</option>
                    <option value="date_desc" <?= $sort == 'date_desc' ? 'selected' : '' ?>>Date (Descending)</option>
                    <option value="alpha_asc" <?= $sort == 'alpha_asc' ? 'selected' : '' ?>>A - Z</option>
                    <option value="alpha_desc" <?= $sort == 'alpha_desc' ? 'selected' : '' ?>>Z - A</option>
                </select>
            </div>
            <div class="col-md-5">
                <div class="input-group gap-2">
                    <input type="text" name="q" class="form-control fw-bold border-2" placeholder="SEARCH TASKS..." value="<?= htmlspecialchars($search) ?>">
                    <button type="submit" class="btn btn-warning neo-border ps-3 pe-3 text-dark">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>

        </form>

        <?php if (empty($overdue_tasks) && empty($today_tasks) && empty($completed_tasks)): ?>
            <div class="text-center py-5">
                <?php if ($is_filter_active): ?>
                    <div class="mb-3">
                        <i class="fas fa-search fs-1 text-dark"></i>
                    </div>
                    <h4 class="fw-bold text-dark">NO RESULTS FOUND</h4>
                    <p class="fw-bold text-dark">We couldn't find any tasks matching your search.</p>
                    <a href="tasks.php" class="btn btn-primary px-4 mt-2">CLEAR FILTERS</a>
                <?php else: ?>
                    <h1 class="text-dark mb-3"><i class="fas fa-ghost"></i></h1>
                    <h4 class="fw-bold text-dark">NOTHING HERE</h4>
                    <p class="fw-bold text-dark">Start by adding a new task!</p>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="vstack gap-5">
            <?php if (!empty($overdue_tasks)): ?>
                <div>
                    <div class="d-inline-block bg-danger neo-border px-3 py-1 mb-3">
                        <h6 class="fw-bold text-dark mb-0 m-0">OVERDUE TASKS</h6>
                    </div>
                    <div class="row g-3"> <?php foreach ($overdue_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($today_tasks)): ?>
                <div>
                    <div class="d-inline-block bg-primary neo-border px-3 py-1 mb-3">
                        <h6 class="fw-bold text-dark mb-0 m-0">ACTIVE TASKS</h6>
                    </div>
                    <div class="row g-3"> <?php foreach ($today_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if (!empty($completed_tasks)): ?>
                <div>
                    <div class="d-inline-block bg-success neo-border px-3 py-1 mb-3 opacity-75">
                        <h6 class="fw-bold text-dark mb-0 m-0">COMPLETED TODAY</h6>
                    </div>
                    <div class="row g-3 opacity-75"> <?php foreach ($completed_tasks as $row): ?>
                            <?php include 'views/partials/task_item.php'; ?>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <div class="text-center mt-5 mb-5 border-top border-dark border-3 pt-4">
            <a href="history.php" class="btn btn-light neo-border px-4 py-2 text-dark fw-bold">
                CHECK ALL HISTORY <i class="fas fa-arrow-right ms-2"></i>
            </a>
        </div>

    </div>

    <button class="btn-fab" title="Add Task" data-bs-toggle="modal" data-bs-target="#addTaskModal">
        <i class="fas fa-plus"></i>
    </button>
</main>

<?php
include 'views/modal_add_task.php';
include 'views/modal_edit_task.php';
include 'includes/footer.php';
?>