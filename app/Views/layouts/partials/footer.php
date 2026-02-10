<footer class="mt-auto py-3 bg-white border-top">
    <div class="container-fluid text-center">
        <span class="text-muted text-sm">&copy; <?= date('Y') ?> <?= esc($settings['system_name'] ?? 'Learning Management System') ?>. All rights reserved.</span>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

    // Sidebar toggle logic
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        if (window.innerWidth >= 768) {
            // Desktop: Toggle collapsed class on body
            document.body.classList.toggle('sidebar-collapsed');
            
            // Optional: Save state to localStorage
            const isCollapsed = document.body.classList.contains('sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        } else {
            // Mobile: Toggle show class on sidebar
            document.getElementById('sidebar').classList.toggle('show');
        }
    });

    // Check localStorage on load (optional, prevents flashing)
    if (localStorage.getItem('sidebar-collapsed') === 'true' && window.innerWidth >= 768) {
        document.body.classList.add('sidebar-collapsed');
    }

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
        if (!'<?= base_url('api/notifications') ?>') return;
        
        fetch('<?= base_url('api/notifications') ?>')
            .then(response => {
                if(!response.ok) throw new Error('Network response was not ok');
                return response.json();
            })
            .then(data => {
                const countBadge = document.getElementById('notifCount');
                const list = document.getElementById('notifList');
                const noMsg = document.getElementById('noNotifMsg');
                
                if (!countBadge || !list) return;

                // Update Badge
                if (data.count > 0) {
                    countBadge.textContent = data.count > 99 ? '99+' : data.count;
                    countBadge.style.display = 'block';
                } else {
                    countBadge.style.display = 'none';
                }

                // Clear existing items except header/footer
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
                // Exclude external links, new tabs, dropdowns, empty hashes, and sidebar toggles
                if (link.hostname !== window.location.hostname || 
                    link.target === '_blank' || 
                    link.getAttribute('data-bs-toggle') === 'dropdown' ||
                    link.getAttribute('data-bs-toggle') === 'collapse' ||
                    link.classList.contains('sidebar-dropdown-toggle') ||
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
