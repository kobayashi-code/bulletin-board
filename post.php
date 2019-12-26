<?php
//ページの切り替え
$page_flag = "";
$page_flag=0;
if(!empty($_POST["fileup_btn"])){
	$page_flag=1;
}
else if(!empty($_POST["conform_btn"])){
	$page_flag=2;
}else if( !empty($_POST['submit_btn']) ) {
	$page_flag = 3;
}else{
	$page_flag = 0;
}

//データベースに接続
$dsn='mysql:dbname=データベース名;host=localhost;';
$user='ユーザ名';
$password='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//ファイルアップロードがあったとき
$message = "";
if(isset($_FILES["upfile"])){
    $upfile = "";
    $upfile_name = "";
    $upfile = $_FILES["upfile"];
    $upfile_name = $upfile["name"];

    // 拡張子を調べる
    $tmp = pathinfo($_FILES["upfile"]["name"]);
    $extension = $tmp["extension"];
    if($extension === "jpg" || $extension === "jpeg" || $extension === "JPG" || $extension === "JPEG"){
        $extension = "jpeg";
    }elseif($extension === "mp4" || $extension === "MP4"){
        $extension = "mp4";
    }else{
    	$message = "非対応ファイルです";
    }

    if($upfile["size"] > 0){
    	if($upfile["size"] > 10000000){
    			$message= "ファイルが大きすぎます";
    	}else{      
    			move_uploaded_file($upfile["tmp_name"],'./gazou/'.$upfile["name"]);
    	}
    }
}
    
//動画、画像以外
if( isset($_POST["zip01"]) && isset($_POST["pref01"]) && isset($_POST["addr01"]) && isset($_POST["station"]) && isset($_POST["email"]) ){
	//変数に格納
	$zip01="";
	$pref01=""; 
	$addr01=""; 
	$station=""; 
	$email=""; 
	$zip01 = $_POST["zip01"]; 
	$pref01 = $_POST["pref01"];
	$addr01 = $_POST["addr01"];
	$station = $_POST["station"];
	$email = $_POST["email"];
	$upfile_name = $_POST["upfile_name"];
	$extension = $_POST["extension"];
//ここからデータベースに書き込み
//送信ボタンが押された
	if( isset($_POST["submit_btn"]) ){
		//ファイル以外
			$addr = $pref01.$addr01;
			$sql = $pdo -> prepare("INSERT INTO musicRoomData (postalCode, address, station, email, image, video) VALUES (:postalCode, :address, :station, :email, :image, :video)");
			$sql -> bindParam(':postalCode', $zip01, PDO::PARAM_STR);
			$sql -> bindParam(':address', $addr, PDO::PARAM_STR);
			$sql -> bindParam(':station', $station, PDO::PARAM_STR);
			$sql -> bindParam(':email', $email, PDO::PARAM_STR);
			$sql -> bindParam(':image', $upfile_name, PDO::PARAM_STR);
			$sql -> bindParam(':video', $extension, PDO::PARAM_STR);
			$sql -> execute();
	}
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>lendMusicRoom</title>
	<link rel="stylesheet" href="mission_6-lendMusicRoom.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
	<script src="https://ajaxzip3.github.io/ajaxzip3.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.17.0/jquery.validate.min.js"></script>
	<script　src="https://tb-210603.tech-base.net/mission_6-comp/mission_6-lendMusicRoom.js"></script>
</head>
<body>
	<?php if( $page_flag === 2 ): ?>
	<!-- 確認ページここから -->
	<form action="" method="post">
		<label>郵便番号(ハイフンなし)</label>		
		<input type="hidden" value="<?php echo $zip01 ;?>"name="zip01">
		<p><?php echo $zip01; ?></p>		
		
		<label>都道府県</label>	
		<input type="hidden" value="<?php echo $pref01 ;?>"name="pref01" >
		<p><?php  echo $pref01; ?></p>
  		
  		<label>以降の住所</label>
		<input type="hidden" value="<?php echo $addr01 ;?>"name="addr01" >		
		<p><?php  echo $addr01; ?></p>
		
		<label>最寄駅</label>
		<input type="hidden" value="<?php echo $station ;?>"name="station">				
		<p><?php  echo $station; ?></p>

		<label>お問い合わせ用メールアドレス（公開されます）</label>
		<input type="hidden" value="<?php echo $email ;?>"name="email">
		<p><?php  echo $email; ?></p>

		<input type="hidden" name="upfile_name" value="<?php echo $upfile_name;?>">
		<input type="hidden" name="extension" value="<?php echo $extension;?>">

		<input type="button" onclick = "history.back()" value="戻る">
		<input type="submit" name="submit_btn" value="送信">
	</form>
	<!-- 確認ページここまで -->

	<?php elseif( $page_flag === 3 ): ?>

	<p>送信完了しました</p>
	<?php header("refresh:2;url=https://tb-210603.tech-base.net/mission_6-main.php")?>

	<?php elseif( $page_flag === 1): ?>
	<p><?php if(isset($message)){echo $message;} ?></p>
	<form method="post" action="">
  		<label>郵便番号(ハイフンなし７桁) <strong>必須</strong></label>
  		<input class="required" type="text" name="zip01" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','pref01','addr01');">
  		<br><br><label>都道府県 <strong>必須</strong></label>
  		<input class="required" type="text" name="pref01" size="20">
  		<br><br><label>以降の住所 <strong>必須</strong></label>
		<input class="required" type="text" name="addr01" size="60">
		<br><br><label>最寄駅 <strong>必須</strong></label>
		<input class="required" type="text" name="station"　placeholder="例）新宿駅　徒歩５分">
		<br><br><label>お問い合わせ用メールアドレス（公開されます） <strong>必須</strong></label>
		<input class="required" type="email" name="email">
		<input type="hidden" name="upfile_name" value="<?php echo $upfile_name;?>">
		<input type="hidden" name="extension" value="<?php echo $extension;?>">

		<br><br><input type="button" onclick = "history.back()" value="戻る">
		<input type="submit" name="conform_btn" value="入力内容を確認">
	</form>	
	<?php else: ?>
	<form method="post" action="" enctype="multipart/form-data">
	<label>画像/動画アップロード</label>
        <input type="file" name="upfile">
        <br>
        <p>※画像はjpeg方式に対応しています．動画はmp4方式に対応しています．<br></p>
        <input type="submit" value="アップロード" name="fileup_btn">
	</form>
	
	<?php endif; ?>

</body>
</html>