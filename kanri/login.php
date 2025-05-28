<?php

/**
 * login.php - 管理者用
 * 
 * 管理者用ページへのログイン認証を行う
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

// CSRFトークンを生成する
$csrftoken = csrf::createToken();

// データベース接続
$pdo = db_connect();

// リダイレクトされた入力を取得
$username = req_post("username");

// login_user.php からのメッセージを受け取る
$message = get_message("login");
$errorMessage = get_error_message("login");
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>ログイン</title>
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <article>
      <section class="page">
         <h1>ログイン</h1>

         <?php
         // リダイレクト元から送られてきたメッセージ
         if ($message !== ""): ?>
            <p class="message"><?php echo $message; ?></p>

         <?php endif;
         // リダイレクト元から送られてきたエラーメッセージ
         if ($errorMessage !== ""): ?>
            <p class="warn"><?php echo $errorMessage; ?></p>

         <?php endif; ?>
         <form action="login_user.php" method="post">
            <input type="hidden" name="csrf-token" value="<?php echo $csrftoken; ?>">

            <p class="input-s">
               <label for="username">ユーザー名</label>
               <input type="text" name="username" id="username" value="<?php echo $username; ?>">
            </p>

            <p class="input-s">
               <label for="password">パスワード</label>
               <input type="password" name="password" id="password">
            </p>

            <p class="center"><button type="submit" class="text-button">ログイン</button></p>
         </form>
      </section>
   </article>
</body>

</html>