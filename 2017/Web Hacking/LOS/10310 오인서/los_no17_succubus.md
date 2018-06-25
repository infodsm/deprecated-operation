# Lord of SQL Injection No.17 - succubus

## 문제 출제 의도

SQL 문을 이스케이프 문자로 조작하는 방법을 아는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
  if(preg_match('/\'/i', $_GET[id])) exit("HeHe"); 
  if(preg_match('/\'/i', $_GET[pw])) exit("HeHe"); 
  $query = "select id from prob_succubus where id='{$_GET[id]}' and pw='{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) solve("succubus"); 
  highlight_file(__FILE__); 
?>
```
* preg_match 에서 id와 pw의 문자열 중 'prob','_','.','()'를 필터링 해낸다.

* preg_match 에서 id와 pw의 문자열 중 '\''를 필터링 해낸다.

* 문제 성공 여부는 $result['id'] 의 값이 0이 아니여야한다.

## 문제 해결 방안

'를 필터링 해내고 있지만 \를 필터링 해내고 있지 않다. SQL문에서 '를 \로 무력화시켜 '의 원 기능을 없애서 문제를 푼다.

…?id=\를 해내면 select id from prob_succubus where id='\' and pw='가 되는데 여기서 id=' 에서 '와 pw=' 에서 '가 하나의 문자열로 인식 되기때문에 pw의 값에서 or 1=1과 같이 언제나 참을 만들어주는 문자를 입력해줄시에 문제는 해결된다.

…?id=\ & pw = or 1=1 -- -