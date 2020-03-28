<?php
    include_once __DIR__ . "/utils/cors.php";
    date_default_timezone_set ("Asia/Tokyo");
    include_once __DIR__ . "/utils/auth.php";
    $auth_util = new Auth();
    if($auth_util->isDepCheckAvailable()) {
        echo "依存関係チェック用ファイルが削除されていません。使用開始前に削除して下さい。";
        exit;
    }

    $str_in = file_get_contents("php://input");
    $json = json_decode($str_in);

    $user = $json->{"user"};
    $token = $json->{"token"};
    $expires_in = $json->{"expires_in"};

    $rnum = random_int(0, 100000);
    $body = array(
        "commonHeader"=>array(
            "receiptNumber"=>$rnum
        ),
        "layerId"=>5000008,
        "inclusion"=>1,
        "buffer"=>100,
        "srid"=>4326,
        "type"=>"Feature",
        "geometry"=>array(
            "type"=>"Point",
            "coordinates"=>array(
                0,
                0
            )
        )
    );

    $body = json_encode($body);

    $url = 'https://pascali.info-mapping.com/webservices/publicservice/JsonService.asmx/GetFeaturesByExtent';

    $options = array(
        'http' => array(
          'method'  => 'POST',
          'content' => $body,
          'ignore_errors' => true,
          'header'=>  "Content-Type: application/json\r\n" .
                      "Accept: application/json\r\n" . 
                      "X-Map-Api-Access-Token: " . $token
          )
      );

    $context  = stream_context_create( $options );
    $html = file_get_contents( $url, false, $context );

    preg_match("/[0-9]{3}/", $http_response_header[0], $stcode);

    if(intval($stcode[0]) == 200) {
        $payload = array(
            "iat"=>time(),
            "user"=>$user,
            "token"=>$token,
            "expires_in"=>$expires_in
        );
        $header_s = $auth_util->base64_urlsafe_encode('{ "alg" : "RS256", "type" : "JWT" }');
        $payload_s = $auth_util->base64_urlsafe_encode(json_encode($payload));
        $signature = $auth_util->createSignature($header_s, $payload_s);
        $jwt = $header_s . "." . $payload_s . "." . $signature;
        setcookie('jwt', $jwt);
        echo '{ "status" : 200 }';
    } else {
        echo '{ "status" : 401 }';
    }
?>