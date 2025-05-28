<?php

/**
 * cart_add.php
 * 
 * カートに商品を追加する
 */

declare(strict_types=1);
error_reporting(-1);

session_start();
require_once("cart_control.php");
require_once("common.php");
require_once("message.php");
require_once("csrf.php");

// ユーザーから送られてきたCSRFトークンを取得
$usertoken = req_post("csrf-token");
// CSRF検証を行う
if (!Csrf::verify($usertoken)) {
   set_error_message("cart", "CSRF検証に失敗しました");
   header("Location: cart.php");
   exit;
}

// カート用セッションを操作するためのクラス
$cart = new Cart();

// 商品番号
$addGoodsId = req_post_int("goods_id");
// カートに入れる商品の数、nullの場合 0 とする
$addGoodsQuantity = req_post_int("quantity") ?? 0;

if (is_null($addGoodsId)) {
   set_error_message("cart", "商品番号が指定されていません。");
   header("Location: cart.php");
   exit;
}

// 数量が1未満だった場合
if ($addGoodsQuantity < 1) {
   set_error_message("cart", "カートに追加する商品の数量は1以上にしてください。");
   header("Location: cart.php");
   exit;
}

// カートに商品を追加する
$cart->addGoods($addGoodsId, $addGoodsQuantity);

// cart.phpにメッセージを渡す
set_message("cart", "カートに商品を追加しました。");

header("Location: cart.php");
exit;
