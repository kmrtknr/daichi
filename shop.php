<?php

/**
 * shop.php
 * 
 * 商品の一覧を表示する
 * 商品を選びカートに追加できる
 */

declare(strict_types=1);
error_reporting(-1);

session_start();
require_once("common.php");
require_once("db_connect.php");
require_once("csrf.php");

// CSRFトークンを生成する
$csrftoken = csrf::createToken();

// データベース接続
$pdo = db_connect();


// 商品の一覧を取得するSQL文
$sql = "SELECT * FROM goods";
// SQLの実行
/** @var array{id: int, name:string, description: string, price: int, image: string}[] */
$result = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <title>大地の詩オンラインショップ</title>
   <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&family=Shippori+Mincho:wght@400;700&display=swap"
      rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/css/lightbox.min.css"
      integrity="sha512-xtV3HfYNbQXS/1R1jP53KbFcU9WXiSA1RFKzl5hRlJgdOJm4OxHCWYpskm6lN0xp0XtKGpAfVShpbvlFH3MDAA=="
      crossorigin="anonymous" referrerpolicy="no-referrer" />

   <link rel="stylesheet" href="css/ress.css">
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php require_once("header.html") ?>

   <article>
      <div class="top">
         <h1>SHOP</h1>
      </div>

      <section class="shop">
         <h2>健やかな肌へ、オーガニックの贈り物</h2>
         <p>
            大切な方への贈り物に。<br>
            ラベンダーの香りは、心を安らげ、リラックス効果も。可愛ら
            しいパッケージも魅力です。<br>
            日頃の感謝の気持ちを込めて、贈ってみてはいかがでしょうか。
         </p>

         <!-- 商品一覧 -->
         <section class="goods-list">

            <?php
            foreach ($result as $record) {
               $id = $record["id"];
               $name  = $record["name"];
               $description = $record["description"];
               $price  = $record["price"];
               $image = $record["image"];
               $image = $image ?: "no_image.jpg";
            ?>

               <!-- 各商品の情報 -->
               <div class="goods">
                  <figure>
                     <figcaption>
                        <!--商品名-->
                        <h3><?php echo $name; ?></h3>

                        <!-- 商品説明と値段-->
                        <div class="price">
                           <span><?php echo $description; ?></span><span>&yen;<?php echo $price; ?></span>
                        </div>
                     </figcaption>

                     <!-- light box用のリンク-->
                     <a href="image/<?php echo $image; ?>" data-lightbox="image-<?php echo $id; ?>" data-title="<?php echo $name; ?> - <?php echo $description; ?>">
                        <img src="image/<?php echo $image; ?>" alt="商品の写真"></a>
                  </figure>

                  <!-- カートに商品を入れるためのフォーム -->
                  <form action="cart_add.php" method="post">
                     <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                     <label for="quantity_1">個数</label>
                     <!-- 商品数量 -->
                     <select name="quantity" id="quantity_1">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                     </select>
                     <!-- 商品番号 -->
                     <input type="hidden" name="goods_id" value="<?php echo $id; ?>">
                     <button type="submit" class="text-button">カートに入れる</button>
                  </form>
               </div>
            <?php }
            ?>

         </section>
      </section>

   </article>

   <?php require_once("footer.html") ?>

   <!-- lightbox - サムネイルを拡大表示するためのライブラリ -->
   <script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.11.5/js/lightbox.min.js"
      integrity="sha512-KbRFbjA5bwNan6DvPl1ODUolvTTZ/vckssnFhka5cG80JVa5zSlRPCr055xSgU/q6oMIGhZWLhcbgIC0fyw3RQ=="
      crossorigin="anonymous" referrerpolicy="no-referrer"></script>

   <script>
      // lightbox のセキュリティ用の設定(xssへの対応)
      lightbox.option({
         "sanitizeTitle": true
      })

      // メニューボタンの切り替え
      $(".menu-button").click(function() {
         $("nav.menu").toggleClass("show");
         $(".menu-button").toggleClass("opened");
      });
   </script>
</body>

</html>