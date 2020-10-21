<?php
try {
$db = new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー： ' . $e->getMessage();
}
?>
<?php
session_start();

if(isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
  //ログインしている
  $_SESSION['time'] = time();

  $members = $db->prepare('SELECT * FROM members WHERE id=?');
  $members->execute(array($_SESSION['id']));
  $member = $members->fetch();
} else {
  header('Location: login.php');
  exit();
}

// 投稿を記録する
if (!empty($_POST)) {
	if ($_POST['message'] != '') {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, reply_post_id=?,created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
      $_POST['reply_post_id']
		));
		header('Location: index.php'); exit();
	}
}
 //投稿を取得する
 $posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

 //返信の場合
 if(isset($_REQUEST['res'])) {
   $response = $db->prepare('SELECT m.name,m.picture,p.* FROM members m, posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
   $response->execute(array($_REQUEST['res']));

   $table = $response->fetch();
   $message= '@' . $table['name'] . '  ' . $table['message'];
 }

 ?>

<!DOCTYPE html>
<html lang="ja">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
        <link rel="stylesheet" href="style2.css"/>

        <title>twitter app</title>
    </head>
    <body>
      <div class="header">
        <h3>|ひとこと掲示板</h3>
      </div>

      <form action="" method="post" class="message">
        <h3><?php echo htmlspecialchars($member['name'],ENT_QUOTES); ?> さん、メッセージをどうぞ</h3>
        <textarea name="message" class="message-form"><?php echo htmlspecialchars($message, ENT_QUOTES); ?></textarea>
        <input type="hidden" name="reply_post_id" value="<?php echo htmlspecialchars($_REQUEST['res'],ENT_QUOTES); ?>" >
        <div><input type="submit" value="投稿する"></div>
      </form>

      <?php
      foreach ($posts as $post):
      ?>
      <div class="msg-wrapper">
      <div class="msg">
        <img src="member_picture/<?php echo htmlspecialchars($post['picture'],ENT_QUOTES); ?>" alt="<?php echo htmlspecialchars($post['name'],ENT_QUOTES); ?>">
        <div class="content">
          <p><?php echo htmlspecialchars($post['message'],ENT_QUOTES); ?><span class="name"> (<?php echo htmlspecialchars($post['name'],ENT_QUOTES); ?>)</span>[<a href="index.php?res=<?php echo htmlspecialchars($post['id'],ENT_QUOTES); ?>">Re</a>]</p>
          <p class="day"><a href="view.php?id=<?php echo htmlspecialchars($post['id'],ENT_QUOTES); ?>"><?php echo htmlspecialchars($post['created'],ENT_QUOTES); ?></a></p>
          <?php
          if($post['reply_post_id'] > 0):
          ?>
             <a href="view.php?id=<?php echo htmlspecialchars($post['reply_post_id'], ENT_QUOTES); ?>">返信元のメッセージ</a>
          <?php endif; ?>
          <?php
          if($_SESSION['id'] == $post['member_id']): ?>
          [<a href="delete.php?id=<?php echo ($post['id']); ?>" style='color:#f33;'>削除</a>]
        <?php endif; ?>

        </div>
      </div>
    <?php endforeach; ?>
    </div>
    </body>
</html>
