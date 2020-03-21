<?php
try {
  $db = new PDO('mysql:dbname=pianoschool;host=localhost;charset=utf8','root','root');
}catch(PDOException $e) {
  echo 'エラー：' . $e->getMessage();
}
?>