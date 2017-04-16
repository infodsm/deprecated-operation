<?php
    require("../config.php");
    require("../functions.php");
    require("assignmentManager.php");

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_name'])) {
        echo '<script>alert("로그인 후 이용해주세요!!");</script>
        <meta http-equiv="refresh" content="0;url=/Assignment/assignment.php">';
        exit;
    }

    if (!empty($_GET['id'])) {
        if (in_array($_SESSION['user_id'], $assignmentManager)) {
            $sql = "DELETE FROM assignment WHERE id='".$_GET['id']."'";
            $result = mysqli_query($conn, $sql);
        } else {
            echo '<script>alert("접근 권한이 없습니다!!");</script>
            <meta http-equiv="refresh" content="0;url=/Assignment/assignment.php">';
            exit;
        }
    }
    header('Location: /Assignment/assignment.php');
