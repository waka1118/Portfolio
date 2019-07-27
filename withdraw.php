<?php 
    require('function.php');
    debug('「「「「「「「「「「「「「「「「「「「「「「「');
    debug('退会ページ');
    debug('「「「「「「「「「「「「「「「「「「「「「「「');
    debugLogStart();

    require('auth.php');

//================================
//画面処理
//================================
//POSTされたとき
if(!empty($_POST)){
    debug('POST送信があります');
    try{
        $dbh = dbConnect();
        $sql = 'UPDATE users SET delete_flg = 1 WHERE id = :us_id';
        $data = array(':us_id' => $_SESSION['user_id']);
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            //セッション削除
            session_destroy();
            debug('セッション変数の中身：'.print_r($_SESSION,true));
            debug('トップページへ遷移します');
            header("Location:index.php");
        }else{
            debug('クエリ失敗しました');
            $err_msg['common'] = MSG07;
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
debug('画面表示処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>


<?php
$siteTitle = '退会';
require('head.php'); 
?>

  <body class="page-withdraw page-1colum">

    <style>
      .form .btn{
        float: none;
      }
      .form{
        text-align: center;
      }
    </style>

    <!-- メニュー -->
    <?php
    require('header.php'); 
    ?>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
      <!-- Main -->
      <section id="main" >
        <div class="form-container">
          <form action="" method="post" class="form">
            <h2 class="title">退会</h2>
            <div class="area-msg">
              <?php 
              if(!empty($err_msg['common'])) echo $err_msg['common'];
              ?>
            </div>
            <div class="btn-container">
              <input type="submit" class="btn btn-mid" value="退会する" name="submit">
            </div>
          </form>
        </div>
        <a href="mypage.php">&lt; マイページに戻る</a>
      </section>
    </div>

    <!-- footer -->
    <?php
    require('footer.php'); 
    ?>
