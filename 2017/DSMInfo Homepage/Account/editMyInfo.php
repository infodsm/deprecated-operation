<?php
    require("../config.php");
    require("../functions.php");

    $active = "myInfo";

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $logined = true;

        $sql = "SELECT * FROM student WHERE id='".$_SESSION['user_id']."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);

        if (!empty($_POST['edit_user_id']) && !empty($_POST['edit_user_pw'])) {
            $user_id = mysqli_real_escape_string($conn, $_POST['edit_user_id']);
            $user_pw = mysqli_real_escape_string($conn, $_POST['edit_user_pw']);
            $user_name = mysqli_real_escape_string($conn, $_POST['edit_user_name']);
            $user_gender = mysqli_real_escape_string($conn, $_POST['edit_user_gender']);
            $user_favorite = mysqli_real_escape_string($conn, $_POST['edit_user_favorite']);
            $user_msg = mysqli_real_escape_string($conn, $_POST['edit_user_msg']);
            $sql = "SELECT * FROM student WHERE id='".$user_id."'";
            $result = mysqli_query($conn, $sql);

            if ($_SESSION['user_id'] == $user_id || $result -> num_rows == 0) {
                $sql = "UPDATE student SET id='".$user_id."', password='".$user_pw."', name='".$user_name."', gender='".$user_gender."', favorite='".$user_favorite."', msg='".$user_msg."' WHERE id='".$_SESSION['user_id']."'";
                mysqli_query($conn, $sql);

                echo '<script>alert("회원정보 수정이 완료되었습니다.");</script>
                <meta http-equiv="refresh" content="0;url=/Account/myInfo.php">';
                exit;
            } else {
                echo '<script>alert("이미 존재하는 학번입니다!!");</script>
                <meta http-equiv="refresh" content="0;url=/Account/editMyInfo.php">';
                exit;
            }
        }
    } else {
        echo '<script>alert("로그인 후 이용해주세요!!");</script>
        <meta http-equiv="refresh" content="0;url=/Account/login.php">';
        exit;
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
                        <?php
                            echo '<div id="articleHeader"><h3>내 정보 수정</h3></div>';
                            echo '<div id="articleText">';
                            echo '
                            <form id="editMyInfoForm" action="/Account/editMyInfo.php" method="post">
                                <div class="form-group">
                                    <label for="edit_user_id">학번</label>
                                    <input id="edit_user_id" type="text" class="form-control" name="edit_user_id" value="'.$row['id'].'">
                                </div>
                                <div class="form-group">
                                    <label for="edit_user_pw">Password</label>
                                    <input id="edit_user_pw" type="password" class="form-control" name="edit_user_pw" value="'.$row['password'].'">
                                </div>
                                <div class="form-group">
                                    <label for="edit_user_pw">이름</label>
                                    <input id="edit_user_name" type="text" class="form-control" name="edit_user_name" value="'.$row['name'].'">
                                </div>
                                <div class="form-group">
                                    <label>성별<br><label>
                                    <label class="radio-inline">';

                            if ($row['gender'] == "M") {
                                echo '<input id="edit_user_gender_M" type="radio" name="edit_user_gender" value="M" checked>남자
                                    </label>
                                    <label class="radio-inline">
                                        <input id="edit_user_gender_F" type="radio" name="edit_user_gender" value="F">여자
                                    </label>';
                            } else {
                                echo '<input id="edit_user_gender_M" type="radio" name="edit_user_gender" value="M">남자
                                    </label>
                                    <label class="radio-inline">
                                        <input id="edit_user_gender_F" type="radio" name="edit_user_gender" value="F" checked>여자
                                    </label>';
                            }

                            echo '</div>
                                <div class="form-group">
                                    <label for="edit_user_favorite">가장 좋아하는 것</label>
                                    <input id="edit_user_favorite" type="text" class="form-control" name="edit_user_favorite" value="'.$row['favorite'].'">
                                </div>
                                <div class="form-group">
                                    <label for="edit_user_msg">가입인사</label><br>
                                    <textarea id="edit_user_msg" type="text" class="form-control" name="edit_user_msg">'.$row['msg'].'</textarea>
                                </div>';
                            echo '<hr>';
                            echo '<div class="btn-group btn-group-lg">';
                            echo '<input type="button" class="btn btn-success" value="저장" onclick="editMyInfoSubmit();">';
                            echo '<input type="button" class="btn btn-danger" value="취소" onclick="location.href=\'/Account/myInfo.php\'">';
                            echo '</div></form>';
                            echo "</div>";
                        ?>
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
