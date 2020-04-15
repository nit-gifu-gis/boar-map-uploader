<html lang="ja">
    <head>
        <title>Environment Checker</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <?php
            $host = "localhost";
            //$host = $_SERVER["HTTP_HOST"]; // <- アクセスしているアドレスと同じドメインにする場合はコメントアウトを外す
            // 例) https://example.com/envcheck.php にアクセスした場合 -> example.com

            $isPassed = true;

            /**
             * 依存関係チェック
             */
            $isDepPassed = true;
            if(extension_loaded('gd')){
                echo "php-gdは利用可能です。<br>";
            } else {
                $isDepPassed = false;
                echo "php-gdは現在利用できません。インストール/有効化して下さい。<br>" . PHP_EOL;
            }
            if($isDepPassed) {
                echo "<font color=\"#00d438\">依存ライブラリに問題はありません。</font><br><br>" . PHP_EOL;
            } else {
                $isPassed = false;
                echo "<font color=\"#ed0000\">依存ライブラリに問題があります。</font><br><br>" . PHP_EOL;
            }

            /**
             * ファイル過不足チェック
             */
            $isFilePassed = true;
            
            $check_exist = array(
                "auth.php",
                "logout.php",
                "upload.php",
                "publish.php",
                "view.php",
                "utils/",
                "utils/auth.php",
                "utils/cors.php",
                "utils/jwt-key.pem",
                "utils/clean.php",
                "utils/last-clean.txt",
                "images/",
                "images/boar/",
                "images/trap/",
                "images/vaccine/",
                "images/temp/boar/",
                "images/temp/trap/",
                "images/temp/vaccine/",
                "images/error_login.jpg",
                "images/error_notfound.jpg",
                "images/error_parameter.jpg",
                "images/error_permission.jpg"
            );
            $check_notfound = array(
                "README.md",
                "test-scripts/",
                "test-scripts/auth-sample.html",
                "test-scripts/post-sample.html",
                "test-scripts/view-sample.html"
            );

            foreach($check_exist as $path) {
                if(file_exists(__DIR__ . "/" . $path)) {
                    echo "" . __DIR__ . "/" . $path . "は存在します。<br>";
                } else {
                    echo ">> " . __DIR__ . "/" . $path . "が存在しません。アップロード漏れ/設定漏れがないか確認して下さい。<br>";
                    $isFilePassed = false;
                }
            }

            foreach($check_notfound as $path) {
                if(!file_exists(__DIR__ . "/" . $path)) {
                    echo "" . __DIR__ . "/" . $path . "は存在しません。<br>";
                } else {
                    echo ">> " . __DIR__ . "/" . $path . "が存在します。削除して下さい。<br>";
                    $isFilePassed = false;
                }
            }

            if($isFilePassed) {
                echo "<font color=\"#00d438\">ファイルチェックに問題はありません。</font><br><br>" . PHP_EOL;
            } else {
                $isPassed = false;
                echo "<font color=\"#ed0000\">ファイルチェックに問題があります。</font><br><br>" . PHP_EOL;
            }

            /**
             * アクセス権限チェック
             */
            $isPermPassed = true;
            
            $check_read = array(
                "auth.php",
                "logout.php",
                "upload.php",
                "publish.php",
                "view.php",
                "utils/",
                "utils/auth.php",
                "utils/cors.php",
                "utils/jwt-key.pem",
                "utils/clean.php",
                "utils/last-clean.txt",
                "images/",
                "images/boar/",
                "images/trap/",
                "images/vaccine/",
                "images/temp/boar/",
                "images/temp/trap/",
                "images/temp/vaccine/",
                "images/error_login.jpg",
                "images/error_notfound.jpg",
                "images/error_parameter.jpg",
                "images/error_permission.jpg"
            );

            $check_write = array(
                "images/",
                "images/boar/",
                "images/trap/",
                "images/vaccine/",
                "images/temp/boar/",
                "images/temp/trap/",
                "images/temp/vaccine/",
                "utils/last-clean.txt",
            );

            foreach($check_read as $path) {
                if (is_readable(__DIR__ . "/" . $path)) {
                    echo "phpから" . __DIR__ . "/" . $path . "の読み込みが可能です。<br>";
                } else {
                    echo ">> phpから" . __DIR__ . "/" . $path . "の読み込みができません。アクセス権限を変更して下さい。<br>";
                    $isPermPassed = false;
                }
            }

            echo "<br>";
            foreach($check_write as $path) {
                if (is_writable(__DIR__ . "/" . $path)) {
                    echo "phpから" . __DIR__ . "/" . $path . "への書き込みが可能です。<br>";
                } else {
                    echo ">> phpから" . __DIR__ . "/" . $path . "への書き込みができません。アクセス権限を変更して下さい。<br>";
                    $isPermPassed = false;
                }
            }

            echo "<br>";
            $base = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $host . $_SERVER["REQUEST_URI"];
            $base = str_replace("envcheck.php", "", $base);

            $check_access = array(
                "utils/",
                "utils/auth.php",
                "utils/cors.php",
                "utils/jwt-key.pem",
                "utils/clean.php",
                "utils/last-clean.txt",
                "images/",
                "images/boar/",
                "images/trap/",
                "images/vaccine/",
                "images/temp/boar/",
                "images/temp/trap/",
                "images/temp/vaccine/",
                "images/error_login.jpg",
                "images/error_notfound.jpg",
                "images/error_parameter.jpg",
                "images/error_permission.jpg"
            );

            $options = array(
                'ssl' => array(
                    'verify_peer'=>false,
                    'verify_peer_name'=>false
                ),
                'http' => array(
                'method'  => 'GET',
                'ignore_errors' => true,
                'header'=>  "Content-Type: application/json\r\n" .
                            "Accept: application/json\r\n"
                )
            );

            $context  = stream_context_create( $options );

            foreach($check_access as $path) {
                $url = $base . $path;
                $html = file_get_contents( $url, false, $context );

                preg_match("/[0-9]{3}/", $http_response_header[0], $stcode);

                if(intval($stcode[0]) != 403) {
                    echo ">> Web経由で" . $path . "に外部からアクセスが可能です。アクセス権限を変更して下さい。(" . $stcode[0] . ")<br>";
                    $isPermPassed = false;
                } else {
                    echo "Web経由での" . $path . "へのアクセスは拒否されました。(" . $stcode[0] . ")<br>";
                }
            }

            if($isPermPassed) {
                echo "<font color=\"#00d438\">アクセス権限に問題はありません。</font><br><br>" . PHP_EOL;
            } else {
                $isPassed = false;
                echo "<font color=\"#ed0000\">アクセス権限に問題があります。</font><br><br>" . PHP_EOL;
            }

            if($isPassed) {
                echo "<font size=\"+2\"><font color=\"#00d438\">環境チェックが完了しました。<br>このファイルを削除することで、利用を開始できます。</font></font>" . PHP_EOL;
            } else {
                echo "<font size=\"+2\"><font color=\"#ed0000\">環境チェックが完了しました。<br>不足している依存ライブラリ、ファイルの過不足、不適切な権限設定のいずれかが存在します。</font></font>" . PHP_EOL;
            }
        ?>
    </body>
</html>