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
        if (isset($_POST['editComment_description']) && isset($_POST['post_id'])) {
            $sql = "SELECT author FROM comment WHERE post='".$_POST['post_id']."' AND id='".$_POST['comment_id']."'";
            $result = mysqli_query($conn, $sql);
            $row = mysqli_fetch_assoc($result);
            if ($row['author']==$_SESSION['user_id']) {
                $comment = mysqli_real_escape_string($conn, $_POST['editComment_description']);
                $sql = "UPDATE comment SET description='".$comment."' WHERE id='".$_POST['comment_id']."'";
                mysqli_query($conn, $sql);
                header('Location: /Freeboard/freeboard.php?id='.$_POST['post_id'].'&page='.$_POST['page']);
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
                        <?php
                            echo '<div id="articleHeader"><h3>자유게시판</h3></div>';
                            echo '<div id="articleText">';
                            if (isset($_GET['id'])) {
                                $sql = "SELECT freeboard.id, title, description, created, name FROM freeboard LEFT JOIN student ON freeboard.author = student.id WHERE freeboard.id=".$_GET['id'];
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                if ($row['name'] == $_SESSION['user_name']) {
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
                                echo '<pre id="fbDescription">'.htmlspecialchars($row['description']).'</pre>';
                                echo '</div>';
                                echo '</div>';

                                $sql = "SELECT comment.id, author, name, description, created FROM comment LEFT JOIN student ON comment.author = student.id WHERE post='".$_GET['id']."'";
                                $result = mysqli_query($conn, $sql);
                                if ($result -> num_rows == 0) {
                                    echo '<div id="noComments"><p>댓글 없음!</p></div>';
                                } else {
                                    echo '<table id="freeboardCommentTable" class="table table-condensed table-bordered">';
                                    while ($row = mysqli_fetch_assoc($result)) {
                                        echo '<tr>';
                                        echo '<td>
                                            <p style="text-align: right;">
                                            작성자 : '.htmlspecialchars($row['name']).' / 작성 일시 : '.$row['created'].'
                                            </p>
                                            <form id="editCommentForm" action="/Freeboard/editComment.php" method="post" style="text-align: left;">
                                                <input type="text" id="editComment_description" name="editComment_description" class="form-control" value="'.htmlspecialchars($row['description']).'">
                                                <input type="button" class="btn btn-success" value="제출" onclick="editCommentSubmit();">
                                                <input type="hidden" value="'.$_GET['id'].'" name="post_id">
                                                <input type="hidden" value="'.$_GET['comment_id'].'" name="comment_id">
                                                <input type="hidden" value="'.$_GET['page'].'" name="page">
                                            </form>';
                                        if ($_SESSION['user_id'] == $row['author']) {
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
                                echo '<input type="hidden" value="'.$_GET['id'].'" name="post_id">';
                                echo '<input type="hidden" value="'.$_GET['page'].'" name="post_page">';
                                echo '</div>';
                                echo '</form>';
                            } else {
                                echoTopNav($active);
                            }

                            echo '<div id="freeboardTable">';
                            echo '<table class="table table-condensed">';
                            echo '<tr class="info">';
                            echo '<td width="55%">제목</td>';
                            echo '<td width="15%">작성자</td>';
                            echo '<td width="30%">시간</td>';
                            echo '</tr>';

                            $sql = "SELECT count(id) AS c FROM freeboard";
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $numOfPage = ceil($row['c']/$pageLimit);

                            $sql = "SELECT freeboard.id, title, created, name, author FROM freeboard LEFT JOIN student ON freeboard.author = student.id ORDER BY created DESC, id DESC LIMIT ".$pageLimit." OFFSET ".(($_GET['page']-1)*$pageLimit);
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo '<tr class="active">';
                                echo '<td><a href="/Freeboard/freeboard.php?page='.$_GET['page'].'&id='.$row['id'].'">'.htmlspecialchars($row['title']).'</a></td>';
                                echo '<td><a href="/Account/info.php?user_id='.$row['author'].'">'.htmlspecialchars($row['name']).'</a></td>';
                                echo '<td>'.htmlspecialchars($row['created']).'</td>';
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
