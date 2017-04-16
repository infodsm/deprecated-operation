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

        if (isset($_POST['writePost_title']) && isset($_POST['writePost_description'])) {
            $title = mysqli_real_escape_string($conn, $_POST['writePost_title']);
            $description = mysqli_real_escape_string($conn, $_POST['writePost_description']);
            if ($_POST['isFileExist']=='true') {
                $fileUploadDir = "../Files/".$_SESSION['user_id']."/";
                for ($i=1; $i<=5; $i++) {
                    if ($_FILES['writePost_file'.$i]['type'] != "") {
                        $sql = "SELECT id FROM freeboard WHERE id IN (SELECT MAX(id) FROM freeboard)";
                        $result = mysqli_query($conn, $sql);
                        $row = mysqli_fetch_assoc($result);
                        $post_id = ((int)$row['id'] + 1);
                        $sql = "ALTER TABLE freeboard AUTO_INCREMENT = ".$post_id;
                        mysqli_query($conn, $sql);
                        $uploadFile = $fileUploadDir.$post_id.$i;
                        if (!is_dir($fileUploadDir)) {
                            mkdir($fileUploadDir, 0700, true);
                        }
                        if (!move_uploaded_file($_FILES['writePost_file'.$i]['tmp_name'], $uploadFile)) {
                            echo '<script>alert("파일 업로드 실패!!");</script><meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php">';
                        } else {
                            $description = "<img src=\'".$uploadFile."\' alt=\'".basename($_FILES['writePost_file'.$i]['name'])."\' width=\'100%\'>

".$description;
                        }
                    }
                }
                $sql = "INSERT INTO freeboard (title, description, author, created, file) VALUES('".$title."', '".$description."', '".$_SESSION['user_id']."', now(), 1)";
            } else {
                $sql = "INSERT INTO freeboard (title, description, author, created) VALUES('".$title."', '".$description."', '".$_SESSION['user_id']."', now())";
            }

            mysqli_query($conn, $sql);

            echo '<meta http-equiv="refresh" content="0;url=/Freeboard/freeboard.php">';
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
                        <div id="articleHeader"><h3>자유게시판</h3></div>
                        <div id="articleText">
                            <form id="writePostForm" enctype="multipart/form-data" action="/Freeboard/writePost.php" method="post">
                                <div class="form-group">
                                    <label for="writePost_title">제목</label><br>
                                    <input id="writePost_title" type="text" class="form-control" name="writePost_title" >
                                </div>
                                <div class="form-group">
                                    <label for="writePost_description">본문</label><br>
                                    <textarea id="writePost_description" type="text" class="form-control" name="writePost_description"></textarea>
                                </div>
                                <div class="form-group" id="uploadFileOn">
                                    <input type="button" class="form-control" onclick="document.getElementById('uploadFileForm').hidden=false; document.getElementById('uploadFileOff').hidden=false; document.getElementById('uploadFileOn').hidden=true; document.getElementById('isFileExist').value='true';" value="파일 첨부하기">
                                </div>
                                <div class="form-group" id="uploadFileOff" hidden="true">
                                    <input type="button" class="form-control" onclick="document.getElementById('uploadFileForm').hidden=true; document.getElementById('uploadFileOff').hidden=true; document.getElementById('uploadFileOn').hidden=false; document.getElementById('isFileExist').value='false';" value="파일 첨부 안하기">
                                </div>
                                <div class="form-group" id='uploadFileForm' hidden="true">
                                    <label for="writePost_file">이미지 파일 첨부</label><br>
                                    <input id="writePost_file" type="file" class="form-control" name="writePost_file5">
                                    <input id="writePost_file" type="file" class="form-control" name="writePost_file4">
                                    <input id="writePost_file" type="file" class="form-control" name="writePost_file3">
                                    <input id="writePost_file" type="file" class="form-control" name="writePost_file2">
                                    <input id="writePost_file" type="file" class="form-control" name="writePost_file1">
                                    <input id="isFileExist" type="hidden" name="isFileExist" value='false'>
                                </div>
                                <hr>
                                <div class="btn-group btn-group-lg">
                                    <input type="button" class="btn btn-success" value="완료" onclick="writePostSubmit();">
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
