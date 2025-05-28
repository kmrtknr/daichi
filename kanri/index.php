<?php

/**
 * index.php - 管理者用
 * 
 * 管理者用マイページ
 */

declare(strict_types=1);
error_reporting(-1);

require_once("../common.php");
session_start();

// ログインしていない場合はログイン画面に移動させる
if (!isset($_SESSION["user"])) {
   header("Location: login.php");
   exit;
}

?>

<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>マイページ</title>
   <link rel="stylesheet" href="css/style.css">
</head>

<body>
   <article>
      <section class="page">
         <h1>マイページ</h1>

         <p>ようこそ<?php echo $_SESSION["user"]["user"] ?>さん！</p>
         <p class="mt30"><a href="list.php">一覧へ</a></p>
         <p class="mt30"><a href="register.php">新規登録</a></p>


         <p class="mt30"><a href="logout.php">ログアウト</a></p>
      </section>
   </article>
</body>

</html>