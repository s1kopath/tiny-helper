<?php
$targetFolder = __DIR__ . '/core/storage/app/public'; // The folder you want to link to
storage/app/public/media
$linkFolder = __DIR__ . '/storage'; // The shortcut link you want to create

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
?>
