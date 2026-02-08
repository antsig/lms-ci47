<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Page Header -->
    <div class="row justify-content-center text-center mb-5">
        <div class="col-lg-8">
            <h1 class="fw-bold">Get In Touch</h1>
            <p class="lead text-muted">We'd love to hear from you. Whether you have a question, feedback, or just want to say hello, feel free to reach out.</p>
        </div>
    </div>

    <div class="row g-5">
        <!-- Contact Form -->
        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Send Us a Message</h3>
                    
                    <?php if (session()->has('success')): ?>
                        <div class="alert alert-success">
                            <?= session('success') ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                    
                    <form action="<?= base_url('/contact/submit') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="subject" class="form-label">Subject *</label>
                            <input type="text" class="form-control" id="subject" name="subject" value="<?= old('subject') ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required><?= old('message') ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane me-2"></i> Send Message
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Contact Details -->
        <div class="col-lg-5">
            <div class="card h-100">
                <div class="card-body p-4">
                    <h3 class="fw-bold mb-4">Contact Information</h3>
                    <ul class="list-unstyled">
                        <li class="d-flex align-items-start mb-4">
                            <i class="fas fa-map-marker-alt fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Address</h5>
                                <p class="text-muted">123 Learning St, Education City, 12345</p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <i class="fas fa-phone fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Phone</h5>
                                <p class="text-muted"><a href="tel:+1234567890" class="text-reset">+1 (234) 567-890</a></p>
                            </div>
                        </li>
                        <li class="d-flex align-items-start mb-4">
                            <i class="fas fa-envelope fa-2x text-primary me-4"></i>
                            <div>
                                <h5 class="mb-1">Email</h5>
                                <p class="text-muted"><a href="mailto:support@lms.com" class="text-reset">support@lms.com</a></p>
                            </div>
                        </li>
                    </ul>
                    <div class="mt-4">
                        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.227497126131!2d-122.4214628846816!3d37.78438497975849!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzfCsDQ3JzAzLjgiTiAxMjLCsDI1JzE3LjMiVw!5e0!3m2!1sen!2sus!4v1646768846831!5m2!1sen!2sus" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>