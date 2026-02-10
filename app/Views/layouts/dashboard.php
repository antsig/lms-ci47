<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->include('layouts/partials/head') ?>
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
    <div class="main-content d-flex flex-column min-vh-100">
        <?= $this->include('layouts/partials/topbar') ?>

        <!-- Page Content -->
        <div class="content-wrapper flex-grow-1">
            <?= $this->renderSection('content') ?>
        </div>

        <?= $this->include('layouts/partials/footer') ?>
    </div>
</body>
</html>
