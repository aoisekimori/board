<?php
try {
$db = new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー： ' . $e->getMessage();
}
?>

<?php
session_start();

if ($_COOKIE['email'] != '') {
$_POST['email'] = $_COOKIE['email'];
$_POST['password'] = $_COOKIE['password'];
$_POST['save'] = 'on';
}


if (!empty($_POST)) {
	// ログインの処理
	if ($_POST['email'] != '' && $_POST['password'] != '') {
		$login = $db->prepare('SELECT * FROM members WHERE email=? AND
			password=?');
			$login->execute(array(
				$_POST['email'],
				sha1($_POST['password'])
			));
			$member = $login->fetch();
			if ($member) {
				// ログイン成功
				$_SESSION['id'] = $member['id'];
				$_SESSION['time'] = time();

				// ログイン情報を記録する
				if ($_POST['save'] == 'on') {
				setcookie('email', $_POST['email'], time()+60*60*24*14);
				setcookie('password', $_POST['password'], time()+60*60*24*14);
				}

				header('Location: index.php'); exit();
			} else {
				$error['login'] = 'failed';
			}
		} else {
			$error['login'] = 'blank';
		}
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
        <link rel="stylesheet" href="style2.css">

        <title>twitter app</title>
    </head>
    <body>
      <div class="header">
        <h3>|ログインする</h3>
      </div>
      <div class="form-wrapper">
        <p>メールアドレスとパスワードを記入してログインしてください。</p>
        <p>入会手続きがまだの方はこちらからどうぞ。</p>
        <p>&raquo;<a href="join/">入会手続きをする</a></p>
        <form action="" method="post">

          <div class="form-group">
            メールアドレス <span class="required">必須</span>
            <input type="text" class="form-control" name="email" value="<?php echo htmlspecialchars($_POST['email'],ENT_QUOTES); ?>">
            <?php if($error['login']=='blank'): ?>
              <p class="error">* メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if($error['login'] == 'failed'): ?>
              <p class="error">* ログインに失敗しました。正しくご記入ください</p>
            <?php endif; ?>
          </div>

          <div class="form-group">
            パスワード <span class="required">必須</span>
            <input type="text" class="form-control pass" name="password" value="<?php echo htmlspecialchars($_POST['password'],ENT_QUOTES); ?>">
          </div>

          <div class="form-group">
            ログイン情報の記録<br>
            <input type="checkbox" id="save" name="save" value="on"><label for="save">次回からは自動的にログインする</label>
            <br>
            <input type="submit" value="ログインする">
        </form>
      </div>


        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS, then Font Awesome -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.6/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        <script defer src="https://use.fontawesome.com/releases/v5.7.2/js/all.js"></script>
  　</body>
