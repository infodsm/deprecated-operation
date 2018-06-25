# Lord of SQL Injection No.20 - dragon

## 문제 출제 의도

주석을 우회할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
  $query = "select id from prob_dragon where id='guest'# and pw='{$_GET[pw]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
  if($result['id'] == 'admin') solve("dragon");
  highlight_file(__FILE__); 
?>
```
* preg_match()로 pw라는 문자열의 'prob','_','.','()' 를 필터링 해낸다..

* '$query = "select id from prob_dragon where id='guest'# and pw='{$_GET[pw]}'";'에서 #으로 'guest' 뒤부터 주석처리 한다.

* id의 값을 admin으로 만들어 주면 문제가 해결된다.

## 문제 해결 방안

* # 주석은 한줄만을 주석처리한다.

* 다음줄로 넘기게 된다면 주석처리에서 벗어나겠지?

* 라인 피드가 있네 ? %0a !!

* …?pw=%a를 하면 다음줄이니까 우회가 되겠지?

* …?pw=%a and pw='123asd1461' or id='admin' 이런 식으로 하면 id='guest' and pw='123asd1461' or id='admin'과 같은 역할이니까 guest는 무력화되고 id는 admin인게 되어 문제가 해결되네 !!