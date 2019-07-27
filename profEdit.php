<?php 
    require('function.php');
    debug('「「「「「「「「「「「「「「「「「「');
    debug('プロフィール編集画面');
    debug('「「「「「「「「「「「「「「「「「「');
    debugLogStart();
//ログイン認証
require('auth.php');
//================================
//画面処理
//================================
//DBからユーザーデータを取得
$dbFormData = getUser($_SESSION['user_id']);
    debug('取得したユーザー情報:'.print_r($_SESSION['user_id']));

//POST送信された場合
if(!empty($_POST)){
    debug('POST送信があります');
    debug('POST情報：'.print_r($_POST,true));
    debug('FILE情報：'.print_r($_FILES,true));
    
    //変数にユーザー情報代入
    $username = $_POST['username'];
    $addr = $_POST['addr'];
    $email = $_POST['email'];
    $m_instument = $_POST['m_instrument'];
    $s_o_n = $_POST['singer_or_not'];
    $introduce = $_POST['introduce'];
    //画像をアップロードし、パスを格納
    $pic = (!empty($_FILES['pic']['name'])) ? uploadImg($_FILES['pic'],'pic') : '';
    //画像をPOSTしていない（登録していない）がすでにDBに登録されている場合、DBのパスを入れる（POSTには反映されないので）
    $pic = ( empty($pic) && !empty($dbFormData['pic']) ) ? $dbFormData['pic'] : $pic;
    
    //DBの情報と入力情報が異なる場合にバリデーションを行う
    if($dbFormData['username'] !== $username){
        validMaxLen($username,'username');
    }
    if($dbFormData['email'] !== $email){
        //emailの最大文字数チェック
        validMaxLen($email, 'email');
        if(empty($err_msg['email'])){
        //emailの重複チェック
        validEmailDup($email);
    }
    //emailの形式チェック
    validEmail($email, 'email');
    //emailの未入力チェック
    validRequired($email, 'email');
  }
    if($dbFormData['introduce'] !== $introduce){
        //自己紹介文字数チェック
        validMinLen($introduce,'introduce');
        validMaxLen($introduce,'introduce',1000);
    }
    if(empty($err_msg)){
        debug('バリデーションOKです');
        //例外処理
        try{
            $dbh = dbConnect();
            $sql = 'UPDATE users SET username = :u_name, addr =:addr, email = :email, m_instrument = :m_instrument, singer_or_not = :s_o_n, introduce = :introduce, pic = :pic WHERE id = :u_id';
            $data = array(':u_name' => $username, ':addr' => $addr, ':email' => $email, ':m_instrument' => $m_instument, ':s_o_n' => $s_o_n, ':introduce'=> $introduce, ':pic' => $pic,':u_id' => $dbFormData['id']);
            
            $stmt = queryPost($dbh, $sql, $data);
            
            //クエリ成功の場合
            if($stmt){
                $_SESSION['msg_success'] = SUC02;
                debug('ユーザー一覧へ遷移します');
                header("Location:userList.php");
            }
        }catch(Exception $e){
            error_log('エラーが発生：'.$e->getMessage());
            $err_msg['common'] = MSG07;
        }
    }
}
debug('画面表示処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php 
    $siteTitle = 'プロフィール編集';
    require('head.php'); 
?>

<?php require('header.php'); ?>

<!-- メインコンテンツ -->
<div id="contents" class="site-width">
    <h1 class="page-title">プロフィール編集</h1>
    <!-- Main -->
    <section id="main">
        <div class="form-container">
            <form action="" method="post" class="form" id="prof" enctype="multipart/form-data">
                <div class="area-msg">
                    <?php 
            if(!empty($err_msg['common'])) echo $err_msg['common'];
            ?>
                </div>
                <label class="<?php if(!empty($err_msg['username'])) echo 'err'; ?>">
                    名前
                    <input type="text" name="username" value="<?php echo getFormData('username'); ?>">
                </label>
                <div class="area-msg">
                    <?php 
            if(!empty($err_msg['username'])) echo $err_msg['username'];
            ?>
                </div>
                <label class="<?php if(!empty($err_msg['addr'])) echo 'err'; ?>">
                    住所・所属
                    <input type="text" name="addr" value="<?php echo getFormData('addr'); ?>">
                </label>
                <div class="area-msg">
                    <?php 
            if(!empty($err_msg['addr'])) echo $err_msg['addr'];
            ?>
                </div>
                <label class="<?php if(!empty($err_msg['email'])) echo 'err'; ?>">
                    Email
                    <input type="text" name="email" value="<?php echo getFormData('email'); ?>">
                </label>
                <div class="area-msg">
                    <?php 
            if(!empty($err_msg['email'])) echo $err_msg['email'];
            ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    主な楽器・パート※
                    <input type="text" name="m_instrument" value="<?php echo getFormData('m_instrument'); ?>">
                </label>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    歌うかどうか<br>
                    <input type="radio" name="singer_or_not" value="ボーカルがしたい！"
                    <?php echo (getFormData('singer_or_not') == "ボーカルがしたい！")? " checked":''; ?>>ボーカルがしたい！
                    <input type="radio" name="singer_or_not" value="ボーカルは希望しない" 
                    <?php echo (getFormData('singer_or_not') == "ボーカルは希望しない")? " checked":''; ?>>ボーカルは希望しない
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['s_o_n'])) echo $err_msg['s_o_n']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    自己PR(6文字以上1000文字以下)
                    <textarea name="introduce" id="" cols="50" rows="10" style="height: 300px;" ><?php echo getFormData('introduce'); ?></textarea>
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['introduce'])) echo $err_msg['introduce']; ?>
                </div>

                プロフィール画像
                <label class="area-drop <?php if(!empty($err_msg['pic'])) echo 'err'; ?>" style="height:370px;line-height:370px;">
                    <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                    <input type="file" name="pic" class="input-file" style="height:370px;">
                    <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
                    ドラッグ＆ドロップ
                </label>
                <div class="area-msg">
                    <?php 
            if(!empty($err_msg['pic'])) echo $err_msg['pic'];
            ?>
                </div>
                <div class="btn-container">
                    <input type="submit" class="btn btn-mid" value="変更する">
                </div>
                <a href="withdraw.php">&lt;退会はこちら</a>
            </form>
        </div>
    </section>

    <!-- footer -->
    <?php
  require('footer.php'); 
  ?>
