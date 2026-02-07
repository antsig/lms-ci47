<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('student/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row">
    <div class="col-12">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h4 class="mb-0 fw-bold">Transaction History</h4>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="py-3 ps-4 border-0">Transaction ID</th>
                                <th class="py-3 border-0">Course</th>
                                <th class="py-3 border-0">Date</th>
                                <th class="py-3 border-0">Amount</th>
                                <th class="py-3 border-0">Status</th>
                                <th class="py-3 border-0">Proof</th>
                                <th class="py-3 pe-4 border-0 text-end">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($payments)): ?>
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <div class="text-muted">
                                            <i class="fas fa-receipt fa-3x mb-3 opacity-50"></i>
                                            <p class="mb-0">No transactions found.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td class="ps-4">
                                            <span class="font-monospace small bg-light px-2 py-1 rounded">
                                                #<?= esc($payment['transaction_id']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="<?= base_url('uploads/thumbnails/' . ($payment['thumbnail'] ?? 'default.jpg')) ?>" 
                                                     alt="Thumbnail" 
                                                     class="rounded me-2" 
                                                     style="width: 50px; height: 35px; object-fit: cover;">
                                                <div class="fw-bold"><?= esc($payment['course_title']) ?></div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small text-muted">
                                                <?= date('d M Y', $payment['date_added']) ?>
                                            </div>
                                            <div class="small text-muted opacity-75">
                                                <?= date('H:i', $payment['date_added']) ?>
                                            </div>
                                        </td>
                                        <td class="fw-bold">
                                            Rp <?= number_format($payment['amount']) ?>
                                        </td>
                                        <td>
                                            <?php
                                            $badgeClass = 'bg-secondary';
                                            $statusLabel = 'Unknown';

                                            switch ($payment['payment_status']) {
                                                case 'pending':
                                                    $badgeClass = 'bg-warning text-dark';
                                                    $statusLabel = 'Pending';
                                                    break;
                                                case 'verification_pending':
                                                    $badgeClass = 'bg-info text-white';
                                                    $statusLabel = 'Verifying';
                                                    break;
                                                case 'paid':
                                                    $badgeClass = 'bg-success';
                                                    $statusLabel = 'Paid';
                                                    break;
                                                case 'failed':
                                                case 'cancelled':
                                                    $badgeClass = 'bg-danger';
                                                    $statusLabel = 'Failed';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge rounded-pill <?= $badgeClass ?>">
                                                <?= $statusLabel ?>
                                            </span>
                                        <td>
                                            <?php if (!empty($payment['proof_file'])): ?>
                                                <a href="<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>" 
                                                   target="_blank" 
                                                   class="btn btn-sm btn-light border" 
                                                   title="View Uploaded Proof">
                                                    <i class="fas fa-file-image text-primary"></i>
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="pe-4 text-end">
                                            <a href="<?= base_url('payment/instruction/' . $payment['id']) ?>" class="btn btn-sm btn-outline-primary">
                                                Details
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
    </div>
</div>

<?= $this->endSection() ?>
