<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Inbox</h4>
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
                        <th class="ps-4">Sender</th>
                        <th>Subject</th>
                        <th>Date</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($messages)): ?>
                        <tr>
                            <td colspan="4" class="text-center py-5 text-muted">
                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                <p>No messages found.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($messages as $msg): ?>
                            <tr class="<?= $msg['read_status'] == 0 ? 'fw-bold bg-light-info' : '' ?>">
                                <td class="ps-4">
                                    <div class="d-flex flex-column">
                                        <span><?= esc($msg['name']) ?></span>
                                        <small class="text-muted"><?= esc($msg['email']) ?></small>
                                    </div>
                                </td>
                                <td>
                                    <?= esc($msg['subject']) ?>
                                    <div class="text-muted small text-truncate" style="max-width: 400px;"><?= esc($msg['message']) ?></div>
                                </td>
                                <td><?= date('M d, Y H:i', $msg['created_at']) ?></td>
                                <td class="text-end pe-4">
                                    <a href="#" class="btn btn-sm btn-outline-primary me-1" data-bs-toggle="modal" data-bs-target="#messageModal<?= $msg['id'] ?>">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="<?= base_url('/admin/messages/delete/' . $msg['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>

                            <!-- Message Modal -->
                            <div class="modal fade" id="messageModal<?= $msg['id'] ?>" tabindex="-1">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><?= esc($msg['subject']) ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body fw-normal">
                                            <div class="mb-3">
                                                <strong>From:</strong> <?= esc($msg['name']) ?> &lt;<?= esc($msg['email']) ?>&gt;<br>
                                                <strong>Date:</strong> <?= date('F d, Y H:i', $msg['created_at']) ?>
                                            </div>
                                            <hr>
                                            <div class="p-3 bg-light rounded">
                                                <?= nl2br(esc($msg['message'])) ?>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <a href="mailto:<?= esc($msg['email']) ?>" class="btn btn-primary">
                                                <i class="fas fa-reply me-1"></i> Reply
                                            </a>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="card-footer bg-white">
        <?= $pager->links() ?>
    </div>
</div>

<?= $this->endSection() ?>
