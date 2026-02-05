<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <h2 class="mb-4 fw-bold">Checkout</h2>

            <div class="card mb-4 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Order Summary</h5>
                    <div class="d-flex align-items-start mt-3">
                        <img src="<?= base_url('uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                             alt="Course Thumbnail" class="rounded me-3" style="width: 100px; height: 75px; object-fit: cover;">
                        <div>
                            <h6 class="mb-1"><?= esc($course['title']) ?></h6>
                            <?php
                            $price = $course['discount_flag'] ? $course['discounted_price'] : $course['price'];
                            ?>
                            <p class="text-primary fw-bold mb-0">Rp <?= number_format($price) ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <form action="<?= base_url('payment/process') ?>" method="POST" id="checkoutForm">
                <?= csrf_field() ?>
                <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                
                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0 fw-bold">Select Payment Method</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="method_manual" value="manual" checked>
                            <label class="form-check-label w-100" for="method_manual">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong>Manual Bank Transfer</strong><br>
                                        <small class="text-muted">Transfer to BCA / Mandiri. Verification within 24 hours.</small>
                                    </span>
                                    <i class="fas fa-university fa-2x text-secondary"></i>
                                </div>
                            </label>
                        </div>

                        <div class="form-check mb-3 p-3 border rounded">
                            <input class="form-check-input" type="radio" name="payment_method" id="method_qris" value="qris">
                            <label class="form-check-label w-100" for="method_qris">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span>
                                        <strong>QRIS</strong><br>
                                        <small class="text-muted">Instant processing. <strong>+1.5% Admin Fee</strong></small>
                                    </span>
                                    <i class="fas fa-qrcode fa-2x text-secondary"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal</span>
                            <span>Rp <?= number_format($price) ?></span>
                        </div>
                        <div class="d-flex justify-content-between mb-2 text-danger" id="fee_row" style="display: none;">
                            <span>Admin Fee (1.5%)</span>
                            <span id="fee_amount">Rp 0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-4">
                            <span class="h5 fw-bold">Total to Pay</span>
                            <span class="h4 fw-bold text-primary" id="total_amount">Rp <?= number_format($price) ?></span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            Proceed to Payment <i class="fas fa-arrow-right ms-2"></i>
                        </button>
                    </div>
                </div>
            </form>
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
