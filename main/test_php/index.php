<!doctype html>
<html lang="en">
 <head>
  <meta charset="UTF-8">
  <meta name="Generator" content="EditPlus®">
  <meta name="Author" content="">
  <meta name="Keywords" content="">
  <meta name="Description" content="">
  <title>FCM Push Example</title>
 </head>
 <body>
  
<div class="messageWrapper">
    <h2>Push Message</h2>
 
    <form action="push_notification.php" method="post">
        <textarea name="message" rows="4" cols="50" placeholder="메세지를 입력하세요"  required></textarea><br>
        <input type="submit" name="submit" value="Send" id="submitButton">
    </form>
 
</div>
 
<?php
    include("config.php");
 
    $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    $sql = "Select Token From PushUser";
 
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <ul>
        <li><?php echo $row["Token"]; ?> ...</li>
    </ul>
 
<?php
    }
?>
 </body>
</html>
 