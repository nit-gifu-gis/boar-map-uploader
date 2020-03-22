<?php
    include_once __DIR__ . "/utils/cors.php";
    function show_img($path) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime_type = $finfo->file($path);

        header('Content-Type: ' . $mime_type);
        readfile($path);
    }

    date_default_timezone_set ("Asia/Tokyo");
    include_once __DIR__ . "/utils/auth.php";
    $auth_util = new Auth();

    include_once __DIR__ . "/utils/data.php";
    $data = new DataManager();

    if(empty($_COOKIE['jwt'])) {
        show_img('./images/error_login.jpg');
        exit;
    }

    if(!$auth_util->isValid($_COOKIE['jwt'])) {
        show_img('./images/error_login.jpg');
        exit;
    }

    if(!$auth_util->isAvailable($_COOKIE['jwt'])) {
        show_img('./images/error_login.jpg');
        exit;
    }
        
    if(empty($_GET['id'])) {
        show_img('./images/error_parameter.jpg');
        exit;
    }

    $info = $data->getInfo($_GET['id']);
    if($info == null) {
        show_img('./images/error_notfound.jpg');
        exit;
    }

    if(!$auth_util->hasPermission($info["type"])){
        show_img('./images/error_permission.jpg');
        exit;
    }

    show_img('./images/' . $info["type"] . "/" . $info["uid"] . "-" . $info["filename"]);
?>