<?php
    error_reporting(E_COMPILE_ERROR | E_RECOVERABLE_ERROR | E_ERROR | E_CORE_ERROR);
    function startsWith($haystack, $needle) {
        return (strpos($haystack, $needle) === 0);
    }

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

    if(empty($_FILES["files"])) {
        echo '{ "status" : 400, "message" : "Your request does not have any images." }';
        exit;
    }
    
    $resp = array(
        "status" => 200,
        "results" => array()
    );

    $type = basename($_GET['type']);
    
    /*
     * アップロード時のエラーコードは次を参照
     * https://www.php.net/manual/ja/features.file-upload.errors.php
     * ↑に加え次のエラーコードがあります。
     * 9: 画像以外がアップロードされた場合
     * 10: jpg, jpeg, png以外がアップロードされた場合
     */

    foreach ($_FILES["files"]["error"] as $key => $error) {
        if ($error == UPLOAD_ERR_OK) {
            $finfo = new finfo(FILEINFO_MIME_TYPE);
            $mime_type = $finfo->file($_FILES["files"]["tmp_name"][$key]);
            if(startsWith($mime_type, "image/")) {
                $uid = makeRandStr(15);
                if($mime_type == "image/jpeg") {
                    $uid = "j" . $uid;
                    $ext = "jpg";
                } else if($mime_type == "image/png") {
                    $uid = "p" . $uid;
                    $ext = "png";
                } else {
                    $ext = "null";
                }

                while(file_exists(__DIR__ . "/images/" . $type . "/" . $uid . "." . $ext)) {
                    $uid = makeRandStr(20);
                }
                $tmp_name = $_FILES["files"]["tmp_name"][$key];
                $name = basename($_FILES["files"]["name"][$key]);
                $ext = substr($name, strrpos($name, '.') + 1);
                move_uploaded_file($tmp_name, __DIR__ . "/images/" . $_GET['type'] . "/" . $uid . "_");
                $file = __DIR__ . "/images/" . $_GET['type'] . "/" . $uid . "_";
                
                list($w, $h, $t) = getimagesize($file);

                $max_width = 1024;

                $width = ($w >= $max_width) ? $max_width : $w;
                // 元の解像度 * 拡大率(%) / 100
                $height = ($w >= $max_width) ? $h * ($max_width * 100 / $w) / 100 : $h;

                if($t == IMAGETYPE_JPEG) {
                    $orig = imagecreatefromjpeg($file);
                } else if($t == IMAGETYPE_PNG) {
                    $orig = imagecreatefrompng($file);
                } else {
                    unlink($file);
                    $resp["results"][count($resp["results"])] = array(
                        "error"=>10,
                        "id"=>""
                    );
                    continue;
                }
                $canvas = imagecreatetruecolor($width, $height);
                if($t == IMAGETYPE_PNG) {
                    imagealphablending($canvas, false);
                    $color = imagecolorallocatealpha($canvas, 0, 0, 0, 127);
                    imagefill(canvas, 0, 0, $color);
                    imagesavealpha($canvas, true);
                }
                imagecopyresampled($canvas, $orig, 0,0,0,0, $width, $height, $w, $h);
                $path = __DIR__ . "/images/" . $_GET['type'] . "/" . $uid . "." . $ext;

                if($t == IMAGETYPE_JPEG) {
                    imagejpeg($canvas, $path);
                } else if($t == IMAGETYPE_PNG) {
                    imagepng($canvas, $path);
                }

                imagedestroy($orig);
                imagedestroy($canvas);

                unlink($file);
                $resp["results"][count($resp["results"])] = array(
                    "error"=>$error,
                    "id"=>$uid 
                );
            } else {
                $resp["results"][count($resp["results"])] = array(
                    "error"=>9,
                    "id"=>""
                );
            }
        } else {
            $resp["results"][count($resp["results"])] = array(
                "error"=>$error,
                "id"=>"" 
            );
        }
    }
    
    echo json_encode($resp);

?>