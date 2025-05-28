<?php

/**
 * logout.php - 管理者用
 * 
 * ログアウトを行う
 */

declare(strict_types=1);
error_reporting(-1);

session_start();

// セッションからユーザー情報を削除する
unset($_SESSION["user"]);

// ログインページに戻る
header("LOCATION: login.php");
exit;
