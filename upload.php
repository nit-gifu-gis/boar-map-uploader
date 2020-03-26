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

    if(empty($_GET['type'])) {
        echo '{ "status" : 400, "message" : "Check your arguments." }';
        exit;
    }

    if(!$auth_util->hasWritePermission($_COOKIE['jwt'], $_GET['type'])) {
        echo '{ "status" : 403, "message" : "You don\'t have permission to write." }';
        exit;
    }

    if(empty($_FILES["files"])) {
        echo '{ "status" : 400, "message" : "Your request does not have any images." }';
        exit;
    }
    
    $resp = array(
        "status" => 200,
        "results" => array()
    );
    
    foreach ($_FILES["files"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $uid = makeRandStr(15);
            while($data->isExist($uid)) {
                $uid = makeRandStr(20);
            }
            $tmp_name = $_FILES["files"]["tmp_name"][$key];
            $name = basename($_FILES["files"]["name"][$key]);
            $ext = substr($name, strrpos($name, '.') + 1);
            move_uploaded_file($tmp_name, __DIR__ . "/images/" . $_GET['type'] . "/" . $uid . "." . $ext);
            echo "Moved to: " . __DIR__ . "/images/" . $_GET['type'] . "/" . $uid . "." . $ext;
            $resp["results"][count($resp["results"])] = array(
                "error"=>$error,
                "id"=>$uid 
            );
            $data->register($uid, $_GET['type'], $uid . "." . $ext);
        } else {
            $resp["results"][count($resp["results"])] = array(
                "error"=>$error,
                "id"=>"" 
            );
        }
    }
    
    echo json_encode($resp);

?>