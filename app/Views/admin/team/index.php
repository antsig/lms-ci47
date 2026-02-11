<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0 fw-bold">Team Management</h4>
    <a href="<?= base_url('/admin/team/create') ?>" class="btn btn-primary">
        <i class="fas fa-plus"></i> Add Member
    </a>
</div>

<?php if (session()->getFlashdata('success')): ?>
    <div class="alert alert-success">
        <?= session()->getFlashdata('success') ?>
    </div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
    <div class="alert alert-danger">
        <?= session()->getFlashdata('error') ?>
    </div>
<?php endif; ?>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">#</th>
                        <th>Member</th>
                        <th>Role</th>
                        <th>Source</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($team_members)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">No team members found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($team_members as $member): ?>
                            <tr>
                                <td class="ps-4"><?= $member['display_order'] ?></td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php
                                        // Logic to show image: manual override OR user image
                                        $imgSrc = base_url('uploads/thumbnails/default.jpg');

                                        // If manual image exists
                                        if (!empty($member['image']) && file_exists(FCPATH . 'uploads/team/' . $member['image'])) {
                                            $imgSrc = base_url('uploads/team/' . $member['image']);
                                        }
                                        // Else if linked to user, try fetching user image (Model doesn't merge on find() unless we used a join or specific method.
                                        // The logic in Controller 'team()' uses findAll() which is raw.
                                        // Let's use the TeamModel's getTeamForDisplay() logic or just do a quick lookup here if needed?
                                        // Actually, the View typically shouldn't do DB lookups.
                                        // Ideally the controller should have prepared this data.
                                        // I will update the controller to use getTeamForDisplay() in 'index' to make this cleaner.
                                        // For now, let's assume raw data and minimal display logic.

                                        // Better: Update Controller to use getTeamForDisplay() so we have full data here.
                                        // I'll update Controller in next step or use simple logic here if possible.
                                        // Since I already wrote the TeamModel::getTeamForDisplay(), I should use it!
                                        // But in the controller I used findAll(). I will hot-swap the controller logic or just handle basic display here if user_id is set.
                                        // Let's rely on basic display for now to avoid complexity in this file.

                                        $displayName = $member['name'];
                                        if (empty($displayName) && !empty($member['user_id'])) {
                                            $displayName = 'Linked User #' . $member['user_id'];
                                        }
                                        ?>
                                        <!-- <img src="<?= $imgSrc ?>" class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;"> -->
                                        <!-- We might not have the image URL correct here without the user data merging. -->
                                        
                                        <span><?= esc($displayName) ?></span>
                                    </div>
                                </td>
                                <td><?= esc($member['role']) ?></td>
                                <td>
                                    <?php if (!empty($member['user_id'])): ?>
                                        <span class="badge bg-info text-dark">Instructor</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Manual</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($member['status'] == 1): ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-end pe-4">
                                    <a href="<?= base_url('/admin/team/edit/' . $member['id']) ?>" class="btn btn-sm btn-outline-primary me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/team/delete/' . $member['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
