# Lord of SQL Injection No.8 - troll

## 문제 출제 의도

admin 문자열을 직접적으로 필터링하는 ereg() 함수에 필터링 당하지 않고 admin을 id에 전달할 수 있는지 확인한다.

## 소스 코드 분석
```php
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
이것이 troll 문제의 소스 코드 이다.
-----
```php
if(preg_match('/\'/i', $_GET[id])) exit("No Hack ~_~");
if(@ereg("admin",$_GET[id])) exit("HeHe");
```
* preg_match 함수에서 '만을 잡아낸다.

* ereg 함수로 admin을 필터링 해내 입력값이 admin을 직접 입력하면 잡아낸다.

## 문제 해결방안

* SQL 문의 특징인 대소문자 구분x 를 이용한다.

SQL 문은 대소문자를 구분하지 않는다. 하지만 ereg()함수는 구분하므로 …?id=ADMIN을 입력하게 되면 문제가 해결된다.

