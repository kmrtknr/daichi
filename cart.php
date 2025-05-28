<?php

/**
 * cart.php
 * 
 * カートの中の商品の一覧を表示する
 */

declare(strict_types=1);
error_reporting(-1);

session_start();

require_once("cart_control.php");
require_once("common.php");
require_once("db_connect.php");
require_once("goods.php");
require_once("message.php");
require_once("csrf.php");

// CSRFトークンを生成する
$csrftoken = csrf::createToken();

// ユーザーの操作とその結果に対するメッセージを受け取る
$message = get_message("cart");
$errorMessage = get_error_message("cart");

/**
 * ユーザーに見せるためのカート情報のリストを作成する
 * 
 * @param PDO $pdo  データベース接続オブジェクト
 * @return array  一覧表示するためのカート内の情報リスト
 */
function create_cart_data(PDO $pdo): array
{
   // カート用セッションを操作するためのクラス
   $cart = new Cart();
   // カート内の商品番号を取得する
   $goodsIds = $cart->getCartAllGoodsId();
   // 商品の情報を取得する
   $goodsList = fetch_goods($pdo, $goodsIds);
   if (!$goodsList) {
      // 商品情報が無かった
      return [];
   }

   // ["商品番号","商品名","価格","数量"] のリスト
   $cartDataList = [];
   // セッション内のカート情報から商品情報を含めたカート情報を作る
   foreach ($goodsList as $goods) {

      // 商品番号
      $id = $goods["id"];

      // 商品数量
      $quantity = $cart->getGoodsQuantity($id);

      // ["商品番号","商品名","価格","数量"] のリストを作りカート情報に追加する
      $cartDataList[] = [
         "id" => $id,
         "name" => $goods["name"],
         "price" => $goods["price"],
         "image" => $goods["image"],
         "quantity" => $quantity
      ];
   }
   return $cartDataList;
}

// データベースに接続する
$pdo = db_connect();

/** 
 * @var array $cartDataList 一覧表示するためのカート内の情報リスト
 * $cartDataList = [["商品番号","商品名","価格","数量"]]
 */
$cartDataList = create_cart_data($pdo);


$pdo = null;
?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>大地の詩カートページ</title>
   <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&family=Shippori+Mincho:wght@400;700&display=swap"
      rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <link rel="stylesheet" href="css/ress.css">
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php require_once("header.html") ?>

   <article>
      <div class="top">
         <h1>ONLINESHOP</h1>
      </div>

      <section class="cart">
         <?php
         // エラーメッセージ
         if ($errorMessage !== ""): ?>
            <p class="warn"><?php echo $errorMessage; ?></p>

         <?php endif; ?>
         <div class="cart-header">
            <div class="left">
               <a href="shop.php">&lt; 買い物を続ける</a>
            </div>
            <!-- 処理を行った際のメッセージ -->
            <p class="center"><?php echo $message; ?></p>
            <div class="right">
               <form action="cart_clear.php" method="post">
                  <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                  <button type="submit" class="text-button">カートを空にする</button>
               </form>
            </div>
         </div>

         <p><img src="image/cart.png" alt="ショッピングカートのアイコン"></p>

         <!-- カートに入っている商品の一覧 -->
         <div class="goods-list">

            <?php
            $totalAmount = 0;
            foreach ($cartDataList as $cartData) {
               // 商品番号
               $id = $cartData["id"];
               // 商品名
               $name = $cartData["name"];
               // 価格
               $price = $cartData["price"];
               // 数量
               $quantity = $cartData["quantity"];
               // 商品のイメージ、無かった場合は "no_image.jpg"を使う
               $image = $cartData["image"] ?: "no_image.jpg";

               // 小計
               $subTotal = $price * $quantity;
               // カートの合計金額
               $totalAmount += $subTotal;
            ?>

               <figure class="goods">
                  <img src="image/<?php echo $image; ?>" alt="<?php echo $name; ?>の写真">
                  <figcaption>
                     <p><?php echo $name; ?></p>
                  </figcaption>

                  <!-- 商品の値段 -->
                  <p class="price">&yen;<?php echo $price; ?></p>

                  <!-- 商品の小計 -->
                  <p class="subtotal">× <?php echo $quantity; ?> = <?php echo $quantity * $price; ?></p>

                  <!-- 商品の数量の枠 -->
                  <div class="quantity-container">

                     <!-- 商品の数量を減らす「-」ボタン -->
                     <form action="cart_decrement.php" method="post">
                        <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit" class="icon-button silent"><i class="fa-solid fa-minus"></i></button>
                     </form>

                     <!-- 商品の数量 -->
                     <div><?php echo $quantity; ?></div>

                     <!-- 商品の数量を増やす「+」ボタン -->
                     <form action="cart_increment.php" method="post">
                        <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
                        <input type="hidden" name="id" value="<?php echo $id; ?>">
                        <button type="submit" class="icon-button silent"><i class="fa-solid fa-plus"></i></button>
                     </form>
                  </div>
               </figure>

            <?php } ?>
         </div>

         <hr>

         <dl class="total">
            <dt>合計</dt>
            <dd>&yen;<?php echo $totalAmount; ?></dd>
         </dl>

         <form action="payment.php" method="post">
            <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">
            <button type="submit" class="text-button primary mb30">決済に進む</button>
         </form>

      </section>
   </article>

   <?php require_once("footer.html"); ?>

   <script>
      $(".menu-button").click(function() {
         $("nav.menu").toggleClass("show");
         $(".menu-button").toggleClass("opened");
      });
   </script>
</body>

</html>