# Lord of SQL Injection No.2 - cobolt

## 문제 출제의도

SQL Injection에서 특정 값을 추출해 내기위한 방법을 아는지 확인한다.

## 소스 코드 분석
```php
    <?php
        include "./config.php"; 
        login_chk();
        dbconnect();
        if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); 
        if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~"); 
        $query = "select id from prob_cobolt where id='{$_GET[id]}' and pw=md5('{$_GET[pw]}')"; 
        echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
        $result = @mysql_fetch_array(mysql_query($query)); 
        if($result['id'] == 'admin') solve("cobolt");
        elseif($result['id']) echo "<h2>Hello {$result['id']}<br>You are not admin :(</h2>"; 
        highlight_file(__FILE__); 
    ?>
```
cobolt 문제의 소스 코드이다. preg_match() 문으로 if문을 다루는 것은 이전 문제인 gremlin 문제와 같다.
-----
cobolt 문제와 gremlin 문제의 차이는 
```php
    if($result['id'] == 'admin') solve("cobolt");
    elseif($result['id']) echo "<h2>Hello {$result['id']}<br>You are not admin :(</h2>"; 
```

해결되기 위한 조건이 id값에 admin이면 해결되는 문제이다.

## 문제 해결 방안
1. admin 문자열 삽입
    …?id=admin' -- - 를 통해서 id의 값에 admin을 삽입한 후 pw부분을 무력화시킨다.

2. ord 함수 이용
    …?id=' or ord(id)=97 -- -을 이용하여 해결한다
    ## ord()
        ord() 함수는 문자열의 가장 앞에 있는 값을 반환해준다.

    ord(id) id의 문자열 가장 앞에 있는 값인 a, 97번을 찾게되고 
    
    ord(id)=97 을 하게되면 문자열 가장 앞값이 97번이 맞는지 체크하게 되어 admin이 나오게 된다.