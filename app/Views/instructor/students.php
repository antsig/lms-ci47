<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor') ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/instructor/courses') ?>">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/instructor/create-course') ?>">
    <i class="fas fa-plus-circle"></i> Create Course
</a>
<a href="<?= base_url('/instructor/students') ?>" class="active">
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
        <h5 class="mb-0 fw-bold">My Students</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Student Name</th>
                        <th>Enrolled Course</th>
                        <th>Enrollment Date</th>
                        <th>Progress</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($students)): ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($student['first_name'] . ' ' . $student['last_name']) ?></strong>
                                    <br><small class="text-muted"><?= esc($student['email']) ?></small>
                                </td>
                                <td><?= esc($student['course_title']) ?></td>
                                <td><?= date('M d, Y', $student['date_added']) ?></td>
                                <td>
                                    <div class="progress" style="height: 5px;">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted">0%</small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="4" class="text-center text-muted">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
