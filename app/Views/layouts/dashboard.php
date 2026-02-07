<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard') ?> - LMS</title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">
    
    <?= $this->renderSection('styles') ?>
    <style>
        #page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.8);
            z-index: 9999;
            display: none;
            justify-content: center;
            align-items: center;
        }
        .spinner {
            border: 8px solid #f3f3f3;
            border-radius: 50%;
            border-top: 8px solid #3498db;
            width: 60px;
            height: 60px;
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <div id="page-loader">
        <div class="spinner"></div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i class="fas fa-graduation-cap"></i> LMS
        </div>
        <div class="sidebar-menu">
            <?= $this->renderSection('sidebar') ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div>
                <button class="btn btn-link d-md-none" id="sidebarToggle">
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



        <!-- Page Content -->
        <div class="content-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
        // Sidebar toggle for mobile
        document.getElementById('sidebarToggle')?.addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('show');
        });
    </script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Global Flash Messages to SweetAlert2
        <?php if (session()->has('success')): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '<?= esc(session('success')) ?>',
                timer: 3000,
                timerProgressBar: true,
                showConfirmButton: false
            });
        <?php endif; ?>

        <?php if (session()->has('error')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error!',
                text: '<?= esc(session('error')) ?>',
            });
        <?php endif; ?>
        
        <?php if (session()->has('errors')): ?>
            Swal.fire({
                icon: 'error',
                title: 'Validation Error',
                html: '<?= implode('<br>', array_map('esc', session('errors'))) ?>',
            });
        <?php endif; ?>

        // Notification Polling
        function fetchNotifications() {
            fetch('<?= base_url('api/notifications') ?>')
                .then(response => response.json())
                .then(data => {
                    const countBadge = document.getElementById('notifCount');
                    const list = document.getElementById('notifList');
                    const noMsg = document.getElementById('noNotifMsg');
                    
                    // Update Badge
                    if (data.count > 0) {
                        countBadge.textContent = data.count > 99 ? '99+' : data.count;
                        countBadge.style.display = 'block';
                    } else {
                        countBadge.style.display = 'none';
                    }

                    // Update List (Simplified for demo)
                    // In real app, diffing or React/Vue is better, but here we just rebuild if count changed or first load
                    // Ideally we check if list content needs update. For now, simple rebuild if ANY notification.
                    
                    // Clear existing items except header/footer (crudely)
                    // Actually, let's just keep it simple: clear all <li> after divider
                    const items = list.querySelectorAll('li.notif-item');
                    items.forEach(el => el.remove());

                    if (data.notifications.length > 0) {
                        if(noMsg) noMsg.style.display = 'none';
                        
                        data.notifications.forEach(notif => {
                            const li = document.createElement('li');
                            li.className = 'notif-item';
                            li.innerHTML = `
                                <a class="dropdown-item py-2 border-bottom ${notif.is_read == 0 ? 'bg-light' : ''}" href="#" onclick="markRead(${notif.id}, '${notif.url}')">
                                    <div class="d-flex w-100 justify-content-between">
                                        <strong class="mb-1 small">${notif.title}</strong>
                                        <small class="text-muted" style="font-size: 0.7rem;">Just now</small> 
                                    </div>
                                    <p class="mb-1 small text-muted text-truncate" style="max-width: 250px;">${notif.message}</p>
                                </a>
                            `;
                            list.appendChild(li);
                        });
                    } else {
                        if(noMsg) noMsg.style.display = 'block';
                    }
                })
                .catch(err => console.error('Notif Error:', err));
        }

        function markRead(id, url) {
            fetch('<?= base_url('api/notifications/mark-read') ?>/' + id, { method: 'POST' })
                .then(() => {
                    if (url && url !== 'null') window.location.href = '<?= base_url() ?>/' + url;
                });
        }

        // Poll every 10 seconds
        setInterval(fetchNotifications, 10000);
        // Initial call
        document.addEventListener('DOMContentLoaded', fetchNotifications);
    </script>

    <?= $this->renderSection('scripts') ?>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loader = document.getElementById('page-loader');

            // Function to show the loader
            function showLoader() {
                if (loader) {
                    loader.style.display = 'flex';
                }
            }

            // Function to hide the loader
            function hideLoader() {
                if (loader) {
                    loader.style.display = 'none';
                }
            }

            // Combine sidebar links and other important navigation links
            const navLinks = document.querySelectorAll('.sidebar-menu a, .content-wrapper a.btn');

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    // Exclude external links, new tabs, dropdowns, and empty hashes
                    if (link.hostname !== window.location.hostname || 
                        link.target === '_blank' || 
                        link.getAttribute('data-bs-toggle') === 'dropdown' ||
                        link.getAttribute('href') === '#') {
                        return;
                    }
                    showLoader();
                });
            });

            // Hide loader when page is shown
            window.addEventListener('pageshow', function() {
                setTimeout(hideLoader, 0);
            });

        });
    </script>
</body>
</html>
