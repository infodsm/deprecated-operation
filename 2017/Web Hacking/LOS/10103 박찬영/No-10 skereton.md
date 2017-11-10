Lord of SQL Injection No.10 - skereton
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  $query = "select id from prob_skeleton where id='guest' and pw='{$_GET[pw]}' and 1=0";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id'] == 'admin') solve("skeleton");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------

preg_match로 인해 . ()가 $_GET[pw]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.

DB에 전달된 id가 admin이면 문제가 풀리게 된다.

문제 풀이 법
-------------

5번째 줄에서 pw='{$_GET[pw]}' and 1=0"가 있는데 이 부분에서 pw에 어떤 값 pw='or 1=1을 입력하면 뒤에 and1=0이 실행되어서
실행 되지 않는다.

1. 주석처리

뒤에 and 1=0이 실행되지 않도록 주석처리를 해준다.

pw=' or id='admin'-- -
