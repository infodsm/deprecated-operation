# Lord of SQL Injection No.1 - gremlin

## 문제 출제의도

코드를 분석한 후 URL 입력으로 SQL문을 작성하여 SQL Injection이 무엇인지 생각한다.

## 소스 코드 분석
```php
    <?php
    include "./config.php";
    login_chk();
    dbconnect();
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); // do not try to attack another table, database!
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
    $query = "select id from prob_gremlin where id='{$_GET[id]}' and pw='{$_GET[pw]}'";
    echo "<hr>query : <strong>{$query}</strong><hr><br>";
    $result = @mysql_fetch_array(mysql_query($query));
    if($result['id']) solve("gremlin");
    highlight_file(__FILE__);
    ?>
```
gremlin문제의 소스코드이다.
-----
```php
    include "./config.php";
    login_chk();
    dbconnect();
```

* config.php 파일을 불러와 적용시키는데 코드의 분석을 통해 gremlin 문제풀이를 하는데에 필요한 요소들이 들어있음을 추측한다.

* login_chk() 함수 명으로 보아 로그인을 체크하는 것 같다.

* dbconnect() 역시 함수 명을 보아 데이터 베이스와 연결을 하는 기능으로 추측 된다.

-----
```php
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[id])) exit("No Hack ~_~"); // do not try to attack another table, database!
    if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
```

* GET 메소드를 입력을 받는데 입력값들을 preg_match 함수로 검사한다.

* preg_match 함수는 정규표현식으로 문자열을 검사하여 정규표현식과 일치하는 문자가 있는지 검사한다. 반환값은 1 or 0으로 한번 검사되어 있음을 확인했을 경우 검색을 중지하며 함수를 정지한다.

* 이 문제에서 검사하는 문자의 경우 "prob","_",".","()" 등이 있다.

* 정규표현식과 일치하는 문자가 있을 경우 "No Hack ~_~"이 출력되며 문제풀이를 실패했음을 보인다.
-----
```php
    $query = "select id from prob_gremlin where id='{$_GET[id]}' and pw='{$_GET[pw]}'";
    echo "<hr>query : <strong>{$query}</strong><hr><br>";
    $result = @mysql_fetch_array(mysql_query($query));
    if($result['id']) solve("gremlin");
```
* GET 메소드로 입력된 id의 값과 pw의 값을 쿼리 스트링에 입력한다.

* 쿼리 스트링에 해당하는 값을 데이터베이스에 요청하고 반환 값을 result에 담는다.

* result의 id값이 0이 아닌경우 solve("gremlin")을 실행시키며 문제풀이에 성공시킴을 보여준다.

## 문제 해결 방법

이 문제를 푸는 방안은 여러가지 있겠지만 나는 where 조건절의 pw값을 무력화 시키는 방법을 선택하였다.

GET 메소드 방식의 정보 전송에서 …?id = ' or 1 = 1 -- -을 입력하여 쿼리스트링을 채워보면

select id from prob_gremlin where id = '' or 1 = 1 -- - ' and pw = '{$_GET[pw]}'가 되는데

-- - 의 경우 한줄 주석의 의미로 뒤에 pw부분을 무시하게 되므로 id = '' or 1=1 부분만 취급되어 항상 참을 만들기 때문에

모든 행의 id값을 불러오므로 문제 풀이에 성공하게 된다.