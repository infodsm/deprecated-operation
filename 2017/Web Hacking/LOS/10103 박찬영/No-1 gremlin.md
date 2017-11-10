Lord of SQL Injection No.1 - gremlin
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); // do not try to attack another table, database!
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  $query = "select id from prob_gremlin where id='{$_GET[id]}' and pw='{$_GET[pw]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id']) solve("gremlin");
  highlight_file(__FILE__);
?>
```
위 코드에서 알수 있는 것
-------------
4번째와 5번째줄 if문의 preg_match에서 id와 pw에 . 이나 ()가 들어가있으며 No Hack ~_~라고 뜨고 문제 풀이에 실패하게되는걸 알수있다.
DB에 저장되있는 값인 $result[id]에 어떠한 값이라도 들어가 있으면 문제를 풀수있다.

문제풀이 법
-------------
1.http://los.eagle-jump.org/gremlin_bbc5af7bed14aa50b84986f2de742f31.php?id=123'or 1=1-- -
그냥 읽어보자면 아이디의 값이 123이거나 1=1 곧 참이라는 것이다.
뒤에 -- -는 뒤에 올 pw 및 다른 값들을 입력하지 않아도 되도록 주석처리를 하는것이다.

  *주석처리 법 --%20(%20은 공백), #,/**/

  id=123 or 1=1-- -이 아니라 id=123' 싱글 쿼터로 닫아 줌으로써 하나의  id 값을 마무리해주면 sql문을 입력할 수 있게되는데
  or연산은 앞에 값이 틀리더라도 뒤의 값이 참이면 참이게 되는 연산으로 or연산을 써줌으로 $result[id]가 참이 되어 문제가 풀리게된다.

2.http://los.eagle-jump.org/gremlin_bbc5af7bed14aa50b84986f2de742f31.php?id=123'or%20'123'%20-- -
이번 방법은 위의 방법과 동일하지만 '0'이 아닌 어떠한 문자열이 들어오게되면 이 값은 참으로 받아들여지게되어 문제가 풀리게된다.
