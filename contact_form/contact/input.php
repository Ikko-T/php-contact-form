<?php
session_start();

header('X-Frame-Options: SAMEORIGIN');

session_regenerate_id(TRUE);

if (!isset($_SESSION["token"])) {
  $_SESSION["token"] = bin2hex(random_bytes(32));
}
$token = $_SESSION["token"];

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

$name = $_SESSION["name"] ?? NULL;
$furigana = $_SESSION["furigana"] ?? NULL;
$email = $_SESSION["email"] ?? NULL;
$tel = $_SESSION["tel"] ?? NULL;
$sex = $_SESSION["sex"] ?? NULL;
$pref =  $_SESSION["pref"] ?? NULL;
$reasons = $_SESSION["reason"] ?? NULL;
$contact_body = $_SESSION["contact_body"] ?? NULL;
$errors = $_SESSION["errors"] ?? NULL;

$error_name = $errors["name"] ?? NULL;
$error_furigana = $errors["furigana"] ?? NULL;
$error_email = $errors["email"] ?? NULL;
$error_tel = $errors["tel"] ?? NULL;
$error_sex = $errors["sex"] ?? NULL;
$error_pref =  $errors["pref"] ?? NULL;
$error_reason = $errors["reason"] ?? NULL;
$error_contact_body = $errors["contact_body"] ?? NULL;
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
  <!-- https://www.webdesignleaves.com/pr/php/php_contact_form_02.php -->
</head>
<body>
  <form action="confirm.php" method="post" name="form">
    <h2>お問い合わせ内容をご入力の上、「確認画面へ」ボタンをクリックしてください。</h2>

    <div class="input-area">
      <label>名前<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_name); ?></p>
      <input type="text" name="name" placeholder="例）山田太郎" value="<?php echo h($name); ?>">
    </div>

    <div class="input-area">
      <label>ふりがな<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_furigana); ?></p>
      <input type="text" name="furigana" placeholder="例）やまだたろう" value="<?php echo h($furigana); ?>">
    </div>

    <div class="input-area">
      <label>メールアドレス<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_email); ?></p>
      <input type="text" name="email" placeholder="例）guest@example.com" value="<?php echo h($email); ?>">
    </div>

    <div class="input-area">
      <label>電話番号</label>
      <p class="error"><?php echo h($error_tel); ?></p>
      <input type="text" name="tel" placeholder="例）0000000000" value="<?php echo h($tel); ?>">
    </div>

    <div class="input-area">
      <label>性別<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_sex); ?></p>
      <input type="radio" name="sex" value="男性" <?php if(isset($sex) && $sex === "男性"){echo "checked";} else { echo "checked"; }?>> 男性
      <input type="radio" name="sex" value="女性" <?php if(isset($sex) && $sex === "女性") { echo "checked"; } ?>> 女性
    </div>

    <div class="input-area">
      <label>お住まいの地域<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_pref); ?></p>
      <select name="pref" required>
        <option value="">選択してください</option>
        <option value="東京都"<?php if(isset($pref) && $pref === "東京都") {echo "selected";} ?>>東京都</option>
        <option value="愛知県"<?php if(isset($pref) && $pref === "愛知県") {echo "selected";} ?>>愛知県</option>
        <option value="大阪府"<?php if(isset($pref) && $pref === "大阪府") {echo "selected";} ?>>大阪府</option>
      </select>
    </div>

    <div class="input-area">
      <label>お問い合わせ理由<span class="span">必須</span></label>
      <p class="error"><?php echo h($error_reason); ?></p>
      <!-- <label><input type="checkbox" name="reason[]" value="質問" <?php if(isset($reasons[0])) { echo "checked"; } ?>>質問</label>
      <label><input type="checkbox" name="reason[]" value="ご意見ご要望" <?php if(isset($reasons[1])) { echo "checked"; } ?>>ご意見ご要望</label>
      <label><input type="checkbox" name="reason[]" value="掲載依頼" <?php if(isset($reasons[2])) { echo "checked"; } ?>>掲載依頼</label>
      <label><input type="checkbox" name="reason[]" value="資料請求" <?php if(isset($reasons[3])) { echo "checked"; } ?>>資料請求</label>
      <label><input type="checkbox" name="reason[]" value="その他" <?php if(isset($reasons[4])) { echo "checked"; } ?>>その他</label> -->
    </div>

    <div class="input-area">
      <label>お問い合わせ内容</label>
      <p class="error"><?php echo h($error_contact_body); ?></p>
      <textarea name="contact_body" rows="5" placeholder="お問合せ内容を入力"><?php echo h($contact_body); ?></textarea>
    </div>

    <div class="input-area">
      <input class="btn-border" name="submit" type="submit" value="確認画面へ">
      <input type="hidden" name="token" value="<?= $token ?>">
    </div>

  </form>
</body>
</html>
