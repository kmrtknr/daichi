<?php

/**
 * cart_control.php
 * 
 * セッション内のカートの情報を操作するためのクラス
 */

declare(strict_types=1);
error_reporting(-1);

/**
 * セッション内のカートの情報を操作するためのクラス
 */
final class Cart
{
    /** @var array $cart カート内の商品とその数量のリスト $cart[商品番号] = 商品の数量 */
    private array $cart;

    public function __construct()
    {
        // セッションにカートがなければ初期化
        $_SESSION["cart"] = $_SESSION["cart"] ?? [];

        // セッションのカート情報を取得
        $this->cart = $_SESSION["cart"];
    }

    /**
     * カート情報のセッションを更新する
     *
     * @return void
     */
    private function updateSession(): void
    {
        $_SESSION["cart"] = $this->cart;
    }

    /**
     * カート内の指定商品の数量を取得する、指定商品がカートにない場合は 0 を返す
     *
     * @param int $id 商品番号 
     * @return int 商品の数量
     */
    public function getGoodsQuantity(int $id): int
    {
        return $this->cart[$id] ?? 0;
    }

    /**
     * カート内の指定商品の数量を指定数分増やす
     *
     * @param int $id 商品番号 
     * @param int $quantity 商品数量
     * @return void
     */
    public function addGoods(int $id, int $quantity): void
    {
        // カート内の指定商品の数量を取得
        $goodsQuantity = $this->getGoodsQuantity($id);
        // カート内の数量を更新する
        $this->cart[$id] = $goodsQuantity + $quantity;
        // セッションを更新する
        $this->updateSession();
    }

    /**
     * カート内の指定商品の数量を指定数分減らす
     * また、数量が0になった場合はカートからその商品を削除する
     *
     * @param int $id 商品番号 
     * @param int $quantity 商品数量
     * @return void
     */
    public function subGoods(int $id, int $quantity): void
    {
        // カート内の指定商品の数量を取得
        $goodsQuantity = $this->getGoodsQuantity($id);
        // カート内の数量を更新する
        $this->cart[$id] = $goodsQuantity - $quantity;

        if ($this->cart[$id] <= 0) {
            // カート内の指定商品の数量が 0以下になったのでカートから指定商品を削除する
            $this->removeGoods($id);
        }

        // セッションを更新する
        $this->updateSession();
    }

    /**
     * カート内の指定商品を削除する
     *
     * @param int $id 商品番号
     * @return void
     */
    public function removeGoods(int $id): void
    {
        // カートから指定商品を削除
        unset($this->cart[$id]);
        // セッションを更新する
        $this->updateSession();
    }

    /**
     * カートを空にする
     *
     * @return void
     */
    public function clear(): void
    {
        $this->cart = [];
        $this->updateSession();
    }

    /**
     * カート内の商品番号を全て取得する
     * 
     * @return int[] 商品番号のリスト
     */
    public function getCartAllGoodsId(): array
    {
        return array_keys($this->cart);
    }
}
