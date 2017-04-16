<?php
    require("../config.php");
    require("../functions.php");
    require("assignmentManager.php");

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $logined = true;
        if (in_array($_SESSION['user_id'], $assignmentManager)) {
            if (!empty($_POST['writeAssignment_subject']) && !empty($_POST['writeAssignment_title']) && !empty($_POST['writeAssignment_description'])) {
                $subject = mysqli_real_escape_string($conn, $_POST['writeAssignment_subject']);
                $title = mysqli_real_escape_string($conn, $_POST['writeAssignment_title']);
                $description = mysqli_real_escape_string($conn, $_POST['writeAssignment_description']);
                $month = mysqli_real_escape_string($conn, $_POST['writeAssignment_month']);
                $day = mysqli_real_escape_string($conn, $_POST['writeAssignment_day']);

                $sql = "INSERT INTO assignment (subject, title, description, untildate) VALUES('".$subject."', '".$title."', '".$description."', '2017-".$month."-".$day."')";
                mysqli_query($conn, $sql);

                echo '<meta http-equiv="refresh" content="0;url=/Assignment/assignment.php">';
                exit;
            }
        } else {
            echo '<script>alert("접근 권한이 없습니다!!");</script>
            <meta http-equiv="refresh" content="0;url=/Assignment/assignment.php">';
            exit;
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
                        <div id="articleHeader"><h3>과제</h3></div>
                        <div id="articleText">
                            <form id="writeAssignmentForm" action="/Assignment/writeAssignment.php" method="post">
                                <div class="form-group">
                                    <label for="writeAssignment_subject">과목</label><br>
                                    <input id="writeAssignment_subject" type="text" class="form-control" name="writeAssignment_subject" >
                                </div>
                                <div class="form-group">
                                    <label for="writeAssignment_title">제목</label><br>
                                    <input id="writeAssignment_title" type="text" class="form-control" name="writeAssignment_title">
                                </div>
                                <div class="form-group">
                                    <label for="writeAssignment_description">내용</label><br>
                                    <textarea id="writeAssignment_description" type="text" class="form-control" name="writeAssignment_description"></textarea>
                                </div>
                                <div class="form-group">
                                    <label>기한</label>
                                    <p>2017년
                                    <input id="writeAssignment_month" type="text" class="form-control" name="writeAssignment_month">월
                                    <input id="writeAssignment_day" type="text" class="form-control" name="writeAssignment_day">일</p>
                                </div>
                                <hr>
                                <div class="btn-group btn-group-lg">
                                    <input type="button" class="btn btn-success" value="완료" onclick="writeAssignmentSubmit();">
                                    <input type="button" class="btn btn-danger" value="취소" onclick="window.history.back();">
                                </div>
                            </form>
                        </div>
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
