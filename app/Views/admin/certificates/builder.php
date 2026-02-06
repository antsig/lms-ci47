<?= $this->extend('layouts/dashboard') ?>

<?= $this->section('sidebar') ?>
    <?= $this->include('admin/sidebar') ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<style>
    #certificate-canvas-container {
        width: 100%;
        overflow: auto;
        background: #f0f0f0;
        padding: 20px;
        border: 1px solid #ccc;
    }
    #certificate-canvas {
        position: relative;
        width: 800px; /* Default A4 Landscape approx width in px for screen */
        height: 600px;
        background-color: white;
        background-size: cover;
        background-position: center;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: 0 auto;
    }
    .draggable-item {
        position: absolute;
        cursor: move;
        border: 1px dashed #007bff;
        padding: 5px 10px;
        background: rgba(255, 255, 255, 0.7);
        font-family: 'Arial', sans-serif;
        white-space: nowrap;
    }
    .draggable-item:hover {
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid #0056b3;
    }
    .draggable-item.selected {
        border: 2px solid #e74a3b;
        z-index: 100 !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-gray-800"><?= esc($title) ?></h1>
</div>

<form action="<?= isset($certificate) ? base_url('admin/certificates/update/' . $certificate['id']) : base_url('admin/certificates/store') ?>" 
      method="POST" enctype="multipart/form-data" id="builderForm">
    <?= csrf_field() ?>
    
    <div class="row">
        <!-- Sidebar Controls -->
        <div class="col-md-3">
            <div class="card mb-3">
                <div class="card-header fw-bold">1. Settings</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Template Title</label>
                        <input type="text" class="form-control" name="title" value="<?= isset($certificate) ? esc($certificate['title']) : '' ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Background Image</label>
                        <input type="file" class="form-control" name="background_image" id="bgInput" accept="image/*">
                        <small class="text-muted">Upload an empty certificate image.</small>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header fw-bold">2. Elements</div>
                <div class="card-body">
                    <p class="small text-muted">Click to add placeholders:</p>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{student_name}', 'Student Name')">
                            <i class="fas fa-user"></i> Student Name
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{course_name}', 'Course Name')">
                            <i class="fas fa-book"></i> Course Name
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{completion_date}', 'Date')">
                            <i class="fas fa-calendar"></i> Date
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{certificate_code}', 'Code')">
                            <i class="fas fa-barcode"></i> Certificate Code
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{instructor_name}', 'Instructor Name')">
                            <i class="fas fa-chalkboard-teacher"></i> Instructor Name
                        </button>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="addElement('{instructor_signature}', 'Signature')">
                            <i class="fas fa-signature"></i> Signature
                        </button>
                         <button type="button" class="btn btn-outline-secondary btn-sm" onclick="addElement('text', 'New Text')">
                            <i class="fas fa-font"></i> Add Custom Text
                        </button>
                    </div>

                    <hr>
                    
                    <div id="itemProperties" style="display:none;">
                        <h6 class="fw-bold">Item Style</h6>
                        
                        <!-- Text Content Edit (Only for custom text) -->
                        <div class="mb-2" id="textContentGroup" style="display:none;">
                            <label class="small">Text Content</label>
                            <textarea class="form-control form-control-sm" id="pText" rows="3" onkeyup="updateSelected()"></textarea>
                            <div class="d-flex gap-1 mt-1">
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="setTextAlign('left')"><i class="fas fa-align-left"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="setTextAlign('center')"><i class="fas fa-align-center"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="setTextAlign('right')"><i class="fas fa-align-right"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="setTextAlign('justify')"><i class="fas fa-align-justify"></i></button>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label class="small">Line Height</label>
                            <input type="number" class="form-control form-control-sm" id="pLineHeight" value="1.2" step="0.1" onchange="updateSelected()">
                        </div>

                        <div class="mb-2">
                             <label class="small">Font Family</label>
                             <select class="form-select form-select-sm" id="pFontFamily" onchange="updateSelected()">
                                 <option value="'Times New Roman', serif">Times New Roman</option>
                                 <option value="Arial, sans-serif">Arial</option>
                                 <option value="'Courier New', monospace">Courier New</option>
                                 <option value="Georgia, serif">Georgia</option>
                                 <option value="Verdana, sans-serif">Verdana</option>
                                 <option value="Tahoma, sans-serif">Tahoma</option>
                                 <option value="'Trebuchet MS', sans-serif">Trebuchet MS</option>
                             </select>
                        </div>
                        
                        <div class="mb-2">
                            <label class="small">Font Size (px)</label>
                            <input type="number" class="form-control form-control-sm" id="pFontSize" value="16" onchange="updateSelected()">
                        </div>
                        <div class="mb-2">
                            <label class="small">Color</label>
                            <input type="color" class="form-control form-control-sm" id="pColor" value="#000000" onchange="updateSelected()">
                        </div>
                        <div class="mb-2">
                            <label class="small">Font Weight</label>
                            <select class="form-select form-select-sm" id="pFontWeight" onchange="updateSelected()">
                                <option value="normal">Normal</option>
                                <option value="bold">Bold</option>
                            </select>
                        </div>
                        <button type="button" class="btn btn-danger btn-sm w-100" onclick="deleteSelected()">Remove Item</button>
                    </div>

                </div>
            </div>

            <button type="submit" class="btn btn-success w-100 py-2">
                <i class="fas fa-save"></i> Save Template
            </button>
        </div>

        <!-- Canvas Area -->
        <div class="col-md-9">
            <div id="certificate-canvas-container">
                <div id="certificate-canvas">
                    <!-- Elements will be dropped here -->
                </div>
            </div>
            <div class="text-center small text-muted mt-2">
                Drag elements to position them. Click to edit styles.
            </div>
        </div>
    </div>

    <!-- Hidden Input for JSON Data -->
    <input type="hidden" name="template_data" id="templateDataInput">
</form>

<script>
    let canvas = document.getElementById('certificate-canvas');
    let selectedElement = null;
    let elements = [];

    // Load existing data if editing
    <?php if (isset($certificate) && $certificate['template_data']): ?>
        try {
            let savedData = <?= $certificate['template_data'] ?>;
            if (Array.isArray(savedData)) {
                savedData.forEach(el => {
                    renderElement(el);
                });
            }
        } catch(e) { console.error('Error loading template data', e); }
    <?php endif; ?>

    // Background Image Preview
    document.getElementById('bgInput').addEventListener('change', function(event) {
        if (event.target.files && event.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                canvas.style.backgroundImage = 'url(' + e.target.result + ')';
            }
            reader.readAsDataURL(event.target.files[0]);
        }
    });
    
    // Set initial background if exists (For Edit mode)
    <?php if (isset($certificate) && $certificate['background_image']): ?>
        canvas.style.backgroundImage = 'url(<?= base_url('uploads/certificates/' . $certificate['background_image']) ?>)';
    <?php endif; ?>


    function addElement(type, text) {
        let elData = {
            id: 'el_' + Date.now(),
            type: type,
            text: text, // Display text
            x: 50,
            y: 50,
            fontSize: 24,
            color: '#000000',
            color: '#000000',
            fontWeight: 'normal',
            fontFamily: "'Times New Roman', serif",
            textAlign: 'left',
            lineHeight: 1.2
        };
        renderElement(elData);
    }

    function renderElement(data) {
        let div = document.createElement('div');
        div.classList.add('draggable-item');
        div.id = data.id;
        div.innerText = data.text;
        div.dataset.type = data.type; // Save the raw type like {student_name}
        
        // Apply styles
        div.style.left = data.x + 'px';
        div.style.top = data.y + 'px';
        div.style.fontSize = data.fontSize + 'px';
        div.style.color = data.color;
        div.style.fontWeight = data.fontWeight;
        div.style.fontFamily = data.fontFamily || "'Times New Roman', serif";
        div.style.textAlign = data.textAlign || 'left';
        div.style.whiteSpace = 'pre-wrap'; // Support multiline
        div.style.lineHeight = data.lineHeight || 1.2;

        // Save data object attached to element for easy access
        div.dataset.fontSize = data.fontSize;
        div.dataset.color = data.color;
        div.dataset.fontWeight = data.fontWeight;
        div.dataset.fontFamily = data.fontFamily || "'Times New Roman', serif";
        div.dataset.textAlign = data.textAlign || 'left';
        div.dataset.lineHeight = data.lineHeight || 1.2;

        // Events
        div.addEventListener('mousedown', initDrag);
        div.addEventListener('click', (e) => {
            e.stopPropagation();
            selectElement(div);
        });

        canvas.appendChild(div);
        elements.push(div);
    }

    function selectElement(el) {
        if (selectedElement) selectedElement.classList.remove('selected');
        selectedElement = el;
        selectedElement.classList.add('selected');
        
        // Show properties
        document.getElementById('itemProperties').style.display = 'block';
        
        // Show/Hide Text Edit
        if (el.dataset.type === 'text') {
            document.getElementById('textContentGroup').style.display = 'block';
            document.getElementById('pText').value = el.innerText;
        } else {
            document.getElementById('textContentGroup').style.display = 'none';
        }
        
        // Load values
        document.getElementById('pFontSize').value = el.dataset.fontSize || 24;
        document.getElementById('pColor').value = el.dataset.color || '#000000';
        document.getElementById('pFontWeight').value = el.dataset.fontWeight || 'normal';
        document.getElementById('pFontFamily').value = el.dataset.fontFamily || "'Times New Roman', serif";
        document.getElementById('pLineHeight').value = el.dataset.lineHeight || 1.2;
    }

    function setTextAlign(align) {
        if (!selectedElement) return;
        selectedElement.style.textAlign = align;
        selectedElement.dataset.textAlign = align;
    }

    function updateSelected() {
        if (!selectedElement) return;
        
        let fontSize = document.getElementById('pFontSize').value;
        let color = document.getElementById('pColor').value;
        let fontWeight = document.getElementById('pFontWeight').value;
        let fontFamily = document.getElementById('pFontFamily').value;
        let lineHeight = document.getElementById('pLineHeight').value;
        
        // Update Text if applicable
        if (selectedElement.dataset.type === 'text') {
            let text = document.getElementById('pText').value;
            selectedElement.innerText = text;
        }

        selectedElement.style.fontSize = fontSize + 'px';
        selectedElement.style.color = color;
        selectedElement.style.fontWeight = fontWeight;
        selectedElement.style.fontFamily = fontFamily;
        selectedElement.style.lineHeight = lineHeight;

        selectedElement.dataset.fontSize = fontSize;
        selectedElement.dataset.color = color;
        selectedElement.dataset.fontWeight = fontWeight;
        selectedElement.dataset.fontFamily = fontFamily;
        selectedElement.dataset.lineHeight = lineHeight;
    }

    function deleteSelected() {
        if (selectedElement) {
            selectedElement.remove();
            selectedElement = null;
            document.getElementById('itemProperties').style.display = 'none';
        }
    }

    // Drag Logic
    let isDragging = false;
    let dragOffsetX = 0;
    let dragOffsetY = 0;

    function initDrag(e) {
        if (e.button !== 0) return; // Only left click
        isDragging = true;
        selectElement(e.target);
        
        let rect = selectedElement.getBoundingClientRect();
        let canvasRect = canvas.getBoundingClientRect();
        
        // Calculate offset relative to the element
        dragOffsetX = e.clientX - rect.left;
        dragOffsetY = e.clientY - rect.top;
        
        document.addEventListener('mousemove', doDrag);
        document.addEventListener('mouseup', stopDrag);
    }

    function doDrag(e) {
        if (!isDragging || !selectedElement) return;
        
        let canvasRect = canvas.getBoundingClientRect();
        
        // Calculate new x, y relative to canvas
        let newX = e.clientX - canvasRect.left - dragOffsetX;
        let newY = e.clientY - canvasRect.top - dragOffsetY;
        
        // Constraints (optional, keep inside canvas)
        // ...

        selectedElement.style.left = newX + 'px';
        selectedElement.style.top = newY + 'px';
    }

    function stopDrag() {
        isDragging = false;
        document.removeEventListener('mousemove', doDrag);
        document.removeEventListener('mouseup', stopDrag);
    }
    
    // Clear selection on canvas click
    canvas.addEventListener('click', (e) => {
        if (e.target === canvas) {
            if (selectedElement) selectedElement.classList.remove('selected');
            selectedElement = null;
            document.getElementById('itemProperties').style.display = 'none';
        }
    });

    // Form Submit - Serialize Data
    document.getElementById('builderForm').addEventListener('submit', function() {
        let dataToSave = [];
        let items = canvas.querySelectorAll('.draggable-item');
        
        items.forEach(item => {
            dataToSave.push({
                type: item.dataset.type,
                text: item.innerText,
                x: parseInt(item.style.left),
                y: parseInt(item.style.top),
                fontSize: parseInt(item.dataset.fontSize),
                color: item.dataset.color,
                fontWeight: item.dataset.fontWeight,
                fontFamily: item.dataset.fontFamily,
                textAlign: item.dataset.textAlign,
                lineHeight: item.dataset.lineHeight
            });
        });

        document.getElementById('templateDataInput').value = JSON.stringify(dataToSave);
    });

</script>

<?= $this->endSection() ?>
