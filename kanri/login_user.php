<?php

/**
 * login_user.php - 管理者用
 * 
 * login.phpから送られてきたデータを元に管理者用ページへのログイン認証を行う
 */

declare(strict_types=1);
error_reporting(-1);

require_once("../common.php");
require_once("../db_connect.php");
require_once("../message.php");
require_once("../csrf.php");

session_start();

// すでにログインしている場合は目次ページへ移動させる
if (isset($_SESSION["user"])) {
   header("Location: index.php");
   exit;
}

// ユーザーから送られてきたCSRFトークンを取得
$usertoken = req_post("csrf-token");
// CSRF検証を行う
if (!Csrf::verify($usertoken)) {
   set_error_message("login", "CSRF検証に失敗しました");
   header("Location: login.php");
   exit;
}

/**
 * ユーザー名からユーザー情報を取得する
 * 
 * @param PDO $pdo  データベース接続オブジェクト
 * @param string $username ユーザー名
 * 
 * @return array|null
 */
function fetch_user(PDO $pdo, string $username): ?array
{
   // ユーザー名からユーザーを問い合わせるSQL文
   $sql = "SELECT * FROM user WHERE user = ?";
   // SQL文の準備
   $stmt = $pdo->prepare($sql);
   // SQL文の実行
   $stmt->execute([$username]);

   // ユーザー情報を取得
   /** @var array{} $record */
   foreach ($stmt as $record) {
      return $record;
   }
   return null;
}

/**
 * ユーザー名とパスワードが正しいか検証する
 * 
 * @param PDO $pdo  データベース接続オブジェクト
 * @param string $username ユーザー名
 * @param string $password パスワード
 * 
 * @param array | null ユーザー情報
 */
function login_verify(PDO $pdo, string $username, string $password): ?array
{
   // ユーザー情報を取得する
   $user = fetch_user($pdo, $username);
   // ユーザーが存在しない
   if (!$user) {
      return null;
   }

   // パスワードの検証
   $result = password_verify($password, $user["pass"]);

   if ($result) {
      return $user;
   }
   return null;
}

// 入力を取得
$username = req_post("username");
$password = req_post("password");

// データベース接続
$pdo = db_connect();

// ログイン認証を行う
$user = login_verify($pdo, $username, $password);

// ログインに成功したらセッションにユーザー情報を保存して目次ページへ移動させる
if ($user) {
   $_SESSION["user"] = $user;
   header("Location: index.php");
   exit;
}

// 認証失敗
set_error_message("login", "ユーザー名かパスワードが間違っています");
header("Location: login.php", true, 307);
exit;
