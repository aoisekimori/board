
<?php
try {
$db = new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー： ' . $e->getMessage();
}
?>

<?php
session_start();

if (!isset($_SESSION['join'])) {
header('Location: index.php');
exit();
}
if (!empty($_POST)) {
	// 登録処理をする
	$statement = $db->prepare('INSERT INTO members SET name=?, email=?,	password=?, picture=?, created=NOW()');
		echo $ret = $statement->execute(array(
			$_SESSION['join']['name'],
			$_SESSION['join']['email'],
			sha1($_SESSION['join']['password']),
			$_SESSION['join']['image']
		));
		unset($_SESSION['join']);
		header('Location: thanks.php');
		exit();
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
        <link rel="stylesheet" href="style.css">

        <title>twitter app</title>
    </head>
    <body>
      <div class="header">
        <h3>|会員登録</h3>
      </div>
      <div class="form-wrapper">
        <p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
        <form action="" method="post" enctype="multipart/form-data">
            <input type="hidden" name="action" value="submit">
          <div class="form-group">
            ニックネーム
            <?php echo htmlspecialchars($_SESSION['join']['name'],ENT_QUOTES,'UTF-8'); ?>
          </div>

          <div class="form-group">
            メールアドレス
            <?php echo htmlspecialchars($_SESSION['join']['email'],ENT_QUOTES,'UTF-8'); ?>

          </div>

          <div class="form-group">
            パスワード
            <p>表示されません</p>
          </div>

          <p>写真など</p>
          <img src="../member_picture/<?php echo htmlspecialchars($_SESSION['join']['image'],ENT_QUOTES,'UTF-8'); ?>" Width="100" height="100" alt="">
            <div><a href="index.php?action=rewite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する"></div>
        </form>
      </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS, then Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
    </body>
</html>
