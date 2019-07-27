<?php 
$siteTitle = 'トップページ';
require('head.php');
  ?>
<body id="index">
  
  <!-- header始まり -->
  <header>
    <div class="logo">
      <a href="index.php">Let's Music</a>
    </div>
  </header>
  <!-- header終わり -->
  
  <!-- wrap始まり -->
  <div id="wrap">
    <div class="content">
        <h1>Music Guild,<br>Come on now!</h1>
        <p style="color: white">ここは、音楽ギルドです。理想のバンドを組もう！</p>
        <p class="btn"><a href="signup.php">ユーザー登録</a></p>
        <p class="btn"><a href="login.php">ログイン</a></p>
    </div>
  </div>
  <!-- wrap終わり -->
  
  <!-- footer始まり -->
<?php 
    require('footer.php');
?>