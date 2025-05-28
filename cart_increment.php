<?php

/**
 * cart_increment.php
 * 
 * カート内の指定商品を一つ増やす
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
$addGoodsId = req_post_int("id");

if (is_null($addGoodsId)) {
   set_error_message("cart", "商品番号が指定されていません。");
   header("Location: cart.php");
   exit;
}

// カートの商品を1つ追加する
$cart->addGoods($addGoodsId, 1);

// cart.phpにメッセージを渡す
set_message("cart", "カートの商品を1つ増やしました。");

header("Location: cart.php");
exit;
