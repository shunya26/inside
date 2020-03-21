<?php
session_start();
require('dbconnect.php');


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
  <meta name="description" content="">
  <link rel="stylesheet" href="input.css">
</head>
<body>
  <header>
    <h1>PianoSchool</h1>
    <a href="register.php">新規登録</a>
    <a href="login.php">ログイン</a>
    </header>
    <main>
    <h2>自由にメッセージを投稿をしてみよう</h2>
    <article>
      <?php while($message = $messages->fetch()): ?>
        <p><?php print(htmlspecialchars($message['name'],ENT_QUOTES)); ?></p>
        <p><?php print(htmlspecialchars($message['message'],ENT_QUOTES)); ?></p>
        <time><?php print(htmlspecialchars($message['created'],ENT_QUOTES)); ?></time>
        <hr>
      <?php endwhile; ?>
    </article>
    <?php if($page > 1): ?>
    <a class="page" href="index.php?page=<?php print($page - 1); ?>">前のページ</a>
    <?php endif; ?>
    <?php
      $counts = $db->query('SELECT COUNT(*) AS cnt FROM posts');
      $count = $counts->fetch();
      $max_page = ceil($count['cnt'] / 5);
      if($page < $max_page):
    ?>
    <a class="page" href="index.php?page=<?php print($page + 1); ?>">次のページ</a>
      <?php endif; ?>
    </main>
</body>
</html>