<?php 
require('function.php');

debug('「「「「「「「「「「「「「「「「「「「「「');
debug('ユーザー登録画面');
debug('「「「「「「「「「「「「「「「「「「「「「');
debugLogStart();

if(!empty($_POST)){
    
    //変数にユーザー情報代入
    $username = $_POST['username'];
    $addr = $_POST['addr'];
    $age = $_POST['age'];
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $pass_re = $_POST['pass_re'];
    $m_inst = $_POST['m_instrument'];
    $gender = $_POST['gender'];
    $s_o_n = $_POST['sing'];
    $introduce = $_POST['introduce'];
    $pic = (!empty($_FILES['pic']['name']))? uploadImg($_FILES['pic'],'pic') : '';
    //未入力チェック
    validRequired($username,'username');
  validRequired($email, 'email');
  validRequired($pass, 'pass');
  validRequired($pass_re, 'pass_re');
    validRequired($age,'age');
    //validRequired($gender,'gender');
    //validRequired($s_o_n,'sing');

  if(empty($err_msg)){

    //emailの形式チェック
    validEmail($email, 'email');
    //emailの最大文字数チェック
    validMaxLen($email, 'email');
    //email重複チェック
    validEmailDup($email);

    //パスワードの半角英数字チェック
    validHalf($pass, 'pass');
    //パスワードの最大文字数チェック
    validMaxLen($pass, 'pass');
    //パスワードの最小文字数チェック
    validMinLen($pass, 'pass');

    //パスワード（再入力）の最大文字数チェック
    validMaxLen($pass_re, 'pass_re');
    //パスワード（再入力）の最小文字数チェック
    validMinLen($pass_re, 'pass_re');

    if(empty($err_msg)){

      //パスワードとパスワード再入力が合っているかチェック
      validMatch($pass, $pass_re, 'pass_re');

        //自己紹介文字数チェック
        validMinLen($introduce,'introduce');
        validMaxLen($introduce,'introduce',1000);
        
      if(empty($err_msg)){
          debug('バリデーションOK');
          //例外処理
        try {
          // DBへ接続
          $dbh = dbConnect();
          // SQL文作成
          $sql = 'INSERT INTO users (username, addr, age, gender, email, password, m_instrument, singer_or_not, introduce, create_date, login_time, pic) VALUES(:username,:addr,:age,:gender,:email, :pass, :m_instrument, :s_o_n,:introduce, :create_date, :login_time, :pic)';
          $data = array(':email' => $email, 
                        ':pass' => password_hash($pass, PASSWORD_DEFAULT),
                        ':login_time' => date('Y-m-d H:i:s'),
                        ':create_date' => date('Y-m-d H:i:s'),
                        ':username' => $username,
                        ':addr' => $addr, 
                        ':age' => $age,
                        ':gender' => $gender, 
                        ':m_instrument' => $m_inst, 
                        ':s_o_n'=> $s_o_n, 
                        ':introduce' => $introduce,
                        ':pic' => $pic);
          // クエリ実行
          $stmt = queryPost($dbh, $sql, $data);
          
          // クエリ成功の場合
          if($stmt){
            //ログイン有効期限（デフォルトを１時間とする）
            $sesLimit = 60*60;
            // 最終ログイン日時を現在日時に
            $_SESSION['login_date'] = time();
            $_SESSION['login_limit'] = $sesLimit;
            // ユーザーIDを格納
            $_SESSION['user_id'] = $dbh->lastInsertId();

            debug('セッション変数の中身：'.print_r($_SESSION,true));

            header("Location:userlist.php"); //マイページへ
          }

        } catch (Exception $e) {
          error_log('エラー発生:' . $e->getMessage());
          $err_msg['common'] = MSG07;
        }
      }
    }
  }
}

?>

<?php 
$siteTitle = 'ユーザー登録';
require('head.php');
?>
<?php require('header.php'); ?>

<body class="page-signup page-1colum">
    <div id="contents" class="site-width">
        <h1 class="page-title">ユーザー登録</h1>
        <!--  メイン  -->
        <section id="main">
           <div class="form-container">
            <form action="" method="post" class="form" id="prof" enctype="multipart/form-data">
                ※は任意入力箇所です
                <div class="area-msg">
                    <?phpif(!empty($err_msg['common'])) echo $err_msg['common']; ?>
                </div>
                <div style="overflow:hidden;">
              <div class="imgDrop-container">
                プロフィール画像
                <label class="area-drop">
                  <input type="hidden" name="MAX_FILE_SIZE" value="3145728">
                  <input type="file" name="pic" class="input-file">
                   <img src="<?php echo getFormData('pic'); ?>" alt="" class="prev-img" style="<?php if(empty(getFormData('pic'))) echo 'display:none;' ?>">
                    ドラッグ＆ドロップ
                </label>
                <div class="area-msg">
                  <?php 
                  if(!empty($err_msg['pic'])) echo $err_msg['pic'];
                  ?>
                </div>
              </div>
            </div>
               
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    名前
                    <input type="text" name="username" value="<?php if(!empty($_POST['username'])) echo $_POST['username']; ?>">
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['username'])) echo $err_msg['username']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    住所※
                    <input type="text" name="addr" value="<?php if(!empty($_POST['addr'])) echo $_POST['addr']; ?>">
                </label>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    年齢
                    <input type="number" name="age" value="<?php if(!empty($_POST['age'])) echo $_POST['age']; ?>">
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['age'])) echo $err_msg['age']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    性別
                    <input type="radio" name="gender" value="男性" checked>男性
                    <input type="radio" name="gender" value="女性">女性
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['gender'])) echo $err_msg['gender']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    メールアドレス（ログインにも使います）
                    <input type="text" name="email" value="<?php if(!empty($_POST['email'])) echo $_POST['email']; ?>">
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['email'])) echo $err_msg['email']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    パスワード（ログインにも使います）
                    <input type="password" name="pass" value="<?php if(!empty($_POST['pass'])) echo $_POST['pass']; ?>">
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['pass'])) echo $err_msg['pass']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    パスワード再入力
                    <input type="password" name="pass_re" value="<?php if(!empty($_POST['pass_re'])) echo $_POST['pass_re']; ?>">
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['pass_re'])) echo $err_msg['pass_re']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    主な楽器・パート※
                    <input type="text" name="m_instrument" value="<?php if(!empty($_POST['m_instrument'])) echo $_POST['m_instrument']; ?>">
                </label>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    歌うかどうか<br>
                    <input type="radio" name="sing" value="ボーカルがしたい！">ボーカルがしたい！
                    <input type="radio" name="sing" value="ボーカルは希望しない" checked>ボーカルは希望しない
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['s_o_n'])) echo $err_msg['s_o_n']; ?>
                </div>
                <label class="<?php if(!empty($err_msg)) echo 'err'; ?>">
                    自己PR(6文字以上1000文字以下)
                    <textarea name="introduce" id="" cols="50" rows="10" style="height: 300px;" value="<?php if(!empty($_POST['introduce'])) echo $_POST['introduce']; ?>"></textarea>
                </label>
                <div class="area-msg">
                    <?php if(!empty($err_msg['introduce'])) echo $err_msg['introduce']; ?>
                </div>

                <div class="btn-container">
                    <input type="submit" class="btn btn-mid" value="登録する">
                </div>
            </form>
            </div>
        </section>
    </div>


    <?php 
    require('footer.php');
?>
