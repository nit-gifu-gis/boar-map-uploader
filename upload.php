<?php
    function makeRandStr($length) {
        $str = array_merge(range('a', 'z'), range('0', '9'), range('A', 'Z'));
        $r_str = null;
        for ($i = 0; $i < $length; $i++) {
            $r_str .= $str[rand(0, count($str) - 1)];
        }
        return $r_str;
    }

    include_once __DIR__ . "/utils/cors.php";
    date_default_timezone_set ("Asia/Tokyo");
    include_once __DIR__ . "/utils/auth.php";
    $auth_util = new Auth();

    include_once __DIR__ . "/utils/data.php";
    $data = new DataManager();

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

    // TODO: Add check photo is uploaded or not.
    if(empty($_GET['type'])) {
        echo '{ "status" : 400, "message" : "Check your arguments." }';
        exit;
    }

    if(!$auth_util->hasWritePermission($_COOKIE['jwt'], $_GET['type'])) {
        echo '{ "status" : 403, "message" : "You don\'t have permission to write." }';
        exit;
    }
    
    $uid = makeRandStr(20);
    while($data->isExist($uid)) {
        $uid = makeRandStr(20);
    }

    // 画像取得処理
    $filename = "";

    $data->register($uid, $_GET['type'], $filename);
    echo '{ "status" : 200, "message" : "OK." }';

?>