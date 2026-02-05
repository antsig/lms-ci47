<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="card">
    <div class="card-header bg-white">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">Manage Categories</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                <i class="fas fa-plus"></i> Add Category
            </button>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Thumbnail</th>
                        <th>Name</th>
                        <th>Parent Category</th>
                        <th>Slug</th>
                        <th>Icon</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($categories)): ?>
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td><?= $category['id'] ?></td>
                                <td>
                                    <?php if ($category['thumbnail']): ?>
                                        <img src="<?= base_url('/uploads/category_thumbnails/' . $category['thumbnail']) ?>" 
                                             alt="thumb" width="40" height="40" class="rounded">
                                    <?php else: ?>
                                        <span class="text-muted">No thumb</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?= esc($category['name']) ?></strong>
                                </td>
                                <td>
                                    <?php if ($category['parent'] > 0 && isset($category['parent_name'])): ?>
                                        <span class="badge bg-secondary"><?= esc($category['parent_name']) ?></span>
                                    <?php else: ?>
                                        <span class="badge bg-light text-dark">Root</span>
                                    <?php endif; ?>
                                </td>
                                <td><?= esc($category['slug']) ?></td>
                                <td>
                                    <i class="<?= $category['font_awesome_class'] ?>"></i> <?= $category['font_awesome_class'] ?>
                                </td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-outline-primary" 
                                       data-bs-toggle="modal" data-bs-target="#editCategoryModal<?= $category['id'] ?>">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= base_url('/admin/delete-category/' . $category['id']) ?>" 
                                       class="btn btn-sm btn-outline-danger"
                                       onclick="return confirm('Are you sure you want to delete this category?')">
                                        <i class="fas fa-trash"></i>
                                    </a>

                                    <!-- Edit Modal -->
                                    <div class="modal fade" id="editCategoryModal<?= $category['id'] ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Edit Category</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="<?= base_url('/admin/update-category/' . $category['id']) ?>" method="POST" enctype="multipart/form-data">
                                                    <?= csrf_field() ?>
                                                    <div class="modal-body">
                                                        <div class="mb-3">
                                                            <label class="form-label">Name</label>
                                                            <input type="text" class="form-control" name="name" value="<?= esc($category['name']) ?>" required>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Parent Category</label>
                                                            <select class="form-select" name="parent">
                                                                <option value="0">None (Root)</option>
                                                                <?php foreach ($parent_categories as $parent): ?>
                                                                    <?php if ($parent['id'] != $category['id']): ?>
                                                                        <option value="<?= $parent['id'] ?>" <?= $category['parent'] == $parent['id'] ? 'selected' : '' ?>>
                                                                            <?= esc($parent['name']) ?>
                                                                        </option>
                                                                    <?php endif; ?>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Font Awesome Class</label>
                                                            <input type="text" class="form-control" name="font_awesome_class" value="<?= esc($category['font_awesome_class']) ?>">
                                                        </div>
                                                        <div class="mb-3">
                                                            <label class="form-label">Thumbnail</label>
                                                            <input type="file" class="form-control" name="thumbnail">
                                                            <small class="text-muted">Leave blank to keep current thumbnail</small>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update Category</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center text-muted">No categories found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Category</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-category') ?>" method="POST" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name *</label>
                        <input type="text" class="form-control" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select class="form-select" name="parent">
                            <option value="0">None (Root)</option>
                            <?php foreach ($parent_categories as $parent): ?>
                                <option value="<?= $parent['id'] ?>"><?= esc($parent['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Font Awesome Class</label>
                        <input type="text" class="form-control" name="font_awesome_class" placeholder="fas fa-code">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Thumbnail</label>
                        <input type="file" class="form-control" name="thumbnail">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
