function loginSubmit() {
    id = document.getElementById('user_id').value;
    pw = document.getElementById('user_pw').value;

    if (id == "" && pw == "") {
        alert("학번과 Password를 입력해 주세요.");
    } else if (id == "") {
        alert("학번을 입력해 주세요.");
    } else if (pw == "") {
        alert("Password를 입력해 주세요.");
    } else {
        document.getElementById('loginForm').submit();
    }
}

function joinSubmit() {
    id = document.getElementById('join_user_id').value;
    pw = document.getElementById('join_user_pw').value;
    name = document.getElementById('join_user_name').value;
    msg = document.getElementById('join_user_msg').value;
    if (document.getElementById('join_user_gender_M').checked) {
        gender = "남자";
    } else if (document.getElementById('join_user_gender_F').checked) {
        gender = "여자";
    } else {
        gender = "";
    }

    errorMsg = "";
    existError = false;

    if (id == "") {
        errorMsg += "학번을 입력해 주세요.";
        existError = true;
    }
    if (pw == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "Password를 입력해 주세요.";
        existError = true;
    }
    if (name == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "이름을 입력해 주세요.";
        existError = true;
    }
    if (msg == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "가입인사를 입력해 주세요.";
        existError = true;
    }
    if (gender == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "성별을 선택해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        if (confirm("ID : " + id + "\nPW : " + pw + "\n이름 : " + name + "\n성별 : " + gender + "\n이 정보가 맞습니까?")) {
            document.getElementById('joinForm').submit();
        }
    }
}

function editMyInfoSubmit() {
    id = document.getElementById('edit_user_id').value;
    pw = document.getElementById('edit_user_pw').value;
    name = document.getElementById('edit_user_name').value;
    favorite = document.getElementById('edit_user_favorite').value;
    msg = document.getElementById('edit_user_msg').value;
    if (document.getElementById('edit_user_gender_M').checked) {
        gender = "남자";
    } else if (document.getElementById('edit_user_gender_F').checked) {
        gender = "여자";
    } else {
        gender = "";
    }

    errorMsg = "";
    existError = false;

    if (id == "") {
        errorMsg += "학번을 입력해 주세요.";
        existError = true;
    }
    if (pw == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "Password를 입력해 주세요.";
        existError = true;
    }
    if (name == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "이름을 입력해 주세요.";
        existError = true;
    }
    if (favorite == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "가장 좋아하는 것을 입력해 주세요.";
        existError = true;
    }
    if (msg == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "가입인사를 입력해 주세요.";
        existError = true;
    }
    if (gender == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "성별을 선택해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        if (confirm("ID : " + id + "\nPW : " + pw + "\n이름 : " + name + "\n성별 : " + gender + "\n이 정보로 수정됩니다.")) {
            document.getElementById('editMyInfoForm').submit();
        }
    }
}

function writePostSubmit() {
    title = document.getElementById('writePost_title').value;
    description = document.getElementById('writePost_description').value;

    errorMsg = "";
    existError = false;

    if (title == "") {
        errorMsg += "제목을 입력해 주세요.";
        existError = true;
    }
    if (description == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "본문을 입력해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        document.getElementById('writePostForm').submit();
    }
}

function writeCommentSubmit() {
    description = document.getElementById('writeComment_description').value;

    if (description == "") {
        alert("댓글을 입력해 주세요.");
    } else {
        document.getElementById('writeCommentForm').submit();
    }
}

function editCommentSubmit() {
    description = document.getElementById('editComment_description').value;

    if (description == "") {
        alert("댓글을 입력해 주세요.");
    } else {
        document.getElementById('editCommentForm').submit();
    }
}


function writeAssignmentSubmit() {
    subject = document.getElementById('writeAssignment_subject').value;
    title = document.getElementById('writeAssignment_title').value;
    description = document.getElementById('writeAssignment_description').value;
    month = document.getElementById('writeAssignment_month').value;
    day = document.getElementById('writeAssignment_day').value;

    errorMsg = "";
    existError = false;

    if (subject == "") {
        errorMsg += "과목을 입력해 주세요.";
        existError = true;
    }
    if (title == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "제목을 입력해 주세요.";
        existError = true;
    }
    if (description == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "내용을 입력해 주세요.";
        existError = true;
    }
    if (month == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "기한 월을 입력해 주세요.";
        existError = true;
    }
    if (day == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "기한 일을 입력해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        document.getElementById('writeAssignmentForm').submit();
    }
}

function editAssignmentSubmit() {
    subject = document.getElementById('editAssignment_subject').value;
    title = document.getElementById('editAssignment_title').value;
    description = document.getElementById('editAssignment_description').value;
    month = document.getElementById('editAssignment_month').value;
    day = document.getElementById('editAssignment_day').value;

    errorMsg = "";
    existError = false;

    if (subject == "") {
        errorMsg += "과목을 입력해 주세요.";
        existError = true;
    }
    if (title == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "제목을 입력해 주세요.";
        existError = true;
    }
    if (description == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "내용을 입력해 주세요.";
        existError = true;
    }
    if (month == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "기한 월을 입력해 주세요.";
        existError = true;
    }
    if (day == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "기한 일을 입력해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        document.getElementById('editAssignmentForm').submit();
    }
}

function editPostSubmit() {
    title = document.getElementById('editPost_title').value;
    description = document.getElementById('editPost_description').value;

    errorMsg = "";
    existError = false;

    if (title == "") {
        errorMsg += "제목을 입력해 주세요.";
        existError = true;
    }
    if (description == "") {
        if (existError) {
            errorMsg += "\n";
        }
        errorMsg += "본문을 입력해 주세요.";
        existError = true;
    }

    if (existError) {
        alert(errorMsg);
    } else {
        document.getElementById('editPostForm').submit();
    }
}

function deleteAccount() {
    if (confirm("정말 회원탈퇴 하시겠습니까?")) {
        location.href = "/Account/deleteAccount.php";
    }
}
