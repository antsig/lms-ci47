<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Manage Users</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addUserModal">
                <i class="fas fa-plus"></i> Add User
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Tabs -->
        <ul class="nav nav-tabs mb-3">
            <li class="nav-item">
                <a class="nav-link <?= $role_filter == 'all' ? 'active' : '' ?>" href="<?= base_url('/admin/users') ?>">
                    All Users (<?= count($users) ?>)
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $role_filter == 'students' ? 'active' : '' ?>" href="<?= base_url('/admin/users/students') ?>">
                    Students
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $role_filter == 'instructors' ? 'active' : '' ?>" href="<?= base_url('/admin/users/instructors') ?>">
                    Instructors
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $role_filter == 'admins' ? 'active' : '' ?>" href="<?= base_url('/admin/users/admins') ?>">
                    Admins
                </a>
            </li>
        </ul>
        
        <!-- Users Table -->
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($users)): ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td>
                                    <strong><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></strong>
                                </td>
                                <td><?= esc($user['email']) ?></td>
                                <td>
                                    <?php if ($user['role_id'] == 1): ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php elseif ($user['is_instructor']): ?>
                                        <span class="badge bg-primary">Instructor</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Student</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($user['status']): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= date('M d, Y', $user['date_added'] ?? time()) ?></td>
                                <td>
                                    <a href="<?= base_url('/admin/edit-user/' . $user['id']) ?>" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/delete-user/' . $user['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-user') ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">First Name *</label>
                        <input type="text" class="form-control" name="first_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Last Name *</label>
                        <input type="text" class="form-control" name="last_name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email *</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Password *</label>
                        <input type="password" class="form-control" name="password" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Role *</label>
                        <select class="form-select" name="role_id" required>
                            <option value="2">Student</option>
                            <option value="1">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_instructor" value="1" id="isInstructor">
                            <label class="form-check-label" for="isInstructor">
                                Make this user an instructor
                            </label>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Phone</label>
                        <input type="tel" class="form-control" name="phone">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add User</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
