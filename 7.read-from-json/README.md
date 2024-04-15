# 7. read from json

```code
<?php
    $students = json_decode(file_get_contents(public_path() . "/stock.json"), true);
?>

```
