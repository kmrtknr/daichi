<?php

/**
 * goods.php
 * 
 * 商品に関する情報をデータベースから問い合わせる関数群
 */

declare(strict_types=1);
error_reporting(-1);

/**
 * 商品番号のリストから商品の情報を取得する
 *
 * @param PDO $pdo  データベース接続オブジェクト
 * @param array<int> $ids 商品番号のリスト
 * 
 * @return array{id: int, name:string, description: string, price: int, image: string}[]|null 商品情報のリスト
 */
function fetch_goods(PDO $pdo, array $ids): ?array
{
   if (empty($ids)) {
      // 商品番号のリストが空だった場合
      return null;
   }

   // プレースホルダーを作成する implode(",", array_fill(0, count($ids), "?")) <- $idsの要素数だけ ? を生成する
   $placeholders = implode(",", array_fill(0, count($ids), "?"));
   // SQL文を作成する
   $stmt = $pdo->prepare("SELECT * FROM goods WHERE id IN (" . $placeholders . ")");
   // SQL文を実行する
   $stmt->execute($ids);
   // 問い合わせた商品情報を全て取得する
   $goodsList = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return $goodsList;
}

/**
 * 画像をアップロードしファイル名を取得する
 * 
 * @param $_FILES $uploadedFile 
 * @param array<string> $allowedExtensions 許可する拡張子 (例: ["jpg","jpeg","png","gif","webp"])
 * @param int $maxFileSize ファイルサイズの上限(bytes) デフォルトでは2MB
 * 
 * @return string|null  成功したらファイル名が返る
 */
function image_upload(array $uploadedFile, array $allowedExtensions, int $maxFileSize = 1024 * 1024 * 2,): ?string
{

   if (!isset($uploadedFile) || $uploadedFile["error"] !== UPLOAD_ERR_OK) {
      // アップロードエラー
      return null;
   }
   if ($uploadedFile["size"] > $maxFileSize) {
      // ファイルサイズが上限を超えています
      return null;
   }

   // 拡張子を取得し小文字に変換
   $ext = strtolower(pathinfo($uploadedFile["name"], PATHINFO_EXTENSION));
   if (!in_array($ext, $allowedExtensions, true)) {
      // 許可されていない拡張子です
      return null;
   }

   // アップロード先ディレクトリ
   $uploadDir = "../image/";

   if (!is_dir($uploadDir)) {
      // ディレクトリが無ければ作成する
      if (!mkdir($uploadDir, 0777, true)) {
         // ディレクトリ作成に失敗
         return null;
      }
   }

   // タイムスタンプと拡張子を合わせてファイル名とする
   $filename = time() . "." . $ext;
   // 保存先パスを設定する
   $filepath =  $uploadDir . $filename;
   // ファイルを保存する
   $result = move_uploaded_file($uploadedFile["tmp_name"], $filepath);

   if ($result) {
      return $filename;
   }
   return null;
}
