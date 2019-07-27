<header>
    <div class="logo">
      <a href="index.php">Let's Music</a>
    </div>
    <nav>
      <ul class="global-nav">
       <?php if(empty($_SESSION['user_id'])){ ?>
        <li><a href="signup.php">ユーザー登録</a></li>
        <li><a href="login.php">ログイン</a></li>
        <?php }else{ ?>
        <li><a href="userList.php">ユーザー一覧</a></li>
        <li><a href="profEdit.php">プロフ編集</a></li>
        <li><a href="mypage.php">マイページ</a></li>
        <li><a href="logout.php">ログアウト</a></li>
        <?php } ?>
      </ul>
    </nav>
  </header>