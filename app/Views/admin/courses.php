<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Manage Courses</h5>
            <a href="<?= base_url('/admin/create-course') ?>" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Add New Course
            </a>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link <?= $status_filter == 'all' ? 'active' : '' ?>" href="<?= base_url('/admin/courses') ?>">
                    All Courses
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $status_filter == 'active' ? 'active' : '' ?>" href="<?= base_url('/admin/courses/active') ?>">
                    Active
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $status_filter == 'pending' ? 'active' : '' ?>" href="<?= base_url('/admin/courses/pending') ?>">
                    Pending Approval
                </a>
            </li>
        </ul>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Instructor</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($courses)): ?>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <strong><?= esc($course['title']) ?></strong>
                                </td>
                                <td><?= esc($course['instructor_name'] ?? 'Unknown') ?></td>
                                <td><?= esc($course['category_name'] ?? 'General') ?></td>
                                <td>
                                    <?php if ($course['is_free_course']): ?>
                                        <span class="badge bg-success">Free</span>
                                    <?php else: ?>
                                        Rp <?= number_format($course['price']) ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['status'] == 'active'): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php elseif ($course['status'] == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">Pending</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?= base_url('/course/' . $course['id']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="<?= base_url('/admin/edit-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-primary" title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <?php if ($course['status'] == 'pending'): ?>
                                            <a href="<?= base_url('/admin/approve-course/' . $course['id']) ?>" class="btn btn-sm btn-outline-success" title="Approve">
                                                <i class="fas fa-check"></i>
                                            </a>
                                        <?php endif; ?>

                                        <?php if ($course['status'] == 'active'): ?>
                                            <a href="<?= base_url('/admin/course-status/' . $course['id'] . '/inactive') ?>" class="btn btn-sm btn-outline-warning" title="Deactivate">
                                                <i class="fas fa-ban"></i>
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('/admin/course-status/' . $course['id'] . '/active') ?>" class="btn btn-sm btn-outline-success" title="Activate">
                                                <i class="fas fa-check-circle"></i>
                                            </a>
                                        <?php endif; ?>

                                        <a href="<?= base_url('/admin/delete-course/' . $course['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Are you sure you want to delete this course?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center text-muted">No courses found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
