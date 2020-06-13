<?php
    session_start();
    date_default_timezone_set("Asia/Tokyo");

    function is_enable() {
        include_once __DIR__ . "/config.php";
        return $SETTINGS["is_enabled"];
    }

    function is_login() {
        if(empty($_SESSION["user"]) || empty($_SESSION["exp"])) return false;

        if(intval($_SESSION["exp"]) < time()) {
            $_SESSION["err"] = "セッションがタイムアウトしました。<br>再度ログインしてください。";
            $_SESSION["user"] = null;
            $_SESSION["exp"] = null;
            return false;
        }

        return true;
    }

    function get_user() {
        if(empty($_SESSION["user"])) return "";
        return $_SESSION["user"];
    }

    function get_login_error(){
        if(is_login()) {
            return null;
        }

        if(empty($_SESSION["err"])) return null;
        
        $err = $_SESSION["err"];
        $_SESSION["err"] = null;
        return $err;
    }

    function login($user, $pass) {
        include_once __DIR__ . "/config.php";
        $isFound = false;
        $isLogin = false;
        foreach($SETTINGS["local_accounts"] as $account_id => $pass_md5) {
            if($account_id == $user) {
                $isFound = true;
                if($pass_md5 == md5($pass)) {
                    $_SESSION["user"] = $user;
                    $_SESSION["exp"] = time() + (6 * 60 * 60);
                    $isLogin = true;
                }
            }
        }

        foreach($SETTINGS["gis_accounts"] as $account) {
            if($account == $user) {
                // login process.
                $_SESSION["err"] = "このIDでのログインはサポートされていません。";
                $isFound = true;
            }
        }

        if(!$isFound) {
            $_SESSION["err"] = "アカウントが見つかりませんでした。";
        } else if(!$isLogin) {
            $_SESSION["err"] = "パスワードが正しくありません。";
        }

        if($isLogin) {
            header("Location: ./");
        } else {
            header("Location: ./login.php");
        }
    }

?>