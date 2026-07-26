<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

$q_done = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = '$user_id' AND status = 'completed'");
$total_done = mysqli_fetch_assoc($q_done)['total'];

$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = '$user_id' AND status = 'pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="container-fluid p-0">

        <h2 class="fw-bold mb-4">MY PROGRESS</h2>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card h-100" style="background-color: var(--neo-light-green);">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fw-bold text-dark" style="font-family: 'JetBrains Mono', monospace;">TASKS COMPLETED</p>
                            <h1 class="fw-bold mb-0 text-dark" style="font-size: 3rem;"><?= $total_done ?></h1>
                        </div>
                        <div class="bg-white neo-border rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; box-shadow: 3px 3px 0px var(--black);">
                            <i class="fas fa-check-double fs-3 text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card h-100" style="background-color: var(--neo-yellow);">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 fw-bold text-dark" style="font-family: 'JetBrains Mono', monospace;">PENDING TASKS</p>
                            <h1 class="fw-bold mb-0 text-dark" style="font-size: 3rem;"><?= $total_pending ?></h1>
                        </div>
                        <div class="bg-white neo-border rounded-circle d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; box-shadow: 3px 3px 0px var(--black);">
                            <i class="fas fa-hourglass-half fs-3 text-dark"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card h-100">
                    <div class="card-header bg-white border-bottom border-dark border-3 pt-4 px-4 pb-3 d-flex justify-content-between align-items-center">
                        <h4 class="fw-bold mb-0 text-dark">WEEKLY ACTIVITY</h4>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-light" disabled>LAST 7 DAYS</button>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4 pt-4 bg-light">
                        <div style="height: 300px;">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card h-100">
                    <div class="card-header bg-white border-bottom border-dark border-3 pt-4 px-4 pb-3">
                        <h4 class="fw-bold mb-0 text-dark">BY CATEGORY</h4>
                    </div>
                    <div class="card-body px-4 pb-4 pt-4 bg-light">
                        <div style="height: 250px; position: relative;">
                            <canvas id="categoryChart"></canvas>
                        </div>
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