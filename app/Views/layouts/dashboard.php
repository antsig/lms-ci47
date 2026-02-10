<?php
$settings = model('App\Models\BaseModel')->get_settings();
$layoutMode = $settings['layout_mode'] ?? 'fluid';
$sidebarStyle = $settings['sidebar_style'] ?? 'dark';  // Default to dark
$navbarColor = $settings['navbar_color'] ?? 'white';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?= $this->include('layouts/partials/head', ['settings' => $settings]) ?>
</head>
<body class="<?= $layoutMode == 'boxed' ? 'layout-boxed' : '' ?>">
    <div id="page-loader">
        <div class="spinner"></div>
    </div>
    <!-- Sidebar -->
    <div class="sidebar <?= $sidebarStyle == 'light' ? 'bg-light text-dark' : 'bg-dark text-white' ?>" id="sidebar">
        <div class="sidebar-brand">
            <?php if (!empty($settings['system_logo'])): ?>
                <img src="<?= base_url('uploads/system/' . $settings['system_logo']) ?>" alt="Logo" class="img-fluid" style="max-height: 40px;">
            <?php else: ?>
                <i class="fas fa-graduation-cap"></i> <?= esc($settings['system_name'] ?? 'LMS') ?>
            <?php endif; ?>
        </div>
        <div class="sidebar-menu">
            <?= $this->renderSection('sidebar') ?>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content d-flex flex-column min-vh-100">
        <?= $this->include('layouts/partials/topbar', ['settings' => $settings]) ?>

        <!-- Page Content -->
        <div class="content-wrapper flex-grow-1">
            <?= $this->renderSection('content') ?>
        </div>

        <?= $this->include('layouts/partials/footer', ['settings' => $settings]) ?>
    </div>
</body>
</html>
