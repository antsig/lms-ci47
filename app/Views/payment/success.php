<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="card shadow-sm py-5">
                <div class="card-body">
                    <div class="mb-4">
                        <i class="fas fa-check-circle fa-5x text-success"></i>
                    </div>
                    <h2 class="fw-bold mb-3">Payment Submitted!</h2>
                    <p class="text-muted mb-4 lead">
                        Thank you for your payment. We have received your proof of payment and it is currently under review.
                    </p>
                    <p class="mb-4">
                        Verification usually takes less than 24 hours. You will be automatically enrolled once approved.
                    </p>
                    
                    <a href="<?= base_url('/student/dashboard') ?>" class="btn btn-primary">
                        Go to Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
