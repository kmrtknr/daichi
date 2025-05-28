<?php

/**
 * update.php - 管理者用
 * 
 * update_form.phpから送られてきたデータを元に商品の情報を更新する
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
   set_error_message("update", "CSRF検証に失敗しました");
   header("Location: update_form.php");
   exit;
}

// 各種入力を取得
$id = req_post_int("id");
$name = req_post("name");
$description = req_post("description");
$price = req_post_int("price");
$image = req_file("file");
$oldImage = req_post("old_image");

try {
   if ($name === "" || $price === "") {
      throw new Exception("商品名と価格は両方ともに記入してください");
   }

   // アップロード後の画像のファイル名
   $imageFilename = "";

   // 画像の指定がある場合アップロードする
   if ($image && $image["full_path"] !== "") {
      $imageFilename = image_upload($image, ["jpg", "jpeg", "png", "gif", "webp"]);
      if (is_null($imageFilename)) {
         throw new Exception("画像のアップロードに失敗しました");
      }
   }
} catch (Exception $e) {
   set_error_message("update", $e->getMessage());
   header("Location: update_form.php", true, 307);
   exit;
}

// $imageFilename === null アップロードに失敗している
// $imageFilename === "" アップロードを行っていない
// 画像のアップロードに失敗したので登録失敗とする
if ($imageFilename !== "" && $oldImage !== "") {
   // アップロードに成功したうえで古い画像があれば削除する
   unlink("../image/{$oldImage}");
}

// 画像指定がない場合は今までの画像を設定する
$imageFilename = $imageFilename ?: $oldImage;

// データベース接続
$pdo = db_connect();

// 商品の情報を更新するSQL文
$sql = "UPDATE goods SET name = ?, description = ? ,price = ?, image = ? WHERE  id = ?";
$stmt = $pdo->prepare($sql);
// SQL文の実行
$result = $stmt->execute([$name, $description, $price, $imageFilename, $id]);

if ($result) {
   set_message("list", "[{$id}]の商品情報を修正しました。");
   header("Location: list.php");
   exit;
} else {
   set_error_message("update", "商品情報を修正に失敗しました");
   header("Location: update_form.php", true, 307);
   exit;
}
