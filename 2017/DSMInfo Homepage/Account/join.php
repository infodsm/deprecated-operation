<?php
    require("../config.php");
    require("../functions.php");

    $active = "join";

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        header('Location: /');
    } else {
        $logined = false;
        if (!empty($_POST['join_user_id']) && !empty($_POST['join_user_pw'])) {
            $user_id = mysqli_real_escape_string($conn, $_POST['join_user_id']);
            $user_pw = mysqli_real_escape_string($conn, $_POST['join_user_pw']);
            $user_name = mysqli_real_escape_string($conn, $_POST['join_user_name']);
            $user_gender = mysqli_real_escape_string($conn, $_POST['join_user_gender']);
            $user_msg = mysqli_real_escape_string($conn, $_POST['join_user_msg']);
            $sql = "SELECT * FROM student WHERE id='".$user_id."'";
            $result = mysqli_query($conn, $sql);

            if ($result -> num_rows == 0) {
                $sql = "INSERT INTO student (id, name, gender, password, msg) VALUES('".$user_id."', '".$user_name."', '".$user_gender."', '".$user_pw."', '".$user_msg."')";
                mysqli_query($conn, $sql);

                $_SESSION['user_id'] = $user_id;
                $_SESSION['user_name'] = $user_name;
                header('Location: /Account/thankToJoin.php');
            } else {
                echo '<script>alert("이미 존재하는 학번입니다!!");</script>
                <meta http-equiv="refresh" content="0;url=/Account/join.php">';
                exit;
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
                        <h3>Sign Up</h3>
                        <form id="joinForm" action="/Account/join.php" method="post">
                            <div class="form-group">
                                <label for="join_user_id">학번</label>
                                <input id="join_user_id" type="text" class="form-control" name="join_user_id" placeholder="ID">
                            </div>
                            <div class="form-group">
                                <label for="join_user_pw">Password</label>
                                <input id="join_user_pw" type="password" class="form-control" name="join_user_pw" placeholder="Password">
                            </div>
                            <div class="form-group">
                                <label for="join_user_pw">이름</label>
                                <input id="join_user_name" type="text" class="form-control" name="join_user_name" placeholder="Name">
                            </div>
                            <div class="form-group">
                                <label>성별<br><label>
                                <label class="radio-inline">
                                    <input id="join_user_gender_M" type="radio" name="join_user_gender" value="M">남자
                                </label>
                                <label class="radio-inline">
                                    <input id="join_user_gender_F" type="radio" name="join_user_gender" value="F">여자
                                </label>
                            </div>
                            <div class="form-group">
                                <label for="join_user_msg">가입인사</label>
                                <textarea id="join_user_msg" type="text" class="form-control" name="join_user_msg" placeholder="가입인사"></textarea>
                            </div>
                            <input type="button" class="btn btn-success" value="Join" onclick="joinSubmit();">
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
