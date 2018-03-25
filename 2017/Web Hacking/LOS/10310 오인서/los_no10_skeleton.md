# Lord of SQL Injection No.10 - skeleton

## 문제 출제 의도

SQL Injection에 걸림돌이 되는 것을 무력화 시키는 방법을 아는지 확인한다.

## 소스 코드 분석
```php
<?php 
include "./config.php"; 
login_chk(); 
dbconnect(); 
if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
$query = "select id from prob_skeleton where id='guest' and pw='{$_GET[pw]}' and 1=0"; 
echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
$result = @mysql_fetch_array(mysql_query($query)); 
if($result['id'] == 'admin') solve("skeleton"); 
highlight_file(__FILE__); 
?>
```
이 소스코드는 skeleton의 문제 소스코드이다.
-----
```php
if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
$query = "select id from prob_skeleton where id='guest' and pw='{$_GET[pw]}' and 1=0"; 
echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
$result = @mysql_fetch_array(mysql_query($query)); 
if($result['id'] == 'admin') solve("skeleton"); 
```

* preg_match 함수로 "prob","_",".","()" 를 필터링해 걸릴시 문제풀이에 실패한다.

* 쿼리에 and 1=0으로 인해 항상 거짓이 되어 and 1=0부분을 무력화 시켜야한다.

* id가 admin값이 될 때 문제 풀이에 성공한다.

## 문제 해결방안

* 주석문을 이용한다.

    Mysql에서는 #과 -- -과 같은 주석문 뒤에 문자열을 무시하게 된다.
    
    이를 이용하여 1=0 부분을 무시시켜 무력화시키게 되면 문제가 해결된다.

    …?pw=' or id='admin'-- -이나 …?pw=' or id='admin'#과 같은 방법으로 뒤의 문장을 무력화 시켜 문제를 해결한다.