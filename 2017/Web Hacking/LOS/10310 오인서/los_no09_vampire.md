# Lord of SQL Injection No.9 - vampire

## 문제 출제 의도

str_replace()를 이해하고 우회하여 SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 이해

```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/\'/i', $_GET[id])) exit("No Hack ~_~"); 
  $_GET[id] = str_replace("admin","",$_GET[id]); 
  $query = "select id from prob_vampire where id='{$_GET[id]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id'] == 'admin') solve("vampire"); 
  highlight_file(__FILE__); 
?>
```
이 소스코드는 vampire 문제의 코드이다.
-----
```php
if(preg_match('/\'/i', $_GET[id])) exit("No Hack ~_~"); 
$_GET[id] = str_replace("admin","",$_GET[id]); 
$query = "select id from prob_vampire where id='{$_GET[id]}'";
echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
$result = @mysql_fetch_array(mysql_query($query)); 
if($result['id'] == 'admin') solve("vampire"); 
```

* preg_match로 '를 필터링해내 걸릴시 문제풀이에 실패한다.

* 받은 id 값이 admin이면 문제풀이에 성공한다.

* str_replace()함수를 이용해 admin 문자열을 빈 문자열로 교체한다.

### str_replace()

str_replace("찾을 문자열","바꿀 문자열","대상 문자열") 은 대상 문자열에서 대소문자를 구분하여 찾을 문자열을 찾아 바꿀 문자열로 바꾸어 준다.

## 문제 해결방안

1. str_replace는 대소문자를 구분하지만 SQL문은 구분하지 않아 …?id=ADMIN을 입력하면 문제가 해결된다.

2. str_replace를 이용하여 문제를 해결한다.

str_replace("admin","",$_GET[id])는 admin이 이어져있어야 치환이 되므로 aadmindmin과 같이 admin 문자열이 빈문자열로 바뀌어도 admin으로 완성되게끔 해주어 입력하면 문제가 해결된다.