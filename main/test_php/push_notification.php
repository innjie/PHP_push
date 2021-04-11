<meta charset="UTF-8">
<?php
    
	function send_notification($tokens, $message)
	{
	 $url='https://fcm.googleapis.com/fcm/send';
	 $fields= array(
	 	         'registration_ids' => $tokens,
            	 'data' => $message
            );
            
	$headers=array(
		         'Authorization:key ='. GOOGLE_API_KEY,
           		 'Content-Type: application/json'
            );
	
	$ch=curl_init();
	    curl_setopt($ch,CURLOPT_URL, $url);
	    curl_setopt($ch, CURLOPT_POST,true);
	    curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
      	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
       	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	 $result = curl_exec($ch);           
       if ($result === FALSE) {
           die('Curl failed: ' . curl_error($ch));
       }
       curl_close($ch);
       return $result;
    }
    
 
    //데이터베이스에 접속해서 토큰들을 가져와서 FCM에 발신요청
    include_once 'config.php';
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
 
    $sql = "Select Token From PushUser WHERE state=1";
 
    $result = mysqli_query($conn,$sql);
    $tokens = array();
    if(mysqli_num_rows($result) > 0 ){
        echo mysqli_num_rows($result);
        while ($row = mysqli_fetch_assoc($result)) {
            $tokens[] = $row["Token"];
            echo $tokens[0];
        }
    }
    
    mysqli_close($conn);
    
    $myMessage = $_POST['message']; //폼에서 입력한 메세지를 받음
    if ($myMessage == ""){
        $myMessage = "디폴트 메시지.";
    }
 
    $message = array("message" => $myMessage);
    
    //토큰 나누기
    $arr_chunk = array_chunk($tokens, 10);
    foreach($arr_chunk as $arr_token){
        //나눠진 $arr_token 배열 foreach문으로 나눠보낸다
        $message_status = send_notification($arr_token, $message);   
    }
    //$message_status = send_notification($tokens, $message);
    echo $message_status;
?>