<?php
    require("../config.php");
    require("../functions.php");
    require("freeboardManager.php");

    $conn = mysqli_connect($databaseHost, $databaseUser, $databasePassword);
    mysqli_select_db($conn, $databaseName);
    mysqli_set_charset($conn, "utf8");

    session_start();
    if (isset($_SESSION['user_id']) && isset($_SESSION['user_name'])) {
        $logined = true;

        if (isset($_POST['editPost_title']) && isset($_POST['editPost_description']) && isset($_POST['post_id'])) {
            $sql = "SELECT * FROM freeboard WHERE id='".$_POST['post_id']."'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if (!strcmp($row['author'], $_SESSION['user_id'])) {
                $title = mysqli_real_escape_string($conn, $_POST['editPost_title']);
                $description = mysqli_real_escape_string($conn, $_POST['editPost_description']);
                $sql = "UPDATE freeboard SET title='".$title."', description='".$description."' WHERE id='".$_POST['post_id']."'";
                mysqli_query($conn, $sql);

                echo '<script>alert("글 수정이 완료되었습니다.");</script>
                <meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php?page='.$_POST['post_page'].'&id='.$_POST['post_id'].'">';
                exit;
            } else {
                header('Location: /Freeboard/freeboard.php');
            }
        } else {
            $sql = "SELECT * FROM freeboard WHERE id='".$_GET['id']."'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
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
                            <div id="articleHeader"><h3>자유게시판</h3></div>
                            <div id="articleText">
                            <form id="editPostForm" action="/Freeboard/editPost.php" method="post">
                                <div class="form-group">
                                    <label for="editPost_title">제목</label><br>
                                    <input id="editPost_title" type="text" class="form-control" name="editPost_title" value="<?=$row['title']?>">
                                </div>
                                <div class="form-group">
                                    <label for="editPost_description">본문</label><br>
                                    <textarea id="editPost_description" type="text" class="form-control" name="editPost_description"><?=$row['description']?></textarea>
                                </div>
                                <input type="hidden" name="post_id" value='.$_GET['id'].'>
                                <input type="hidden" name="post_page" value='.$_GET['page'].'>
                                <hr>
                                <div class="btn-group btn-group-lg">
                                    <input type="button" class="btn btn-success" value="완료" onclick="editPostSubmit();">
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
