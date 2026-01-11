<?php

function insertFooterCSS($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() == 'php') {
            $filePath = $file->getRealPath();
            $content = file_get_contents($filePath);

            // Check if the footer.css link already exists
            if (strpos($content, 'css/footer.css') === false) {
                // Insert the footer.css link in the <head> section
                $updatedContent = preg_replace(
                    '/<head>(.*?)<\/head>/is',
                    '<head>$1<link rel="stylesheet" href="css/footer.css"></head>',
                    $content
                );

                // Save the updated content back to the file
                file_put_contents($filePath, $updatedContent);
                echo "Updated: $filePath\n";
            }
        }
    }
}

function countPHPFiles($dir) {
    $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    $count = 0; // Initialize a counter for the number of PHP files
    foreach ($files as $file) {
        if ($file->isFile() && $file->getExtension() == 'php') {
            $count++; // Increment the counter for each PHP file
        }
    }
    return $count; // Return the total count
}

// Specify the directory to start the search
$projectDir = 'C:\xampp\htdocs\ΠΤΥΧΙΑΚΗ';
insertFooterCSS($projectDir);

$totalPHPFiles = countPHPFiles($projectDir);

echo "Footer CSS insertion completed.\n";
echo "Total PHP files: $totalPHPFiles\n";
?>