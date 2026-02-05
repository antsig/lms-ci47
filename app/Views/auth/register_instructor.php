<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <div class="row justify-content-center py-5">
        <div class="col-md-7">
            <div class="card shadow-lg">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <i class="fas fa-chalkboard-teacher fa-3x text-primary mb-3"></i>
                        <h2 class="fw-bold">Become an Instructor</h2>
                        <p class="text-muted">Share your knowledge and earn money teaching online</p>
                    </div>

                    <?php if (session()->has('errors')): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach (session('errors') as $error): ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('/register/instructor/process') ?>" method="POST">
                        <?= csrf_field() ?>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="first_name" class="form-label">First Name *</label>
                                <input type="text" class="form-control" id="first_name" name="first_name" 
                                       value="<?= old('first_name') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="last_name" class="form-label">Last Name *</label>
                                <input type="text" class="form-control" id="last_name" name="last_name" 
                                       value="<?= old('last_name') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address *</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= old('email') ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone Number *</label>
                            <input type="tel" class="form-control" id="phone" name="phone" 
                                   value="<?= old('phone') ?>" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" 
                                       required minlength="6">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="confirm_password" class="form-label">Confirm Password *</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password" 
                                       required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="skills" class="form-label">Skills/Expertise</label>
                            <input type="text" class="form-control" id="skills" name="skills" 
                                   value="<?= old('skills') ?>" placeholder="e.g., Web Development, Digital Marketing">
                            <small class="text-muted">Separate multiple skills with commas</small>
                        </div>

                        <div class="mb-3">
                            <label for="biography" class="form-label">Biography *</label>
                            <textarea class="form-control" id="biography" name="biography" rows="4" 
                                      required minlength="50"><?= old('biography') ?></textarea>
                            <small class="text-muted">Tell us about your teaching experience (minimum 50 characters)</small>
                        </div>

                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms" value="1" required>
                            <label class="form-check-label" for="terms">
                                I agree to the <a href="#" class="text-decoration-none">Instructor Terms & Conditions</a>
                            </label>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Your application will be reviewed by our team. You'll receive an email once approved.
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mb-3">
                            <i class="fas fa-paper-plane"></i> Submit Application
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-center">
                        <p class="mb-0">Already have an account? 
                            <a href="<?= base_url('/login') ?>" class="fw-bold text-decoration-none">Login</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
