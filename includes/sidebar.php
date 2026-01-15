<aside class="sidebar shadow">
    <div class="sidebar-logo">
<img src="../../runtut-web/assets/img/logo/icon.png" alt="Runtut Logo" class="img-fluid" style="max-height: 40px;">
    </div>

    <nav class="nav flex-column align-items-center w-100 flex-grow-1">

        <a href="tasks.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'tasks.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Tasks">
            <i class="fas fa-check-circle text-primary fs-6"></i>
        </a>

        <a href="calendar.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'calendar.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Calendar">
            <i class="fas fa-calendar-alt text-primary fs-6"></i>
        </a>

        <a href="progress.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'progress.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="My Progress">
            <i class="fas fa-chart-pie text-primary fs-6"></i>
        </a>

    </nav>

    <div class="mt-auto w-100 d-flex flex-column align-items-center gap-2">
        
    <div class="mt-auto" data-bs-toggle="tooltip" data-bs-placement="right" title="Logout">
        <a href="actions/auth.php?action=logout" class="nav-link text-primary" data-bs-toggle="modal" data-bs-target="#logoutModal">
        <i class="fas fa-sign-out-alt fs-6"></i>
    </a>
</div>

        <div class="nav-link" style="cursor: default;" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="User Profile">
            <div class="rounded-circle bg-secondary d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px;">
                <i class="fas fa-user small fs-6"></i>
            </div>
        </div>

    </div>
</aside>