# ショッピングサイト開発練習用サイト  
  
このプロジェクトはHTML,css,PHPへの理解を深めるための開発練習として作られたショッピングサイトです。  
  
## ファイル構成  
`/index.php`: トップページ  
`/header.html`: 各ページ共通のページの上部部分  
`/footer.html`: 各ページ共通のページの下部部分  
`/concept.php`: 商品コンセプトを紹介するページ  
`/shop.php`: 商品一覧ページ、商品をカートに追加できる  
`/cart.php`: カートの中身を表示するページ  
`/cart_control.php`: セッション内のカートの情報を操作するためのクラスを定義したファイル  
`/cart_add.php`: カートに商品を追加する操作を行う、cart.phpへリダイレクトする  
`/cart_clear.php`: カートを空にする、cart.phpへリダイレクトする  
`/cart_decrement.php`: カート内の指定商品を１つ減らす、cart.phpへリダイレクトする  
`/cart_increment.php`: カート内の指定商品を一つ増やす、cart.phpへリダイレクトする  
`/common.php`: よく使う汎用的な処理を行う関数群  
`/csrf.php`: CSRFトークンの生成と検証を行うクラス  
`/db_connect.php`: データベース接続を行う  
`/db_connect_star.php`: 本番環境でのデータベース接続を行う  
`/message.php`: header()などでページ移動する際に処理内容をメッセージとしてセッションに登録し、移動先に渡すための関数群  
`/goods.php`: 商品に関する情報をデータベースから問い合わせる関数群  
`/kanri/index.php`: 管理者用マイページ  
`/kanri/list.php`: 登録されている商品の一覧を表示する、商品番号を指定し商品情報の修正(update.php)と商品削除(delete.php)へ飛べる  
`/kanri/login_user.php`: login.phpから送られてきたデータを元に管理者用ページへのログイン認証を行う  
`/kanri/login.php`: 管理者用ページへのログイン認証を行う  
`/kanri/logout.php`: ログアウトを行う  
`/kanri/register_goods.php`: register.phpから送られてきたデータを元に商品登録の処理を行う、register.phpへリダイレクトする  
`/kanri/register.php`: 商品の登録を行うフォーム  
`/kanri/remove.php`: list.phpから送られてきた商品番号を元に商品を削除する、list.phpへリダイレクトする  
`/kanri/update_form.php`: 商品情報の修正を行う入力フォーム  
`/kanri/update.php`: update_form.phpから送られてきたデータを元に商品の情報を更新する  