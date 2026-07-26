<aside class="sidebar pt-3 pb-2">
    <div class="sidebar-logo">
        <img src="../../runtut-web/assets/img/logo/icon.png" alt="Runtut Logo" class="img-fluid" style="max-height: 50px;">
    </div>

    <nav class="nav flex-column align-items-center w-100 flex-grow-1">
        <a href="tasks.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'tasks.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Tasks">
            <i class="fas fa-check-square fs-5"></i>
        </a>

        <a href="calendar.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'calendar.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="Calendar">
            <i class="fas fa-calendar-alt fs-5"></i>
        </a>

        <a href="progress.php"
            class="nav-link main-menu-item <?= (basename($_SERVER['PHP_SELF']) == 'progress.php') ? 'active' : '' ?>"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="My Progress">
            <i class="fas fa-chart-bar fs-5"></i>
        </a>
        <div class="mt-auto w-100 d-flex flex-column align-items-center">
            <!-- Dropup Menu for User Profile -->
            <div class="dropup" data-bs-toggle="tooltip" data-bs-placement="right" data-bs-title="User Profile">
                <button class="nav-link neo-hover p-0 d-flex justify-content-center align-items-center" type="button" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: var(--neo-light-green); border: 2px solid var(--black); box-shadow: 2px 2px 0px var(--black); width: 45px; height: 45px;">
                    <i class="fas fa-user-astronaut text-dark fs-5"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-profile neo-border neo-shadow mb-2 rounded-0">
                    <!-- Informasi Profil -->
                    <li>
                        <div class="px-3 py-2 d-flex align-items-center text-dark" style="cursor: default;">
                            <i class="fas fa-user-circle fs-2 me-3 text-primary"></i>
                            <div class="d-flex flex-column">
                                <span class="fw-bold text-uppercase text-nowrap" style="line-height: 1.2; font-size: 0.9rem;">
                                    <?= htmlspecialchars($_SESSION['full_name']) ?>
                                </span>
                                <span class="text-muted fw-bold" style="font-family: 'JetBrains Mono', monospace; font-size: 0.75rem;">
                                    @<?= htmlspecialchars($_SESSION['username'] ?? 'username') ?>
                                </span>
                            </div>
                        </div>
                    </li>

                    <li>
                        <hr class="dropdown-divider border-dark border-2 m-0">
                    </li>

                    <!-- Tombol Logout -->
                    <li>
                        <a class="dropdown-item fw-bold text-danger py-2 mt-1" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                            <i class="fas fa-sign-out-alt me-2"></i>LOGOUT
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

</aside>