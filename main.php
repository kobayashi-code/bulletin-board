<?php
//データベースに接続
$dsn = 'mysql:dbname=データベース名;host=localhost;';
$user='ユーザ名';
$password='パスワード';
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>main</title>
    <link rel="stylesheet" href="./main.css">
</head>
<body>
    <h1>メイン</h1>
    <a href="https://tb-210603.tech-base.net/mission_6-lendMusicRoom.php">防音室を登録</a>
    <a href="https://tb-210603.tech-base.net/mission_6-editMusicRoom.php">掲載情報を編集</a>
    <br>
    <h3>防音室一覧</h3>

<!-- 掲示板の表示 -->
    
<?php   
    try{
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = 'SELECT * FROM musicRoomData';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            echo "<div class=\"bb\">";
            if($row["video"] == "jpeg"){
                echo "<img src=\"./gazou/".$row["image"]."\">";
            }else if($row["video"]== "mp4"){
                echo "<video autoplay controls width=\"265px\" src=\"./gazou/".$row["image"]."\"></video>";
            }
            echo "<table>
                    <tr>
                        <td>所在地</td>
                        <td>".$row['address']."</td>
                    </tr>
                    <tr>
                        <td>最寄駅</td>
                        <td>".$row['station']."</td>
                    </tr>
                    <tr>
                        <td>連絡先</td>
                        <td>".$row['email']."</td>
                    </tr>
                </table>";
            echo "</div>";
        }
    }catch(Exeption $e){
        $res = $e->getMessage();
    }
?>
</body>
</html>