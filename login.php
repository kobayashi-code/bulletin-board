<?php
//サーバーに接続
$dsn='mysql:dbname=データベース名;host=localhost;';
$user='ユーザ名';
$password='パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

// エラーメッセージの初期化
$errorMessage = "";

// ログインボタンが押された場合
if(isset($_POST["login"])){
    // 1. ユーザ名の入力チェック
    if (empty($_POST["userid"])) {  
        $errorMessage = 'ユーザー名が未入力です。';
    } else if (empty($_POST["password"])) {
        $errorMessage = 'パスワードが未入力です。';
    }

    if (!empty($_POST["userid"]) && !empty($_POST["password"])) {
        // 入力したユーザIDを格納
        $userid = $_POST["userid"];
        $password=$_POST["password"];
    }

    //ユーザ名とパスワードがかぶっているか調べる
    $sql = 'SELECT * FROM userData WHERE userName ='."'".$userid."'" ;
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        if($row['userPassword'] != $password){//ユーザ名は存在するが、パスワードが違う
        $errorMessage = "ユーザー名あるいはパスワードに誤りがあります。";
        }
    }
    if(empty($errorMessage)){
        header("Location:https://tb-210603.tech-base.net/mission_6-main.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ログイン</title>
    <link rel="stylesheet" href="mission_6-login.css">
</head>
<body>
<h1>ログイン画面</h1>
        <form id="loginForm" name="loginForm" action="" method="POST">
            <fieldset>
                
                <div><font color="#ff0000"><?php echo htmlspecialchars($errorMessage, ENT_QUOTES); ?></font></div>
                <label for="userid">ユーザー名</label><input type="text" id="userid" name="userid" placeholder="ユーザーIDを入力" value="<?php if (!empty($_POST["userid"])) {echo htmlspecialchars($_POST["userid"], ENT_QUOTES);} ?>">
                <br>
                <label for="password">パスワード</label><input type="password" id="password" name="password" value="" placeholder="パスワードを入力">
                <br>
                <input type="submit" id="login" name="login" value="ログイン">
            </fieldset>
        </form>
        <br>
        <form action="mission_6-registerMember.php">
            <fieldset>          
                <legend>新規登録フォーム</legend>
                <input type="submit" value="新規登録">
            </fieldset>
        </form>
</body>
</html>