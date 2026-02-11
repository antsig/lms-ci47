<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // admin/[segment]
if (!$current)
    $current = 'dashboard';
?>

<a href="<?= base_url('/admin/dashboard') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span>
</a>

<a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#courseSubmenu" class="sidebar-dropdown-toggle <?= in_array($current, ['categories', 'courses', 'create-course', 'edit-course', 'enrollments', 'certificates']) ? '' : 'collapsed' ?>" aria-expanded="<?= in_array($current, ['categories', 'courses', 'create-course', 'edit-course', 'enrollments', 'certificates']) ? 'true' : 'false' ?>">
    <div class="d-flex align-items-center">
        <i class="fas fa-book"></i> <span class="link-text">Courses</span>
    </div>
</a>
<div class="collapse sidebar-submenu <?= in_array($current, ['categories', 'courses', 'create-course', 'edit-course', 'enrollments', 'certificates']) ? 'show' : '' ?>" id="courseSubmenu">
    <a href="<?= base_url('/admin/courses') ?>" class="<?= in_array($current, ['courses', 'create-course', 'edit-course']) ? 'active' : '' ?>">All Courses</a>
    <a href="<?= base_url('/admin/categories') ?>" class="<?= $current == 'categories' ? 'active' : '' ?>">Categories</a>
    <a href="<?= base_url('/admin/enrollments') ?>" class="<?= $current == 'enrollments' ? 'active' : '' ?>">Enrollments</a>
    <a href="<?= base_url('/admin/certificates') ?>" class="<?= $current == 'certificates' ? 'active' : '' ?>">Certificates</a>
</div>

<a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#financeSubmenu" class="sidebar-dropdown-toggle <?= in_array($current, ['payment-requests', 'revenue', 'payment_history']) ? '' : 'collapsed' ?>" aria-expanded="<?= in_array($current, ['payment-requests', 'revenue', 'payment_history']) ? 'true' : 'false' ?>">
    <div class="d-flex align-items-center">
        <i class="fas fa-dollar-sign"></i> <span class="link-text">Finance</span>
    </div>
</a>
<div class="collapse sidebar-submenu <?= in_array($current, ['payment-requests', 'revenue', 'payment_history']) ? 'show' : '' ?>" id="financeSubmenu">
    <a href="<?= base_url('/admin/payment-requests') ?>" class="<?= $current == 'payment-requests' ? 'active' : '' ?>">
        Payment Requests
        <?php
        $pendingPayments = model('App\Models\PaymentModel')->where('payment_status', 'verification_pending')->countAllResults();
        if ($pendingPayments > 0):
            ?>
            <span class="badge bg-danger rounded-pill ms-2"><?= $pendingPayments ?></span>
        <?php endif; ?>
    </a>
    <a href="<?= base_url('/admin/revenue') ?>" class="<?= $current == 'revenue' ? 'active' : '' ?>">Revenue</a>
    <a href="<?= base_url('/admin/payment_history') ?>" class="<?= $current == 'payment_history' ? 'active' : '' ?>">History</a>
</div>

<a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#userSubmenu" class="sidebar-dropdown-toggle <?= in_array($current, ['users', 'team']) || strpos($current, 'user') !== false ? '' : 'collapsed' ?>" aria-expanded="<?= in_array($current, ['users', 'team']) || strpos($current, 'user') !== false ? 'true' : 'false' ?>">
    <div class="d-flex align-items-center">
        <i class="fas fa-users"></i> <span class="link-text">User Management</span>
    </div>
</a>
<div class="collapse sidebar-submenu <?= in_array($current, ['users', 'team']) || strpos($current, 'user') !== false ? 'show' : '' ?>" id="userSubmenu">
    <a href="<?= base_url('/admin/users') ?>" class="<?= uri_string() == 'admin/users' || strpos(uri_string(), 'admin/users/') !== false ? 'active' : '' ?>">Users</a>
    <a href="<?= base_url('/admin/team') ?>" class="<?= strpos($current, 'team') !== false ? 'active' : '' ?>">Team Management</a>
</div>

<a href="<?= base_url('/admin/messages') ?>" class="<?= strpos($current, 'messages') !== false ? 'active' : '' ?>">
    <i class="fas fa-inbox"></i> <span class="link-text">Messages</span>
    <?php
    $unreadCount = model('App\Models\ContactModel')->getUnreadCount();
    if ($unreadCount > 0):
        ?>
        <span class="badge bg-danger rounded-pill ms-2"><?= $unreadCount ?></span>
    <?php endif; ?>
</a>

<a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#settingsSubmenu" class="sidebar-dropdown-toggle <?= in_array($current, ['settings', 'add_user', 'edit_user', 'page_settings', 'layouting', 'icons', 'logo', 'banner', 'contact_settings']) ? '' : 'collapsed' ?>" aria-expanded="<?= in_array($current, ['settings', 'add_user', 'edit_user', 'page_settings', 'layouting', 'icons', 'logo', 'banner', 'contact_settings']) ? 'true' : 'false' ?>">
    <div class="d-flex align-items-center">
        <i class="fas fa-cogs"></i> <span class="link-text">Settings</span>
    </div>
</a>
<div class="collapse sidebar-submenu <?= in_array($current, ['settings', 'add_user', 'edit_user', 'page_settings', 'layouting', 'icons', 'logo', 'banner', 'contact_settings']) ? 'show' : '' ?>" id="settingsSubmenu">
    <!-- <a href="<?= base_url('/admin/features') ?>" class="<?= strpos(uri_string(), 'features') !== false ? 'active' : '' ?>">Website Features</a> -->
    <a href="<?= base_url('/admin/settings') ?>" class="<?= $current == 'settings' ? 'active' : '' ?>">System Settings</a>
    <a href="<?= base_url('/admin/settings/page') ?>" class="<?= $current == 'page_settings' ? 'active' : '' ?>">Page Settings</a>
    <a href="<?= base_url('/admin/settings/about') ?>" class="<?= strpos(uri_string(), 'settings/about') !== false ? 'active' : '' ?>">About Page</a>
    <a href="<?= base_url('/admin/settings/contact') ?>" class="<?= strpos(uri_string(), 'settings/contact') !== false ? 'active' : '' ?>">Contact Page</a>
    <a href="<?= base_url('/admin/settings/layout') ?>" class="<?= $current == 'layout' ? 'active' : '' ?>">Home Layout</a>
    <a href="<?= base_url('/admin/settings/icons') ?>" class="<?= $current == 'icons' ? 'active' : '' ?>">Manage Icons</a>
    <a href="<?= base_url('/admin/settings/logo') ?>" class="<?= $current == 'logo' ? 'active' : '' ?>">Logo</a>
    <a href="<?= base_url('/admin/settings/banner') ?>" class="<?= $current == 'banner' ? 'active' : '' ?>">Banner</a>
</div>

<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> <span class="link-text">Back to Home</span>
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span>
</a>
