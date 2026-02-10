<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= esc($title ?? 'Dashboard') ?> - <?= esc($settings['system_name'] ?? 'LMS') ?></title>
<meta name="description" content="<?= esc($settings['meta_description'] ?? '') ?>">
<meta name="keywords" content="<?= esc($settings['meta_keywords'] ?? '') ?>">
<meta name="author" content="<?= esc($settings['meta_author'] ?? '') ?>">

<?php if (!empty($settings['favicon'])): ?>
<link rel="icon" href="<?= base_url('uploads/system/' . $settings['favicon']) ?>" type="image/x-icon">
<?php else: ?>
<link rel="icon" href="<?= base_url('favicon.ico') ?>" type="image/x-icon">
<?php endif; ?>

<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome (Dynamic Check) -->
<?php if (!empty($settings['fa_cdn_url'])): ?>
<link rel="stylesheet" href="<?= esc($settings['fa_cdn_url']) ?>">
<?php else: ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<?php endif; ?>

<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="<?= base_url('css/dashboard.css') ?>">

<?= $this->renderSection('styles') ?>
