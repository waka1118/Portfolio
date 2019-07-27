<?php
    require('function.php');
    debug('「「「「「「「「「「「「「「「「「「');
    debug('ログイン後トップページ');
    debug('「「「「「「「「「「「「「「「「「「');
    debugLogStart();

//================================
//画面処理
//================================
//ログイン認証
require('auth.php');
//画面表示用データ取得
//GETパラメータ取得
//カレント（現在）ペーじ
$currentPageNum = (!empty($_GET['p'])) ? $_GET['p'] : 1; //デフォルトは一ページ目
//ユーザーID格納
$u_id = $_SESSION['user_id'];
//パラメータに不正な値が入っているかチェック
//if(is_int($currentPageNum)){
//    error_log('エラー発生：指定のページに不正な値が入りました');
    //header("Location:mypage.php");//トップページへ
//}

//表示件数
$listSpan = 8;
//現在の表示レコード先頭を算出
$currentMinNum = (($currentPageNum-1)*$listSpan);//1ページ目なら(1-1)*9 =0,2ページ目なら(2-1)*9 = 9
//DBからユーザー情報を取得
$dbUserData = getUserList($currentMinNum);
//DBから自分のユーザー情報を取得
$dbMyUserData = getUserOne($u_id);
//var_dump($dbUserData);

//DBから連絡掲示板情報を取得
$bordData = getMyMsgsAndBord($u_id);
//var_dump($bordData);
//DBからデータが全て取れるかのチェックはなし、取れなければ非表示
debug('UserListページにて取得した掲示板データ：'.print_r($bordData,true));

debug('画面表示処理終了＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞＞');
?>

<?php
$siteTitle = 'ユーザー一覧';
require('head.php'); 
?>
<p id="js-show-msg"
  style="display:none;
  position: fixed;
  top: 0;
  width:100%;
  height:40px;
  background: rgba(122,206,230,0.6);
  text-align: center;
  font-size:16px;
  line-height: 40px;" class="msg-slide">
<!--  なぜか外部ファイルを読み込まないため直書き -->
    <?php echo getSessionFlash('msg_success'); ?>
</p>
<?php require('header.php'); ?>
<!--jsアニメーション-->


<body class="page-mypage page-1colum page-logined">
    <style>
        #main {
            border: none !important;
        }

    </style>

    <!-- メインコンテンツ -->
    <div id="contents" class="site-width">
    
 <!--連絡掲示板-->
       <section class="list list-table">
           <h2 class="title">連絡掲示板</h2>
           <table class="table">
              <thead>
               <tr>
                   <th>最新送信日時</th>
                   <th>送信相手</th>
                   <th>メッセージ</th>
               </tr>
               </thead>
               <tbody>
                   <?php 
                        if(!empty($bordData)){
                            foreach($bordData as $key => $val){
                                if(!empty($val['msg'])){
                                    $msg = array_shift($val['msg']);
                                    $fromUserName = getUserName($val['receive_user']);
                                    //debug('$fromUserNameの中身：'.print_r($fromUserName,true));
                                    //var_dump($val['receive_user']);
                   ?>
                <tr>
                    <td><?php echo sanitize(date('Y.m.d H:i:s', strtotime($msg['send_date']))); ?></td>
                    <td><?php echo $fromUserName['username']; ?></td>
                    <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>"><?php echo mb_substr(sanitize($msg['msg']),0,40); ?>...</a></td>
                </tr>
                <?php }else{ ?>
                <tr>
                    <td>--</td>
                    <td>--</td>
                    <td><a href="msg.php?m_id=<?php echo sanitize($val['id']); ?>">まだメッセージはありません。</a></td>
                </tr>
                <?php
                           }
                        }
                    }
                ?>
               </tbody>
           </table>
       </section>
        <!-- Main -->
        <section id="main">
            
            <!--ユーザー一覧表示-->
               <h2>Member List</h2>
               <div class="search-title">
            <!--      ユーザー名前表示        -->
               <?php foreach((array)$dbMyUserData as $key) :?>
                <div class="search-left">
                    ようこそ、<b><?php echo $key['username']; ?></b>さん。
                </div>
                <?php endforeach; ?>
                <div class="search-right">
                    <span class="total-num">
                        <?php echo sanitize($dbUserData['total']); ?></span>件のユーザーが見つかりました。
                </div>
                <div class="search-right2">
                    <span class="num">
                        <?php echo (!empty($dbUserData['data'])) ? $currentMinNum+1 : 0; ?></span> - <span class="num">
                        <?php echo $currentMinNum+count($dbUserData['data']); ?></span>件 / <span class="num">
                        <?php echo sanitize($dbUserData['total']); ?></span>件中
                </div>
            </div>
            <div class="panel-list">
                <?php foreach((array)$dbUserData['data'] as $key) : ?>
                <a href="userDetail.php<?php echo '?u_id='.$key['id']; ?>" class="panel">
                    <div class="panel-head">
                        <img src="<?php echo showImg(sanitize($key['pic'])); ?>" alt="<?php echo sanitize($key['username']); ?> ">
                    </div>
                    <div class="panel-body">
                        <p class="panel-title">
                            <?php echo sanitize($key['username'])."　さん"; ?>
                        </p>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>

            <?php pagination($currentPageNum, $dbUserData['total_page']); ?>


        </section>
               
    </div>


    <?php require('footer.php'); ?>
