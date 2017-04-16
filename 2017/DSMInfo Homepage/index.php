<?php
    require_once("config.php");
    require_once("functions.php");

    $active = "index";

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $logined = true;
        $user_id = $_SESSION['user_id'];

        $sql = "SELECT * FROM student WHERE id='".$user_id."'";
        $result = mysqli_query($conn, $sql);
        $row = mysqli_fetch_assoc($result);
    } else {
        $logined = false;
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
                            echo '<div id="articleHeader"><h3>안녕하세요!</h3></div>';
                            echo '<div id="articleText">';
                            echo "<p>대덕소프트웨어마이스터고등학교</p>";
                            echo "<p>정보보안동아리 Info 웹페이지입니다.</p><br>";
                            echo "<p>완성한 기능</p>";
                            echo "<ul>
                                    <li>Account</li>
                                    <ul>
                                        <li>Log in</li>
                                        <li>Log out</li>
                                        <li>Sign up</li>
                                        <li>Delete Account</li>
                                        <li>My Info</li>
                                        <li>Edit My Info</li>
                                        <li>Thank To Join</li>
                                    </ul>
                                    <li>Assignment</li>
                                    <ul>
                                        <li>Show Assignment</li>
                                        <li>Show Assignment Order By Remaining Time</li>
                                        <li>Add Assignment</li>
                                        <li>Edit Assignment</li>
                                        <li>Delete Assignment</li>
                                        <li>Set Assignment Manager</li>
                                    </ul>
                                    <li>Freeboard</li>
                                    <ul>
                                        <li>Show Posts</li>
                                        <li>Write Post</li>
                                        <li>Edit Post</li>
                                        <li>Delete Post</li>
                                        <li>Add Comment</li>
                                        <li>Edit Comment</li>
                                        <li>Delete Comment</li>
                                    </ul>
                                </ul><br>";
                            echo "<p>Other</p>";
                            echo '<ul>
                                <li>Twitter Bootstrap 3.3.4</li>
                                <li>Naver Nanum Gothic Font</li>
                            </ul>';

                        ?>
                    </div>
                </article>
            </div>
        </div>

        <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
        <!-- Include all compiled plugins (below), or include individual files as needed -->
        <script src="/bootstrap-3.3.4-dist/js/bootstrap.min.js"></script>
    </body
</html>
