<meta charset="UTF-8">
<?php  
    include_once 'config.php';
    $conn=mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    if(conn){
        echo "연결성공<br>";
    }
    else echo "연결실패<br>";
    $token=$_POST["Token"];
    $state=(int)$_POST["state"];
    echo $state;
    $db_sql="UPDATE PushUser SET state=$state WHERE Token='$token';";
    mysqli_query($conn, $db_sql);
    $response=array();
    $response["success"]=true;

    echo json_encode($response);
    mysqli_close($conn);
    ?>