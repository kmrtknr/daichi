<?php

/**
 * common.php
 * 
 * よく使う汎用的な処理を行う関数群
 */

declare(strict_types=1);
error_reporting(-1);

/**
 * postが無い場合、指定ページに移動させる
 * @param string $pagePath パス
 */
function ck_ac(string $pagePath): void
{
   if (empty($_POST)) {
      header("Location: " . $pagePath);
      exit();
   }
}

/**
 * 文字列中のタグを無効化して返す
 * @param string $str
 * 
 * @return string
 */
function no_tag(string $str): string
{
   return htmlspecialchars($str, ENT_QUOTES);
}


/**
 * $_POSTの値をhtmlを無力化して取得する
 * @param string $key
 * 
 * @return string
 */
function req_post(string $key): string
{
   if (isset($_POST[$key])) {
      return no_tag($_POST[$key]);
   }
   return "";
}

/**
 * $_POSTの値を数値として取得する、数値以外の場合nullが返る
 * @param string $key
 * 
 * @return int | null
 */
function req_post_int(string $key): ?int
{
   $post = req_post($key);
   if (is_numeric($post)) {
      return (int)$post;
   }
   return null;
}

/**
 * $_GETの値をhtmlを無力化して取得する
 * @param string $key
 * 
 * @return string
 */
function req_get(string $key): string
{
   if (isset($_GET[$key])) {
      return no_tag($_GET[$key]);
   }
   return "";
}

/**
 * $_GETの値を数値として取得する、数値以外の場合nullが返る
 * @param string $key
 * 
 * @return int | null
 */
function req_get_int(string $key): ?int
{
   $post = req_get($key);
   if (is_numeric($post)) {
      return (int)$post;
   }
   return null;
}

/**
 * $_FILESの値を取得する
 * @param string $key
 * 
 * @return array|null
 */
function req_file(string $key): ?array
{
   return $_FILES[$key] ?: null;
}


/**
 * $_SESSIONの値を取得する
 * @param string $key
 * 
 * @return mixed
 */
function req_session(string $key): mixed
{
   return $_SESSION[$key] ?: "";
}
