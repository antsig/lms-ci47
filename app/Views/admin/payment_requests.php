<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0 fw-bold">Pending Payment Requests</h5>
    </div>
    <div class="card-body">
        <?php if (!empty($payments)): ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Student</th>
                            <th>Course</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Method</th>
                            <th>Proof</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td><code><?= esc($payment['transaction_id']) ?></code></td>
                                <td>
                                    <div class="fw-bold"><?= esc($payment['first_name'] . ' ' . $payment['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($payment['email']) ?></small>
                                </td>
                                <td><?= esc($payment['course_title']) ?></td>
                                <td class="fw-bold text-primary">Rp <?= number_format($payment['amount']) ?></td>
                                <td>
                                    <?php if ($payment['payment_status'] == 'verification_pending'): ?>
                                        <span class="badge bg-warning text-dark">Verifying</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payment['payment_method'] == 'qris'): ?>
                                        <span class="badge bg-info text-dark">QRIS</span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark border">Manual</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($payment['proof_file']): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info" 
                                                onclick="showProof('<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>')">
                                            <i class="fas fa-image"></i> View
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">No Proof</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('d M Y H:i', $payment['date_added']) ?></td>
                                <td>
                                    <?php if ($payment['payment_status'] == 'verification_pending'): ?>
                                        <a href="<?= base_url('/admin/approve-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-success"
                                           onclick="return confirm('Approve this payment and enroll student?');">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="<?= base_url('/admin/reject-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Reject this payment?');">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                    <?php else: ?>
                                        <a href="<?= base_url('/admin/approve-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-outline-success"
                                           onclick="return confirm('Force approve this payment without proof?');">
                                            <i class="fas fa-check"></i> Approve
                                        </a>
                                        <a href="<?= base_url('/admin/reject-payment/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Reject this payment request?');">
                                            <i class="fas fa-times"></i> Reject
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-check-circle fa-3x text-muted mb-3"></i>
                <h5>No Pending Payments</h5>
                <p class="text-muted">All payment requests have been processed.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Proof Preview Modal -->
<div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Payment Proof</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-0 bg-light">
                <img id="proofImage" src="" alt="Proof" class="img-fluid" style="max-height: 80vh;">
            </div>
        </div>
    </div>
</div>

<script>
    function showProof(url) {
        document.getElementById('proofImage').src = url;
        var myModal = new bootstrap.Modal(document.getElementById('proofModal'));
        myModal.show();
    }
</script>

<?= $this->endSection() ?>
