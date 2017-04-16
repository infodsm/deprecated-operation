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
        if (isset($_POST['choice'])) {
            if ($_POST['choice'] == "comment" && isset($_POST['writeComment_description']) && isset($_POST['post_id'])) {
                $comment = mysqli_real_escape_string($conn, $_POST['writeComment_description']);
                $sql = "INSERT INTO comment (author, description, created, post) VALUES ('".$_SESSION['user_id']."', '".$comment."', now(), '".$_POST['post_id']."')";
                mysqli_query($conn, $sql);
                header('Location: /Freeboard/freeboard.php?id='.$_POST['post_id'].'&page='.$_POST['post_page']);
            } elseif ($_POST['choice'] == "best") {
                $sql = "SELECT student FROM freeboardBest WHERE id='".$_POST['post_id']."'";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                    if ($row['student'] == $_SESSION['user_id']) {
                        $sql = "UPDATE freeboard SET best = best - 1 WHERE id='".$_POST['post_id']."'";
                        mysqli_query($conn, $sql);
                        $sql = "DELETE FROM freeboardBest WHERE id='".$_POST['post_id']."' AND student='".$_SESSION['user_id']."'";
                        mysqli_query($conn, $sql);
                        echo '<script>alert("추천이 취소되었습니다!!");</script>
                        <meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php?id='.$_POST['post_id'].'&page='.$_POST['post_page'].'">';
                        exit;
                    }
                }
                $sql = "UPDATE freeboard SET best = best + 1 WHERE id='".$_POST['post_id']."'";
                mysqli_query($conn, $sql);
                $sql = "INSERT INTO freeboardBest (id, student) VALUES ('".$_POST['post_id']."', '".$_SESSION['user_id']."')";
                mysqli_query($conn, $sql);
                header('Location: /Freeboard/freeboard.php?id='.$_POST['post_id'].'&page='.$_POST['post_page']);
            }
        }
    } else {
        echo '<script>alert("로그인 후 이용해주세요!!");</script>
        <meta http-equiv="refresh" content="0;url=/Account/login.php">';
        exit;
    }

    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
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
                        <?php
                            if (isset($_GET['id'])) {
                                $sql = "SELECT freeboard.id, title, description, created, name, best FROM freeboard LEFT JOIN student ON freeboard.author = student.id WHERE freeboard.id=".$_GET['id'];
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                if ($row['name'] == $_SESSION['user_name'] || in_array($_SESSION['user_id'], $freeboardManager)) {
                                    echoTopNav($active, true, $_GET['id'], $_GET['page']);
                                } else {
                                    echoTopNav($active);
                                }

                                echo '<div id="freeboardText" class="panel panel-default">';
                                echo '<div class="panel-heading"><h3 id="fbTitle">'.htmlspecialchars($row['title']).'</h3>';
                                echo '<p id="fbName">작성자 : '.htmlspecialchars($row['name']).'</p>';
                                echo '<p id="fbCreated">작성 일시 : '.htmlspecialchars($row['created']).'</p>';
                                echo '</div>';
                                echo '<div class="panel-body">';
                                echo '<pre id="fbDescription">'.strip_tags($row['description'], '<img><br>').'</pre><br>';
                                echo '<form id="fbBestForm" action="/Freeboard/freeboard.php" method="post">';
                                echo '<div class="form-group">';
                                echo '<input type="submit" class="btn btn-info" value="추천 '.$row['best'].'">';
                                echo '<input type="hidden" value="best" name="choice">';
                                echo '<input type="hidden" value="'.$_GET['id'].'" name="post_id">';
                                echo '<input type="hidden" value="'.$_GET['page'].'" name="post_page">';
                                echo '</div>';
                                echo '</form>';
                                echo '</div>';
                                echo '</div>';

                                $sql = "SELECT comment.id, author, name, description, created FROM comment LEFT JOIN student ON comment.author = student.id WHERE post='".$_GET['id']."'";
                                $result = mysqli_query($conn, $sql);
                                if ($result -> num_rows == 0) {
                                    echo '<div id="noComments"><p>댓글 없음!</p></div>';
                                } else {
                                    echo '<table id="freeboardCommentTable" class="table table-condensed">';
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td>
                                        <p style="text-align: right;">
                                        작성자 : '.htmlspecialchars($row['name']).' / 작성 일시 : '.$row['created'].'
                                        </p>
                                        <p style="text-align: left;">
                                        '.htmlspecialchars($row['description']).'
                                        </p>';
                                        if ($_SESSION['user_id'] == $row['author'] || in_array($_SESSION['user_id'], $freeboardManager)) {
                                            echo '<p style="text-align: right;"><a href="/Freeboard/deleteComment.php?page='.$_GET['page'].'&post_id='.$_GET['id'].'&comment_id='.$row['id'].'">삭제</a> <a href="/Freeboard/editComment.php?page='.$_GET['page'].'&id='.$_GET['id'].'&comment_id='.$row['id'].'">수정</a></p>';
                                        }
                                        echo '</td>';
                                        echo '</tr>';
                                    }
                                    echo  '</table>';
                                }
                                echo '<form id="writeCommentForm" action="/Freeboard/freeboard.php" method="post">';
                                echo '<div class="form-group">';
                                echo '<input type="text" id="writeComment_description" class="form-control" name="writeComment_description">';
                                echo '<input type="button" class="btn btn-success" value="제출" onclick="writeCommentSubmit();">';
                                echo '<input type="hidden" value="comment" name="choice">';
                                echo '<input type="hidden" value="'.$_GET['id'].'" name="post_id">';
                                echo '<input type="hidden" value="'.$_GET['page'].'" name="post_page">';
                                echo '</div>';
                                echo '</form>';
                            } else {
                                echoTopNav($active);
                            }
                        ?>

                        <div id="freeboardTable">
                            <table class="table table-condensed">
                                <tr class="info">
                                    <td width="47%">제목</td>
                                    <td width="15%">작성자</td>
                                    <td width="28%">시간</td>
                                    <td width="5%">댓글</td>
                                    <td width="5%">추천</td>
                                </tr>
                        <?php
                            if (isset($_GET['show'])) {
                                if ($_GET['show'] == "dailyBest") {
                                    $sql = "SELECT count(id) AS c FROM freeboard WHERE TIMESTAMPDIFF(SECOND, created, NOW()) < 86400";
                                } elseif ($_GET['show'] == "monthlyBest") {
                                    $sql = "SELECT count(id) AS c FROM freeboard WHERE TIMESTAMPDIFF(SECOND, created, NOW()) < 86400*30";
                                } else {
                                    echo '<script>alert("잘못된 접근입니다!!");</script><meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php">';
                                    exit;
                                }
                            } else {
                                $sql = "SELECT count(id) AS c FROM freeboard";
                            }
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $numOfPage = ceil($row['c']/$pageLimit);

                            if (isset($_GET['show'])) {
                                if ($_GET['show'] == "dailyBest") {
                                    $sql = "SELECT freeboard.id, title, freeboard.created, name, freeboard.author, best, count(comment.id) AS comment FROM freeboard LEFT JOIN student ON freeboard.author = student.id LEFT JOIN comment ON freeboard.id=comment.post GROUP BY freeboard.id HAVING TIMESTAMPDIFF(SECOND, created, NOW()) < 86400 ORDER BY best DESC, freeboard.created DESC, freeboard.id DESC LIMIT ".$pageLimit." OFFSET ".(($_GET['page']-1)*$pageLimit);
                                } elseif ($_GET['show'] == "monthlyBest") {
                                    $sql = "SELECT freeboard.id, title, freeboard.created, name, freeboard.author, best, count(comment.id) AS comment FROM freeboard LEFT JOIN student ON freeboard.author = student.id LEFT JOIN comment ON freeboard.id=comment.post GROUP BY freeboard.id HAVING TIMESTAMPDIFF(SECOND, created, NOW()) < 86400*30 ORDER BY best DESC, freeboard.created DESC, freeboard.id DESC LIMIT ".$pageLimit." OFFSET ".(($_GET['page']-1)*$pageLimit);
                                }
                            } else {
                                $sql = "SELECT freeboard.id, title, freeboard.created, name, freeboard.author, best, count(comment.id) AS comment FROM freeboard LEFT JOIN student ON freeboard.author = student.id LEFT JOIN comment ON freeboard.id=comment.post GROUP BY freeboard.id ORDER BY freeboard.created DESC, freeboard.id DESC LIMIT ".$pageLimit." OFFSET ".(($_GET['page']-1)*$pageLimit);
                            }
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr class="active">';
                                echo '<td><a href="/Freeboard/freeboard.php?page='.$_GET['page'].'&id='.$row['id'].'">'.htmlspecialchars($row['title']).'</a></td>';
                                echo '<td><a href="/Account/info.php?user_id='.$row['author'].'">'.htmlspecialchars($row['name']).'</a></td>';
                                echo '<td>'.htmlspecialchars($row['created']).'</td>';
                                echo '<td>'.$row['comment'].'</td>';
                                echo '<td>'.$row['best'].'</td>';
                                echo '</tr>';
                            }
                            echo '</table>';
                            echo '</div>';
                            echo '</div>';

                            echoBottomNav($active, $_GET['page'], $numOfPage);
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
