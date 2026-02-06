<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Edit Course: <?= esc($course['title']) ?> (Admin Mode)</h4>
    <div>
        <a href="<?= base_url('/admin/courses') ?>" class="btn btn-outline-secondary me-2">
            <i class="fas fa-arrow-left"></i> Back to List
        </a>
        <a href="<?= base_url('/student/course-player/' . $course['id']) ?>" target="_blank" class="btn btn-outline-primary">
            <i class="fas fa-eye"></i> Preview
        </a>
    </div>
</div>

<div class="row">
    <!-- Course Info Form (Left) -->
    <div class="col-lg-12 mb-4">
        <div class="card">
            <div class="card-header bg-white">
                <ul class="nav nav-tabs card-header-tabs" id="editCourseTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active" id="curriculum-tab" data-bs-toggle="tab" data-bs-target="#curriculum" type="button">Curriculum</button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button">Course Settings</button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="editCourseTabContent">
                    
                    <!-- Curriculum Tab -->
                    <div class="tab-pane fade show active" id="curriculum" role="tabpanel">

                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h5 class="fw-bold">Curriculum</h5>
                            <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addSectionModal">
                                <i class="fas fa-plus"></i> Add Section
                            </button>
                        </div>
                        
                        <div class="accordion" id="curriculumAccordion">
                            <?php if (!empty($course['sections'])): ?>
                                <?php foreach ($course['sections'] as $index => $section): ?>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header">
                                            <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                    data-bs-toggle="collapse" data-bs-target="#section<?= $section['id'] ?>">
                                                <?= esc($section['title']) ?>
                                            </button>
                                        </h2>
                                        <div id="section<?= $section['id'] ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" 
                                             data-bs-parent="#curriculumAccordion">
                                            <div class="accordion-body">
                                                <ul class="list-group mb-3">
                                                    <?php if (!empty($section['lessons'])): ?>
                                                        <?php foreach ($section['lessons'] as $lesson): ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="fas fa-play-circle text-muted me-2"></i>
                                                                    <?= esc($lesson['title']) ?>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-secondary"><?= esc($lesson['lesson_type']) ?></span>
                                                                    <button class="btn btn-sm btn-outline-primary ms-2" onclick="editLesson(<?= $lesson['id'] ?>)"><i class="fas fa-edit"></i></button>
                                                                    <a href="<?= base_url('/admin/delete-lesson/' . $lesson['id']) ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Are you sure you want to delete this lesson?')"><i class="fas fa-trash"></i></a>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <li class="list-group-item text-muted text-center">No lessons in this section</li>
                                                    <?php endif; ?>

                                                    <!-- Quizzes -->
                                                    <?php if (!empty($section['quizzes'])): ?>
                                                        <?php foreach ($section['quizzes'] as $quiz): ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="fas fa-question-circle text-warning me-2"></i>
                                                                    <?= esc($quiz['title']) ?>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-warning text-dark">Quiz</span>
                                                                    <a href="<?= base_url('/admin/edit-quiz/' . $quiz['id']) ?>" class="btn btn-sm btn-outline-primary ms-2"><i class="fas fa-edit"></i></a>
                                                                    <a href="<?= base_url('/admin/delete-quiz/' . $quiz['id']) ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Delete quiz?')"><i class="fas fa-trash"></i></a>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>

                                                    <!-- Assignments -->
                                                    <?php if (!empty($section['assignments'])): ?>
                                                        <?php foreach ($section['assignments'] as $assignment): ?>
                                                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <i class="fas fa-clipboard-check text-info me-2"></i>
                                                                    <?= esc($assignment['title']) ?>
                                                                </div>
                                                                <div>
                                                                    <span class="badge bg-info text-dark">Assignment</span>
                                                                    <a href="<?= base_url('/admin/edit-assignment/' . $assignment['id']) ?>" class="btn btn-sm btn-outline-primary ms-2"><i class="fas fa-edit"></i></a>
                                                                    <a href="<?= base_url('/admin/delete-assignment/' . $assignment['id']) ?>" class="btn btn-sm btn-outline-danger ms-1" onclick="return confirm('Delete assignment?')"><i class="fas fa-trash"></i></a>
                                                                </div>
                                                            </li>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </ul>
                                                
                                                <button class="btn btn-sm btn-outline-primary" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#addLessonModal" 
                                                        onclick="setSectionId(<?= $section['id'] ?>)">
                                                    <i class="fas fa-plus"></i> Add Lesson
                                                </button>
                                                <button class="btn btn-sm btn-outline-warning ms-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#addQuizModal" 
                                                        onclick="setQuizSectionId(<?= $section['id'] ?>)">
                                                    <i class="fas fa-plus"></i> Add Quiz
                                                </button>
                                                <button class="btn btn-sm btn-outline-info ms-1" 
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#addAssignmentModal" 
                                                        onclick="setAssignmentSectionId(<?= $section['id'] ?>)">
                                                    <i class="fas fa-plus"></i> Add Assignment
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="text-center py-4 text-muted">
                                    No sections created yet. Click "Add Section" to start.
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <!-- Settings Tab -->
                    <div class="tab-pane fade" id="settings" role="tabpanel">
                        <form action="<?= base_url('/admin/update-course/' . $course['id']) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            
                            <div class="mb-3">
                                <label class="form-label">Course Title</label>
                                <input type="text" class="form-control" name="title" value="<?= esc($course['title']) ?>" required>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-select" name="category_id" required>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>" <?= $course['category_id'] == $category['id'] ? 'selected' : '' ?>>
                                                <?= esc($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Level</label>
                                    <select class="form-select" name="level" required>
                                        <option value="Beginner" <?= $course['level'] == 'Beginner' ? 'selected' : '' ?>>Beginner</option>
                                        <option value="Intermediate" <?= $course['level'] == 'Intermediate' ? 'selected' : '' ?>>Intermediate</option>
                                        <option value="Advanced" <?= $course['level'] == 'Advanced' ? 'selected' : '' ?>>Advanced</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label">Language</label>
                                <select class="form-select" name="language" required>
                                    <option value="English" <?= $course['language'] == 'English' ? 'selected' : '' ?>>English</option>
                                    <option value="Indonesian" <?= $course['language'] == 'Indonesian' ? 'selected' : '' ?>>Indonesian</option>
                                    <option value="Spanish" <?= $course['language'] == 'Spanish' ? 'selected' : '' ?>>Spanish</option>
                                </select>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Outcomes</label>
                                    <textarea class="form-control" name="outcomes" rows="4"><?= esc($course['outcomes']) ?></textarea>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Requirements</label>
                                    <textarea class="form-control" name="requirements" rows="4"><?= esc($course['requirements']) ?></textarea>
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3 form-check">
                                <input type="checkbox" class="form-check-input" id="is_free_course" name="is_free_course" value="1" <?= $course['is_free_course'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="is_free_course">Is Free Course?</label>
                            </div>

                            <div id="pricing_fields" style="<?= $course['is_free_course'] ? 'opacity: 0.5; pointer-events: none;' : '' ?>">
                                <div class="mb-3">
                                    <label class="form-label">Price (Rp)</label>
                                    <input type="number" class="form-control" name="price" value="<?= $course['price'] ?>">
                                </div>

                                <div class="mb-3 form-check">
                                    <input type="checkbox" class="form-check-input" id="discount_flag" name="discount_flag" value="1" <?= $course['discount_flag'] ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="discount_flag">Discount?</label>
                                </div>

                                <div class="mb-3" id="discount_field" style="<?= $course['discount_flag'] ? '' : 'display: none;' ?>">
                                    <label class="form-label">Discounted Price (Rp)</label>
                                    <input type="number" class="form-control" name="discounted_price" value="<?= $course['discounted_price'] ?>">
                                </div>
                            </div>

                            <hr>

                            <div class="mb-3">
                                <label class="form-label">Short Description</label>
                                <textarea class="form-control" name="short_description" rows="3"><?= esc($course['short_description']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="10"><?= esc($course['description']) ?></textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Thumbnail</label>
                                <input type="file" class="form-control" name="thumbnail">
                                <?php if ($course['thumbnail']): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('/uploads/thumbnails/' . $course['thumbnail']) ?>" alt="current thumb" width="100" class="rounded">
                                        <small class="text-muted">Current Thumbnail</small>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Certificate Template</label>
                                <select class="form-select" name="certificate_id">
                                    <option value="">No Certificate</option>
                                    <?php if (!empty($certificates)): ?>
                                        <?php foreach ($certificates as $cert): ?>
                                            <option value="<?= $cert['id'] ?>" <?= isset($course['certificate_id']) && $course['certificate_id'] == $cert['id'] ? 'selected' : '' ?>>
                                                <?= esc($cert['title']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <small class="text-muted">Select a certificate template to be awarded upon completion.</small>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Update Course Settings</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Section</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-section/' . $course['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Section Title</label>
                        <input type="text" class="form-control" name="title" required placeholder="e.g., Introduction">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Section</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Lesson Modal -->
<div class="modal fade" id="addLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Lesson</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-lesson/' . $course['id'] . '/0') ?>" method="POST" id="addLessonForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Lesson Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lesson Type</label>
                        <select class="form-select" name="lesson_type" id="lesson_type" required>
                            <option value="video">Video</option>
                            <option value="text">Text / Document</option>
                        </select>
                    </div>

                    <div id="video_fields">
                        <div class="mb-3">
                            <label class="form-label">Video Type</label>
                            <select class="form-select" name="video_type">
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="html5">HTML5 (MP4)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Video URL</label>
                            <input type="text" class="form-control" name="video_url" placeholder="https://youtube.com/...">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration (e.g., 05:30)</label>
                            <input type="text" class="form-control" name="duration">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Summary</label>
                        <textarea class="form-control" name="summary" rows="3"></textarea>
                    </div>

                    <!-- Video Progression (Drip) -->
                    <div class="mb-3">
                        <label class="form-label">Required Watch Percentage (%)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            <input type="number" class="form-control" name="video_progression" min="0" max="100" value="0">
                        </div>
                        <small class="text-muted">Percentage of video duration required to unlock next lesson (0-100).</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="attachment">
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_free" value="1" id="is_free_lesson">
                        <label class="form-check-label" for="is_free_lesson">Allow Free Preview?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Quiz Modal -->
<div class="modal fade" id="addQuizModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Quiz</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-quiz/' . $course['id'] . '/0') ?>" method="POST" id="addQuizForm">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Quiz Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Summary / Instructions</label>
                        <textarea class="form-control" name="summary" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Minutes, 0 for unlimited)</label>
                        <input type="number" class="form-control" name="duration" value="0">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Marks</label>
                        <input type="number" class="form-control" name="total_marks" value="100">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Quiz</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Assignment Modal -->
<div class="modal fade" id="addAssignmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Assignment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/add-assignment/' . $course['id'] . '/0') ?>" method="POST" id="addAssignmentForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Assignment Title</label>
                        <input type="text" class="form-control" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Deadline (Optional)</label>
                        <input type="datetime-local" class="form-control" name="deadline">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Attachment (Optional)</label>
                        <input type="file" class="form-control" name="attachment">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add Assignment</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Lesson Modal -->
<div class="modal fade" id="editLessonModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Lesson</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" id="editLessonForm" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <input type="hidden" name="lesson_id" id="edit_lesson_id">
                    
                    <div class="mb-3">
                        <label class="form-label">Lesson Title</label>
                        <input type="text" class="form-control" name="title" id="edit_title" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Lesson Type</label>
                        <select class="form-select" name="lesson_type" id="edit_lesson_type" required>
                            <option value="video">Video</option>
                            <option value="text">Text / Document</option>
                        </select>
                    </div>

                    <div id="edit_video_fields">
                        <div class="mb-3">
                            <label class="form-label">Video Type</label>
                            <select class="form-select" name="video_type" id="edit_video_type">
                                <option value="youtube">YouTube</option>
                                <option value="vimeo">Vimeo</option>
                                <option value="html5">HTML5 (MP4)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Video URL</label>
                            <input type="text" class="form-control" name="video_url" id="edit_video_url">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Duration</label>
                            <input type="text" class="form-control" name="duration" id="edit_duration">
                        </div>
                        
                        <!-- Video Preview Container -->
                        <div class="mb-3">
                            <label class="form-label">Video Preview</label>
                            <div id="video_preview_container" class="ratio ratio-16x9 bg-light d-flex align-items-center justify-content-center border rounded">
                                <span class="text-muted">Enter URL to preview</span>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Description / Summary</label>
                        <textarea class="form-control" name="summary" id="edit_summary" rows="3"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Required Watch Percentage (%)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                            <input type="number" class="form-control" name="video_progression" id="edit_video_progression" min="0" max="100">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Attachment</label>
                        <input type="file" class="form-control" name="attachment">
                        <div id="current_attachment_preview" class="mt-2" style="display:none;">
                            <small class="text-muted">Current File:</small>
                            <a href="#" target="_blank" id="attachment_link" class="btn btn-sm btn-outline-info">
                                <i class="fas fa-download"></i> View Current Attachment
                            </a>
                        </div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_free" value="1" id="edit_is_free">
                        <label class="form-check-label" for="edit_is_free">Allow Free Preview?</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- YouTube IFrame API -->
<script src="https://www.youtube.com/iframe_api"></script>
<!-- Vimeo Player API -->
<script src="https://player.vimeo.com/api/player.js"></script>

<script>
    function setSectionId(sectionId) {
        const form = document.getElementById('addLessonForm');
        // Update form action URL to include correct section ID
        const baseUrl = '<?= base_url('/admin/add-lesson/' . $course['id']) ?>';
        form.action = baseUrl + '/' + sectionId;
    }

    function setQuizSectionId(sectionId) {
        const form = document.getElementById('addQuizForm');
        const baseUrl = '<?= base_url('/admin/add-quiz/' . $course['id']) ?>';
        form.action = baseUrl + '/' + sectionId;
    }

    function setAssignmentSectionId(sectionId) {
        const form = document.getElementById('addAssignmentForm');
        const baseUrl = '<?= base_url('/admin/add-assignment/' . $course['id']) ?>';
        form.action = baseUrl + '/' + sectionId;
    }
    
    document.addEventListener('DOMContentLoaded', function() {
        // Toggle pricing fields in settings tab
        const isFreeCheckbox = document.getElementById('is_free_course');
        const pricingFields = document.getElementById('pricing_fields');
        
        if(isFreeCheckbox) {
            isFreeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    pricingFields.style.opacity = '0.5';
                    pricingFields.style.pointerEvents = 'none';
                } else {
                    pricingFields.style.opacity = '1';
                    pricingFields.style.pointerEvents = 'auto';
                }
            });
        }
        
        const discountCheckbox = document.getElementById('discount_flag');
        const discountField = document.getElementById('discount_field');
        
        if(discountCheckbox) {
            discountCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    discountField.style.display = 'block';
                } else {
                    discountField.style.display = 'none';
                }
            });
        }
        
        // Toggle lesson type fields
        const lessonTypeSelect = document.getElementById('lesson_type');
        const videoFields = document.getElementById('video_fields');
        
        if(lessonTypeSelect) {
            lessonTypeSelect.addEventListener('change', function() {
                if (this.value === 'video') {
                    videoFields.style.display = 'block';
                } else {
                    videoFields.style.display = 'none';
                }
            });
        }
    });


    function editLesson(lessonId) {
        const url = `<?= base_url('/admin/get-lesson/') ?>/${lessonId}`;
        console.log("Fetching lesson from:", url);

        // Fetch lesson data
        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if(data.error) {
                    Swal.fire('Error', data.error, 'error');
                    return;
                }
                
                console.log("Lesson Data:", data);

                // Populate Modal
                document.getElementById('edit_lesson_id').value = data.id || '';
                document.getElementById('edit_title').value = data.title || '';
                document.getElementById('edit_lesson_type').value = data.lesson_type || 'video';
                document.getElementById('edit_video_type').value = data.video_type || 'youtube';
                document.getElementById('edit_video_url').value = data.video_url || '';
                document.getElementById('edit_duration').value = data.duration || '';
                document.getElementById('edit_summary').value = data.summary || '';
                document.getElementById('edit_video_progression').value = data.video_progression || 0;
                document.getElementById('edit_is_free').checked = parseInt(data.is_free) === 1;

                const videoFields = document.getElementById('edit_video_fields');
                if (data.lesson_type === 'video') {
                    videoFields.style.display = 'block';
                    updateVideoPreview(data.video_url, data.video_type); // Initial Preview
                } else {
                    videoFields.style.display = 'none';
                }

                // Handle Attachment Preview
                const previewDiv = document.getElementById('current_attachment_preview');
                const link = document.getElementById('attachment_link');
                const embedContainer = document.getElementById('attachment_embed_container'); // Ensure this ID exists in HTML below

                if (data.attachment) {
                    previewDiv.style.display = 'block';
                    const fileUrl = `<?= base_url('/uploads/lesson_files/') ?>/${data.attachment}`;
                    link.href = fileUrl;
                    
                    // Call preview update
                    updateAttachmentPreview(data.attachment, fileUrl);
                } else {
                    previewDiv.style.display = 'none';
                    if(document.getElementById('attachment_embed_preview')) {
                         document.getElementById('attachment_embed_preview').innerHTML = '';
                    }
                }

                // Set Form Action
                document.getElementById('editLessonForm').action = `<?= base_url('/admin/update-lesson/') ?>/${data.id}`;

                // Show Modal
                new bootstrap.Modal(document.getElementById('editLessonModal')).show();
            })
            .catch(err => {
                console.error(err);
                Swal.fire('Error', 'Failed to fetch lesson data: ' + err.message, 'error');
            });
    }

    // Helper: Update Video Preview & Get Duration
    function updateVideoPreview(url, type) {
        const container = document.getElementById('video_preview_container');
        if (!url) {
            container.innerHTML = '<span class="text-muted">Enter URL to preview</span>';
            return;
        }

        let html = '';
        if (type === 'youtube') {
            // Extract ID
            let videoId = '';
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|\&v=)([^#\&\?]*).*/;
            const match = url.match(regExp);
            if (match && match[2].length == 11) {
                videoId = match[2];
                // Use a dedicated div for YouTube API
                html = `<div id="yt-player"></div>`;
                container.innerHTML = html;
                
                // Init YouTube Player
                new YT.Player('yt-player', {
                    height: '100%',
                    width: '100%',
                    videoId: videoId,
                    events: {
                        'onReady': onPlayerReady
                    }
                });
            } else {
                container.innerHTML = '<span class="text-danger">Invalid YouTube URL</span>';
            }
        } else if (type === 'vimeo') {
             // Extract ID (simple regex)
             const regExp = /vimeo.com\/(\d+)/;
             const match = url.match(regExp);
             if (match) {
                 html = `<iframe id="vimeo-player" src="https://player.vimeo.com/video/${match[1]}" allowfullscreen style="width:100%; height:100%; border:none;"></iframe>`;
                 container.innerHTML = html;
                 
                 // Init Vimeo Player
                 const iframe = document.querySelector('#vimeo-player');
                 const player = new Vimeo.Player(iframe);
                 player.getDuration().then(function(duration) {
                     updateDurationField(duration);
                 });
             } else {
                 container.innerHTML = '<span class="text-danger">Invalid Vimeo URL</span>';
             }
        } else if (type === 'html5') {
            const video = document.createElement('video');
            video.src = url;
            video.controls = true;
            video.className = 'w-100';
            video.onloadedmetadata = function() {
                updateDurationField(this.duration);
            };
            container.innerHTML = '';
            container.appendChild(video);
        }
    }

    function onPlayerReady(event) {
        // YouTube Player Ready
        const duration = event.target.getDuration();
        updateDurationField(duration);
    }

    function updateDurationField(seconds) {
        if (!seconds || seconds <= 0) return;
        
        // Format to HH:MM:SS
        const sec_num = parseInt(seconds, 10);
        let hours   = Math.floor(sec_num / 3600);
        let minutes = Math.floor((sec_num - (hours * 3600)) / 60);
        let secs    = sec_num - (hours * 3600) - (minutes * 60);

        if (hours   < 10) {hours   = "0"+hours;}
        if (minutes < 10) {minutes = "0"+minutes;}
        if (secs    < 10) {secs    = "0"+secs;}
        
        const timeString = hours + ':' + minutes + ':' + secs;
        document.getElementById('edit_duration').value = timeString;
    }

    // Helper: Update Attachment Preview

    // Helper: Update Attachment Preview
    function updateAttachmentPreview(filename, url) {
        // Create container if not exists (in case)
        let container = document.getElementById('attachment_embed_preview');
        if (!container) {
            const previewDiv = document.getElementById('current_attachment_preview');
            container = document.createElement('div');
            container.id = 'attachment_embed_preview';
            container.className = 'mt-3 border rounded p-2 bg-light text-center';
            previewDiv.appendChild(container);
        }

        const ext = filename.split('.').pop().toLowerCase();
        
        if (['jpg', 'jpeg', 'png', 'gif', 'webp'].includes(ext)) {
            container.innerHTML = `<img src="${url}" class="img-fluid" style="max-height: 300px;">`;
        } else if (ext === 'pdf') {
            container.innerHTML = `<iframe src="${url}" style="width:100%; height:400px; border:none;"></iframe>`;
        } else {
            container.innerHTML = '<small class="text-muted">Preview not available for this file type.</small>';
        }
    }

    // Listeners for Video Preview
    document.getElementById('edit_video_url')?.addEventListener('change', function() {
        updateVideoPreview(this.value, document.getElementById('edit_video_type').value);
    });
    document.getElementById('edit_video_url')?.addEventListener('input', function() { // Debounce could be added
        // Optional: Live preview on type
    });
    document.getElementById('edit_video_type')?.addEventListener('change', function() {
        updateVideoPreview(document.getElementById('edit_video_url').value, this.value);
    });

    // Bind change event for Edit Modal lesson type
    document.getElementById('edit_lesson_type')?.addEventListener('change', function() {
        const videoFields = document.getElementById('edit_video_fields');
        if (this.value === 'video') {
            videoFields.style.display = 'block';
        } else {
            videoFields.style.display = 'none';
        }
    });

</script>

<?= $this->endSection() ?>
