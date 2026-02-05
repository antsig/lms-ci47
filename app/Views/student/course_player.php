<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">
    <div class="row">
        <!-- Sidebar - Curriculum -->
        <div class="col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><?= esc($course['title']) ?></h5>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                    </div>
                    <small class="text-muted">0% Complete</small>
                </div>
                <div class="card-body p-0" style="max-height: 80vh; overflow-y: auto;">
                    <div class="accordion accordion-flush" id="courseCurriculum">
                        <?php if (!empty($course['sections'])): ?>
                            <?php foreach ($course['sections'] as $index => $section): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button <?= $index > 0 ? 'collapsed' : '' ?>" type="button" 
                                                data-bs-toggle="collapse" data-bs-target="#section<?= $section['id'] ?>">
                                            <small class="fw-bold"><?= esc($section['title']) ?></small>
                                        </button>
                                    </h2>
                                    <div id="section<?= $section['id'] ?>" class="accordion-collapse collapse <?= $index == 0 ? 'show' : '' ?>" 
                                         data-bs-parent="#courseCurriculum">
                                        <div class="accordion-body p-0">
                                            <ul class="list-group list-group-flush">
                                                <!-- Lessons -->
                                                <?php foreach ($section['lessons'] as $lesson): ?>
                                                    <?php $isActive = ($current_type == 'lesson' && $lesson['id'] == $current_item['id']); ?>
                                                    <a href="<?= base_url('/student/course-player/' . $course['id'] . '/' . $lesson['id']) ?>" 
                                                       class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($lesson['lesson_type'] == 'video'): ?>
                                                                <i class="fas fa-play-circle me-2"></i>
                                                            <?php else: ?>
                                                                <i class="fas fa-file-alt me-2"></i>
                                                            <?php endif; ?>
                                                            <small><?= esc($lesson['title']) ?></small>
                                                            <?php if ($lesson['duration']): ?>
                                                                <span class="ms-auto small text-muted"><?= esc($lesson['duration']) ?></span>
                                                            <?php endif; ?>
                                                        </div>
                                                    </a>
                                                <?php endforeach; ?>

                                                <!-- Quizzes -->
                                                <?php if (!empty($section['quizzes'])): ?>
                                                    <?php foreach ($section['quizzes'] as $quiz): ?>
                                                        <?php $isActive = ($current_type == 'quiz' && $quiz['id'] == $current_item['id']); ?>
                                                        <a href="<?= base_url('/student/course-player/' . $course['id'] . '/quiz/' . $quiz['id']) ?>" 
                                                           class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-question-circle me-2 text-warning"></i>
                                                                <small><?= esc($quiz['title']) ?></small>
                                                                <span class="ms-auto small text-muted"><?= esc($quiz['duration']) ?>m</span>
                                                            </div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <!-- Assignments -->
                                                <?php if (!empty($section['assignments'])): ?>
                                                    <?php foreach ($section['assignments'] as $assignment): ?>
                                                        <?php $isActive = ($current_type == 'assignment' && $assignment['id'] == $current_item['id']); ?>
                                                        <a href="<?= base_url('/student/course-player/' . $course['id'] . '/assignment/' . $assignment['id']) ?>" 
                                                           class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-clipboard-check me-2 text-info"></i>
                                                                <small><?= esc($assignment['title']) ?></small>
                                                            </div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <?php if ($current_item): ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h3 class="fw-bold mb-3"><?= esc($current_item['title']) ?></h3>
                        
                        <!-- LESSON VIEW -->
                        <?php if ($current_type == 'lesson'): ?>
                            <?php if ($current_item['lesson_type'] == 'video'): ?>
                                <div class="ratio ratio-16x9 bg-dark mb-4">
                                    <?php if ($current_item['video_type'] == 'youtube'): ?>
                                        <iframe src="<?= esc($current_item['video_url']) ?>" allowfullscreen></iframe>
                                    <?php elseif ($current_item['video_type'] == 'vimeo'): ?>
                                        <iframe src="<?= esc($current_item['video_url']) ?>" allowfullscreen></iframe>
                                    <?php elseif ($current_item['video_type'] == 'html5'): ?>
                                        <video controls>
                                            <source src="<?= esc($current_item['video_url']) ?>" type="video/mp4">
                                            Your browser does not support the video tag.
                                        </video>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($current_item['summary']): ?>
                                <div class="mb-4">
                                    <h5 class="fw-bold">Lesson Summary</h5>
                                    <div class="p-3 bg-light rounded">
                                        <?= nl2br(esc($current_item['summary'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($current_item['attachment']): ?>
                                <div class="mb-4">
                                    <h5 class="fw-bold">Attachments</h5>
                                    <a href="<?= base_url('/uploads/attachments/' . $current_item['attachment']) ?>" class="btn btn-outline-primary" download>
                                        <i class="fas fa-paperclip"></i> Download Resource
                                    </a>
                                </div>
                            <?php endif; ?>

                        <!-- QUIZ VIEW -->
                        <?php elseif ($current_type == 'quiz'): ?>
                            <div class="alert alert-warning">
                                <i class="fas fa-info-circle"></i> This is a timed quiz. Duration: <strong><?= $current_item['duration'] > 0 ? $current_item['duration'] . ' Minutes' : 'Unlimited' ?></strong>.
                            </div>
                            
                            <?php if (!empty($current_item['summary'])): ?>
                                <div class="mb-4">
                                    <h6>Instructions:</h6>
                                    <p><?= nl2br(esc($current_item['summary'])) ?></p>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <form action="<?= base_url('/student/quiz/submit/' . $current_item['id']) ?>" method="POST">
                                <?php if (!empty($current_item['questions'])): ?>
                                    <?php foreach ($current_item['questions'] as $qIndex => $question): ?>
                                        <div class="card mb-3 border-0 shadow-sm">
                                            <div class="card-body">
                                                <h5 class="card-title"><?= $qIndex + 1 ?>. <?= esc($question['title']) ?></h5>
                                                
                                                <?php
                                                $options = json_decode($question['options'], true);
                                                ?>
                                                
                                                <div class="mt-3">
                                                    <?php foreach ($options as $oIndex => $option): ?>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio" 
                                                                   name="answers[<?= $question['id'] ?>]" 
                                                                   id="q<?= $question['id'] ?>o<?= $oIndex ?>" 
                                                                   value="<?= $oIndex + 1 ?>">
                                                            <label class="form-check-label" for="q<?= $question['id'] ?>o<?= $oIndex ?>">
                                                                <?= esc($option) ?>
                                                            </label>
                                                        </div>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    
                                    <button type="submit" class="btn btn-primary btn-lg mt-3" onclick="return confirm('Submit Quiz?')">
                                        Submit Quiz
                                    </button>
                                <?php else: ?>
                                    <p class="text-muted">No questions available for this quiz.</p>
                                <?php endif; ?>
                            </form>

                        <!-- ASSIGNMENT VIEW -->
                        <?php elseif ($current_type == 'assignment'): ?>
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> Please review the instructions and upload your work.
                            </div>

                            <div class="mb-4">
                                <h5 class="fw-bold">Instructions</h5>
                                <div class="p-3 bg-light rounded">
                                    <?= nl2br(esc($current_item['description'])) ?>
                                </div>
                            </div>

                            <?php if ($current_item['attachment_url']): ?>
                                <div class="mb-4">
                                    <h5 class="fw-bold">Reference Material</h5>
                                    <a href="<?= base_url('/uploads/assignment_files/' . $current_item['attachment_url']) ?>" class="btn btn-outline-info" download>
                                        <i class="fas fa-download"></i> Download Attachment
                                    </a>
                                </div>
                            <?php endif; ?>

                            <hr>

                            <div class="card">
                                <div class="card-header bg-white fw-bold">Submit Assignment</div>
                                <div class="card-body">
                                    <form action="<?= base_url('/student/assignment/submit/' . $current_item['id']) ?>" method="POST" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label class="form-label">Upload File</label>
                                            <input type="file" class="form-control" name="submission_file" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Note (Optional)</label>
                                            <textarea class="form-control" name="note" rows="3"></textarea>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Assignment</button>
                                    </form>
                                </div>
                            </div>

                        <?php endif; ?>
                    </div>
                </div>
            <?php else: ?>
                <div class="alert alert-info">
                    Select an item from the curriculum to start learning.
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
