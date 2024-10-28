<?php
// Get 'target' and 'link' parameters from the URL
$target = $_GET['target'] ?? null;
$link = $_GET['link'] ?? null;

// Validate and sanitize inputs
$baseDir = __DIR__;
$targetFolder = realpath($baseDir . '/' . $target);
$linkFolder = $baseDir . '/' . basename($link);

// Ensure the target folder exists and both paths are within the allowed directory
if ($targetFolder && file_exists($targetFolder) && strpos($targetFolder, $baseDir) === 0 && strpos($linkFolder, $baseDir) === 0) {
    // Check if the link already exists
    if (file_exists($linkFolder)) {
        echo "Shortcut already exists!";
    } else {
        // Attempt to create the symbolic link
        if (symlink($targetFolder, $linkFolder)) {
            echo "Shortcut created successfully!";
        } else {
            echo "Failed to create shortcut. Check your permissions.";
        }
    }
} else {
    echo "Invalid paths provided.";
}
?>
