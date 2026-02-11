<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Add Team Member</h5>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('errors')): ?>
                    <div class="alert alert-danger">
                        <ul>
                            <?php foreach (session()->getFlashdata('errors') as $error): ?>
                                <li><?= esc($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="<?= base_url('/admin/team/store') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Member Type</label>
                        <div class="btn-group w-100" role="group">
                            <input type="radio" class="btn-check" name="type" id="type_instructor" value="instructor" checked onclick="toggleType('instructor')">
                            <label class="btn btn-outline-primary" for="type_instructor">Select Instructor</label>

                            <input type="radio" class="btn-check" name="type" id="type_manual" value="manual" onclick="toggleType('manual')">
                            <label class="btn btn-outline-primary" for="type_manual">Manual Entry</label>
                        </div>
                    </div>

                    <!-- Instructor Selection -->
                    <div id="instructor_section">
                        <div class="mb-3">
                            <label class="form-label">Select Instructor</label>
                            <select class="form-select" name="user_id">
                                <option value="">-- Choose an Instructor --</option>
                                <?php foreach ($instructors as $instructor): ?>
                                    <option value="<?= $instructor['id'] ?>">
                                        <?= esc($instructor['first_name'] . ' ' . $instructor['last_name']) ?> (<?= esc($instructor['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <small class="text-muted">Profile image and basic info will be pulled from their account.</small>
                        </div>
                    </div>

                    <!-- Manual Entry -->
                    <div id="manual_section" style="display: none;">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" placeholder="Full Name">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Role / Title</label>
                            <input type="text" class="form-control" name="role" placeholder="e.g. Lead Instructor, CEO">
                            <small class="text-muted">Overrides instructor role if set.</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Display Order</label>
                            <input type="number" class="form-control" name="display_order" value="0">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Biography (Optional Override)</label>
                        <textarea class="form-control" name="biography" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Custom Image (Optional)</label>
                        <input type="file" class="form-control" name="image" accept="image/*">
                        <small class="text-muted">Leave empty to use Instructor's profile picture.</small>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">Save Member</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleType(type) {
        if (type === 'instructor') {
            document.getElementById('instructor_section').style.display = 'block';
            document.getElementById('manual_section').style.display = 'none';
        } else {
            document.getElementById('instructor_section').style.display = 'none';
            document.getElementById('manual_section').style.display = 'block';
        }
    }
</script>

<?= $this->endSection() ?>
