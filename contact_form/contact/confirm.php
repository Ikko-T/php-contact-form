<?php
session_start();

header('X-Frame-Options: SAMEORIGIN');

function h($string) {
  if (is_array($string)) {
    return array_map("h", $string);
  } else {
    return htmlspecialchars($string, ENT_QUOTES, "UTF-8");
  }
}

function checkInput($string) {
  if (is_array($string)) {
    return array_map("checkInput", $string);
  } else {
    if (!mb_check_encoding($string, "UTF-8")) {
      die ("不正な入力です。");
    }
    if (preg_match("/\A[\r\n\t[:^cntrl:]]*\z/u", $string) === 0) {
      die ("不正な入力です。制御文字は使用できません。");
    }
    return $string;
  }
}

$_POST = checkInput($_POST);

if (isset($_POST["token"], $_SESSION["token"])) {
  $token = $_POST["token"];
  if ($token !== $_SESSION["token"]) {
    die ("アクセスできません。");
  }
} else {
  die ("直接このページにアクセスできません。");
}

// $name = trim(filter_input(INPUT_POST, "name"));
// $furigana = trim(filter_input(INPUT_POST, "furigana"));
// $email = trim(filter_input(INPUT_POST, "email"));
// $tel = trim(filter_input(INPUT_POST, "tel"));
// $sex = trim(filter_input(INPUT_POST, "sex"));
// $pref = trim(filter_input(INPUT_POST, "pref"));
// $reason = trim(filter_input(INPUT_POST, "reason"));
// $contact_body = trim(filter_input(INPUT_POST, "contact_body"));

$name = $_POST["name"];
$furigana = $_POST["furigana"];
$email = $_POST["email"];
$tel = $_POST["tel"];
$sex = $_POST["sex"];
$pref = $_POST["pref"];
$reason = $_POST["reason"];
$contact_body = $_POST["contact_body"];

$errors = [];

define("VALIDATE_EMAIL", "/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/");
define("VALIDATE_PHONE_NUMBER", "/\A0(\d{1}[-(]?\d{4}|\d{2}[-(]?\d{3}|\d{3}[-(]?\d{2}|\d{4}[-(]?\d{1})[-)]?\d{4}\z/");

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
if ($pref === "選択してください") {
  $errors["pref"] = "お住まいの地域を選択してください";
} elseif (!in_array($pref, $prefectures)) {
  $errors["pref"] = "お住まいの地域を選択してください";
}

$contact_reason = ["質問", "ご意見ご要望", "資料請求", "掲載依頼", "その他"];
if (empty($reason)) {
  $errors["reason"] = "お問い合わせ内容を選択してください";
} elseif (!in_array($reason, $contact_reason)) {
  $errors["reason"] = "お問い合わせ内容を選択してください";
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
$_SESSION["reason"] = $reason;
$_SESSION["contact_body"] = $contact_body;
$_SESSION["errors"] = $errors;

if (count($errors) > 0) {
  // $dirname = dirname($_SERVER["SCRIPT_NAME"]);
  // $dirname = $dirname == DIRECTORY_SEPARATOR ? "" : $dirname;
  // $url = $_SERVER["SERVER_NAME"] . $_SERVER["SERVER_PORT"] . $dirname . "/input.php";
  header("HTTP/1.1 303 See Other");
  header("location: input.php");
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
  <form action="complete.php" method="post" name="form">
    <h2>お問い合わせ内容確認</h2>

    <div class="input-area">
      <label>名前<span class="span">必須</span></label>
      <p><?= h($name) ?></p>
    </div>

    <div class="input-area">
      <label>ふりがな<span class="span">必須</span></label>
      <p><?= h($furigana) ?></p>
    </div>

    <div class="input-area">
      <label>メールアドレス<span class="span">必須</span></label>
      <p><?= h($email) ?></p>
    </div>

    <div class="input-area">
      <label>電話番号</label>
      <p><?= h($tel) ?></p>
    </div>

    <div class="input-area">
      <label>性別<span class="span">必須</span></label>
      <p><?= h($sex) ?></p>
    </div>

    <div class="input-area">
      <label>お住まいの地域<span class="span">必須</span></label>
      <p><?= h($pref) ?></p>
    </div>

    <div class="input-area">
      <label>お問い合わせ理由<span class="span">必須</span></label>
      <p><?= h($reason) ?></p>
    </div>

    <div class="input-area">
      <label>お問い合わせ内容</label>
      <p><?= nl2br(h($contact_body)) ?></p>
    </div>

    <div class="input-area">
      <input type="button" onclick="history.back()" value="戻る" class="btn-border">
      <input type="submit" name="submit" value="送信" class="btn-border">
      <input type="hidden" name="token" value="<?= $token ?>">
    </div>
  </form>
</body>
</html>
