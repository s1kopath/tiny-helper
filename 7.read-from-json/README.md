# 7. read from jason

```code
<?php
    $students = json_decode(file_get_contents(public_path() . "/stock.json"), true);
?>

```
