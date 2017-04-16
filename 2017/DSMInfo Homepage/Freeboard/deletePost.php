<?php
    require("../config.php");
    require("freeboardManager.php");

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
        echo '<script>alert("로그인 후 이용해주세요!!");</script>
        <meta http-equiv="refresh" content="0;url=/Account/login.php">';
        exit;
    }
    if (isset($_GET['id'])) {
        $sql = "SELECT * FROM freeboard WHERE id='".$_GET['id']."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['author']==$_SESSION['user_id'] || in_array($_SESSION['user_id'], $freeboardManager)) {
            if ($row['file']=='1') {
                if (!@unlink("../Files/".$_SESSION['user_id']."/".$_GET['id'])) {
                    echo '<script>alert("파일 삭제 실패!!");</script><meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php">';
                }
            }
            $sql = "DELETE FROM freeboard WHERE id='".$_GET['id']."'";
            mysqli_query($conn, $sql);
            header('Location: /Freeboard/freeboard.php');
        } else {
            header('Location: /Freeboard/freeboard.php');
        }
    }
