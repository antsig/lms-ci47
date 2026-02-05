<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Enrollment History</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollStudentModal">
                <i class="fas fa-plus"></i> Enroll Student
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Date Enrolled</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($enrollments)): ?>
                        <?php foreach ($enrollments as $enrollment): ?>
                            <tr>
                                <td><?= $enrollment['id'] ?></td>
                                <td>
                                    <strong><?= esc($enrollment['first_name'] . ' ' . $enrollment['last_name']) ?></strong><br>
                                    <small class="text-muted"><?= esc($enrollment['email']) ?></small>
                                </td>
                                <td><?= esc($enrollment['course_title']) ?></td>
                                <td><?= date('M d, Y H:i', $enrollment['date_added']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No enrollments found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Enroll Student Modal -->
<div class="modal fade" id="enrollStudentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Manually Enroll Student</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/enroll-student') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Student</label>
                        <select class="form-select" name="user_id" required>
                            <option value="">Select Student</option>
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $student): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= esc($student['first_name'] . ' ' . $student['last_name']) ?> (<?= esc($student['email']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course</label>
                        <select class="form-select" name="course_id" required>
                            <option value="">Select Course</option>
                            <?php if (!empty($courses)): ?>
                                <?php foreach ($courses as $course): ?>
                                    <option value="<?= $course['id'] ?>">
                                        <?= esc($course['title']) ?>
                                    </option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Enroll</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
