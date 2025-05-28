<?php

/**
 * list.php - 管理者用
 * 
 * 登録されている商品の一覧を表示する
 * 商品番号を指定し商品情報の修正(update.php)と商品削除(delete.php)へ飛べる
 */

declare(strict_types=1);
error_reporting(-1);

require_once("../common.php");
require_once("../db_connect.php");
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

// 送られてきたメッセージを取得
$message = get_message("list");
$errorMessage = get_error_message("list");

// 並べ替え方法
$order = req_get("price");

// 商品番号順に商品を取得するSQL文
$sql = "SELECT * FROM goods";

if ($order ===  "asc") {
   // 価格昇順に商品を取得するSQL文
   $sql = "SELECT * FROM goods ORDER BY price ASC";
} elseif ($order === "desc") {
   // 価格降順に商品を取得するSQL文
   $sql = "SELECT * FROM goods ORDER BY price DESC";
}

// データベース接続
$pdo = db_connect();

// SQL文の実行
/** @var array{id: int, name:string, description: string, price: int, image: string}[]*/
$result = $pdo->query($sql);

?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>商品一覧</title>
   <link rel="stylesheet" href="css/style.css">
   <style>
      .page {
         padding-bottom: 100px;
      }

      td img {
         width: 100px;
         aspect-ratio: 1/1;
         object-fit: cover;
      }
   </style>
</head>

<body>

   <article>
      <section class="page">
         <section class="goods-list">
            <div class="sp-padding">
               <p><a href="index.php">目次へ</a></p>

               <form action="update_form.php" method="post" class="line mt30">
                  <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                  <p class="input-s">
                     <label for="id">商品番号</label>
                     <input type="number" name="id" id="id">
                  </p>
                  <p>
                     <button type="submit" class="text-button">商品情報修正</button>
                  </p>
               </form>

               <form action="remove.php" method="post" class="line mt30 mb30">
                  <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                  <p class="input-s">
                     <label for="id">商品番号</label>
                     <input type="number" name="id" id="id">
                  </p>
                  <p>
                     <button type="submit" class="text-button">商品削除</button>
                  </p>
               </form>
            </div>

            <div class="center">
               <?php
               // 送られてきたメッセージ
               if ($message !== ""): ?>
                  <p class="message"><?php echo $message; ?></p>

               <?php endif;
               // 送られてきたエラーメッセージ
               if ($errorMessage !== ""): ?>
                  <p class="warn"><?php echo $errorMessage; ?></p>

               <?php endif; ?>

            </div>

            <!-- 並べ替え用のリンク -->
            <div class="order-selector">
               <a href="list.php">商品番号順</a>
               <a href="?price=asc">価格昇順</a>
               <a href="?price=desc">価格降順</a>
            </div>

            <table>
               <tr>
                  <th>ID</th>
                  <th>商品名</th>
                  <th>商品説明</th>
                  <th>価格</th>
                  <th>イメージ</th>
               </tr>
               <?php foreach ($result as $record) {
                  $id = $record["id"];
                  $name = $record["name"];
                  $description = $record["description"];
                  $price = $record["price"];
                  $imagePath = $record["image"];
                  $imagePath = $imagePath ?: "no_image.jpg";
                  // $time = $record["time"]
               ?>
                  <tr>
                     <td><?php echo $id; ?></td>
                     <td><?php echo $name; ?></td>
                     <td><?php echo $description; ?></td>
                     <td><?php echo $price; ?></td>
                     <td><img src="../image/<?php echo $imagePath; ?>" alt="商品の画像"></td>
                  </tr>
               <?php }
               $pdo = null;
               ?>
            </table>

            <p class="center mt30"><a href="index.php">目次へ</a></p>
            <p class="center mt30"><a href="register.php">商品登録</a></p>
         </section>
      </section>
   </article>

</body>

</html>