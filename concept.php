<!DOCTYPE html>
<html lang="ja">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>大地の詩コンセプトページ</title>
   <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link
      href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@200..900&family=Roboto+Serif:ital,opsz,wght@0,8..144,100..900;1,8..144,100..900&family=Shippori+Mincho:wght@400;700&display=swap"
      rel="stylesheet">

   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
   <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

   <link rel="stylesheet" href="css/ress.css">
   <link rel="stylesheet" href="css/style.css">

</head>

<body>

   <?php require_once("header.html") ?>

   <article>
      <div class="top">
         <h1>CONCEPT</h1>
      </div>

      <section class="concept">
         <h2>健やかな肌へ、オーガニックの贈り物</h2>

         <figure>
            <img src="image/concept.jpg" alt="石鹸の写真">
            <figcaption>
               <p>
                  オーガニックラベンダーオイルを贅沢に使用。<br>
                  自然の恵みが凝縮された石鹸です。<br>
                  合成香料・着色料不使用。<br>
                  肌へのやさしさにこだわり、一つひとつ丁寧に手作りしていま
                  す。<br>
                  敏感肌の方にもおすすめです。
               </p>
            </figcaption>
         </figure>
      </section>
   </article>

   <?php require_once("footer.html") ?>

   <script>
      $(".menu-button").click(function() {
         $("nav.menu").toggleClass("show");
         $(".menu-button").toggleClass("opened");
      });
   </script>
</body>

</html>