<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800">Certificate Templates</h1>
    <a href="<?= base_url('admin/certificates/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Create New Template
    </a>
</div>

<div class="card shadow mb-4">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Backgroud</th>
                        <th>Created At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($certificates)): ?>
                        <?php foreach ($certificates as $cert): ?>
                            <tr>
                                <td><?= $cert['id'] ?></td>
                                <td><?= esc($cert['title']) ?></td>
                                <td>
                                    <?php if ($cert['background_image']): ?>
                                        <a href="<?= base_url('uploads/certificates/' . $cert['background_image']) ?>" target="_blank">View</a>
                                    <?php else: ?>
                                        <span class="text-muted">None</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y', strtotime($cert['created_at'])) ?></td>
                                <td>
                                    <a href="<?= base_url('admin/certificates/edit/' . $cert['id']) ?>" class="btn btn-sm btn-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('admin/certificates/delete/' . $cert['id']) ?>" 
                                       class="btn btn-sm btn-danger" 
                                       onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No templates found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
