<?php

/** @var array $row */
//  1. SIAPKAN VARIABEL DI ATAS 
$catColor    = getCatColor($row['category']);
$isCompleted = ($row['status'] == 'completed');
// Update logika isOverdue menggunakan strtotime dan time()
$isOverdue   = (!empty($row['due_date']) && strtotime($row['due_date']) < time() && !$isCompleted);

// Menggunakan solid background color dan border tebal untuk badge
$badgeClass  = "badge bg-{$catColor} neo-border text-dark";
$titleClass  = $isCompleted ? 'text-decoration-line-through text-muted' : 'text-dark';
$dateClass   = $isOverdue ? 'fw-bold' : 'text-dark';

$action      = $isCompleted ? 'uncomplete' : 'complete';
$tooltip     = $isCompleted ? 'Mark as Pending' : 'Mark as Done';
$checkedAttr = $isCompleted ? 'checked' : '';
$checkboxUrl = "actions/task_handler.php?action=$action&id={$row['id']}";

$dateDisplay = '<span class="fw-bold"><i class="fas fa-infinity me-1"></i> ANYTIME</span>';
if ($row['due_date']) {
    $formattedDate = date('d M - H:i', strtotime($row['due_date']));
    $dateDisplay   = "<span class='$dateClass fw-bold'><i class='fas fa-calendar-alt me-1'></i> $formattedDate</span>";
}

// 1. Ambil deskripsi dan ubah karakter spesial HTML
$rawDesc = !empty($row['description']) ? htmlspecialchars($row['description']) : 'No description.';

// 2. Deteksi URL dan ubah menjadi Link aktif
$descText = preg_replace('/(https?:\/\/[^\s<]+)/', '<a href="$1" target="_blank" class="text-primary text-decoration-underline" style="position: relative; z-index: 10;">$1</a>', $rawDesc);
?>

<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
    <!-- Menggunakan .card bawaan Bootstrap yang sudah kita override di style.css -->
    <div class="card neo-hover h-100 p-0 overflow-hidden">

        <!-- Header Kartu: Blok Warna Kategori -->
        <div class="border-bottom border-dark border-3 bg-<?php echo $catColor; ?> p-2 px-3 d-flex justify-content-between align-items-center">
            <span class="small fw-bold text-dark" style="font-family: 'JetBrains Mono', monospace; font-size: 0.8rem;">
                <?php echo $dateDisplay; ?>
            </span>
            <div class="form-check m-0">
                <input class="form-check-input neo-border" type="checkbox"
                    style="cursor: pointer; width: 1.4em; height: 1.4em;"
                    <?php echo $checkedAttr; ?>
                    onchange="window.location.href='<?php echo $checkboxUrl; ?>'"
                    data-bs-toggle="tooltip" title="<?php echo $tooltip; ?>">
            </div>
        </div>

        <div class="card-body p-3 d-flex flex-column bg-white">
            <div class="mb-3">
                <h5 class="fw-bold mb-2 text-truncate <?php echo $titleClass; ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </h5>
                <!-- Deskripsi Tugas dengan Logika Show More Dinamis -->
                <div class="mb-3">
                    <p class="small text-dark mb-0 task-desc-text" style="line-height: 1.4; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; font-weight: 500; word-break: break-word;">
                        <?= $descText ?>
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
            </div>

            <!-- Footer Kartu -->
            <div class="d-flex justify-content-between align-items-center mt-auto pt-3 border-top border-dark border-2">
                <span class="<?php echo $badgeClass; ?>" style="font-size: 0.75rem;">
                    <?php echo $row['category']; ?>
                </span>

                <div class="d-flex gap-2">
                    <!-- Tombol Edit -->
                    <button type="button" class="btn btn-sm btn-primary px-2 py-1 btn-edit-task"
                        data-bs-toggle="modal" data-bs-target="#editTaskModal"
                        data-id="<?php echo $row['id']; ?>"
                        data-title="<?php echo htmlspecialchars($row['title'], ENT_QUOTES); ?>"
                        data-desc="<?php echo htmlspecialchars($row['description'] ?? '', ENT_QUOTES); ?>"
                        data-cat="<?php echo $row['category']; ?>"
                        data-due="<?php echo $row['due_date'] ? date('Y-m-d\TH:i', strtotime($row['due_date'])) : ''; ?>"
                        data-recurring="<?php echo $row['is_recurring']; ?>"
                        data-rectype="<?php echo $row['recurrence_type']; ?>"
                        data-status="<?php echo $row['status']; ?>"
                        data-bs-toggle="tooltip" title="Edit Task">
                        <i class="fas fa-edit"></i>
                    </button>

                    <!-- Tombol Delete -->
                    <a href="actions/task_handler.php?action=delete&id=<?php echo $row['id']; ?>"
                        class="btn btn-sm btn-danger px-2 py-1 btn-delete"
                        data-bs-toggle="tooltip" title="Delete Task">
                        <i class="fas fa-trash-alt"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>