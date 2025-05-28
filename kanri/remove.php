<?php

/**
 * remove.php - 管理者用
 * 
 * list.phpから送られてきた商品番号を元に商品を削除する
 * 削除後list.phpへリダイレクトする
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

// 商品番号
$id = req_post_int("id");

// 商品番号の指定がない場合は一覧ページに移動させる
if (is_null($id)) {
   header("Location: list.php");
   exit;
}

// データベース接続
$pdo = db_connect();

// 商品情報を取得する 1件しかヒットしないが配列で返ってくる
$goodsList = fetch_goods($pdo, [$id]);
// 商品が存在しない場合はリストページに戻す
if (!$goodsList) {
   set_error_message("list", "商品番号[{$id}]は存在しません。");
   header("Location: list.php");
   exit;
}

// 配列から変数に変換する
$goods = $goodsList[0];

//画像があれば削除する
if ($goods["image"] !== "") {
   unlink("../image/{$goods["image"]}");
}

// 商品を削除するSQL文
$sql = "DELETE FROM goods WHERE  id = ?";
// SQL文実行の準備
$stmt = $pdo->prepare($sql);
// SQL文の実行
$result = $stmt->execute([$id]);

// セッションにメッセージを登録
set_message("list", "商品[{$goods["name"]}]を削除しました");

header("Location: list.php");
exit;
