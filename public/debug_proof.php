<?php
$dir = __DIR__ . '/uploads/payment_proofs';
$files = scandir($dir);
echo '<h1>Debug Proofs</h1>';
echo "Directory: $dir<br>";
echo '<h2>Files found:</h2>';
echo '<ul>';
foreach ($files as $file) {
    if ($file == '.' || $file == '..')
        continue;
    echo "<li>$file</li>";
}
echo '</ul>';

echo '<h2>Images:</h2>';
foreach ($files as $file) {
    if ($file == '.' || $file == '..')
        continue;
    echo "<h3>$file</h3>";
    echo "<img src='uploads/payment_proofs/$file' style='max-width:200px; border:1px solid red;' alt='Cannot load $file'><br>";
}
