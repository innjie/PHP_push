<meta charset="UTF-8">
<?php
include_once 'config.php';

$conn=mysqli_connect(DB_HOST,DB_USER, DB_PASSWORD, DB_NAME);

if($conn){
	echo"접속 성공<br>";
	}

else{
	echo"접속 실패<br>";
	}

$token=$_POST["Token"];
if($token==NULL) echo "토큰값 없음<br>";
else{
$db_sql="INSERT INTO PushUser(Token) Values ('$token') ON DUPLICATE KEY UPDATE Token='$token';";
$id_sql="SELECT id FROM PushUser WHERE Token ='$state';";
mysqli_query($conn, $db_sql);
$id=mysqli_query($conn, $id_sql);

echo json_encode($id);
mysqli_close($conn);
}
?>