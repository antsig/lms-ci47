<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Website Features</h4>
    <a href="<?= base_url('/admin/features/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Feature
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Order</th>
                        <th class="text-center">Icon</th>
                        <th>Title & Description</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($features)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <p>No features added yet.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($features as $feature): ?>
                            <tr>
                                <td class="ps-4"><?= $feature['display_order'] ?></td>
                                <td class="text-center">
                                    <i class="<?= esc($feature['icon']) ?> fa-2x text-primary"></i>
                                </td>
                                <td>
                                    <strong><?= esc($feature['title']) ?></strong>
                                    <p class="mb-0 text-muted small"><?= esc($feature['description']) ?></p>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= base_url('/admin/features/edit/' . $feature['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/features/delete/' . $feature['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
