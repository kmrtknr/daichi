<?php

/**
 * register_goods.php
 * 
 * register.phpから送られてきたデータを元に商品登録の処理を行う
 * 成否についてメッセージを登録したのちregister.phpへリダイレクトする
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
   set_error_message("register", "CSRF検証に失敗しました");
   header("Location: register.php");
   exit;
}

/**
 * 商品を登録する
 * 
 * @param PDO $pdo  データベース接続オブジェクト
 * @param string $name 商品名
 * @param string $description 商品の説明
 * @param int $price 価格
 * @param string $imageFilename 画像のファイル名、画像がない場合空文字を渡す
 * 
 * @return bool データベース登録の成否
 */
function register_goods(PDO $pdo, string $name, string $description, int $price, string $imageFilename): bool
{
   $sql = "INSERT INTO goods (name, description, price, image) VALUES (?, ?, ?, ?)";
   $stmt = $pdo->prepare($sql);
   $result = $stmt->execute([$name, $description, $price, $imageFilename]);

   return $result;
}

// データベース接続
$pdo = db_connect();

// 各種入力を取得
$name = req_post("name"); // 商品名
$description = req_post("description"); // 商品説明
$price = req_post_int("price"); // 価格
$image = req_file("file"); // 商品のイメージ

try {
   if ($name === "" && $price === "") {
      throw new Exception("商品名と価格を設定して下さい");
   } elseif ($name === "") {
      throw new Exception("商品名を記入してください");
   } elseif ($price === "") {
      throw new Exception("価格を記入してください");
   }

   // アップロードした画像のファイル名
   $imageFilename = "";
   // 画像の指定がある場合アップロードする
   if (isset($image) && $image["full_path"] !== "") {
      // 画像をアップロードしてファイル名を取得
      $imageFilename = image_upload($image, ["jpg", "jpeg", "png", "gif", "webp"]);
   }

   // 画像のアップロードに失敗したので登録失敗とする
   if ($imageFilename === null) {
      throw new Exception("画像のアップロードに失敗しました");
   }

   // 商品を登録する
   $isRegistered = register_goods($pdo, $name, $description, $price, $imageFilename);

   if (!$isRegistered) {
      throw new Exception("予期せぬ理由で登録に失敗しました");
   }
} catch (Exception $e) {
   // セッションにエラーメッセージを登録する
   set_error_message("register", $e->getMessage());
   // 登録ページに戻る
   header("Location: register.php", true, 307);
   exit;
}

// 登録に成功した
set_message("register", "商品[{$name}]の登録に成功しました。");
header("Location: register.php");
exit;
