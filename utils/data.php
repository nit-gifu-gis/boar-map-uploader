<?php

class DataManager {
    
    function isExist($id = "") {
        if(empty($id)) return false;
        $link = $this->getConnection();
        $uid = mysqli_real_escape_string($link, $id);
        $sql = "SELECT * FROM bm_image WHERE uid='" . $uid . "';";
        if($result = mysqli_query($link, $sql)) {
            foreach ($result as $row) {
                mysqli_close($link);
                return true;
            }
        }

        mysqli_close($link);
        return false;
    }

    function register($id, $type, $filename) {

    }

    function getInfo($id = "") {
        if(empty($id)) return false;
        $link = $this->getConnection();
        $uid = mysqli_real_escape_string($link, $id);
        $sql = "SELECT * FROM bm_image WHERE uid='" . $uid . "';";
        if($result = mysqli_query($link, $sql)) {
            foreach ($result as $row) {
                $info = array(
                    "id"=>$row["id"],
                    "uid"=>$row["uid"],
                    "type"=>$row["type"],
                    "filename"=>$row["filename"]
                );
                mysqli_close($link);
                return $info;
            }
        }

        mysqli_close($link);
        return null;
    }

    function getConnection() {
        include __DIR__ . "/settings.php";
        $link = mysqli_connect($SETTINGS["database"]["host"] . ":" . $SETTINGS["database"]["port"], $SETTINGS["database"]["user"], $SETTINGS["database"]["password"], $SETTINGS["database"]["database"]);
        if (mysqli_connect_errno()) {
            die("データベースに接続できません: " . mysqli_connect_error() . "\n");
        } else {
            return $link;
        }
    }
}
?>