<html lang="ja">
    <head>
        <title>Dependency Checker</title>
        <meta charset="utf-8" />
    </head>
    <body>
        <?php
            $isAvailable = true;
            if(extension_loaded('gd')){
                echo "php-gdは利用可能です。<br>";
            } else {
                $isAvailable = false;
                echo "php-gdは現在利用できません。インストール/有効化して下さい。<br>" . PHP_EOL;
            }
            if($isAvailable) {
                echo "<font color=\"#00d438\">依存関係チェックが完了しました。<br>このファイルを削除することで、利用を開始できます。</font>" . PHP_EOL;
            } else {
                echo "<font color=\"#00d438\">依存関係チェックが完了しました。<br>不足している依存ライブラリがあります。</font>" . PHP_EOL;
            }
        ?>
    </body>
</html>