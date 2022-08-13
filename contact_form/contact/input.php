<?php
session_start();

header('X-Frame-Options: SAMEORIGIN');

$token = bin2hex(random_bytes(32));
$_SESSION["token"] = $token;

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
  <form action="confirm.php" method="post" name="form">
    <h2>お問い合わせ内容をご入力の上、「確認画面へ」ボタンをクリックしてください。</h2>

    <div class="input-area">
      <label>名前<span class="span">必須</span></label>
      <p class="error"><?= $errors["name"]; ?></p>
      <input type="text" name="name" placeholder="例）山田太郎" value="">
    </div>

    <div class="input-area">
      <label>ふりがな<span class="span">必須</span></label>
      <p class="error"><?= $errors["furigana"]; ?></p>
      <input type="text" name="furigana" placeholder="例）やまだたろう" value="">
    </div>

    <div class="input-area">
      <label>メールアドレス<span class="span">必須</span></label>
      <p class="error"><?= $errors["email"]; ?></p>
      <input type="text" name="email" placeholder="例）guest@example.com" value="">
    </div>

    <div class="input-area">
      <label>電話番号</label>
      <p class="error"><?= $errors["tel"]; ?></p>
      <input type="text" name="tel" placeholder="例）0000000000" value="">
    </div>

    <div class="input-area">
      <label>性別<span class="span">必須</span></label>
      <p class="error"><?= $errors["sex"]; ?></p>
      <input type="radio" name="sex" value="男性" checked> 男性
      <input type="radio" name="sex" value="女性"> 女性
    </div>

    <div class="input-area">
      <label>お住まいの地域<span class="span">必須</span></label>
      <p class="error"><?= $errors["pref"]; ?></p>
      <select name="pref" required>
        <option value="">-選択-</option>
        <option value="東京都">東京都</option>
        <option value="愛知県">愛知県</option>
        <option value="大阪府">大阪府</option>
      </select>
    </div>

    <div class="input-area">
      <label>お問い合わせ理由<span class="span">必須</span></label>
      <p class="error"><?= $errors["reasons"]; ?></p>
      <label><input type="checkbox" name="reason[]" value="質問">質問</label>
      <label><input type="checkbox" name="reason[]" value="ご意見ご要望">ご意見ご要望</label>
      <label><input type="checkbox" name="reason[]" value="資料請求">資料請求</label>
      <label><input type="checkbox" name="reason[]" value="掲載依頼">掲載依頼</label>
      <label><input type="checkbox" name="reason[]" value="その他">その他</label>
    </div>

    <div class="input-area">
      <label>お問い合わせ内容</label>
      <p class="error"><?= $errors["contact_body"]; ?></p>
      <textarea name="contact_body" rows="5" placeholder="お問合せ内容を入力"></textarea>
    </div>

    <div class="input-area">
      <input class="btn-border" name="submit" type="submit" value="確認画面へ">
      <input type="hidden" name="token" value="<?= $token ?>">
    </div>

  </form>
</body>
</html>
