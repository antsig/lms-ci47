<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row">
        <!-- Main Content: Payment Methods -->
        <div class="col-lg-8 order-2 order-lg-1">
            <h2 class="mb-4 fw-bold">Checkout</h2>

            <form action="<?= base_url('payment/process') ?>" method="POST" id="checkoutForm">
                <?= csrf_field() ?>
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                
                <h5 class="mb-3 fw-bold">Select Payment Method</h5>
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <!-- Manual Transfer -->
                            <label class="list-group-item list-group-item-action p-4 d-flex align-items-center cursor-pointer">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="method_manual" value="manual" checked style="transform: scale(1.2);">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold">Manual Bank Transfer</h6>
                                        <i class="fas fa-university fa-lg text-secondary"></i>
                                    </div>
                                    <small class="text-muted">Transfer directly to our BCA or Mandiri account. Verification usually takes 24 hours.</small>
                                </div>
                            </label>

                            <!-- QRIS -->
                            <label class="list-group-item list-group-item-action p-4 d-flex align-items-center cursor-pointer">
                                <input class="form-check-input me-3" type="radio" name="payment_method" id="method_qris" value="qris" style="transform: scale(1.2);">
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <h6 class="mb-0 fw-bold">QRIS (Instant)</h6>
                                        <i class="fas fa-qrcode fa-lg text-secondary"></i>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <small class="text-muted">Pay instantly with GoPay, OVO, Dana, ShopeePay, etc.</small>
                                        <span class="badge bg-light text-dark border">+1.5% Fee</span>
                                    </div>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow-sm">
                        Complete Payment <i class="fas fa-lock ms-2"></i>
                    </button>
                    <div class="text-center mt-2">
                        <small class="text-muted"><i class="fas fa-shield-alt me-1"></i> Secure SSL Encrypted Transaction</small>
                    </div>
                </div>
            </form>
        </div>

        <!-- Sidebar: Order Summary -->
        <div class="col-lg-4 order-1 order-lg-2 mb-4">
             <div class="card shadow-sm border-0 sticky-top" style="top: 2rem;">
                <div class="card-body bg-light rounded-top p-4">
                    <h5 class="card-title fw-bold mb-3">Order Summary</h5>
                    <div class="d-flex mb-3">
                         <img src="<?= base_url('uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                             alt="Course Thumbnail" class="rounded me-3" style="width: 80px; height: 60px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0 small fw-bold text-dark line-clamp-2"><?= esc($course['title']) ?></h6>
                            <small class="text-muted">Lifetime Access</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-4 border-top">
                    <?php
                    $price = $course['discount_flag'] ? $course['discounted_price'] : $course['price'];
                    ?>
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Original Price</span>
                        <span class="fw-bold">Rp <?= number_format($price) ?></span>
                    </div>
                    <div class="d-flex justify-content-between mb-2 text-danger" id="fee_row" style="display: none;">
                        <span>Transaction Fee (1.5%)</span>
                        <span id="fee_amount">Rp 0</span>
                    </div>
                    <hr class="my-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="h5 fw-bold mb-0">Total</span>
                        <span class="h4 fw-bold text-primary mb-0" id="total_amount">Rp <?= number_format($price) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const basePrice = <?= $price ?>;
        const feePercentage = 0.015; // 1.5%

        const radioManual = document.getElementById('method_manual');
        const radioQris = document.getElementById('method_qris');
        
        const feeRow = document.getElementById('fee_row');
        const feeDisplay = document.getElementById('fee_amount');
        const totalDisplay = document.getElementById('total_amount');

        function updateTotals() {
            let fee = 0;
            if (radioQris.checked) {
                fee = basePrice * feePercentage;
                feeRow.style.display = 'flex';
                feeDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(fee);
            } else {
                feeRow.style.display = 'none';
            }

            const total = basePrice + fee;
            totalDisplay.textContent = 'Rp ' + new Intl.NumberFormat('id-ID').format(total);
        }

        radioManual.addEventListener('change', updateTotals);
        radioQris.addEventListener('change', updateTotals);
    });
</script>

<?= $this->endSection() ?>
