<?php
date_default_timezone_set ("Asia/Tokyo");
class Auth {
    function base64_urlsafe_encode($val) {
        $val = base64_encode($val);
        return str_replace(array('+', '/', '='), array('_', '-', ''), $val);
    }

    function createSignature($header, $payload) {
        $key_path = __DIR__ . "/jwt-key.pem";

        $key = openssl_pkey_get_private(file_get_contents($key_path));
        $unsigned_token = $header . "." . $payload;

        $signature = "";
        openssl_sign($unsigned_token, $signature , $key, OPENSSL_ALGO_SHA256);

        return $this->base64_urlsafe_encode($signature);
    }

    function isValid($jwt) {
        $jwt_data = explode(".", $jwt);
        if(count($jwt_data) != 3) return false;

        $header_b64 = $jwt_data[0];
        $payload_b64 = $jwt_data[1];
        $signature = $jwt_data[2];

        $header = json_decode(base64_decode($header_b64));

        if($header == null) return false;
        if($header->{"alg"} !== "RS256") return false;

        return ($signature === $this->createSignature($header_b64, $payload_b64));
    }

    function hasWritePermission($jwt, $type = "") {
        $jwt_data = explode(".", $jwt);
        if(count($jwt_data) != 3) return false;
        $payload_b64 = $jwt_data[1];

        $payload = json_decode(base64_decode($payload_b64));
        if($payload == null) return false;
        
        $userDepartment = substr($payload->{"user"}, 0, 1);

        // 開発環境用
        switch ($payload->{"user"}) {
            case "tyousa":
                $userDepartment = "T";
                break;
            case "yuugai":
                $userDepartment = "U";
                break;
            case "shityouson":
                $userDepartment = "S";
                break;
            case "trap";
                $userDepartment = "W";
                break;
            case "pref";
                $userDepartment = "K";
                break;
        }

        if ($type === "boar" || $type === "trap") {
            if($userDepartment === "W") {
                return false;
            }
            return true;
        } else if ($type === "vaccine") {
            if($userDepartment === "W" || $userDepartment === "K") {
                return true;
            }
            return false;
        }

        return false;
    }

    function hasPermission($jwt, $type = "") {
        if($type !== "vaccine") return true;

        $jwt_data = explode(".", $jwt);
        if(count($jwt_data) != 3) return false;
        $payload_b64 = $jwt_data[1];

        $payload = json_decode(base64_decode($payload_b64));
        if($payload == null) return false;
        
        $userDepartment = substr($payload->{"user"}, 0, 1);

        // 開発環境用
        switch ($payload->{"user"}) {
            case "tyousa":
                $userDepartment = "T";
                break;
            case "yuugai":
                $userDepartment = "U";
                break;
            case "shityouson":
                $userDepartment = "S";
                break;
            case "trap";
                $userDepartment = "W";
                break;
            case "pref";
                $userDepartment = "K";
                break;
        }

        if($userDepartment === "W" || $userDepartment === "K") {
            return true;
        }

        return false;
    }

    function isAvailable($jwt) {
        if(!$this->isValid($jwt)) return false;
        $jwt_data = explode(".", $jwt);
        if(count($jwt_data) != 3) return false;
        $payload_b64 = $jwt_data[1];

        $payload = json_decode(base64_decode($payload_b64));
        if($payload == null) return false;
        return ($payload->{"expires_in"} >= time());
    }

}

?>