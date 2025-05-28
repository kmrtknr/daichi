<?php

/**
 * message.php
 * 
 * header()などでページ移動する際に処理内容をメッセージとしてセッションに登録し、移動先に渡すための関数群
 */

declare(strict_types=1);
error_reporting(-1);

/**
 * ページごとにセッションにメッセージを登録する
 * 
 * @param string $pageName ページ名
 * @param string $message メッセージ
 * @return void
 */
function set_message(string $pageName, string $message): void
{
   // セッション名を作成する
   $key = $pageName . "_message";
   // セッションにメッセージを登録
   $_SESSION[$key] = $message;
}

/**
 * ページごとにセッションからメッセージを取得する
 * 
 * @param string $pageName ページ名
 * @return string メッセージ
 */
function get_message(string $pageName): string
{
   // セッション名を作成する
   $key = $pageName . "_message";
   // セッションからメッセージを取得、無ければ空文字とする
   $message = $_SESSION[$key] ?? "";
   // メッセージを取得したのちそのセッションを削除
   unset($_SESSION[$key]);
   return $message;
}


/**
 * ページごとにセッションにエラーメッセージを登録する
 * 
 * @param string $pageName ページ名
 * @param string $message メッセージ
 * @return void
 */
function set_error_message(string $pageName, string $message): void
{
   // セッション名を作成する
   $key = $pageName . "_error_message";
   // セッションにエラーメッセージを登録
   $_SESSION[$key] = $message;
}


/**
 * ページごとにセッションからエラーメッセージを取得する
 * 
 * @param string $pageName ページ名
 * @return string メッセージ
 */
function get_error_message(string $pageName): string
{
   // セッション名を作成する
   $key = $pageName . "_error_message";
   // セッションからエラーメッセージを取得、無ければ空文字とする
   $message = $_SESSION[$key] ?? "";
   // メッセージを取得したのちそのセッションを削除
   unset($_SESSION[$key]);
   return $message;
}
