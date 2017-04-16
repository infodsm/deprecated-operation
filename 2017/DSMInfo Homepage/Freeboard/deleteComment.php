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
    if (isset($_GET['post_id']) && isset($_GET['comment_id'])) {
        $sql = "SELECT author FROM comment WHERE post='".$_GET['post_id']."' AND id='".$_GET['comment_id']."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if ($row['author']==$_SESSION['user_id'] || in_array($_SESSION['user_id'], $freeboardManager)) {
            $sql = "DELETE FROM comment WHERE id='".$_GET['comment_id']."'";
            mysqli_query($conn, $sql);
            header('Location: /Freeboard/freeboard.php?id='.$_GET['post_id'].'&page='.$_GET['page']);
        } else {
            header('Location: /Freeboard/freeboard.php?id='.$_GET['post_id'].'&page='.$_GET['page']);
        }
    }
