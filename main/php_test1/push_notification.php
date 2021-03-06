<meta charset="UTF-8">
<?php
	// function send_notification($tokens, $message)
	// {
	//     $url='https://fcm.googleapis.com/fcm/send';
	//     $fields= array(
	//  	         'registration_ids' => $tokens,
    //         	 'data' => $message
    //         );
            
	//     $headers=array(
	// 	         'Authorization:key ='. GOOGLE_API_KEY,
    //        		 'Content-Type: application/json'
    //         );
	
	//     $ch=curl_init();
	//     curl_setopt($ch,CURLOPT_URL, $url);
	//     curl_setopt($ch, CURLOPT_POST,true);
	//     curl_setopt($ch, CURLOPT_HTTPHEADER,$headers);
	//     curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
	//     curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);  
    //   	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    //    	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
	//     $result = curl_exec($ch);           
    //     if ($result === FALSE) {
    //         die('Curl failed: ' . curl_error($ch));
    //     }
    //     curl_close($ch);
    //     return $result;
    // }

    function multi_send_notification($url_list, $tokens, $message)
	{
        //$url='https://fcm.googleapis.com/fcm/send';
        //multi, conn_list init
        $mh = curl_multi_init();
        $conn_list = array();
	    $fields= array(
	 	    'registration_ids' => $tokens,
            'data' => $message
        );
            
	    $headers=array(
		    'Authorization:key ='. GOOGLE_API_KEY,
           	'Content-Type: application/json'
            );

            //loop and set option to curl
            foreach ($url_list as $i => $url) {
            $conn_list[$i] = curl_init($url);
            curl_setopt($conn_list[$i],CURLOPT_URL, $url);
            curl_setopt($conn_list[$i], CURLOPT_POST,true);
            curl_setopt($conn_list[$i], CURLOPT_HTTPHEADER,$headers);
            curl_setopt($conn_list[$i], CURLOPT_RETURNTRANSFER,true);
            curl_setopt($conn_list[$i], CURLOPT_SSL_VERIFYHOST, 0);  
            curl_setopt($conn_list[$i], CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($conn_list[$i], CURLOPT_POSTFIELDS, json_encode($fields));
            
            //timeout(optional)
            if ($timeout){
                curl_setopt($conn_list[$i],CURLOPT_TIMEOUT,$timeout);
            }
            curl_multi_add_handle($mh,$conn_list[$i]);
        }

        $active = null;
        //execute curls
        do {
            $mrc = curl_multi_exec($mh,$active);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);
       
        while ($active and $mrc == CURLM_OK) {
            if (curl_multi_select($mh) != -1) {
                do {
                    $mrc = curl_multi_exec($mh,$active);
                } while ($mrc == CURLM_CALL_MULTI_PERFORM);
            }
        }
       
        if ($mrc != CURLM_OK) {
            echo '?????? ????????? ??????:'.$mrc;
        }
       
        //get result
        $res = array();
        foreach ($url_list as $i => $url) {
            if (($err = curl_error($conn_list[$i])) == '') {
                $res[$i] = curl_multi_getcontent($conn_list[$i]);
            } else {
                echo '????????????:'.$url_list[$i].'<br />';
            }
            curl_multi_remove_handle($mh,$conn_list[$i]);
            curl_close($conn_list[$i]);
        }
        curl_multi_close($mh);
       
        return $res;
    }
    $url_list = array(
        'https://fcm.googleapis.com/fcm/send',
        'https://fcm.googleapis.com/fcm/send',
        'https://fcm.googleapis.com/fcm/send',
        'https://fcm.googleapis.com/fcm/send',
        'https://fcm.googleapis.com/fcm/send',
        );
    //????????????????????? ???????????? ???????????? ???????????? FCM??? ????????????
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

    $time = time();

    $myMessage = $_POST['message']; //????????? ????????? ???????????? ??????
    if ($myMessage == ""){
        $myMessage = "????????? ?????????.";
    }
 
    $message = array("message" => $myMessage);
    
    //?????? ?????????
    $arr_chunk = array_chunk($tokens, count($tokens)/count($url_list));
    foreach($arr_chunk as $arr_token){
        //????????? $arr_token ?????? foreach????????? ???????????????
        $message_status = multi_send_notification($url_list,$arr_token, $message);  
        echo $message_status; 
    }
    //$message_status = send_notification($tokens, $message);

    
    //---------------old code & backup --------------------------
//     <?php 
	
// 	function send_notification ($tokens, $message)
// 	{
// 		$url = 'https://fcm.googleapis.com/fcm/send';
// 		$fields = array(
// 			 'registration_ids' => $tokens,
// 			 'data' => $message
// 			);

// 		$headers = array(
// 			'Authorization:key =' . GOOGLE_API_KEY,
// 			'Content-Type: application/json'
// 			);

// 	   $ch = curl_init();
//        curl_setopt($ch, CURLOPT_URL, $url);
//        curl_setopt($ch, CURLOPT_POST, true);
//        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//        curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);  
//        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
//        $result = curl_exec($ch);           
//        if ($result === FALSE) {
//            die('Curl failed: ' . curl_error($ch));
//        }
//        curl_close($ch);
//        return $result;
// 	}
	

// 	//????????????????????? ???????????? ???????????? ???????????? FCM??? ????????????
// 	include_once 'config.php';
// 	$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// 	$sql = "Select Token From users";

// 	$result = mysqli_query($conn,$sql);
// 	$tokens = array();

// 	if(mysqli_num_rows($result) > 0 ){

// 		while ($row = mysqli_fetch_assoc($result)) {
// 			$tokens[] = $row["Token"];
// 		}
// 	}

// 	mysqli_close($conn);
	
//         $myMessage = $_POST['message']; //????????? ????????? ???????????? ??????
// 	if ($myMessage == ""){
// 		$myMessage = "????????? ?????????????????????.";
// 	}

// 	$message = array("message" => $myMessage);
// 	$message_status = send_notification($tokens, $message);
// 	echo $message_status;

//  ?>

?>

