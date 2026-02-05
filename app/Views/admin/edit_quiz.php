<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold">Edit Quiz: <?= esc($quiz['title']) ?> (Admin Mode)</h4>
    <a href="<?= base_url('/admin/edit-course/' . $course['id']) ?>" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left"></i> Back to Course
    </a>
</div>

<div class="row">
    <!-- Quiz Settings -->
    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 fw-bold">Settings</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('/admin/update-quiz/' . $quiz['id']) ?>" method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Title</label>
                        <input type="text" class="form-control" name="title" value="<?= esc($quiz['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Summary</label>
                        <textarea class="form-control" name="summary" rows="3"><?= esc($quiz['summary']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Duration (Minutes)</label>
                        <input type="number" class="form-control" name="duration" value="<?= esc($quiz['duration']) ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Total Marks</label>
                        <input type="number" class="form-control" name="total_marks" value="<?= esc($quiz['total_marks']) ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Settings</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Questions Manager -->
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">Questions</h5>
                <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addQuestionModal">
                    <i class="fas fa-plus"></i> Add Question
                </button>
            </div>
            <div class="card-body">
                <?php if (!empty($questions)): ?>
                    <div class="accordion" id="questionsAccordion">
                        <?php foreach ($questions as $index => $question): ?>
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#q<?= $question['id'] ?>">
                                        <?= $index + 1 ?>. <?= esc($question['title']) ?>
                                    </button>
                                </h2>
                                <div id="q<?= $question['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#questionsAccordion">
                                    <div class="accordion-body">
                                        <p class="text-muted small mb-2">Type: <?= esc($question['type']) ?></p>
                                        
                                        <?php
                                        $options = json_decode($question['options'] ?? '[]', true);
                                        $correct = json_decode($question['correct_answers'] ?? '[]', true);
                                        ?>
                                        
                                        <ul class="list-group list-group-flush mb-3">
                                            <?php foreach ($options as $i => $opt): ?>
                                                <li class="list-group-item <?= in_array($i + 1, $correct) ? 'bg-light text-success fw-bold' : '' ?>">
                                                    <?= $i + 1 ?>. <?= esc($opt) ?>
                                                    <?php if (in_array($i + 1, $correct)): ?>
                                                        <i class="fas fa-check float-end"></i>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; ?>
                                        </ul>

                                        <a href="<?= base_url('/admin/quiz/delete-question/' . $question['id']) ?>" 
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('Delete this question?')">
                                            <i class="fas fa-trash"></i> Delete Question
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        No questions yet.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Question Modal -->
<div class="modal fade" id="addQuestionModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Question</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('/admin/quiz/add-question/' . $quiz['id']) ?>" method="POST">
                <?= csrf_field() ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Question Title</label>
                        <textarea class="form-control" name="title" rows="2" required></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Question Type</label>
                        <select class="form-select" name="type" id="questionType">
                            <option value="multiple_choice">Multiple Choice</option>
                            <!-- <option value="true_false">True / False</option> -->
                        </select>
                    </div>

                    <div id="optionsContainer">
                        <label class="form-label">Options</label>
                        <?php for ($i = 1; $i <= 4; $i++): ?>
                            <div class="input-group mb-2">
                                <div class="input-group-text">
                                    <input class="form-check-input mt-0" type="checkbox" name="correct_answers[]" value="<?= $i ?>">
                                </div>
                                <input type="text" class="form-control" name="options[]" placeholder="Option <?= $i ?>" required>
                            </div>
                        <?php endfor; ?>
                        <small class="text-muted">Check the box next to the correct answer(s).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Question</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
