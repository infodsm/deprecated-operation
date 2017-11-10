# Lord of SQL Injection No.14 - giant

## 문제 풀이 의도

SQL문을 이해하고 공백을 대체할 수 있는 방안들을 안다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(strlen($_GET[shit])>1) exit("No Hack ~_~"); 
  if(preg_match('/ |\n|\r|\t/i', $_GET[shit])) exit("HeHe"); 
  $query = "select 1234 from{$_GET[shit]}prob_giant where 1"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result[1234]) solve("giant"); 
  highlight_file(__FILE__); 
?>
```

* strlen() 함수에서 GET 방식으로 입력한 문자열의 길이가 1이상이 넘으면 안된다는 것을 알 수 있다.

* preg_match() 함수에서 ' ','\n','\r','\t'를 필터링해낸다,

## 문제 해결방안

공백의 길이가 한칸을 넘기는 우회방안이면 안된다. 즉, 공백의 길이가 1인 방법으로 우회시켜야 한다.

%20,%0A,%0D,%09가 필터링 되어있기 때문에 %0B나 %0C를 이용해주면 문제가 쉽게 해결된다.