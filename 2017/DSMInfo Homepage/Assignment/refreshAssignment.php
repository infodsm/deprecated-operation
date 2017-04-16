bep0sitive
<?php
    require("../config.php");
    require("../functions.php");
    require("assignmentManager.php");

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    $sql = "UPDATE assignment SET closed=1 WHERE untildate < now()";
    mysqli_query($conn, $sql);
    $sql = "UPDATE assignment SET closed=0 WHERE untildate >= now()";
    mysqli_query($conn, $sql);

    header('Location: /Assignment/assignment.php');
