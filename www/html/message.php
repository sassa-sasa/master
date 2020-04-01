<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<title>ひと言掲示板</title>
<link rel="stylesheet" type="text/css" href="/css/message.css">
</head>

<?php

$now_date = null;
$data = null;
$split_data = null;
$message = array();
$message_array = array();
$success_message = null;
$error_message = array();
var_dump($_POST);

if( !empty($_POST['btn_submit']) ) {
	
	// タイトルの入力チェック
	if( empty($_POST['sub_title']) ) {
    $error_message[] = '表示名を入力してください。';
	  } else {
		$clean['sub_title'] = htmlspecialchars( $_POST['sub_title'], ENT_QUOTES);
	  }
  
  	// ニックネームの入力チェック
	if( empty($_POST['user_name']) ) {
    $error_message[] = 'ニックネームを入力してください。';
	  } else {
		  $clean['user_name'] = htmlspecialchars( $_POST['user_name'], ENT_QUOTES);
	  }

	// メッセージの入力チェック
	if( empty($_POST['message']) ) {
		$error_message[] = 'ひと言メッセージを入力してください。';
	  } else {
		$clean['message'] = htmlspecialchars( $_POST['message'], ENT_QUOTES);
		$clean['message'] = preg_replace( '/\\r\\n|\\n|\\r/', '', $clean['message']);
  }
}

if( empty($error_message) ) {
  
  try {
    # hostには「docker-compose.yml」で指定したコンテナ名を記載
    $dsn = "mysql:host=192.168.0.3;dbname=testdb;charset=utf8;";
    $db = new PDO($dsn, 'user', 'password');
    // 書き込み日時を取得
    $now_date = date("Y-m-d H:i:s");
    // データを登録するSQL作成
    $sql = "INSERT INTO `tbl_message` (`sub_title`,`user_name`,`message`,`insert_date`) VALUES ('$clean[sub_title]','$clean[user_name]','$clean[message]','$now_date');";
    // データを登録
    $res = $db->query($sql);
    if( $res ) {
      // $success_message = 'メッセージを書き込みました。';
      echo 'メッセージを書き込みました。';
      var_dump($res);
    } else {
       $res = $db->prepare($sql);
       var_dump($res);
      $error_message[] = '書き込みに失敗しました。<br>';
    }
    // $result = $res->fetchAll(PDO::FETCH_ASSOC);
    // var_dump($result);  
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit;
  }
}
?>

<body>
<h1>ひと言掲示板</h1>
<?php if( !empty($error_message) ): ?>
	<ul class="error_message">
		<?php foreach( $error_message as $value ): ?>
			<li>・<?php echo $value; ?></li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<!-- ここにメッセージの入力フォームを設置 -->
<form method="post">
	<div>
		<label for="sub_title">タイトル</label>
		<input id="sub_title" type="text" name="sub_title" value="">
  </div>
  <div>
		<label for="user_name">ニックネーム</label>
		<input id="user_name" type="text" name="user_name" value="">
  </div>
	<div>
		<label for="message">ひと言メッセージ</label>
		<textarea id="message" name="message"></textarea>
	</div>
	<input type="submit" name="btn_submit" value="書き込む">
</form>
<hr>
<section>
<!-- ここに投稿されたメッセージを表示 -->
<?php
try {
    # hostには「docker-compose.yml」で指定したコンテナ名を記載
    $dsn = "mysql:host=192.168.0.3;dbname=testdb;charset=utf8;";
    $db = new PDO($dsn, 'user', 'password');
    // データを登録するSQL作成
    $sql = "SELECT `sub_title`,`user_name`,`message`,`insert_date` FROM `tbl_message` ORDER BY `insert_date` DESC;";
    // データを登録
    $res = $db->query($sql);
    if( !empty($res) ){
      foreach( $res as $value ) { ?>
        <article>
          <div class="info">
            <h2><?php echo $value['sub_title']; ?></h2>
            <time><?php echo $value['insert_date']; ?></time>
          </div>
          <p><?php echo $value['message']; ?></p>
        </article>
      <?php } ?>

    <?php foreach( $res as $value ) {
      $value['insert_date'];
    }
    } else {
      echo "<h2>メッセージはありません</h2>";
    }
    
  } catch (PDOException $e) {
    echo $e->getMessage();
    exit;
  }
  ?>
</section>
</body>
</html>