<?php

/**
 * register.php
 * 
 * 商品の登録を行うフォーム
 * register_goods.php にPOSTする
 * POST後register_goods.phpからリダイレクトされてくる
 */

declare(strict_types=1);
error_reporting(-1);

require_once("../common.php");
require_once("../goods.php");
require_once("../message.php");
require_once("../csrf.php");

session_start();

// ログインしていない場合はログイン画面に移動させる
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

// CSRFトークンを生成する
$csrftoken = csrf::createToken();

// register_goods.phpにて登録失敗した時に返ってくる入力を取得する
$name = req_post("name");
$description = req_post("description");
$price = req_post_int("price");

// register_goods.php からのメッセージを受け取る
$message = get_message("register");
$errorMessage = get_error_message("register");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>商品登録フォーム</title>
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <article>
      <section class="page">
         <h1>商品登録フォーム</h1>

         <?php
         // register_goods.php から送られてきたメッセージ
         if ($message !== ""): ?>
            <p class="message"><?php echo $message; ?></p>

         <?php endif;
         // register_goods.php から送られてきたエラーメッセージ
         if ($errorMessage !== ""): ?>
            <p class="warn"><?php echo $errorMessage; ?></p>

         <?php endif; ?>

         <!-- 登録フォーム -->
         <form action="register_goods.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">

            <p class="input-s">
               <label for="name">商品名</label>
               <input type="text" name="name" id="name" value="<?php echo $name; ?>">
            </p>

            <p class="input-s">
               <label for="description">商品の説明</label>
               <input type="text" name="description" id="description" value="<?php echo $description; ?>">
            </p>
            <p class="input-s">
               <label for="price">価格</label>
               <input type="number" name="price" id="price" value="<?php echo $price; ?>">
            </p>

            <p class="input-s">
               <label for="image">画像</label>
               <input type="file" name="file" id="image">
            </p>

            <p class="center">
               <button type="submit" class="text-button">登録</button>
            </p>
            <p><a href="index.php">目次へ</a></p>

            <p>
               <a href="list.php">商品一覧を見る</a>
            </p>
         </form>
      </section>
   </article>
</body>

</html>