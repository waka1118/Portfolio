<?php 
    require('function.php');
    debug('「「「「「「「「「「「「「「「');
    debug('マイページ');
    debug('「「「「「「「「「「「「「「「');
    debugLogStart();
//ログイン認証
require('auth.php');
//================================
//画面処理
//================================
//DBからユーザーデータを取得
$viewData = getUserOne($_SESSION['user_id']);
    debug('取得したDBデータ：'.print_r($_SESSION['user_id']));

?>

<?php 
    $siteTitle = 'あなたのページです';
    require('head.php'); 
?>

<?php require('header.php'); ?>
<!-- メインコンテンツ -->
    <div id="contents" class="site-width">

      <!-- Main -->
      <section id="main" >
       <h2>マイページ</h2>
        <div class="Dt_user">
         <?php foreach((array)$viewData as $key) :?>
<!--左側-->
    <div class="page-left">
         <div class="product-img-container">
          <div class="panel">
            <img src="<?php echo showImg(sanitize($key['pic'])); ?>" alt="メイン画像：<?php echo sanitize($key['username']); ?>" id="js-switch-img-main" class="img-prof">
          </div>
          <div class="username" style="text-align: center;">
              <?php echo sanitize($key['username']).'　さん'; ?>
          </div>

        </div>
    </div>
    
<!-- 右側 -->
    <div class="page-right">
        <div id="addr">
            <p class="prof-detail"><span class="text-box">所属地域・団体</span><?php echo sanitize($key['addr']); ?></p>
        </div>
        <div id="age">
            <p class="prof-detail"><span class="text-box">年齢</span><?php echo sanitize($key['age']); ?></p>
        </div>
        <div id="gender">
            <p class="prof-detail"><span class="text-box">性別</span><?php echo sanitize($key['gender']); ?></p>
        </div>
        <div id="email">
            <p class="prof-detail"><span class="text-box">E-mail</span><?php echo sanitize($key['email']); ?></p>
        </div>
        <div id="m_instrument">
            <p class="prof-detail"><span class="text-box">主要パート</span><?php echo sanitize($key['m_instrument']); ?></p>
        </div>
        <div id="s_o_n" >
            <p class="prof-detail"><span class="text-box">ボーカルできる？</span><?php echo sanitize($key['singer_or_not']); ?></p>
        </div>
        <div id="introduce">
          <p class="prof-detail" id="introduce"><span class="text-box">自己紹介文</span><br><?php echo sanitize($key['introduce']); ?></p>
          </div>
    </div>
        </div>
        <div class="product-buy">

          <form action="" method="post"> <!-- formタグを追加し、ボタンをinputに変更し、style追加 -->
          </form>
          <?php endforeach; ?>
        </div>

      </section>

    </div>

    
   <?php require('footer.php'); ?>