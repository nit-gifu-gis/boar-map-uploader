<?php
    include_once __DIR__ . "/utils/session.php";

    if(!is_enable()) {
        echo "Administrator panel is not enabled.";
        exit;
    }

    function endsWith($haystack, $needle) {
        return (strrpos($haystack, $needle) === strlen($haystack) - strlen($needle));
    }

    $User = get_user();
    $isLogin = is_login();
    $nav_list = "";
    $nav_search = "";
    $nav_info = "";

    $url = $_SERVER['REQUEST_URI'];
    if(endsWith($url, "list.php")) {
        $nav_list = " active";
    } else if(endsWith($url, "search.php")) {
        $nav_search = " active";
    } else if(endsWith($url, "info.php")) {
        $nav_info = " active";
    }
    
?>

<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="./">いのししマップぎふ</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
  
    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <?php if($isLogin) { ?>
                <li class="nav-item<?php echo $nav_list; ?>">
                    <a class="nav-link" href="./list.php">リスト</span></a>
                </li>
                <li class="nav-item<?php echo $nav_search; ?>">
                    <a class="nav-link" href="./search.php">検索</a>
                </li>
                <li class="nav-item<?php echo $nav_info; ?>">
                    <a class="nav-link" href="./info.php">サーバー情報</a>
                </li>
            <?php } ?>
        </ul>
        <ul class="navbar-nav my-2 my-lg-0">
            <?php if($isLogin) { ?>
                <li class="nav-item active">
                    <span class="nav-link"><?php echo $User; ?> さん、こんにちは。</span>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="./logout.php">ログアウト</a>
                </li>
            <?php } else { ?>
                <li class="nav-item active">
                    <span class="nav-link">現在ログインしていません。</span>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>