<?php
try {
$db = new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー： ' . $e->getMessage();
}
?>


<?php

session_start();
if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['name'] == '') {
		$error['name'] = 'blank';
	}
	if ($_POST['email'] == '') {
		$error['email'] = 'blank';
	}
	if (strlen($_POST['password']) < 4) {
		$error['password'] = 'length';
	}
	if ($_POST['password'] == '') {
		$error['password'] = 'blank';
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'gif') {
			$error['image'] = 'type';
		}
	}

	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE	email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if ($record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}



	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' .$image);
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
		header('Location: check.php');
		exit();
	}

}
  if($_REQUEST['action'] == 'rewrite') {
		$_POST = $_SESSION['join'];
		$error['rewrite'] = true;
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
        <p>次のフォームに必要事項をご記入ください</p>
        <form action="" method="post" enctype="multipart/form-data">
          <div class="form-group">
            ニックネーム <span class="required">必須</span>
            <input type="text" class="form-control" name="name" value="<?php echo htmlspecialchars($_POST['name'],ENT_QUOTES); ?>">
            <?php if($error['name'] == 'blank'): ?>
              <p class="error">* ニックネームを入力してください</P>
            <?php endif; ?>
          </div>

          <div class="form-group">
            メールアドレス <span class="required">必須</span>
            <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'],ENT_QUOTES); ?>">
            <?php if($error['email'] == 'blank'): ?>
              <p class="error">* メールアドレスを入力してください</p>
            <?php endif; ?>
						<?php if($error['email'] == 'duplicate'): ?>
							<p class="error">* 指定されたメールアドレスはすでに使用されています</p>
						<?php endif; ?>
          </div>

          <div class="form-group">
            パスワード <span class="required">必須</span>
            <input type="text" class="form-control pass" name="password" value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES); ?>">
            <?php if($error['password'] == 'blank'): ?>
              <p class="error">* パスワードを入力してください</p>
            <?php endif; ?>
            <?php if($error['password'] == 'length'): ?>
              <p class="error">* パスワードは４文字以上で入力してください</p>
            <?php endif; ?>
          </div>

          <p>写真など</p>
            <dd><input type="file" name="image" size="35" /></dd>
            <?php if($error['image'] == 'type'): ?>
            <p class="error">* 写真などは「.gif」または「.jpg」の画像を指定してください</p>
          <?php endif; ?>
          <?php if(!empty($error)): ?>
          <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
        <?php endif; ?>
            <div><input type="submit" value="入力内容を確認する"></div>
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
