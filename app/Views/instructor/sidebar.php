<?php
$uri = service('uri');
$current = $uri->getSegment(2);  // instructor/[segment]
if (!$current)
    $current = 'dashboard';
?>

<a href="<?= base_url('/instructor') ?>" class="<?= ($current == 'dashboard' || $current == '') ? 'active' : '' ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/instructor/courses') ?>" class="<?= ($current == 'courses' || $current == 'edit-course' || $current == 'edit-quiz' || $current == 'edit-assignment') ? 'active' : '' ?>">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/instructor/create-course') ?>" class="<?= $current == 'create-course' ? 'active' : '' ?>">
    <i class="fas fa-plus-circle"></i> Create Course
</a>
<a href="<?= base_url('/instructor/students') ?>" class="<?= $current == 'students' ? 'active' : '' ?>">
    <i class="fas fa-users"></i> Students
</a>
<a href="<?= base_url('/instructor/revenue') ?>" class="<?= $current == 'revenue' ? 'active' : '' ?>">
    <i class="fas fa-dollar-sign"></i> Revenue
</a>
<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
