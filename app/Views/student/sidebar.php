<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // student/[segment]
if (!$current)
    $current = 'dashboard';
?>

<a href="<?= base_url('/student') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/student/my-courses') ?>" class="<?= $current == 'my-courses' ? 'active' : '' ?>">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/student/wishlist') ?>" class="<?= $current == 'wishlist' ? 'active' : '' ?>">
    <i class="fas fa-heart"></i> Wishlist
</a>
<a href="<?= base_url('/student/profile') ?>" class="<?= ($current == 'profile' || $current == 'change-password') ? 'active' : '' ?>">
    <i class="fas fa-user"></i> Profile
</a>
<a href="<?= base_url('/payment/history') ?>" class="<?= $current == 'history' ? 'active' : '' ?>">
    <i class="fas fa-receipt"></i> Transaction History
</a>

<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
