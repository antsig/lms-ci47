<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid py-4">

    <!-- Mobile Lock Overlay -->
    <style>
        .mobile-lock-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.98);
            z-index: 9999;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 2rem;
        }
        @media (max-width: 991.98px) {
            .mobile-lock-overlay {
                display: flex;
            }
            body { 
                overflow: hidden; 
            }
        }
        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 20px;
            height: calc(100vh - 40px);
            overflow-y: auto;
        }
    </style>

    <div class="mobile-lock-overlay">
        <i class="fas fa-desktop fa-4x text-primary mb-3"></i>
        <h2 class="fw-bold">Desktop Only</h2>
        <p class="lead text-muted">This learning environment is designed for desktop use to ensure the best experience.</p>
        <p>Please open this page on a laptop or desktop computer.</p>
        <a href="<?= base_url('/student/dashboard') ?>" class="btn btn-outline-primary mt-3">Back to Dashboard</a>
    </div>

    <div class="row">
        <!-- Sidebar - Curriculum -->
        <div class="col-lg-3 mb-4">
            <div class="mb-3">
                 <a href="<?= base_url('/student/my-courses') ?>" class="btn btn-outline-secondary btn-sm w-100">
                     <i class="fas fa-arrow-left me-1"></i> Back to My Courses
                 </a>
            </div>
            <div class="card h-100 sticky-sidebar">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold"><?= esc($course['title']) ?></h5>
                    <div class="progress mt-2" style="height: 5px;">
                        <div class="progress-bar" role="progressbar" style="width: <?= $course['progress'] ?? 0 ?>%"></div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-2">
                        <small class="text-muted"><?= $course['progress'] ?? 0 ?>% Complete</small>
                        <?php if (($course['progress'] ?? 0) >= 100): ?>
                            <a href="<?= base_url('/student/certificate/' . $course['id']) ?>" class="btn btn-sm btn-success" target="_blank">
                                <i class="fas fa-certificate"></i> Get Certificate
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="card-body p-0">
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
                                                    <?php
                                                    $isActive = ($current_type == 'lesson' && $lesson['id'] == $current_item['id']);
                                                    $isLessonCompleted = in_array('lesson_' . $lesson['id'], $completed_items ?? []);
                                                    ?>
                                                    <a href="<?= base_url('/student/course-player/' . $course['id'] . '/' . $lesson['id']) ?>" 
                                                       class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                        <div class="d-flex align-items-center">
                                                            <?php if ($isLessonCompleted): ?>
                                                                <i class="fas fa-check-circle me-2 text-success"></i>
                                                            <?php elseif ($lesson['lesson_type'] == 'video'): ?>
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
                                                        <?php
                                                        $isActive = ($current_type == 'quiz' && $quiz['id'] == $current_item['id']);
                                                        $isQuizCompleted = in_array('quiz_' . $quiz['id'], $completed_items ?? []);
                                                        ?>
                                                        <a href="<?= base_url('/student/course-player/' . $course['id'] . '/quiz/' . $quiz['id']) ?>" 
                                                           class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                            <div class="d-flex align-items-center">
                                                                <?php if ($isQuizCompleted): ?>
                                                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                                                <?php else: ?>
                                                                    <i class="fas fa-question-circle me-2 text-warning"></i>
                                                                <?php endif; ?>
                                                                <small><?= esc($quiz['title']) ?></small>
                                                                <span class="ms-auto small text-muted"><?= esc($quiz['duration']) ?>m</span>
                                                            </div>
                                                        </a>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>

                                                <!-- Assignments -->
                                                <?php if (!empty($section['assignments'])): ?>
                                                    <?php foreach ($section['assignments'] as $assignment): ?>
                                                        <?php
                                                        $isActive = ($current_type == 'assignment' && $assignment['id'] == $current_item['id']);
                                                        $isAssignmentCompleted = in_array('assignment_' . $assignment['id'], $completed_items ?? []);
                                                        ?>
                                                        <a href="<?= base_url('/student/course-player/' . $course['id'] . '/assignment/' . $assignment['id']) ?>" 
                                                           class="list-group-item list-group-item-action <?= $isActive ? 'active' : '' ?>">
                                                            <div class="d-flex align-items-center">
                                                                <?php if ($isAssignmentCompleted): ?>
                                                                    <i class="fas fa-check-circle me-2 text-success"></i>
                                                                <?php else: ?>
                                                                    <i class="fas fa-clipboard-check me-2 text-info"></i>
                                                                <?php endif; ?>
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
                            
                            <?php if (!empty($current_item['is_locked'])): ?>
                                <div class="alert alert-secondary text-center py-5">
                                    <i class="fas fa-lock fa-3x mb-3 text-muted"></i>
                                    <h4>Content Locked</h4>
                                    <p class="lead">This content will be available in <strong><?= $current_item['days_remaining'] ?> day(s)</strong>.</p>
                                    <p class="text-muted">Unlock Date: <?= $current_item['unlock_date'] ?></p>
                                </div>
                            <?php else: ?>
                                <!-- Video Player -->
                                <?php if ($current_item['lesson_type'] == 'video'): ?>
                                <div class="ratio ratio-16x9 bg-dark mb-4">
                                    <?php if ($current_item['video_type'] == 'youtube'): ?>
                                        <?php
                                        $videoUrl = $current_item['video_url'];
                                        // Auto-convert watch/short URLs to embed format
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                                            $videoUrl = 'https://www.youtube.com/embed/' . $match[1];
                                        }
                                        ?>
                                        if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $match)) {
                                            $videoUrl = 'https://www.youtube.com/embed/' . $match[1] . '?enablejsapi=1'; // Enable JS API
                                            $youtubeId = $match[1];
                                        }
                                        ?>
                                        <iframe id="yt-player-<?= $current_item['id'] ?>" src="<?= esc($videoUrl) ?>" allowfullscreen></iframe>
                                        <script>
                                            // Queue YouTube Init
                                            window.addEventListener('load', function() {
                                                if (typeof YT !== 'undefined' && YT.Player) {
                                                    new YT.Player('yt-player-<?= $current_item['id'] ?>', {
                                                        events: {
                                                            'onStateChange': function(event) {
                                                                if (event.data == YT.PlayerState.PLAYING) {
                                                                    // Start polling
                                                                    const player = event.target;
                                                                    const duration = player.getDuration();
                                                                    const timer = setInterval(() => {
                                                                        if (typeof checkProgress === 'function') {
                                                                            checkProgress(player.getCurrentTime(), duration);
                                                                        }
                                                                    }, 1000);
                                                                }
                                                            }
                                                        }
                                                    });
                                                }
                                            });
                                        </script>
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

                            <!-- Document/Attachment Viewer -->
                            <?php if ($current_item['attachment']): ?>
                                <div class="mb-4">
                                    <?php
                                    $ext = strtolower($current_item['attachment_type'] ?? pathinfo($current_item['attachment'], PATHINFO_EXTENSION));
                                    $fileUrl = base_url('/uploads/lesson_files/' . $current_item['attachment']);
                                    ?>

                                    <?php if ($ext == 'pdf'): ?>
                                        <div class="ratio ratio-16x9 border">
                                            <iframe src="<?= $fileUrl ?>" allowfullscreen></iframe>
                                        </div>
                                        <div class="mt-2 text-end">
                                            <a href="<?= $fileUrl ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                <i class="fas fa-external-link-alt"></i> Open in New Tab
                                            </a>
                                        </div>
                                    <?php elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])): ?>
                                        <div class="text-center">
                                            <img src="<?= $fileUrl ?>" class="img-fluid rounded shadow-sm" alt="Lesson Image">
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 bg-light rounded text-center border">
                                            <i class="fas fa-file-download fa-3x text-primary mb-3"></i>
                                            <h5>Download Course Material</h5>
                                            <p class="text-muted">This lesson contains a downloadable resource.</p>
                                            <a href="<?= $fileUrl ?>" class="btn btn-primary" download>
                                                <i class="fas fa-download"></i> Download <?= strtoupper($ext) ?> File
                                            </a>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>
                            
                            <!-- Lesson Summary (Moved to bottom) -->
                            <?php if ($current_item['summary']): ?>
                                <div class="mb-4">
                                    <h5 class="fw-bold">Lesson Summary</h5>
                                    <div class="p-3 bg-light rounded">
                                        <?= nl2br(esc($current_item['summary'])) ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <!-- Mark as Complete Button -->
                            <div class="d-flex justify-content-end mb-4">
                                <?php
                                $itemKey = 'lesson_' . $current_item['id'];
                                $isCompleted = in_array($itemKey, $completed_items ?? []);
                                ?>
                                
                                <?php if ($isCompleted): ?>
                                    <button class="btn btn-success" disabled>
                                        <i class="fas fa-check me-1"></i> Completed
                                    </button>
                                <?php else: ?>
                                    <button onclick="markAsComplete(<?= $course['id'] ?>, <?= $current_item['id'] ?>)" 
                                            class="btn btn-outline-success" id="markCompleteBtn">
                                        <i class="fas fa-check-circle me-1"></i> Mark as Complete
                                    </button>
                                <?php endif; ?>
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
                                                
                                                <?php if (isset($question['type']) && $question['type'] == 'essay'): ?>
                                                    <div class="mt-3">
                                                        <textarea class="form-control" name="answers[<?= $question['id'] ?>]" rows="4" placeholder="Type your answer here..." required></textarea>
                                                    </div>
                                                <?php else: ?>
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
                                                <?php endif; ?>
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

<?= $this->section('scripts') ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<!-- Video Players APIs -->
<script src="https://www.youtube.com/iframe_api"></script>
<script src="https://player.vimeo.com/api/player.js"></script>

<script>
    // Configuration
    const IS_LESSON = <?= ($current_type == 'lesson' && !empty($current_item)) ? 'true' : 'false' ?>;
    const LESSON_ID = <?= ($current_type == 'lesson' && !empty($current_item)) ? $current_item['id'] : 'null' ?>;
    const COURSE_ID = <?= $course['id'] ?>;
    const VIDEO_TYPE = '<?= ($current_type == 'lesson' && !empty($current_item) && $current_item['lesson_type'] == 'video') ? $current_item['video_type'] : '' ?>';
    const VIDEO_PROGRESSION = <?= ($current_type == 'lesson' && !empty($current_item)) ? ($current_item['video_progression'] ?? 0) : 0 ?>;
    const HAS_ATTACHMENT = <?= ($current_type == 'lesson' && !empty($current_item) && !empty($current_item['attachment'])) ? 'true' : 'false' ?>;
    const LESSON_TYPE = '<?= ($current_type == 'lesson' && !empty($current_item)) ? $current_item['lesson_type'] : '' ?>';
    
    // State
    let isCompleted = <?= $current_type == 'lesson' && in_array('lesson_' . $current_item['id'], $completed_items ?? []) ? 'true' : 'false' ?>;
    let videoDuration = 0;
    
    document.addEventListener('DOMContentLoaded', function() {
        if (!IS_LESSON || isCompleted) return;

        // 1. Document/Text Lesson Auto-Complete logic
        // If it's a text lesson (no video) or just an attachment, mark complete on view
        if (LESSON_TYPE !== 'video' || (LESSON_TYPE === 'video' && VIDEO_TYPE === '')) {
             console.log("Document/Text lesson detected. Marking complete.");
             // Optional: Add small delay or scroll check
             setTimeout(triggerCompletion, 2000); 
        }

        // 2. Video Tracking
        if (LESSON_TYPE === 'video' && VIDEO_TYPE !== '') {
            initVideoTracking();
        }
    });

    function initVideoTracking() {
        if (VIDEO_TYPE === 'youtube') {
            // YouTube is initialized via onYouTubeIframeAPIReady global function
        } else if (VIDEO_TYPE === 'vimeo') {
            const iframe = document.querySelector('iframe[src*="vimeo"]');
            if (iframe) {
                const player = new Vimeo.Player(iframe);
                player.on('timeupdate', function(data) {
                    checkProgress(data.seconds, data.duration);
                });
            }
        } else if (VIDEO_TYPE === 'html5') {
            const video = document.querySelector('video');
            if (video) {
                video.addEventListener('timeupdate', function() {
                    checkProgress(this.currentTime, this.duration);
                });
            }
        }
    }

    // Global YouTube Callback
    window.onYouTubeIframeAPIReady = function() {
        if (VIDEO_TYPE === 'youtube') {
             const iframe = document.querySelector('iframe[src*="youtube"]');
             // We need to replace iframe with API control? Or uses existing?
             // Existing iframe usually needs enablejsapi=1. 
             // To simplify, we rely on the iframe having enablejsapi=1 appended in view.
             
             // NOTE: Since the view uses simple iframe, we might need to recreate it or ensure ID.
             // But for now, let's assume standard embedding won't easily support tracking without fully replacing with JS Player.
             // ALTERNATIVE: Use the ID from the URL we parsed in PHP.
        }
    }
    
    // Fix for YouTube Tracking: We need to use YT.Player on the ID. 
    // Since the view renders an iframe without ID, let's add an ID to it via JS if possible or assume simple HTML5/Vimeo first.
    // Actually, let's try to attach to the iframe if it exists.
    
    function checkProgress(currentTime, duration) {
        if (isCompleted || duration === 0) return;
        
        const percentage = (currentTime / duration) * 100;
        
        // Debug
        // console.log(`Progress: ${percentage.toFixed(2)}% / Required: ${VIDEO_PROGRESSION}%`);

        if (percentage >= VIDEO_PROGRESSION) {
            triggerCompletion();
        }
    }

    function triggerCompletion() {
        if (isCompleted) return;
        isCompleted = true; // Prevent double trigger
        
        console.log("Triggering auto-complete...");
        
        // Call the AJAX endpoint
        fetch('<?= base_url('/student/mark-lesson-complete') ?>', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                course_id: COURSE_ID,
                lesson_id: LESSON_ID
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // UI Feedback
                const btn = document.getElementById('markCompleteBtn');
                if (btn) {
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Completed';
                    btn.disabled = true;
                }
                
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Lesson Completed!',
                    showConfirmButton: false,
                    timer: 3000
                });

                // Update Progress Bar
                const progressBar = document.querySelector('.progress-bar');
                 if (progressBar) {
                    progressBar.style.width = data.progress + '%';
                    progressBar.setAttribute('aria-valuenow', data.progress);
                }
            }
        });
    }


    function markAsComplete(courseId, itemId) {
        const btn = document.getElementById('markCompleteBtn');
        const originalText = btn.innerHTML;
        
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';

        fetch(`<?= base_url('/student/mark-complete') ?>/${courseId}/${itemId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Completed!',
                        text: 'Lesson marked as complete.',
                        showConfirmButton: false,
                        timer: 1500,
                        toast: true,
                        position: 'top-end'
                    });

                    // Update Progress Bar
                    const progressBar = document.querySelector('.progress-bar');
                    const progressText = document.querySelector('.card-header small.text-muted');
                    
                    if (progressBar) {
                        progressBar.style.width = data.progress + '%';
                        progressBar.setAttribute('aria-valuenow', data.progress);
                    }
                    if (progressText) {
                        progressText.textContent = data.progress + '% Complete';
                    }

                    // Change button state
                    btn.classList.remove('btn-outline-success');
                    btn.classList.add('btn-success');
                    btn.innerHTML = '<i class="fas fa-check me-1"></i> Completed';
                    
                    // Show certificate button if 100%
                    if (data.progress >= 100) {
                        location.reload(); // Reload to show certificate button cleanly
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
    }
</script>
<?= $this->endSection() ?>
