<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

$query = "SELECT * FROM tasks 
          WHERE user_id = '$user_id' 
          AND status = 'completed' 
          ORDER BY completed_at DESC";

$result = mysqli_query($conn, $query);

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

        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom border-dark border-3">
            <div>
                <div class="d-flex align-items-center gap-3">
                    <a href="tasks.php" class="btn btn-light" style="padding: 0.5rem 0.8rem;">
                        <i class="fas fa-arrow-left text-dark"></i>
                    </a>
                    <div>
                        <h2 class="fw-bold mb-0">HISTORY</h2>
                        <p class="text-dark fw-bold small mb-0" style="font-family: 'JetBrains Mono', monospace;">Track what you've accomplished.</p>
                    </div>
                </div>
            </div>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <!-- Tambahkan class ignore-click dan event onclick manual untuk suaranya -->
                <a href="actions/task_handler.php?action=clear_history"
                    class="btn btn-danger btn-delete ignore-click px-3 py-2"
                    onclick="document.getElementById('soundClick').play();">
                    <i class="fas fa-trash-alt me-2"></i>CLEAR
                </a>
            <?php endif; ?>
        </div>

        <div class="row g-3">

            <?php if (mysqli_num_rows($result) > 0): ?>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <?php include 'views/partials/task_item.php'; ?>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-12 text-center py-5">
                    <div class="py-5 border border-dark border-3 bg-light neo-shadow neo-rounded">
                        <i class="fas fa-history fs-1 text-dark mb-3"></i>
                        <h4 class="fw-bold text-dark">NO HISTORY YET</h4>
                        <p class="fw-bold text-dark">Completed tasks will show up here.</p>
                        <a href="tasks.php" class="btn btn-primary px-4 mt-2">GO TO TASKS</a>
                    </div>
                </div>
            <?php endif; ?>

        </div>

    </div>
</main>


<?php
include 'views/modal_edit_task.php';
include 'includes/footer.php'; ?>