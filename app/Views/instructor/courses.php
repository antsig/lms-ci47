<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
<a href="<?= base_url('/instructor') ?>">
    <i class="fas fa-tachometer-alt"></i> Dashboard
</a>
<a href="<?= base_url('/instructor/courses') ?>" class="active">
    <i class="fas fa-book"></i> My Courses
</a>
<a href="<?= base_url('/instructor/create-course') ?>">
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
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">My Courses</h5>
            <a href="<?= base_url('/instructor/create-course') ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Create New Course
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (!empty($courses)): ?>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Enrolled</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="<?= base_url('/uploads/thumbnails/' . ($course['thumbnail'] ?? 'default.jpg')) ?>" 
                                             alt="thumb" width="50" height="50" class="rounded me-2">
                                        <div>
                                            <strong><?= esc($course['title']) ?></strong>
                                        </div>
                                    </div>
                                </td>
                                <td><?= esc($course['category_name'] ?? 'General') ?></td>
                                <td>
                                    <?php if ($course['is_free_course']): ?>
                                        <span class="badge bg-success">Free</span>
                                    <?php else: ?>
                                        Rp <?= number_format($course['price']) ?>
                                    <?php endif; ?>
                                </td>
                                <td><?= $course['enrollment_count'] ?? 0 ?></td>
                                <td>
                                    <?php if ($course['status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($course['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Draft</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="<?= base_url('/instructor/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="<?= base_url('/course/' . $course['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <h5>No courses found</h5>
                <p class="text-muted">Start by creating your first course.</p>
                <a href="<?= base_url('/instructor/create-course') ?>" class="btn btn-primary">
                    Create Course
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<?= $this->endSection() ?>
