<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Add Feature</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/features/create') ?>" method="POST">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" required placeholder="e.g. Expert Instructors">
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Icon Class (FontAwesome)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-icons"></i></span>
                            <input type="text" class="form-control" name="icon" required placeholder="e.g. fas fa-chalkboard-teacher">
                        </div>
                        <small class="text-muted">Use FontAwesome 5 classes.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" required placeholder="Short description..."></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Display Order</label>
                        <input type="number" class="form-control" name="display_order" value="0">
                    </div>

                    <div class="text-end">
                        <a href="<?= base_url('/admin/features') ?>" class="btn btn-secondary me-2">Cancel</a>
                        <button type="submit" class="btn btn-primary">Save Feature</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
