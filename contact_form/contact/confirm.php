<?php
session_start();

header('X-Frame-Options: SAMEORIGIN');

function escape($string) {
  $string = trim($string);
  $string = htmlspecialchars($string, ENT_QUOTES, "UTF-8");
  return $string;
}

if (!isset($_POST["token"]) || $_POST["token"] !== $_SESSION["token"] || $_SERVER["REQUEST_METHOD"] !== "POST") {
  $_SESSTION["error_message"] = "不正なアクセスの可能性があります。";
  header("Location: input.php");
  exit;
} else {
  $name = escape($_POST["name"]);
  $furigana = escape($_POST["furigana"]);
  $email = escape($_POST["email"]);
  $tel = escape($_POST["tel"]);
  $sex = escape($_POST["sex"]);
  $pref = escape($_POST["pref"]);
  $reasons = escape(implode(",", $_POST["reason"]));
  $contact_body = escape($_POST["contact_body"]);

  const VALIDATE_EMAIL = "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/";
  const VALIDATE_PHONE_NUMBER = "/\A0(\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1})[-)]?\d{4}\z/";
  $errors = [];

  if (empty($name)) {
    $errors["name"] = "お名前を入力してください";
  } elseif (mb_strlen(($name) > 20)) {
    $errors["name"] = "20文字以内で入力してください";
  }

  if (empty($furigana)) {
    $errors["furigana"] = "ふりがなを入力してください";
  } elseif (mb_strlen(($furigana) > 20)) {
    $errors["furigana"] = "20文字以内で入力してください";
  }

  if (empty($email)) {
    $errors["email"] = "メールアドレスを入力してください";
  } elseif (mb_strlen(($email) > 100)) {
    $errors["email"] = "100文字以内で入力してください";
  } elseif (!preg_match(VALIDATE_EMAIL, $email)) {
    $errors["email"] = "メールアドレスは正しい形式で入力してください";
  }

  if (mb_strlen($tel) > 15) {
    $errors["tel"] = "15文字以内で入力してください";
  } elseif (!preg_match(VALIDATE_PHONE_NUMBER, $tel)) {
    $errors["tel"] = "電話番号は正しい形式で入力してください";
  }

  $gender = ["男性", "女性"];
  if (empty($sex)) {
    $errors["sex"] = "性別を選択してください";
  } elseif (!in_array($sex, $gender)) {
    $errors["sex"] = "性別を選択してください";
  }

  $prefectures = ["東京都", "愛知県", "大阪府"];
  if (empty($pref)) {
    $errors["pref"] = "お住まいの地域を選択してください";
  } elseif (!in_array($pref, $prefectures)) {
    $errors["pref"] = "お住まいの地域を選択してください";
  }

  $contact_reasons = ["質問", "ご意見ご要望", "資料請求", "掲載依頼", "その他"];
  if (empty($reasons)) {
    $errors["reasons"] = "お住まいの地域を選択してください";
  } elseif (!in_array($reasons, $contact_reasons)) {
    $errors["reasons"] = "お住まいの地域を選択してください";
  }

  if (mb_strlen($contact_body) > 30) {
    $errors["contact_body"] = "30文字以内で入力してください";
  }

  $_SESSION["name"] = $name;
  $_SESSION["furigana"] = $furigana;
  $_SESSION["email"] = $email;
  $_SESSION["tel"] = $tel;
  $_SESSION["sex"] = $sex;
  $_SESSION["pref"] = $pref;
  $_SESSION["reasons"] = $reasons;
  $_SESSION["contact_body"] = $contact_body;
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
  <form action="complete.php" method="post" name="form">
    <h2>お問い合わせ内容確認</h2>

    <div class="input-area">
      <label>名前<span class="span">必須</span></label>
      <p><?= $_SESSION["name"] ?></p>
    </div>

    <div class="input-area">
      <label>ふりがな<span class="span">必須</span></label>
      <p><?= $_SESSION["furigana"] ?></p>
    </div>

    <div class="input-area">
      <label>メールアドレス<span class="span">必須</span></label>
      <p><?= $_SESSION["email"] ?></p>
    </div>

    <div class="input-area">
      <label>電話番号</label>
      <p><?= $_SESSION["tel"] ?></p>
    </div>

    <div class="input-area">
      <label>性別<span class="span">必須</span></label>
      <p><?= $_SESSION["sex"] ?></p>
    </div>

    <div class="input-area">
      <label>お住まいの地域<span class="span">必須</span></label>
      <p><?= $_SESSION["pref"] ?></p>
    </div>

    <div class="input-area">
      <label>お問い合わせ理由<span class="span">必須</span></label>
      <p><?= $_SESSION["reasons"] ?></p>
    </div>

    <div class="input-area">
      <label>お問い合わせ内容</label>
      <p><?= $_SESSION["contact_body"] ?></p>
    </div>

    <div class="input-area">
      <input type="button" onclick="history.back()" value="戻る" class="btn-border">
      <input type="submit" name="submit" value="送信" class="btn-border">
      <input type="hidden" name="name" value="<?= $_SESSION["name"]?>">
      <input type="hidden" name="furigana" value="<?= $_SESSION["furigana"]?>">
      <input type="hidden" name="email" value="<?= $_SESSION["email"]?>">
      <input type="hidden" name="tel" value="<?= $_SESSION["tel"]?>">
      <input type="hidden" name="sex" value="<?= $_SESSION["sex"]?>">
      <input type="hidden" name="pref" value="<?= $_SESSION["pref"]?>">
      <input type="hidden" name="reasons" value="<?= $_SESSION["reasons"]?>">
      <input type="hidden" name="contact_body" value="<?= $_SESSION["contact_body"]?>">
    </div>
  </form>
</body>
</html>
