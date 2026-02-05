<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor') ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/instructor/courses') ?>">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/instructor/create-course') ?>" class="active">
    <i class="fas fa-plus-circle"></i> Create Course
</a>
<a href="<?= base_url('/instructor/students') ?>">
    <i class="fas fa-users"></i> Students
</a>
<a href="<?= base_url('/instructor/revenue') ?>">
    <i class="fas fa-dollar-sign"></i> Revenue
</a>
<hr>
<a href="<?= base_url('/') ?>">
    <i class="fas fa-home"></i> Back to Home
</a>
<a href="<?= base_url('/login/logout') ?>">
    <i class="fas fa-sign-out-alt"></i> Logout
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">Create New Course</h5>
    </div>
    <div class="card-body">
        <form action="<?= base_url('/instructor/store-course') ?>" method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            
            <ul class="nav nav-tabs mb-4" id="courseTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="basic-tab" data-bs-toggle="tab" data-bs-target="#basic" type="button" role="tab">Basic Info</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="pricing-tab" data-bs-toggle="tab" data-bs-target="#pricing" type="button" role="tab">Pricing</button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="media-tab" data-bs-toggle="tab" data-bs-target="#media" type="button" role="tab">Media & Description</button>
                </li>
            </ul>
            
            <div class="tab-content" id="courseTabsContent">
                <!-- Basic Info Tab -->
                <div class="tab-pane fade show active" id="basic" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Course Title *</label>
                        <input type="text" class="form-control" name="title" required minlength="5" placeholder="e.g., Complete Python Bootcamp">
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Category *</label>
                            <select class="form-select" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $category): ?>
                                    <option value="<?= $category['id'] ?>"><?= esc($category['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Level *</label>
                            <select class="form-select" name="level" required>
                                <option value="Beginner">Beginner</option>
                                <option value="Intermediate">Intermediate</option>
                                <option value="Advanced">Advanced</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Language *</label>
                        <select class="form-select" name="language" required>
                            <option value="English">English</option>
                            <option value="Indonesian">Indonesian</option>
                            <option value="Spanish">Spanish</option>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">What will students learn? (Outcomes)</label>
                            <textarea class="form-control" name="outcomes" rows="4" placeholder="Enter outcomes separated by new lines"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Requirements</label>
                            <textarea class="form-control" name="requirements" rows="4" placeholder="Enter requirements separated by new lines"></textarea>
                        </div>
                    </div>
                </div>
                
                <!-- Pricing Tab -->
                <div class="tab-pane fade" id="pricing" role="tabpanel">
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="is_free_course" name="is_free_course" value="1">
                        <label class="form-check-label" for="is_free_course">Check if this is a free course</label>
                    </div>
                    
                    <div id="pricing_fields">
                        <div class="mb-3">
                            <label class="form-label">Course Price (Rp) *</label>
                            <input type="number" class="form-control" name="price" id="price" value="0">
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="discount_flag" name="discount_flag" value="1">
                            <label class="form-check-label" for="discount_flag">Check if this course has a discount</label>
                        </div>
                        
                        <div class="mb-3" id="discount_field" style="display: none;">
                            <label class="form-label">Discounted Price (Rp)</label>
                            <input type="number" class="form-control" name="discounted_price" value="0">
                        </div>
                    </div>
                </div>
                
                <!-- Media & Description Tab -->
                <div class="tab-pane fade" id="media" role="tabpanel">
                    <div class="mb-3">
                        <label class="form-label">Short Description *</label>
                        <textarea class="form-control" name="short_description" rows="3" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Description *</label>
                        <textarea class="form-control" name="description" rows="10" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Course Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail" accept="image/*">
                        <small class="text-muted">Recommended size: 600x400 pixels</small>
                    </div>
                </div>
            </div>
            
            <hr class="mt-4">
            
            <button type="submit" class="btn btn-primary btn-lg w-100">
                <i class="fas fa-save"></i> Create Course
            </button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Free course toggle
        const isFreeCheckbox = document.getElementById('is_free_course');
        const pricingFields = document.getElementById('pricing_fields');
        const priceInput = document.getElementById('price');
        
        isFreeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                pricingFields.style.opacity = '0.5';
                pricingFields.style.pointerEvents = 'none';
                priceInput.value = '0';
            } else {
                pricingFields.style.opacity = '1';
                pricingFields.style.pointerEvents = 'auto';
            }
        });
        
        // Discount toggle
        const discountCheckbox = document.getElementById('discount_flag');
        const discountField = document.getElementById('discount_field');
        
        discountCheckbox.addEventListener('change', function() {
            if (this.checked) {
                discountField.style.display = 'block';
            } else {
                discountField.style.display = 'none';
            }
        });
    });
</script>

<?= $this->endSection() ?>
