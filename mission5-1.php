<?php

            $name = $_POST["name"];
            $comment = $_POST["comment"];
            $pass_sub = $_POST["pass_sub"];
            $submit = $_POST["submit"];
            $date = date(" Y-m-d H:i:s");
            $delete_num = $_POST["delete_num"];
            $pass_dele = $_POST["pass_dele"];
            $delete = $_POST["delete"];
            $edit_num = $_POST["edit_num"];
            $pass_edit = $_POST["pass_edit"];
            $edit = $_POST["edit"];
            $edit_hidden = $_POST["edit_hidden"];
// DB接続設定
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//DBテーブル作成
$sql = "CREATE TABLE IF NOT EXISTS mission5"
	." ("
	. "id INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date DATETIME,"
	. "pass char(30)"
	.");";
	$stmt = $pdo->query($sql);
//投稿
if (isset($submit)){
    if(!empty($name) && !empty($comment) && !empty($pass_sub)){
         $sql = $pdo -> prepare("INSERT INTO mission5 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
	     $sql -> bindParam(':name', $name, PDO::PARAM_STR);
	     $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
	     $sql -> bindParam(':date', $date,PDO::PARAM_STR);
	     $sql -> bindParam(':pass', $pass_sub,PDO::PARAM_STR);
	     
	     $sql -> execute();
    }
}

	    
//投稿削除
if(isset($delete)){
$sql = "SELECT * FROM mission5 where id=$delete_num";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
     if(!empty($delete_num) && $row['pass']==$pass_dele) {
    $id=$delete_num;
	$sql = 'delete from mission5 where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();
    }
  }
}
//投稿編集
if(isset($edit)){
  if(empty($name) && empty($comment)){
    $sql = "SELECT * FROM mission5 where id=$edit_num";
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	    foreach ($results as $row){
	       if($row['pass']==$pass_edit){
	        $rename=$row['name'];
		    $recomment=$row['comment'];
	    }
	    }
  }
  if(!empty($edit_hidden) && !empty($name) && !empty($comment)){
	$sql = 'SELECT * FROM mission5';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
    if($_POST["edit_hidden"]==$row['id']){
        $id =$edit_hidden;
        $sql = 'update mission5 SET name=:name,comment=:comment WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }   
      }
   }
}
?>
<!DOCTYPE html>
<html lang = "ja">
    <head>
        <meta charset = "UTF-8">
        <title>mission 5-1</title>
    </head>
    <body>
        <h1>簡易掲示板</h1>
        <p>投稿：名前とコメント、パスワードを入力して送信</p>
        <p>削除：削除したい番号と投稿時に設定したパスワードを入力し削除</p>
        <p>編集：編集したい番号と投稿時に設定したパスワードを入力し編集を押した後、内容を変更してもう一度編集を押す</p>
        <hr>
        <form action ="" method = "post">
            名前:<br>
            <input type = "text" name = "name" placeholder = "名前" value="<?php echo $rename;?>"><br>
            コメント:<br>
            <textarea name = "comment" cols ="50" rows = "5" placeholder = "コメント" ><?php echo $recomment;?></textarea><br>
            パスワード:<br>
            <input type = "text" name = "pass_sub" placeholder = "パスワード">
            <input type = "submit" name = "submit">
            <br>
            削除:<br>
            <input type = "number" name = "delete_num" placeholder = "削除投稿番号">
            <input type = "text" name = "pass_dele" placeholder = "パスワード">
            <input type = "submit" name = "delete" value = "削除">
            <br>
            編集:<br>
            <input type="hidden" name = "edit_hidden" value="<?php echo $edit_num?>">
            <input type="text" name ="edit_num" placeholder = "編集投稿番号">
            <input type="text" name = "pass_edit" placeholder = "パスワード">
            <input type="submit" name = "edit" value="編集">
        </form>
        <hr>
<?php
$sql = 'SELECT * FROM mission5';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['date'].'<br>';
	echo "<hr>";
	}
	
?>
</body>
</html>
