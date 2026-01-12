<?php
// --- 1. SIAPKAN VARIABEL DI ATAS (Agar HTML di bawah bersih) ---
$catColor    = getCatColor($row['category']);
$isCompleted = ($row['status'] == 'completed');
$isOverdue   = ($row['due_date'] < date('Y-m-d') && !$isCompleted);

// Variabel Style CSS
$borderStyle = "width: 5px; background-color: var(--bs-$catColor);";
$badgeClass  = "badge bg-{$catColor}-subtle text-{$catColor} rounded-pill px-2";
$titleClass  = $isCompleted ? 'text-decoration-line-through text-muted' : '';
$dateClass   = $isOverdue ? 'text-danger' : '';

// Variabel Logika Checkbox
$action      = $isCompleted ? 'uncomplete' : 'complete';
$tooltip     = $isCompleted ? 'Mark as Pending' : 'Mark as Done';
$checkedAttr = $isCompleted ? 'checked' : '';
$checkboxUrl = "actions/task_handler.php?action=$action&id={$row['id']}";

// Variabel Tampilan Tanggal
$dateDisplay = '<span class="opacity-50"><i class="fas fa-infinity me-1"></i> Anytime</span>';
if ($row['due_date']) {
    $formattedDate = date('d M - H.i', strtotime($row['due_date']));
    $dateDisplay   = "<span class='$dateClass'><i class='fas fa-calendar-alt me-1 opacity-50'></i> $formattedDate</span>";
}

// Variabel Deskripsi
$descText = !empty($row['description']) ? htmlspecialchars($row['description']) : '<span class="opacity-25">No description.</span>';
?>

<div class="col-md-6 col-lg-4 col-xl-3">
    
    <div class="card border-0 shadow-sm h-100 rounded-4 animate-hover position-relative overflow-hidden" 
         style="transition: all 0.2s ease-in-out; min-height: 160px;">
        
        <div class="position-absolute start-0 top-0 bottom-0" style="<?php echo $borderStyle; ?>"></div>

        <div class="card-body p-3 ps-4 d-flex flex-column">
            
            <div class="d-flex justify-content-between align-items-start mb-2">
                
                <div class="text-muted small fw-bold pt-1" style="font-size: 0.75rem;">
                    <?php echo $dateDisplay; ?>
                </div>

                <div class="form-check m-0">
                    <input class="form-check-input rounded-circle shadow-sm" type="checkbox" 
                        style="cursor: pointer; width: 1.4em; height: 1.4em; border-color: #6b7280;"
                        <?php echo $checkedAttr; ?>
                        onchange="window.location.href='<?php echo $checkboxUrl; ?>'"
                        data-bs-toggle="tooltip" title="<?php echo $tooltip; ?>">
                </div>

            </div>

            <div class="mb-3">
                <h6 class="fw-bold text-dark mb-1 text-truncate <?php echo $titleClass; ?>">
                    <?php echo htmlspecialchars($row['title']); ?>
                </h6>

                <p class="text-muted small mb-0" style="font-size: 0.8rem; line-height: 1.4; overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">
                    <?php echo $descText; ?>
                </p>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-auto pt-2 border-top border-light">
                
                <span class="<?php echo $badgeClass; ?>" style="font-size: 0.7rem;">
                    <?php echo $row['category']; ?>
                </span>

                <a href="actions/task_handler.php?action=delete&id=<?php echo $row['id']; ?>" 
                   class="text-danger opacity-25 hover-opacity-100 transition-all btn-delete" 
                   data-bs-toggle="tooltip" title="Delete Task">
                    <i class="fas fa-trash-alt"></i>
                </a>

            </div>

        </div>
    </div>
</div>