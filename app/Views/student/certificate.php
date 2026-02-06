<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Certificate of Completion</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            background: #f0f0f0;
            margin: 0;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .certificate-container {
            width: 800px;
            height: 600px;
            background: #fff;
            position: relative;
            padding: 40px;
            text-align: center;
            border: 10px solid #ddd;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            box-sizing: border-box;
        }
        .border-inner {
            border: 5px double #1a4f7c;
            height: 100%;
            padding: 30px;
            box-sizing: border-box;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .header {
            font-size: 40px;
            font-weight: bold;
            color: #1a4f7c;
            margin-bottom: 20px;
            text-transform: uppercase;
        }
        .sub-header {
            font-size: 18px;
            margin-bottom: 30px;
            color: #555;
        }
        .student-name {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            border-bottom: 2px solid #ccc;
            padding-bottom: 5px;
            margin: 0 auto 20px;
            display: inline-block;
            min-width: 400px;
        }
        .course-title {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
            font-weight: bold;
        }
        .date {
            font-size: 16px;
            color: #777;
            margin-top: 40px;
        }
        .footer {
            margin-top: 50px;
            display: flex;
            justify-content: space-around;
        }
        .signature {
            border-top: 1px solid #333;
            width: 200px;
            padding-top: 5px;
            font-size: 14px;
        }
        @media print {
            body { 
                background: none; 
                padding: 0;
            }
            .certificate-container {
                box-shadow: none;
                border: none;
                width: 100%;
                height: 100%;
            }
            .no-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <?php if (!empty($template) && !empty($template['template_data'])): ?>
        <!-- Dynamic Template Rendering -->
        <style>
             .certificate-container {
                position: relative;
                width: 800px;
                height: 600px;
                margin: 0 auto;
                background-color: #fff;
                background-size: cover;
                background-position: center;
                box-shadow: 0 0 20px rgba(0,0,0,0.1);
                overflow: hidden;
            }
            .cert-element {
                position: absolute;
                white-space: nowrap;
                transform: translate(-50%, -50%); /* Center the element on its coords */
                transform-origin: center center;
            }
        </style>
        
        <div class="certificate-container" style="background-image: url('<?= !empty($template['background_image']) ? base_url('uploads/certificates/' . $template['background_image']) : '' ?>');">
            <?php
            $elements = json_decode($template['template_data'], true);
            if (is_array($elements)):
                foreach ($elements as $el):
                    // Replace Placeholders
                    $text = $el['text'];
                    if ($el['type'] !== 'text') {
                        $text = str_replace('{student_name}', esc($user['first_name'] . ' ' . $user['last_name']), $text);
                        $text = str_replace('{course_name}', esc($course['title']), $text);
                        $text = str_replace('{completion_date}', $date, $text);
                        $text = str_replace('{certificate_code}', 'CERT-' . str_pad($enrollment['id'], 6, '0', STR_PAD_LEFT), $text);
                        $text = str_replace('{instructor_name}', esc($course['instructor_name'] ?? 'Instructor'), $text);

                        // Signature Replacement
                        if (strpos($text, '{instructor_signature}') !== false) {
                            $signatureFile = $course['instructors'][0]['signature'] ?? null;
                            if ($signatureFile) {
                                $imgTag = '<img src="' . base_url('uploads/signatures/' . $signatureFile) . '" style="height: 50px; width: auto;">';
                                $text = str_replace('{instructor_signature}', $imgTag, $text);
                            } else {
                                $text = str_replace('{instructor_signature}', '', $text);
                            }
                        }
                    }
                    // For type 'text', we just display the text as saved (which is editable by admin)
                    ?>
                <div class="cert-element" style="
                    left: <?= $el['x'] ?>px; 
                    top: <?= $el['y'] ?>px; 
                    font-size: <?= $el['fontSize'] ?>px; 
                    color: <?= $el['color'] ?>; 
                    font-weight: <?= $el['fontWeight'] ?>;
                ">
                    <?= $text ?>
                </div>
            <?php
                endforeach;
            endif;
            ?>
        </div>

    <?php else: ?>
        <!-- Fallback: Default Static Template -->
        <div class="certificate-container">
            <div class="border-inner">
                <div class="header">Certificate of Completion</div>
                <div class="sub-header">This is to certify that</div>
                
                <div class="student-name"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></div>
                
                <div class="sub-header">has successfully completed the course</div>
                
                <div class="course-title"><?= esc($course['title']) ?></div>
                
                <div class="date">Completed on: <?= $date ?></div>
                
                <div class="footer">
                    <div class="signature">Instructor</div>
                    <div class="signature">Course Director</div>
                </div>
            </div>
        </div>
    <?php endif; ?>
    
    <div style="position: fixed; bottom: 20px; right: 20px;" class="no-print">
        <button onclick="window.print()" style="padding: 10px 20px; background: #1a4f7c; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px;">
            Download / Print
        </button>
        <button onclick="window.history.back()" style="padding: 10px 20px; background: #666; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; margin-left: 10px;">
            Back
        </button>
    </div>
</body>
</html>
