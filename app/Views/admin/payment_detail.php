<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row align-items-center mb-4">
    <div class="col-auto">
        <a href="<?= base_url('/admin/payment_history') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </div>
    <div class="col">
        <h2 class="fw-bold fs-4 mb-0">Transaction Detail</h2>
        <p class="text-muted mb-0">Order #<?= esc($payment['transaction_id']) ?></p>
    </div>
</div>

<div class="row">
    <!-- Transaction Info -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Transaction Information</div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <td class="text-muted" style="width: 140px;">Status</td>
                        <td>
                            <?php if ($payment['payment_status'] == 'paid'): ?>
                                <span class="badge bg-success">Paid</span>
                            <?php elseif ($payment['payment_status'] == 'verification_pending'): ?>
                                <span class="badge bg-info text-white">Verification Pending</span>
                            <?php elseif ($payment['payment_status'] == 'pending'): ?>
                                <span class="badge bg-warning text-dark">Pending</span>
                            <?php else: ?>
                                <span class="badge bg-danger"><?= ucfirst($payment['payment_status']) ?></span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Amount</td>
                        <td class="fw-bold text-success fs-5">Rp <?= number_format($payment['amount']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Payment Method</td>
                        <td><?= strtoupper($payment['payment_method']) ?></td>
                    </tr>
                    <tr>
                        <td class="text-muted">Date</td>
                        <td><?= date('d M Y, H:i', $payment['date_added']) ?></td>
                    </tr>
                </table>
                <hr>
                <h6 class="fw-bold mb-3">Student & Course</h6>
                <table class="table table-borderless mb-0">
                    <tr>
                        <td class="text-muted" style="width: 140px;">Student</td>
                        <td>
                            <div class="fw-bold"><?= esc($payment['first_name'] . ' ' . $payment['last_name']) ?></div>
                            <small class="text-muted"><?= esc($payment['email']) ?></small>
                        </td>
                    </tr>
                    <tr>
                        <td class="text-muted">Course</td>
                        <td><?= esc($payment['course_title']) ?></td>
                    </tr>
                </table>
            </div>
            <?php if ($payment['payment_status'] == 'verification_pending'): ?>
                <div class="card-footer bg-white text-end">
                    <a href="<?= base_url('admin/approve-payment/' . $payment['id']) ?>" 
                       class="btn btn-success" 
                       onclick="return confirm('Are you sure you want to approve this payment?')">
                        <i class="fas fa-check"></i> Approve Payment
                    </a>
                    <a href="<?= base_url('admin/reject-payment/' . $payment['id']) ?>" 
                       class="btn btn-outline-danger" 
                       onclick="return confirm('Are you sure you want to reject this payment?')">
                        <i class="fas fa-times"></i> Reject
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Payment Proof -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm border-0 h-100">
            <div class="card-header bg-white fw-bold">Payment Proof</div>
            <div class="card-body text-center d-flex align-items-center justify-content-center bg-light">
                <?php if (!empty($payment['proof_file'])): ?>
                    <a href="#" onclick="showProof('<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>'); return false;">
                        <img src="<?= base_url('uploads/payment_proofs/' . $payment['proof_file']) ?>" 
                             alt="Payment Proof" 
                             class="img-fluid rounded shadow-sm"
                             style="max-height: 400px;">
                    </a>
                <?php else: ?>
                    <div class="text-muted py-5">
                        <i class="fas fa-image fa-3x mb-3 opacity-25"></i>
                        <p>No proof of payment uploaded yet.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
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
