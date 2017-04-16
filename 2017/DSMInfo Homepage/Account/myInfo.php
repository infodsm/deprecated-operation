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

        if ($row['gender']=="M") {
            $user_gender = "남자";
        } elseif ($row['gender']=="F") {
            $user_gender = "여자";
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
                            echo '<div id="articleHeader"><h3>내 정보</h3></div>';
                            echo '<div id="articleText">';
                            echo "<p>학번 : ".htmlspecialchars($row['id'])."</p>";
                            echo "<p>이름 : ".htmlspecialchars($row['name'])."</p>";
                            echo "<p>성별 : ".htmlspecialchars($user_gender)."</p>";
                            echo "<p>가장 좋아하는 것 : ".htmlspecialchars($row['favorite'])."</p>";
                            echo "<p>가입인사 : ".htmlspecialchars($row['msg'])."</p>";
                            echo '</div>';

                            echo '<hr>';
                            echo '<div class="btn-group btn-group-lg">';
                            echo '<input type="button" class="btn btn-info" value="회원정보 수정" onclick="location.href=\'/Account/editMyInfo.php\'">';
                            echo '<input type="button" class="btn btn-danger" value="회원탈퇴" onclick="deleteAccount();">';
                            echo '</div>';
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
