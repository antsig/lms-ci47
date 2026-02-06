<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // admin/[segment]
if (!$current)
    $current = 'dashboard';
?>

<a href="<?= base_url('/admin/dashboard') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/admin/users') ?>" class="<?= ($current == 'users' || $current == 'add_user' || $current == 'edit_user') ? 'active' : '' ?>">
    <i class="fas fa-users"></i> Manage Users
</a>
<a href="<?= base_url('/admin/categories') ?>" class="<?= ($current == 'categories') ? 'active' : '' ?>">
    <i class="fas fa-list"></i> Categories
</a>
<a href="<?= base_url('/admin/courses') ?>" class="<?= ($current == 'courses' || $current == 'create-course' || $current == 'edit-course') ? 'active' : '' ?>">
    <i class="fas fa-book"></i> Manage Courses
</a>
<a href="<?= base_url('/admin/enrollments') ?>" class="<?= $current == 'enrollments' ? 'active' : '' ?>">
    <i class="fas fa-user-graduate"></i> Enrollments
</a>
<a href="<?= base_url('/admin/payment-requests') ?>" class="<?= $current == 'payment-requests' ? 'active' : '' ?> d-flex justify-content-between align-items-center pe-3">
    <span><i class="fas fa-money-check-alt"></i> Payments</span>
    <?php
    $pendingPayments = model('App\Models\PaymentModel')->where('payment_status', 'verification_pending')->countAllResults();
    if ($pendingPayments > 0):
        ?>
        <span class="badge bg-danger rounded-pill"><?= $pendingPayments ?></span>
    <?php endif; ?>
</a>
<a href="<?= base_url('/admin/revenue') ?>" class="<?= $current == 'revenue' ? 'active' : '' ?>">
    <i class="fas fa-chart-line"></i> Revenue
</a>
<a href="<?= base_url('/admin/payment_history') ?>" class="<?= $current == 'payment_history' ? 'active' : '' ?>">
    <i class="fas fa-receipt"></i> Transaction History
</a>
<a href="<?= base_url('/admin/certificates') ?>" class="<?= $current == 'certificates' ? 'active' : '' ?>">
    <i class="fas fa-certificate"></i> Certificates
</a>
<a href="<?= base_url('/admin/settings') ?>" class="<?= $current == 'settings' ? 'active' : '' ?>">
    <i class="fas fa-cog"></i> Settings
</a>

<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
