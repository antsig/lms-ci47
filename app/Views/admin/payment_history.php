<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row mb-4">
    <div class="col">
        <h2 class="fw-bold fs-4">Transaction History</h2>
        <p class="text-muted mb-0">List of all student course purchases.</p>
    </div>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-4 border-0">ID</th>
                        <th class="py-3 border-0">Student</th>
                        <th class="py-3 border-0">Course</th>
                        <th class="py-3 border-0">Amount</th>
                        <th class="py-3 border-0">Date</th>
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
                                    <p class="mb-0">No transactions recorded yet.</p>
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
                                    <div class="fw-bold"><?= esc($payment['first_name'] . ' ' . $payment['last_name']) ?></div>
                                    <small class="text-muted"><?= esc($payment['email']) ?></small>
                                </td>
                                <td>
                                    <div class="text-truncate" style="max-width: 200px;" title="<?= esc($payment['course_title']) ?>">
                                        <?= esc($payment['course_title']) ?>
                                    </div>
                                </td>
                                <td class="fw-bold">
                                    Rp <?= number_format($payment['amount']) ?>
                                </td>
                                <td>
                                    <div class="small">
                                        <?= date('d M Y', $payment['date_added']) ?>
                                    </div>
                                    <div class="small text-muted opacity-75">
                                        <?= date('H:i', $payment['date_added']) ?>
                                    </div>
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
                                </td>
                                <td>
                                    <?php if (!empty($payment['proof_file'])): ?>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-info" 
                                                onclick="showProof('<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>')"
                                                title="View Proof">
                                            <i class="fas fa-image"></i>
                                        </button>
                                    <?php else: ?>
                                        <span class="text-muted small">-</span>
                                    <?php endif; ?>
                                </td>
                                <td class="pe-4 text-end">
                                    <div class="d-flex justify-content-end gap-1">
                                        <a href="<?= base_url('admin/payment-detail/' . $payment['id']) ?>" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($payment['payment_status'] == 'verification_pending' || $payment['payment_status'] == 'pending'): ?>
                                            <a href="<?= base_url('/admin/approve-payment/' . $payment['id']) ?>" 
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('Approve this payment and enroll student?');"
                                               title="Approve">
                                                <i class="fas fa-check"></i>
                                            </a>
                                            <a href="<?= base_url('/admin/reject-payment/' . $payment['id']) ?>" 
                                               class="btn btn-sm btn-outline-danger"
                                               onclick="return confirm('Reject this payment?');"
                                               title="Reject">
                                                <i class="fas fa-times"></i>
                                            </a>
                                        <?php endif; ?>
                                    </div>
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
