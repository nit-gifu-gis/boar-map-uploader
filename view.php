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
        
    if(empty($_GET['id']) || empty($_GET['type'])) {
        show_img('./images/error_parameter.jpg');
        exit;
    }
    $type = basename($_GET["type"]);
    $id = basename($_GET["id"]);

    $ext = substr($_GET["id"], 0, 1);
    switch($ext) {
        case "j":
            $ext = "jpg";
            break;
        case "p":
            $ext = "png";
            break;
        default:
            $ext = "null";
    }

    if(!file_exists(__DIR__ . "/images/" . $type . "/" . $id . "." . $ext)) {
        show_img('./images/error_notfound.jpg');
        exit;
    }

    if(!$auth_util->hasPermission($_COOKIE['jwt'], $type)){
        show_img('./images/error_permission.jpg');
        exit;
    }

    show_img('./images/' . $type . "/" . $id . "." . $ext);
?>