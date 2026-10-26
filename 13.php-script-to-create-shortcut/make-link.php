<?php
$target = __DIR__ . '/../storage/app/public';
$link   = __DIR__ . '/storage';
if (file_exists($link)) { exit('storage link already exists'); }
echo symlink($target, $link) ? 'Linked OK' : 'symlink() failed – disabled on this host';
