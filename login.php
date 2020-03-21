<?php
session_start();
require('dbconnect.php');

if(!empty($_POST)){
  if($_POST['email'] !== '' && $_POST['password'] !== ''){
    $login = $db->prepare('SELECT * FROM members WHERE email=? AND password=?');
    $login->execute(array($_POST['email'],sha1($_POST['password'])));
    $members = $login->fetch();
    if($members) {
      $_SESSION['id'] = $members['id'];
      $_SESSION['time'] = time();
      header('Location: input.php');
      exit();
    }else {
      $error['login'] = 'failure';
    }
  }else {
    $error['login'] = 'blank';
  }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="register.css">
</head>
<body>
<p class="lead-form">ログイン</p>
<form action="" method="POST">
  
  <div class="item">
    <label class="label">メールアドレス</label>
    <input class="inputs" type="email" name="email" value="<?php print(htmlspecialchars($_POST['email'],ENT_QUOTES)); ?>">
    <?php if($error['login'] === 'blank'): ?>
      <p class="error">メールアドレスとパスワードを入力してください</p>
    <?php endif; ?>
    <?php if($error['login'] === 'failure'): ?>
      <p class="error">ログインに失敗しました</p>
    <?php endif; ?>
  </div>

  <div class="item">
    <label class="label">パスワード</label>
    <input class="inputs" type="password" name="password" value="<?php print(htmlspecialchars($_POST['password'],ENT_QUOTES)); ?>">
  </div>
  <input type="submit" value="ログイン" class="btn-square-shadow">
</form>
</body>
</html>