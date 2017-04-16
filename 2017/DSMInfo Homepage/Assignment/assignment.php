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
    } else {
        echo '<script>alert("로그인 후 이용해주세요!!");</script>
        <meta http-equiv="refresh" content="0;url=/Account/login.php">';
        exit;
    }

    if (!isset($_GET['page'])) {
        $_GET['page'] = 1;
    }
    if (!isset($_GET['show'])) {
        $_GET['show']="all";
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
                            echo '<div id="articleText">';

                            if (!empty($_GET['id'])) {
                                if (in_array($_SESSION['user_id'], $assignmentManager)) {
                                    echoTopNav($active, true, $_GET['id'], $_GET['page']);
                                } else {
                                    echoTopNav($active);
                                }
                                $sql = "SELECT title, description, untildate, subject FROM assignment WHERE id=".$_GET['id'];
                                $result = mysqli_query($conn, $sql);
                                $row = mysqli_fetch_assoc($result);

                                echo '<div id="assignmentText" class="panel panel-default">';
                                echo '<div class="panel-heading"><h3 id="assignmentTitle">'.$row['title'].'</h3>';
                                echo '<p id="assignmentName">과목 : '.htmlspecialchars($row['subject']).'</p>';
                                echo '<p id="assignmentCreated">기한 : '.date("Y년 m월 d일 D", strtotime($row['untildate'])).'</p>';
                                echo '</div>';
                                echo '<div class="panel-body">';
                                echo '<pre id="assignmentDescription">'.htmlspecialchars($row['description']).'</pre>';
                                echo '</div>';
                                echo '</div>';
                            } else {
                                if (in_array($_SESSION['user_id'], $assignmentManager)) {
                                    echoTopNav($active, true);
                                } else {
                                    echoTopNav($active);
                                }
                            }

                            echo '<div id="assignmentTable">';
                            echo '<table class="table table-condensed">';
                            echo '<tr class="info">';
                            echo '<td width="15%">과목</td>';
                            echo '<td width="45%">제목</td>';
                            echo '<td width="30%">기한</td>';
                            echo '<td width="10%">마감</td>';
                            echo '</tr>';

                            if ($_GET['show']=="closed") {
                                $sql = "SELECT count(id) AS c FROM assignment WHERE closed='1'";
                            } elseif ($_GET['show']=="active") {
                                $sql = "SELECT count(id) AS c FROM assignment WHERE closed='0'";
                            } else {
                                $sql = "SELECT count(id) AS c FROM assignment";
                            }
                            $result = mysqli_query($conn, $sql);
                            $row = mysqli_fetch_assoc($result);
                            $numOfPage = ceil($row['c']/$pageLimit);

                            $sql = "SELECT * FROM assignment ORDER BY closed ASC, untildate ASC, subject ASC, title ASC LIMIT ".$pageLimit." OFFSET ".(($_GET['page']-1)*$pageLimit);
                            $result = mysqli_query($conn, $sql);
                            while ($row = mysqli_fetch_assoc($result)) {
                                if ($_GET['show']=="all" || ($_GET['show']=="active" && $row['closed']==0) || ($_GET['show']=="closed" && $row['closed']==1)) {
                                    echo '<tr class="active">';
                                    echo '<td>'.htmlspecialchars($row['subject']).'</td>';
                                    echo '<td><a href="/Assignment/assignment.php?page='.$_GET['page'].'&id='.$row['id'].'">'.htmlspecialchars($row['title']).'</a></td>';
                                    echo '<td>'.date("Y년 m월 d일 D", strtotime($row['untildate'])).'</td>';
                                    if ($row['closed']) {
                                        echo '<td>O</td>';
                                    } else {
                                        echo '<td>X</td>';
                                    }
                                    echo '</tr>';
                                }
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
