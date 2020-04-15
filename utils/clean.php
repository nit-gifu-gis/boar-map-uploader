<?php
    $last = file_get_contents(__DIR__ . "/last-clean.txt");
    date_default_timezone_set ("Asia/Tokyo");
    $now = date('Y/m/d');
    if ($last !== $now) {
        $types = array(
            "boar",
            "trap",
            "vaccine"
        );

        foreach($types as $type) {
            $dir = glob(__DIR__ . "/../images/temp/" . $type . "/*");
            foreach ($dir as $file) {
                unlink($file);
            }
        }
        file_put_contents(__DIR__ . "/last-clean.txt", $now);
    }
?>