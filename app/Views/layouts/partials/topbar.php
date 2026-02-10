<!-- Topbar -->
<div class="topbar">
    <div>
        <button class="btn btn-link link-dark me-2" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        <h4 class="mb-0 d-inline-block"><?= esc($title ?? 'Dashboard') ?></h4>
    </div>
    <div class="d-flex align-items-center">
        <!-- Notifications -->
        <div class="dropdown me-3">
            <a class="nav-link text-muted position-relative" href="#" role="button" data-bs-toggle="dropdown" id="notifDropdown">
                <i class="fas fa-bell fa-lg"></i>
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="notifCount" style="display: none;">
                    0
                </span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow border-0" aria-labelledby="notifDropdown" style="width: 320px; max-height: 400px; overflow-y: auto;" id="notifList">
                <li><h6 class="dropdown-header">Notifications</h6></li>
                <li><hr class="dropdown-divider my-0"></li>
                <!-- Items injected via JS -->
                <li class="text-center py-3 text-muted small" id="noNotifMsg">No new notifications</li>
            </ul>
        </div>

        <div class="dropdown">
            <?php
            $auth = new \App\Libraries\Auth();
            $user = $auth->getUser();
            ?>
            <a class="btn btn-link dropdown-toggle text-decoration-none" href="#" role="button" data-bs-toggle="dropdown">
                <i class="fas fa-user-circle fa-lg"></i> <?= esc($user['first_name'] ?? 'User') ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
                <?php if ($auth->isInstructor()): ?>
                <li><a class="dropdown-item" href="<?= base_url('/instructor/profile') ?>"><i class="fas fa-user"></i> Profile</a></li>
                <?php else: ?>
                <li><a class="dropdown-item" href="<?= base_url('/student/profile') ?>"><i class="fas fa-user"></i> Profile</a></li>
                <?php endif; ?>
                <li><a class="dropdown-item" href="<?= base_url('/') ?>"><i class="fas fa-home"></i> Home</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="<?= base_url('/login/logout') ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>
