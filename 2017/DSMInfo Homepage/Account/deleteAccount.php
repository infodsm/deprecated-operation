<?php
    require("../config.php");
    session_start();

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    $sql = "DELETE FROM student WHERE id='".$_SESSION['user_id']."'";
    mysqli_query($conn, $sql);

    session_destroy();

    echo '<script>alert("이용해 주셔서 감사합니다!!");</script>
    <meta http-equiv="refresh" content="0;url=http://'.$domain.'/">';
    exit;
?>
