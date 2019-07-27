<?php 
    require('function.php');
    debug('「「「「「「「「「「「「「「「「「');
    debug('ユーザー詳細ページ');
    debug('「「「「「「「「「「「「「「「「「');
    debugLogStart();

//================================
//画面処理
//================================

//画面表示用データ取得
//================================
//ユーザーIDのGETパラメータ取得
$u_id = (!empty($_GET['u_id'])) ? $_GET['u_id'] : '';
//DBからユーザーデータを取得
$viewData = getUserOne($u_id);
//var_dump($u_id.'・・・ユーザーID取得成功');
debug('取得したDBデータ：'.print_r($viewData,true));
//var_dump($_SESSION['user_id']);　閲覧ページのユーザーIDが入ってる

//POST送信（メッセージ機能使用）がある場合
if(!empty($_POST)){
    //ログイン認証
    require('auth.php');
    //例外処理
    try{
        $dbh = dbConnect();
        $sql ='INSERT INTO bord (send_user,receive_user, create_date) VALUES (:s_uid, :r_uid,:date)';
        $data = array(':s_uid' => $u_id, ':r_uid' => $_SESSION['user_id'],':date' => date('Y-m-d H:i:s'));
        $stmt = queryPost($dbh, $sql, $data);
        
        if($stmt){
            $_SESSION['msg_success'] = SUC05;
            debug('連絡掲示板へ遷移します');
            header("Location:msg.php?m_id=".$dbh->lastInsertID());
        }
    }catch(Exception $e){
        error_log('エラー発生：'.$e->getMessage());
        $err_msg['common'] = MSG07;
    }
}
?>
<?php
    $siteTitle = 'ユーザー詳細'; 
    require('head.php'); 
?>
<?php require('header.php'); ?>
<!-- メインコンテンツ -->
    <div id="contents" class="site-width">
    

      <!-- Main -->
      <section id="main" >
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
          <p class="prof-detail"><span class="text-box">自己紹介文</span><br><?php echo sanitize($key['introduce']); ?></p>
          </div>
    </div>
        </div>
        <div class="user-offer">

          <form action="" method="post"> <!-- formタグを追加し、ボタンをinputに変更し、style追加 -->
              <input type="submit" value="ここを押してオファー＆メッセージを送ろう" name="submit" class="btn btn-offer" style="margin-top:0;margin-bottom: 50px;">
          </form>
          <?php endforeach; ?>
        </div>

      </section>

    </div>

<?php require('footer.php'); ?>