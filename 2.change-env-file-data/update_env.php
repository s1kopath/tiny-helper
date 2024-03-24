<?php
function updateEnv()
{
    $path = base_path('.env');
    $test = file_get_contents($path);

    if (file_exists($path)) {
        file_put_contents($path, str_replace('APP_ENV=production', 'APP_ENV=local', $test));
    }
}
