<?php
    function file_upload_max_size() {
        static $max_size = -1;
    
        if ($max_size < 0) {
            // Start with post_max_size.
            $post_max_size = parse_size(ini_get('post_max_size'));
            if ($post_max_size > 0) {
                $max_size = $post_max_size;
            }
        
            // If upload_max_size is less, then reduce. Except if upload_max_size is
            // zero, which indicates no limit.
            $upload_max = parse_size(ini_get('upload_max_filesize'));
            if ($upload_max > 0 && $upload_max < $max_size) {
                $max_size = $upload_max;
            }
        }
        return $max_size;
    }
    
    function parse_size($size) {
        $unit = preg_replace('/[^bkmgtpezy]/i', '', $size); // Remove the non-unit characters from the size.
        $size = preg_replace('/[^0-9\.]/', '', $size); // Remove the non-numeric characters from the size.
        if ($unit) {
            // Find the position of the unit in the ordered string which is the power of magnitude to multiply a kilobyte by.
            return round($size * pow(1024, stripos('bkmgtpezy', $unit[0])));
        } else {
            return round($size);
        }
    }

    include_once __DIR__ . "/utils/session.php";
    if(!is_login()) {
        header("Location: ./login.php");
        exit;
    }


    $si_prefix = array( 'B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB' );
    $base = 1024;
    $path = __DIR__ . "/../images/";
    $total_bytes = disk_total_space($path);
    $class = min((int)log($total_bytes , $base) , count($si_prefix) - 1);
    $space_total = sprintf('%1.2f' , $total_bytes / pow($base,$class)) . $si_prefix[$class];
    $free_bytes = disk_free_space($path);
    $class = min((int)log($free_bytes , $base) , count($si_prefix) - 1);
    $space_free = sprintf('%1.2f' , $free_bytes / pow($base,$class)) . $si_prefix[$class];
    $free_percent = round($free_bytes / $total_bytes * 100, 2) . "%"

?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Boar-map image uploader - Admin</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
        <link rel="stylesheet" href="./style.css?t=<?php echo time(); ?>">
        </head>
    <body>
        <?php include __DIR__ . "/navbar.php" ?>
        <main>
            <div class="head_margin"></div>
            <div class="container">
                <h2 class="subtitle">サーバー情報</h2>
                <br>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">項目名</th>
                            <th scope="col">値</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th scope="row">1</th>
                            <td>ディスク空き容量</td>
                            <td><?php echo $space_free . " / " . $space_total . " (" . $free_percent . ")"; ?></td>
                        </tr>
                        <tr>
                            <th scope="row">2</th>
                            <td>画像保存先</td>
                            <td><?php echo realpath(__DIR__ . "/../images/") ?></td>
                        </tr>
                        <tr>
                            <th scope="row">3</th>
                            <td>OS</td>
                            <td><?php echo php_uname(); ?></td>
                        </tr>
                        <tr>
                            <th scope="row">4</th>
                            <td>ドメイン</td>
                            <td><?php echo $_SERVER["HTTP_HOST"] ?></td>
                        </tr>
                        <tr>
                            <th scope="row">5</th>
                            <td>最大アップロード可能容量</td>
                            <td><?php echo file_upload_max_size() / 1024 / 1024 ?> MB</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </main>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>