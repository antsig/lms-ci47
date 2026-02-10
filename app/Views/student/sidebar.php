<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // student/[segment]
if (!$current)
    $current = 'dashboard';
?>

<div class="sidebar-header">Main</div>
<a href="<?= base_url('/student') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span>
</a>

<div class="sidebar-header">Learning</div>
<a href="<?= base_url('/student/my-courses') ?>" class="<?= $current == 'my-courses' ? 'active' : '' ?>">
    <i class="fas fa-book"></i> <span class="link-text">My Courses</span>
</a>
<a href="<?= base_url('/student/wishlist') ?>" class="<?= $current == 'wishlist' ? 'active' : '' ?>">
    <i class="fas fa-heart"></i> <span class="link-text">Wishlist</span>
</a>

<div class="sidebar-header">Account</div>
<a href="<?= base_url('/student/profile') ?>" class="<?= ($current == 'profile' || $current == 'change-password') ? 'active' : '' ?>">
    <i class="fas fa-user"></i> <span class="link-text">Profile</span>
</a>
<a href="<?= base_url('/payment/history') ?>" class="<?= $current == 'history' ? 'active' : '' ?>">
    <i class="fas fa-receipt"></i> <span class="link-text">Transaction History</span>
</a>

<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> <span class="link-text">Back to Home</span>
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span>
</a>
