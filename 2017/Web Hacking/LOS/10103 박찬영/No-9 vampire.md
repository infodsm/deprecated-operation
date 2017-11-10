Lord of SQL Injection No.9 - vampire
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/\'/i', $_GET[id])) exit("No Hack ~_~");
  $_GET[id] = str_replace("admin","",$_GET[id]);
  $query = "select id from prob_vampire where id='{$_GET[id]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id'] == 'admin') solve("vampire");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------
preg_match로 인해 싱글쿼터가 $_GET[id]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.
admin이라는 문자열이 있으면 str_replace의해 공백으로 치환된다.
DB에 전달된 아이디가 admin이면 풀린다.

문제 풀이 법
-------------
1)str_replace 우회
str_replace는 preg_match랑 비슷한 역할을 하는데 찾아서 특정 숫자를 반환하는 것이 아니라 찾아서
특정한 문자열로 바꾸어준다.
str_replace는 대소문자를 구분하기 때문에 admin에서 철자 하나만 대문자가 되면 str_replace를
통과할 수 있다.
sql문에서는 대소문자를 구분하지 않기때문에 admin=Admin이 되어 문제가 풀리게된다.
