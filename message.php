<?php
session_start();
require('dbconnect.php');

if(!isset($_SESSION['id'])) {
  header('Location: login.php');
  exit();
}

if(!empty($_POST)){
  if($_POST['message'] !== ''){
    $messages = $db->prepare('INSERT INTO posts SET message=?, member_id=?,reply_message_id=?,created=NOW()');
    $messages->execute(array($_POST['message'],$_SESSION['id'],$_POST['reply']));
    header('Location: input.php');
    exit();
  }else {
    $error['message'] = 'blank';
  }
}

if(isset($_REQUEST['res'])){
  $response = $db->prepare('SELECT m.name, p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=?');
  $response->execute(array($_REQUEST['res']));

  $table = $response->fetch();
  $message = '@' . $table['name'] . '' . $table['message'] . '=';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="message.css">
</head>
<body>
  <p>メッセージ</p>
  <form action="" method="POST">
    <textarea name="message" cols="30" rows="10" class="textlines" placeholder="メッセージを入力してください"><?php print(htmlspecialchars($message,ENT_QUOTES)); ?></textarea>
    <?php if($error['message']): ?>
      <p class="error">メッセージを入力してください</p>
      <?php endif; ?>
      <input type="submit" value="投稿" class="btn-square-shadow">
      <input type="hidden" name="reply" value="<?php print(htmlspecialchars($_REQUEST['res'],ENT_QUOTES)); ?>">
    </form>
    <a class="bak" href="input.php">戻る</a>
</body>
</html>