<?php

/**
 * csrf.php
 * 
 * CSRFトークンの生成と検証を行うクラス
 */
class Csrf
{
   private function __construct() {}

   /**
    * CSRFトークンを生成し、セッションに保管したのちトークンを返す
    *
    * @return string CSRFトークン
    */
   public static function createToken(): string
   {
      // ランダムなデータを生成する
      $bytes = random_bytes(64);
      // ランダムなデータを文字列に変換し、トークンとする
      $csrfToken = base64_encode($bytes);
      // トークンをセッションに保管
      $_SESSION["csrf-token"] = $csrfToken;

      return $csrfToken;
   }

   /**
    * CSRFの検証を行います
    *
    * @param string $userToken ユーザーからPOSTされたCSRFトークン
    *
    * @return bool true = トークンが一致した
    */
   public static function verify(string $userToken): bool
   {
      if (!isset($_SESSION["csrf-token"])) {
         // セッションにトークンが無かった
         return false;
      }

      // セッションに保管されているトークンとユーザーが送ってきたトークンが一致しているか調べる
      // タイミング攻撃に対応するためにhash_equals関数を使う
      return hash_equals($_SESSION["csrf-token"], $userToken);
   }
}
