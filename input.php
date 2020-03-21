<?php
session_start();
require('dbconnect.php');

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
}else {
  header('Location: login.php');
  exit();
}

if($_REQUEST['page'] !== '' && is_numeric($_REQUEST['page'])){
  $page = $_REQUEST['page'];
}else {
  $page = 1;
}
$start = 5 * ($page - 1);

$messages = $db->prepare('SELECT m.name, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC LIMIT ?,5');
$messages->bindParam(1,$start,PDO::PARAM_INT);
$messages->execute();
?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PianoSchool</title>
  <link rel="stylesheet" href="input.css">
</head>
<body>
  <header>
    <h1>PianoSchool</h1>
    <a href="message.php">メッセージ</a>
    <a href="logout.php">ログアウト</a>
    </header>
    <main>
      <h2><?php print(htmlspecialchars($member['name'],ENT_QUOTES)); ?></h2>
      <p class="title">友達とのコミュニケーションや、質問などを気軽に投稿してください</p>
    <article>
      <?php while($message = $messages->fetch()): ?>
        <p><?php print(htmlspecialchars($message['name'],ENT_QUOTES)); ?></p>
        <p><?php print(htmlspecialchars($message['message'],ENT_QUOTES)); ?></p>
        <time><?php print(htmlspecialchars($message['created'],ENT_QUOTES)); ?></time>
        <?php if($_SESSION['id'] === $message['member_id']): ?>
        <a class="del" href="delete.php?id=<?php print(htmlspecialchars($message['id'],ENT_QUOTES)); ?>">削除</a>
        <?php endif; ?>
        <?php if($message['reply_message_id'] <= 0): ?>
          <a class="reply" href="message.php?res=<?php print(htmlspecialchars($message['id'],ENT_QUOTES)); ?>">返信</a>
        <?php endif; ?>
        <hr>
      <?php endwhile; ?>
    </article>
    <?php if($page > 1): ?>
    <a class="page" href="input.php?page=<?php print($page - 1); ?>">前のページ</a>
    <?php endif; ?>
    <?php
      $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
      $count = $counts->fetch();
      $max_page = ceil($count['cnt'] / 5);
      if($page < $max_page):
    ?>
    <a class="page" href="input.php?page=<?php print($page + 1); ?>">次のページ</a>
      <?php endif; ?>
    </main>
</body>
</html>