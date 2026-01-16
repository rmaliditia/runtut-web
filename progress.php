<?php
session_start();

if (!isset($_SESSION['is_login']) || $_SESSION['is_login'] !== true) {
    header("Location: index.php");
    exit;
}

require 'config/database.php';
$user_id = $_SESSION['user_id'];
$full_name = $_SESSION['full_name'];

// --- HITUNG SUMMARY STATISTIK (PHP NATIVE) ---
// 1. Hitung Tugas Selesai
$q_done = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = '$user_id' AND status = 'completed'");
$total_done = mysqli_fetch_assoc($q_done)['total'];

// 2. Hitung Tugas Pending
$q_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM tasks WHERE user_id = '$user_id' AND status = 'pending'");
$total_pending = mysqli_fetch_assoc($q_pending)['total'];

include 'includes/header.php';
include 'includes/sidebar.php';
?>

<main class="main-content">
    <div class="container-fluid p-0">

        <h2 class="fw-bold mb-4">My Progress</h2>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 bg-primary text-white h-100">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Tasks Completed</p>
                            <h2 class="fw-bold mb-0"><?= $total_done ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-check-double fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100" style="background-color: #2fabe9; color: white;">
                    <div class="card-body p-4 d-flex justify-content-between align-items-center">
                        <div>
                            <p class="mb-1 opacity-75">Pending Tasks</p>
                            <h2 class="fw-bold mb-0"><?= $total_pending ?></h2>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                            <i class="fas fa-hourglass-half fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-4">

            <div class="col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Weekly Activity</h5>
                        <div class="btn-group btn-group-sm">
                            <button class="btn btn-light" disabled>Last 7 Days</button>
                        </div>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 300px;">
                            <canvas id="weeklyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">By Category</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div style="height: 250px; position: relative;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</main>

<?php include 'includes/footer.php'; ?>