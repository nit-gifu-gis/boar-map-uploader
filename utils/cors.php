<?php
    function endsWith($haystack, $needle) {
        return (strlen($haystack) > strlen($needle)) ? (substr($haystack, -strlen($needle)) == $needle) : false;
    }

    $accept_anywhere = false;  //アクセス元に関わらず許可する場合はこれをtrueにする
    // ↑がfalseの場合は↓のhostのみにCORS許可を与える(後方一致のみ可能(*.example.com))
    $accept_origin = array(
        "*.junki-t.net",
        "test-gis-dev.junki-t.net",
        "*.nit-gifu-gis.now.sh",
        "localhost",
    );

    if(!empty($_SERVER["HTTP_ORIGIN"])) {
        if($accept_anywhere || in_array(str_replace(array("http://", "https://"), "", $_SERVER["HTTP_ORIGIN"]), $accept_origin)) {
            header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
            header("Access-Control-Allow-Credentials: true");
        } else {
            foreach($accept_origin as $orig){
                if(strpos($orig,'*') !== false) {
                    $orig = str_replace("*", "", $orig);
                    $srv = str_replace(array("http://", "https://"), "", $_SERVER["HTTP_ORIGIN"]);
                    if(endsWith($srv, $orig)){
                        header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
                        header("Access-Control-Allow-Credentials: true");
                        break;
                    }
                }
            }
        }
    }
?>
