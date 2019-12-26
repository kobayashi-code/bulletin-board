<?php
// データベースへ接続
$dsn='mysql:dbname=データベース名;host=localhost;';
$user='ユーザ名';
$password='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

//テーブル作成
$sql = "CREATE TABLE IF NOT EXISTS userData"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "userName char(32),"
	. "userPassword TEXT"
	.");";
	$stmt = $pdo->query($sql);

// エラーメッセージ、登録完了メッセージの初期化
$errorMessage = "";
$signUpMessage = "";

// ログインボタンが押された場合
if (isset($_POST["signUp"])) {
    // 1. ユーザ名の入力チェック
    if (empty($_POST["username"])) {  // 値が空のとき
        $errorMessage = 'ユーザーIDが未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if (empty($_POST["password2"])) {
        $errorMessage = 'パスワードが未入力です。';
    } else if ($_POST["password"] != $_POST["password2"]){
        $errorMessage = 'パスワードが一致していません。';
    }

    if (!empty($_POST["username"]) && !empty($_POST["password"]) && !empty($_POST["password2"]) && $_POST["password"] === $_POST["password2"]) {
        // 入力したユーザ名とパスワードを格納
        $username = $_POST["username"];
        $password = $_POST["password"];

        //ユーザ名とパスワードがかぶっていないか調べる
        $sql = 'SELECT * FROM userData';
	    $stmt = $pdo->query($sql);
	    $results = $stmt->fetchAll();
	    foreach ($results as $row){
		    if($row['userName']==$username){
                $errorMessage="そのユーザ名はすでに使われています。";
            }else if($row['userPassword']==$password){
                $errorMessage="そのパスワードはすでに使われています。";
            }
        }   
            //かぶっていなかったら登録する
        if(empty($errorMessage)){
            $sql = $pdo -> prepare("INSERT INTO userData (userName, userPassword) VALUES (:userName, :userPassword)");
	        $sql -> bindParam(':userName', $userName, PDO::PARAM_STR);
	        $sql -> bindParam(':userPassword', $userPassword, PDO::PARAM_STR);
	        $userName = $username;
	        $userPassword = $password; 
            $sql -> execute();
            $signUpMessage="登録が完了しました。";
        }
    }   
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>新規ユーザ登録</title>
    </head>
    <body>
        <h1>新規登録画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                <legend>新規登録フォーム</legend>
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <div><font color="#0000ff"><?php echo htmlspecialchars($signUpMessage, ENT_QUOTES); ?></font></div>
                <label for="username">ユーザー名</label><input type="text" id="username" name="username" placeholder="ユーザー名を入力" value="<?php if (!empty($_POST["username"])) {echo htmlspecialchars($_POST["username"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <label for="password2">パスワード(確認用)</label><input type="password" id="password2" name="password2" value="" placeholder="再度パスワードを入力">
                <br>
                <input type="submit" id="signUp" name="signUp" value="新規登録">
            </fieldset>
        </form>
        <br>
        <form action="login.php">
            <input type="submit" value="戻る">
        </form>
    </body>
</html>