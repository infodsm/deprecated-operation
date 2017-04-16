<?php
    require_once('config.php');
    // 코드 중복 방지를 위해 만든 php함수들

    function echoHead()
    {
        echo '
        <head>
            <meta charset="utf-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1">

            <title>DSM Infosec Club - Info</title>

            <link rel="stylesheet" href="/style.css?ver=1.5">
            <link rel="shortcut icon" href="/images/pavicon128.jpg">
            <link href="/bootstrap-3.3.4-dist/css/bootstrap.min.css?ver=0.1" rel="stylesheet">
            <script type="text/javascript" src="/script.js?ver=0.1"></script>
        </head>
        ';
    }

    function echoHeader()
    {
        echo '
            <header>
                <div class="jumbotron">
                    <a href="/"><img src="/images/logo.png" alt="DSM Info 로고" class="img-rounded img-responsive center-block" id="logo"></a>
                    <h2 class="text-center head"><a id="title" href="/">DSM Info Club - Info</a></h2>
                    <h4 class="text-center head">대덕소프트웨어마이스터고등학교 동아리 Info</h4>
                </div>
            </header>
            ';
    }

    function echoNav($logined, $active)
    {
        echo '<nav><div class="col-md-3">';
        echo '<div id="navLogin">';
        echo '<div>';
        if ($logined) {
            $grade = (int)substr($_SESSION['user_id'], 0, 1);
            $class = (int)substr($_SESSION['user_id'], 1, 2);
            $number = (int)substr($_SESSION['user_id'], 3, 2);

            echo '<p id="welcome">'.$grade.'학년 '.$class.'반 '.$number.'번</p>
            <p id="welcome">'.$_SESSION['user_name'].' 님, 환영합니다.</p>
            <div id="navbutton"><input type="button" class="btn btn-default" name="logout" value="logout" onclick="location.href=\'/Account/logout.php\'"></div>';
        } else {
            echo '<div id="navbutton"><input type="button" class="btn btn-default" name="login" value="login" onclick="location.href=\'/Account/login.php\'"></div>';
        }
        echo '</div>';
        echo '</div>';

        echo '<ul class="nav nav-pills nav-stacked">';
        if ($active == "myInfo") {
            echo '<li class="active"><a href="/Account/myInfo.php">내 정보</a></li>';
        } else {
            echo '<li><a href="/Account/myInfo.php">내 정보</a></li>';
        }
        if ($active == "freeboard") {
            echo '<li class="active"><a href="/Freeboard/freeboard.php">자유게시판</a></li>';
        } else {
            echo '<li><a href="/Freeboard/freeboard.php">자유게시판</a></li>';
        }
        if ($active == "assignment") {
            echo '<li class="active"><a href="/Assignment/assignment.php">과제</a></li>';
        } else {
            echo '<li><a href="/Assignment/assignment.php">과제</a></li>';
        }
        echo '</ul></div></nav>';
    }

    function echoTopNav($active, $enable=false, $id=0, $page=0)
    {
        if ($active == "freeboard") {
            echo '<div id="freeboardButtonGroup" class="btn-group">';
            if ($enable) {
                echo '<input type="button" id="editPost" class="btn btn-info" value="수정" onclick="location.href=\'/Freeboard/editPost.php?page='.$page.'&id='.$id.'\'">';
                echo '<input type="button" id="deletePost" class="btn btn-danger" value="삭제" onclick="if(confirm(\'정말 삭제하시겠습니까?\')){location.href=\'/Freeboard/deletePost.php?id='.$id.'\'}">';
            }
            echo '<input type="button" id="writePost" class="btn btn-default" value="글쓰기" onclick="location.href=\'/Freeboard/writePost.php\'">';
            echo '</div>';
        } elseif ($active == "assignment") {
            echo '<div id="assignmentButtonGroup" class="btn-group">';
            if ($enable) {
                echo '<input type="button" id="refreshAssignment" class="btn btn-default" value="갱신" onclick="location.href=\'/Assignment/refreshAssignment.php\'">';
                echo '<input type="button" id="writeAssignment" class="btn btn-default" value="추가" onclick="location.href=\'/Assignment/writeAssignment.php\'">';
                if ($id!=0) {
                    echo '<input type="button" id="editAssignment" class="btn btn-info" value="수정" onclick="location.href=\'/Assignment/editAssignment.php?page='.$page.'&id='.$id.'\'">';
                    echo '<input type="button" id="deleteAssignment" class="btn btn-danger" value="삭제" onclick="if(confirm(\'정말 삭제하시겠습니까?\')){location.href=\'/Assignment/deleteAssignment.php?id='.$id.'\'}">';
                    echo '<input type="button" id="back" class="btn btn-default" value="돌아가기" onclick="window.history.back();">';
                } else {
                    echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="전체 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=all\'">';
                    echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="진행 중인 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=active\'">';
                    echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="완료된 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=closed\'">';
                }
            } else {
                echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="전체 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=all\'">';
                echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="진행 중인 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=active\'">';
                echo '<input type="button" id="showAllAssignment" class="btn btn-default" value="완료된 과제 보기" onclick="location.href=\'/Assignment/assignment.php?show=closed\'">';
            }
            echo '</div>';
        }
    }

    function echoBottomNav($active, $nowPage, $number)
    {
        $page = ((ceil((float)$nowPage/5)-1)*5)+1;
        if ($page-1<1) {
            echo '<ul id="pagination" class="pagination"><li class="disabled"><a href="#"><span>&laquo;</span></a></li>';
        } else {
            if ($active=="freeboard") {
                echo '<ul id="pagination" class="pagination"><li><a href="/Freeboard/freeboard.php?page='.($page-1).'"><span>&laquo;</span></a></li>';
            } elseif ($active=="assignment") {
                echo '<ul id="pagination" class="pagination"><li><a href="/Assignment/assignment.php?page='.($page-1).'"><span>&laquo;</span></a></li>';
            }
        }
        for ($i=0; $i < 5; $i++) {
            if ($page+$i>$number) {
                echo '<li class="disabled"><a href="#">'.($page+$i).'</a></li>';
            } elseif ($nowPage == $page+$i) {
                echo '<li class="active"><a href="#">'.($page+$i).'</a></li>';
            } else {
                if ($active=="freeboard") {
                    echo '<li><a href="/Freeboard/freeboard.php?page='.($page+$i).'">'.($page+$i).'</a></li>';
                } elseif ($active=="assignment") {
                    echo '<li><a href="/Assignment/assignment.php?page='.($page+$i).'">'.($page+$i).'</a></li>';
                }
            }
        }
        if ($page+5>$number) {
            echo '<li class="disabled"><a href="#"><span>&raquo;</span></a></li></ul>';
        } else {
            if ($active=="freeboard") {
                echo '<li><a href="/Freeboard/freeboard.php?page='.($page+5).'"><span>&raquo;</span></a></li></ul>';
            } elseif ($active=="assignment") {
                echo '<li><a href="/Assignment/assignment.php?page='.($page+5).'"><span>&raquo;</span></a></li></ul>';
            }
        }
    }
