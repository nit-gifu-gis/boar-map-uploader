<?php
    include_once __DIR__ . "/utils/cors.php";
    setcookie('jwt', "", time() - 60);
    echo '{ "status" : 200 }';
?>