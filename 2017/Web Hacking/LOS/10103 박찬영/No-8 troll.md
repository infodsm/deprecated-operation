Lord of SQL Injection No.8 - troll
=============
```
<?php  
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/\'/i', $_GET[id])) exit("No Hack ~_~");
  if(@ereg("admin",$_GET[id])) exit("HeHe");
  $query = "select id from prob_troll where id='{$_GET[id]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id'] == 'admin') solve("troll");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------
preg_match로 인해 싱글쿼터가 $_GET[id]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.
ereg로 인해 admin이 $_GET[id]에 들어있으면 HeHe가 뜨면서 문제 풀이에 실패하게 된다._

문제 풀이 법
-------------
1. ereg부분 살펴보기
ereg에서 admin을 찾을때 대소문자를 구분해서 찾게된다.
id값에 Admin이나 ADMIN과 같은 대문자가 섞어서 보내면 ereg부분에 걸리지 않고 sql문이 전달된다.
php에서는 대소문자를 구분하지만 Admin과 같이 sql문으로 보낼수 있는 이유로는 sql에서는 대소문자를 구분하지 않기 때문이다.
