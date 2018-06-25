# Lord of SQL Injection No.18 - nightmare

## 문제 출제 의도

주석을 필터링 하는것을 우회하고 SQL auto casting을 이해하였는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)|#|-/i', $_GET[pw])) exit("No Hack ~_~"); 
  if(strlen($_GET[pw])>6) exit("No Hack ~_~"); 
  $query = "select id from prob_nightmare where pw=('{$_GET[pw]}') and id!='admin'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) solve("nightmare"); 
  highlight_file(__FILE__); 
?>
```

* preg_match 에서 'prob','_','.','()'와 각종 주석인 '#','-' 을 필터링 한다.

* strlen() 함수로 문자열의 길이를 검사하여 6이상이면 'No Hack ~_~' 을 출력하며 문제 풀이에 실패한다.

## 문제 해결 방안

* pw = ')로 괄호를 닫아준다.

* SQL auto casting을 이용한다
### SQL auto casting
 
  * My SQL에서는 문자열 자료형에서 숫자가 가장 앞에 나올경우 숫자부터 문자가 나오기 전까지 숫자로 변환된다.

  * 숫자가 가장 앞에 나오지 않을 경우 0으로 변환된다.

pw=')=0을 하게되면 숫자가 나오지 않았으므로 0으로 변환되는데 =0으로 참을 만들어낸다. 그리고 뒤의 SQL 문을 무력화 시키기 위하여 주석이 필요하다

이때 주석이 필터링 되어있는데 ;%00도 주석의 역할을 하는데 막혀있지 않았다.

따라서 …?pw=')=0 ;%00 에서 %00의 경우 눌문자이기에 문자열의 길이에 포함되지 않아 6글자를 넘기지 않고 문제풀이에 성공한다.