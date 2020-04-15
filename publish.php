<?php
    error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
    function startsWith($haystack, $needle) {
        return (strpos($haystack, $needle) === 0);
    }

    include_once __DIR__ . "/utils/cors.php";
    date_default_timezone_set ("Asia/Tokyo");
    include_once __DIR__ . "/utils/auth.php";
    $auth_util = new Auth();
    if($auth_util->isEnvCheckAvailable()) {
        echo "環境チェック用ファイルが削除されていません。使用開始前に削除して下さい。";
        exit;
    }

    if(empty($_COOKIE['jwt'])) {
        echo '{ "status" : 401, "message" : "Your token is missing." }';
        exit;
    }

    if(!$auth_util->isValid($_COOKIE['jwt'])) {
        echo '{ "status" : 401, "message" : "Your token is invalid." }';
        exit;
    }

    if(!$auth_util->isAvailable($_COOKIE['jwt'])) {
        echo '{ "status" : 401, "message" : "Your token has expired." }';
        exit;
    }

    if(empty($_GET['type'])) {
        echo '{ "status" : 400, "message" : "Check your arguments." }';
        exit;
    }

    if(!$auth_util->hasWritePermission($_COOKIE['jwt'], $_GET['type'])) {
        echo '{ "status" : 403, "message" : "You don\'t have permission to write." }';
        exit;
    }

    $type = basename($_GET['type']);

    $ids = json_decode(file_get_contents("php://input"));
    foreach ($ids as $info) {
        if(!empty($info->{"id"})) {
            $id = basename($info->{"id"});
            $ext = substr($id, 0, 1);
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
            if(file_exists(__DIR__ . "/images/temp/" . $type . "/" . $id . "." . $ext)) {
                rename(__DIR__ . "/images/temp/" . $type . "/" . $id . "." . $ext, __DIR__ . "/images/" . $type . "/" . $id . "." . $ext);
            }
        }
    }
    
    echo '{ "status" : 200 }';

?>