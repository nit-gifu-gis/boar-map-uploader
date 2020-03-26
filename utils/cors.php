<?php
    $accept_anywhere = false;  //アクセス元に関わらず許可する場合はこれをtrueにする
    // ↑がfalseの場合は↓のhostのみにCORS許可を与える(ワイルドカード不可)
    $accept_origin = array(
        "test-gis-dev.junki-t.net",
        "localhost",
    );

    if(!empty($_SERVER["HTTP_ORIGIN"])) {
        if($accept_anywhere || in_array(str_replace(array("http://", "https://"), "", $_SERVER["HTTP_ORIGIN"]), $accept_origin)) {
            header("Access-Control-Allow-Origin: " . $_SERVER["HTTP_ORIGIN"]);
            header("Access-Control-Allow-Credentials: true");
        }
    }
?>
