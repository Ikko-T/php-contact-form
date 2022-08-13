<?php
session_start();

header('X-Frame-Options: SAMEORIGIN');

if (!isset($_POST["token"]) || $_POST["token"] !== $_SESSION["token"] || $_SERVER["REQUEST_METHOD"] !== "POST") {
  $_SESSTION["error_message"] = "不正なアクセスの可能性があります。";
  // echo "不正なアクセスの可能性があります。";
  header("Location: input.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Form</title>
  <link rel="stylesheet" href="../css/styles.css">
  <!-- https://madoseed.com/php-contact-form/ -->
</head>
<body>
  <h2>お問い合わせ完了</h2>
  <p>お問い合わせありがとうございました</p>
  <p>今後とも当サイトをよろしくお願いいたします。</p>
  <p><a href="input.php">お問い合わせトップへ</a></p>
</body>
</html>
