<?php
try {
$db = new PDO('mysql:dbname=mini_bbs;host=localhost;charset=utf8','root', 'root');
} catch (PDOException $e) {
echo 'DB接続エラー： ' . $e->getMessage();
}
?>
<?php
session_start();

if(isset($_SESSION['id'])) {
  $id = $_REQUEST['id'];


$messages = $db->prepare('SELECT * FROM posts WHERE id=?');
$messages->execute(array($id));
$message = $messages->fetch();

if($message['member_id'] == $_SESSION['id']) {
  $del = $db->prepare('DELETE FROM posts WHERE id=?');
  $del->execute(array($id));
}
}

header('Location: index.php'); exit();
?>
