<?php

/**
 * update_form.php - 管理者用
 * 
 * 商品情報の修正を行う入力フォーム
 */

declare(strict_types=1);
error_reporting(-1);

require_once("../common.php");
require_once("../db_connect.php");
require_once("../goods.php");
require_once("../message.php");
require_once("../csrf.php");

session_start();

// ログインしていない場合はログイン画面に移動させる
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

// ユーザーから送られてきたCSRFトークンを取得
$usertoken = req_post("csrf-token");
// CSRF検証を行う
if (!Csrf::verify($usertoken)) {
   set_error_message("list", "CSRF検証に失敗しました");
   header("Location: list.php");
   exit;
}

// CSRFトークンを生成する
$csrftoken = csrf::createToken();

// 商品番号
$id = req_post_int("id");

// 商品番号の指定がない場合は一覧ページに移動させる
if (is_null($id)) {
   header("Location: list.php");
   exit;
}

// データベース接続
$pdo = db_connect();

// 商品情報を取得する
$goodsList = fetch_goods($pdo, [$id]);

// 商品が存在しない場合はリストページに戻す
if (!$goodsList) {
   set_error_message("list", "商品番号[{$id}]は存在しません。");
   header("Location: list.php");
   exit;
}

// 商品情報を配列から変数に変換する
$goods = $goodsList[0];

$name = $goods["name"];
$description = $goods["description"];
$price = $goods["price"];
$image = $goods["image"];

$thumb = $image ?: "no_image.jpg";


// update.php からのメッセージを受け取る
$message = get_message("update");
$errorMessage = get_error_message("update");
?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>商品情報修正フォーム</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      figure img {
         width: 100px;
         aspect-ratio: 1/1;
         object-fit: cover;
      }
   </style>
</head>

<body>
   <article>
      <section class="page">
         <h1>商品情報修正フォーム</h1>

         <div class="center">
            <?php
            // リダイレクト元から送られてきたメッセージ
            if ($message !== ""): ?>
               <p class="message"><?php echo $message; ?></p>

            <?php endif;
            // リダイレクト元から送られてきたエラーメッセージ
            if ($errorMessage !== ""): ?>
               <p class="warn"><?php echo $errorMessage; ?></p>

            <?php endif; ?>

         </div>

         <form action="update.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">

            <input type="hidden" name="id" value="<?php echo $id; ?>">
            <input type="hidden" name="old_image" value="<?php echo $image; ?>">


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
            <figure>
               <img src="../image/<?php echo $thumb; ?>" alt="商品の写真">
            </figure>

            <p class="center">
               <button type="submit" class="text-button">修正する</button>
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