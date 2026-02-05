<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor/courses') ?>" class="active">
    <i class="fas fa-book"></i> Back to Courses
</a>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Edit Assignment: <?= esc($assignment['title']) ?></h4>
    <a href="<?= base_url('/instructor/edit-course/' . $course['id']) ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Course
    </a>
</div>

<div class="row">
    <!-- Assignment Settings -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Settings</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/instructor/update-assignment/' . $assignment['id']) ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?= esc($assignment['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="4"><?= esc($assignment['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" name="deadline" 
                               value="<?= $assignment['deadline'] ? date('Y-m-d\TH:i', $assignment['deadline']) : '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="attachment">
                        <?php if ($assignment['attachment_url']): ?>
                            <div class="mt-2">
                                <a href="<?= base_url('uploads/assignment_files/' . $assignment['attachment_url']) ?>" target="_blank">
                                    <i class="fas fa-paperclip"></i> Current Attachment
                                </a>
                            </div>
                        <?php endif; ?>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Settings</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Submissions (Future Implementation) -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Student Submissions</h5>
            </div>
            <div class="card-body">
                <!-- 
                TODO: List submissions here.
                This requires a `submissions` relation which we haven't fetched yet in Controller. 
                For now, empty state.
                -->
                <div class="text-center text-muted py-4">
                    No submissions yet.
                    <br><small>(Submission listing and grading logic needs to be connected to Student flow first)</small>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
