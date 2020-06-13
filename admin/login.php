<?php
    include_once __DIR__ . "/utils/session.php";

    if(!empty($_POST['userid']) || !empty($_POST['password'])) {
        login($_POST['userid'], $_POST['password']);
        exit;
    }

    if(is_login()) {
        header("Location: ./");
        exit;
    }

    $err = get_login_error();
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf-8">
        <title>Boar-map image uploader - Login</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="./style.css?t=<?php echo time(); ?>">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <?php include "./navbar.php" ?>
        <main>
            <div class="head_margin"></div>
            <h1 class="title">ログイン</h1>
            <br>
            <div class="border col-10 login_form">
                <div class="row">
                    <div class="col-md">
                        <form method="POST">
                            <div class="form-group">
                                <label for="userid">ユーザーID</label>
                                <input type="text" class="form-control" id="userid" name="userid" required>
                            </div>
                            <div class="form-group">
                                <label for="password">パスワード</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-primary col-12">ログイン</button>
                        </form>
                        <?php if(!empty($err)) { ?>
                            <br>
                            <div class="alert alert-danger" role="alert">
                                <?php echo $err; ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </main>
        
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
    </body>
</html>