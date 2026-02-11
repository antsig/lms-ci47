<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0 fw-bold">About Page Settings</h5>
    </div>
    <div class="card-body">
        <!-- Tabs Navigation -->
        <ul class="nav nav-tabs mb-4" id="aboutTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="general-tab" data-bs-toggle="tab" data-bs-target="#general" type="button" role="tab" aria-controls="general" aria-selected="true">General Settings</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="features-tab" data-bs-toggle="tab" data-bs-target="#features" type="button" role="tab" aria-controls="features" aria-selected="false">Website Features</button>
            </li>
        </ul>

        <div class="tab-content" id="aboutTabsContent">
            <!-- General Settings Tab -->
            <div class="tab-pane fade show active" id="general" role="tabpanel" aria-labelledby="general-tab">
                <form action="<?= base_url('/admin/settings/update-about') ?>" method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">Page Title</label>
                        <input type="text" class="form-control" name="settings[about_us_title]" value="<?= esc($settings['about_us_title'] ?? 'About Our Learning Platform') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Page Tagline (Lead Text)</label>
                        <input type="text" class="form-control" name="settings[about_us_tagline]" value="<?= esc($settings['about_us_tagline'] ?? 'Discover our mission, vision, and the team dedicated to empowering learners everywhere.') ?>">
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Main Content</label>
                        <textarea class="form-control" name="settings[about_us_content]" rows="6"><?= esc($settings['about_us_content'] ?? 'Discover our mission, vision, and the team dedicated to empowering learners everywhere.') ?></textarea>
                        <small class="text-muted">You can use basic HTML tags.</small>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input type="hidden" name="settings[about_us_show_team]" value="0">
                        <input class="form-check-input" type="checkbox" id="showTeam" name="settings[about_us_show_team]" value="1" <?= ($settings['about_us_show_team'] ?? '1') == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="showTeam">Show "Meet the Team" Section</label>
                    </div>

                    <div class="mb-3 form-check form-switch">
                        <input type="hidden" name="settings[about_us_show_features]" value="0">
                        <input class="form-check-input" type="checkbox" id="showFeatures" name="settings[about_us_show_features]" value="1" <?= ($settings['about_us_show_features'] ?? '1') == '1' ? 'checked' : '' ?>>
                        <label class="form-check-label fw-bold" for="showFeatures">Show "What We Offer" Section</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Representative Image</label>
                        <div class="input-group">
                            <input type="file" class="form-control" name="about_us_image" accept="image/*">
                        </div>
                        <small class="text-muted">Recommended size: 800x600px</small>
                        <?php if (!empty($settings['about_us_image'])): ?>
                            <div class="mt-2">
                                <label class="form-label d-block">Current Image:</label>
                                <img src="<?= base_url('uploads/system/' . $settings['about_us_image']) ?>" alt="About Image" class="img-thumbnail" style="max-height: 200px;">
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="text-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>

            <!-- Website Features Tab -->
            <div class="tab-pane fade" id="features" role="tabpanel" aria-labelledby="features-tab">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="mb-0 fw-bold">Manage Features</h5>
                    <a href="<?= base_url('/admin/features/create') ?>" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> Add Feature
                    </a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Icon</th>
                                <th>Title</th>
                                <th>Order</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($features)): ?>
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">No features added yet.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($features as $feature): ?>
                                    <tr>
                                        <td class="text-center" style="width: 60px;">
                                            <i class="<?= esc($feature['icon']) ?> fa-lg text-primary"></i>
                                        </td>
                                        <td>
                                            <div class="fw-bold"><?= esc($feature['title']) ?></div>
                                            <small class="text-muted"><?= character_limiter(esc($feature['description']), 50) ?></small>
                                        </td>
                                        <td><?= esc($feature['display_order']) ?></td>
                                        <td class="text-end">
                                            <a href="<?= base_url('/admin/features/edit/' . $feature['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="<?= base_url('/admin/features/delete/' . $feature['id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure?');">
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
    </div>
</div>

<?= $this->endSection() ?>
