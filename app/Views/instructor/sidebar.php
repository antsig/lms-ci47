<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // instructor/[segment]
if (!$current)
    $current = 'dashboard';
?>

<a href="<?= base_url('/instructor') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> <span class="link-text">Dashboard</span>
</a>

<a href="javascript:void(0)" data-bs-toggle="collapse" data-bs-target="#teachingSubmenu" class="sidebar-dropdown-toggle <?= in_array($current, ['courses', 'edit-course', 'edit-quiz', 'edit-assignment', 'students']) ? '' : 'collapsed' ?>" aria-expanded="<?= in_array($current, ['courses', 'edit-course', 'edit-quiz', 'edit-assignment', 'students']) ? 'true' : 'false' ?>">
    <div class="d-flex align-items-center">
        <i class="fas fa-chalkboard-teacher"></i> <span class="link-text">Teaching</span>
    </div>
</a>
<div class="collapse sidebar-submenu <?= in_array($current, ['courses', 'edit-course', 'edit-quiz', 'edit-assignment', 'students']) ? 'show' : '' ?>" id="teachingSubmenu">
    <a href="<?= base_url('/instructor/courses') ?>" class="<?= in_array($current, ['courses', 'edit-course', 'edit-quiz', 'edit-assignment']) ? 'active' : '' ?>">My Courses</a>
    <a href="<?= base_url('/instructor/students') ?>" class="<?= $current == 'students' ? 'active' : '' ?>">My Students</a>
</div>

<a href="<?= base_url('/instructor/my-learning') ?>" class="<?= ($current == 'my-learning') ? 'active' : '' ?>">
    <i class="fas fa-graduation-cap"></i> <span class="link-text">My Learning</span>
</a>
<a href="<?= base_url('/instructor/revenue') ?>" class="<?= $current == 'revenue' ? 'active' : '' ?>">
    <i class="fas fa-dollar-sign"></i> <span class="link-text">Revenue</span>
</a>

<a href="<?= base_url('/instructor/profile') ?>" class="<?= ($current == 'profile' || $current == 'change-password') ? 'active' : '' ?>">
    <i class="fas fa-user"></i> <span class="link-text">My Profile</span>
</a>

<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> <span class="link-text">Back to Home</span>
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> <span class="link-text">Logout</span>
</a>
