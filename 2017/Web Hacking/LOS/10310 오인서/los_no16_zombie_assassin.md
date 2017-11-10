# Lord of SQL Injection No.16 - zombie_assassin

## 문제 출제 의도

ereg를 우회할 수 있는지 확인한다.

## 소스 코드 분석 
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/\\\|prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); 
  if(preg_match('/\\\|prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
  if(@ereg("'",$_GET[id])) exit("HeHe"); 
  if(@ereg("'",$_GET[pw])) exit("HeHe"); 
  $query = "select id from prob_zombie_assassin where id='{$_GET[id]}' and pw='{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) solve("zombie_assassin"); 
  highlight_file(__FILE__); 
?>
```

* preg_match() 함수를 통해 id와 pw의 문자열에서 '\','prob','_','.','()'을 필터링한다.

* ereg() 함수를 통해 id와 pwㅇ에서 '를 필터링 해낸다.

## ereg() 함수
ereg()는 패턴으로 문자열을 검사하게되는데
 
패턴결과가 참이면 true를 반환하고 거짓이면 false 를 반환하게된다.

ereg() 함수는 NULL 값을 만나게 되면 함수 실행을 종료하게 된다.
-----

## 문제 해결 방안
* ereg() 함수 우회하는 방안을 이용한다.

ereg()함수는 NULL값을 만나게되면 함수를 종료하기 때문에 id를 입력할때 NULL값에 해당하는 %00을 입력하면 함수가 바로 종료된다. 즉, id=%00'로 함수를 무력화시킨후에 id값이 항상 참이 성립하게 만들어주게 되면 문제 풀이가 성공한다. 

id=%00'|| 1 하며 뒤의 pw문을 무력화 시키기 위해서 주석을 사용하여 …?id=%00'||1 %23하게 되면 문제풀이에 성공한다.