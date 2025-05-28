<?php

/**
 * db_connect.php
 * 
 * データベース接続を行う
 * $pdo = PDO
 * 
 * TABLE: user
 *    id: int(11) ユーザーID
 *    user: varchar(100) ユーザー名
 *    pass: text 
 * 
 * TABLE: goods
 *    id: int(11) 商品番号
 *    name: varchar(255) 商品名
 *    description: varchar(255) 商品の説明
 *    price: int(11) 価格
 *    image: text 商品のイメージ
 */

declare(strict_types=1);
error_reporting(-1);

/**
 * データベース接続を行いPDOを返す
 * 
 * @return PDO データベース
 */
function db_connect(): PDO
{
   // staticとしてPDOを記憶させる
   static $pdo;

   if (isset($pdo)) {
      // すでにPDOを作成済みであればそれを返す
      return $pdo;
   }

   // データベースのURI
   $dsn = "mysql:dbname=daichi;host=localhost;charset=utf8";
   $user = "root";
   $password = "";
   // データベース接続
   $pdo = new PDO($dsn, $user, $password);
   return $pdo;
}
