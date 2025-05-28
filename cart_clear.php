<?php

/**
 * cart_clear.php
 * 
 * カートを空にする
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
// カートを空にする
$cart->clear();

// cart.phpにメッセージを渡す
set_message("cart", "カートを空にしました");

// cart.phpに戻る
header("Location: cart.php");
exit;
