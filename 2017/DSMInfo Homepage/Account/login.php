<?php
    require("../config.php");
    require("../functions.php");

    $active = "login";

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        header('Location: /');
    } else {
        $logined = false;
        if (!empty($_POST['user_id']) && !empty($_POST['user_pw'])) {
            $user_id = mysqli_real_escape_string($conn, $_POST['user_id']);
            $user_pw = mysqli_real_escape_string($conn, $_POST['user_pw']);
            $sql = "SELECT * FROM student WHERE id='".$user_id."' AND password='".$user_pw."'";
            $result = mysqli_query($conn, $sql);

            if ($result -> num_rows == 0) {
                echo '<script>alert("일치하는 계정이 없습니다!!");</script><meta http-equiv="refresh" content="0;url=/Account/login.php">';
                exit;
            } else {
                $row = mysqli_fetch_assoc($result);
                session_start();
                $_SESSION['user_id'] = $row['id'];
                $_SESSION['user_name'] = $row['name'];
                header('Location: /');
            }
        }
    }
?>

<!DOCTYPE html>
<html>
    <?php
        echoHead();
    ?>
    <body>
        <div class="container">
            <?php
                echoHeader();
            ?>
            <div class="row">
                <?php
                    echoNav($logined, $active);
                ?>
                <article>
                    <div class="col-md-9">
                        <h3>Log In</h3>
                        <form id="loginForm" action="/Account/login.php" method="post">
                            <div class="form-group">
                                <label for="user_id">학번</label>
                                <input id="user_id" type="text" class="form-control" name="user_id" placeholder="ID" onkeydown="if(event.keyCode==13){loginSubmit();}">
                            </div>
                            <div class="form-group">
                                <label for="user_pw">Password</label>
                                <input id="user_pw" type="password" class="form-control" name="user_pw" placeholder="Password" onkeydown="if(event.keyCode==13){loginSubmit();}">
                            </div>
                            <input type="button" class="btn btn-success" value="Login" onclick="loginSubmit();">
                            <input type="button" class="btn btn-info" value="join" onclick="location.href='/Account/join.php';">
                        </form>
                    </div>
                </article>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    </body>
</html>
