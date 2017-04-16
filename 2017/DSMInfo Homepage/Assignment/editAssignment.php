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
            if (!empty($_POST['editAssignment_subject']) && !empty($_POST['editAssignment_title']) && !empty($_POST['editAssignment_description'])) {
                $subject = mysqli_real_escape_string($conn, $_POST['editAssignment_subject']);
                $title = mysqli_real_escape_string($conn, $_POST['editAssignment_title']);
                $description = mysqli_real_escape_string($conn, $_POST['editAssignment_description']);
                $month = mysqli_real_escape_string($conn, $_POST['editAssignment_month']);
                $day = mysqli_real_escape_string($conn, $_POST['editAssignment_day']);

                $sql = "UPDATE assignment SET subject='".$subject."', title='".$title."', description='".$description."', untildate='2017-".$month."-".$day."' WHERE id='".$_POST['post_id']."'";
                mysqli_query($conn, $sql);

                echo '<script>alert("수정이 완료되었습니다.");</script>
                <meta http-equiv="refresh" content="0;url=/Assignment/assignment.php?page='.$_POST['post_page'].'&id='.$_POST['post_id'].'">';
                exit;
            } else {
                $sql = "SELECT * FROM assignment WHERE id='".$_GET['id']."'";
                $result = mysqli_query($conn, $sql);
                $row = mysqli_fetch_assoc($result);
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
                        <?php
                            echo '<div id="articleHeader"><h3>과제</h3></div>';
                            echo '
                                <div id="articleText">
                                    <form id="editAssignmentForm" action="/Assignment/editAssignment.php" method="post">
                                        <div class="form-group">
                                            <label for="editAssignment_subject">과목</label><br>
                                            <input id="editAssignment_subject" type="text" class="form-control" name="editAssignment_subject" value="'.$row['subject'].'">
                                        </div>
                                        <div class="form-group">
                                            <label for="editAssignment_title">제목</label><br>
                                            <input id="editAssignment_title" type="text" class="form-control" name="editAssignment_title" value="'.$row['title'].'">
                                        </div>
                                        <div class="form-group">
                                            <label for="editAssignment_description">내용</label><br>
                                            <textarea id="editAssignment_description" type="text" class="form-control" name="editAssignment_description">'.$row['description'].'</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>기한</label>
                                            <p>
                                                2017년
                                                <input id="editAssignment_month" type="text" class="form-control" name="editAssignment_month" value="'.date("m", strtotime($row['untildate'])).'">월
                                                <input id="editAssignment_day" type="text" class="form-control" name="editAssignment_day" value="'.date("d", strtotime($row['untildate'])).'">일
                                            </p>
                                        </div>
                                        <input type="hidden" name="post_id" value='.$_GET['id'].'>
                                        <input type="hidden" name="post_page" value='.$_GET['page'].'>
                                        <hr>
                                        <div class="btn-group btn-group-lg">
                                            <input type="button" class="btn btn-success" value="완료" onclick="editAssignmentSubmit();">
                                            <input type="button" class="btn btn-danger" value="취소" onclick="window.history.back();">
                                        </div>
                                    </form>
                                </div>';
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
