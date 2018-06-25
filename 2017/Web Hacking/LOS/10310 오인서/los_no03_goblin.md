# Lord of SQL Injection No.3 - goblin

## 문제 풀제의도
    
    문자열을 직접 입력하는 방법이 아닌 다른 방법으로 SQL Injection을 시도하는 방법을 아는지 확인한다.

## 소스 코드 분석

    ```php
    <?php 
        include "./config.php"; 
        login_chk(); 
        dbconnect(); 
        if(preg_match('/prob|_|\.|\(\)/i', $_GET[no])) exit("No Hack ~_~"); 
        if(preg_match('/\'|\"|\`/i', $_GET[no])) exit("No Quotes ~_~"); 
        $query = "select id from prob_goblin where id='guest' and no={$_GET[no]}"; 
        echo "<hr>query : <strong>{$query}</strong><hr><br>"; 
        $result = @mysql_fetch_array(mysql_query($query)); 
        if($result['id']) echo "<h2>Hello {$result[id]}</h2>"; 
        if($result['id'] == 'admin') solve("goblin");
        highlight_file(__FILE__); 
    ?>
    ```
    * 이전 코드와는 다르게 GET 메소드 방식으로 입력하는 값에 쿼터 즉, 따옴표들이 있는지 체크하는 구문이 생겨 문자열을 직접 입력할 경우 문제 풀이에 실패한다.

    * id 값이 admin 값이 들어있음을 보여야 하지만 id = 'guest'를 고정 해놓았으므로 id = 'guest'를 무력화 시킬방법을 찾아야한다.

## 문제 해결방안

    ### no값에 1을 넣어보았을때 guest로 접속되는 것을 보아 id='guest' and no=1이 성립하여 guest는 1번 행에 있음을 알 수 있다. 즉, no가 1이 아닐때 guest로 접속되지 않으므로 성립되지 않는다. 이를 이용하여 SQL Injection을 시도한다.

    * ord() 함수 이용
        앞서 cobolt 문제에서 ord() 함수를 다룬 방식으로 …?no = {이곳에 0이 아닌값} or ord(id)=97 을 하게 될경우 문제가 해결된다.
    
    * ASCii 값과 char() 함수를 이용한다.

        char()함수의 경우 ()안에 들어간 아스키 값에 해당하는 문자를 리턴해주는데 그곳에 admin에 해당하는 97,100,109,105,110 을 인자로 넘겨줄 경우 admin이라는 문자열을 리턴해준다. 

        위의 char함수를 이용하여 우선 id='guest'를 무력화시키기 위해 no값을 1이 아닌 값으로 넣어준 후 id값을 admin으로 만들면 
        …?no={0이 아닌값} or id=char(97,100,109,105,110)을 하게되면 guest가 무력화되며 admin에 해당하는 id 값을 where 조건에 들어가므로 문제가 해결된다.

    * 헥사코드를 이용한다.
    
        앞의 풀이 방식처럼 guest를 무력화 시킨후 hex코드로 admin에 해당하는 0x61646D696E를 입력하면 문자열로 입력하지 않더라도 admin값이 입력되어 문제가 해결된다.