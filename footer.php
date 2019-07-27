  <footer id="footer">
    <small style="color: black;">(C)2019 Music Guild.</small>
  </footer>
  <!-- footer終わり -->
  <script src="https://code.jquery.com/jquery-2.2.2.min.js"></script>
  <script>
      $(function(){
          
          // フッターを最下部に固定
    var $ftr = $('#footer');
    if( window.innerHeight > $ftr.offset().top + $ftr.outerHeight() ){
      $ftr.attr({'style': 'position:fixed; top:' + (window.innerHeight - $ftr.outerHeight()) +'px;' });
    }
    // メッセージ表示
    var $jsShowMsg = $('#js-show-msg');
    var msg = $jsShowMsg.text();
    if(msg.replace(/^[\s　]+|[\s　]+$/g, "").length){
      $jsShowMsg.slideToggle('slow');
      setTimeout(function(){ $jsShowMsg.slideToggle('slow'); }, 5000);
    }
    // 画像切替
    var $switchImgSubs = $('.js-switch-img-sub'),
        $switchImgMain = $('#js-switch-img-main');
    $switchImgSubs.on('click',function(e){
      $switchImgMain.attr('src',$(this).attr('src'));
    });
      //画像ライブプレビュー
      var $dropArea = $('.area-drop');
      var $fileInput = $('.input-file');
      
      $dropArea.on('dragover', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border','3px #ccc dashed' );
      });
      $dropArea.on('dragleave', function(e){
          e.stopPropagation();
          e.preventDefault();
          $(this).css('border', 'none');
      });
      $fileInput.on('change', function(e){
          $dropArea.css('border', 'none');
          var file = this.files[0],                     //files配列にファイルが入っている
          $img = $(this).siblings('.prev-img'),     //jQueryのsiblingsメソッドで兄弟のimgを取得
          fileReader = new FileReader();            //ファイルを読み込むFileReaderオブジェクト
          
          
          //読み込みが完了した際のイベントハンドラ。imgのsrcにデータをセット。
          fileReader.onload = function(event) {
              //読み込んだデータをimgに設定
              $img.attr('src', event.target.result).show();
          };
          //画像読み込み
          fileReader.readAsDataURL(file);
      });
      });
      
</script>
  
</body>
</html>