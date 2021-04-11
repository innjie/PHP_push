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
    $sql = "Select token From pushuser";
 
    $result = mysqli_query($conn,$sql);
    while ($row = mysqli_fetch_assoc($result)) {
?>
    <ul>
        <li><?php echo $row["token"]; ?> ...</li>
    </ul>
 
<?php
    }
?>
 </body>
 <!-- The core Firebase JS SDK is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/8.0.2/firebase-app.js"></script>

<!-- TODO: Add SDKs for Firebase products that you want to use
     https://firebase.google.com/docs/web/setup#available-libraries -->
<script src="https://www.gstatic.com/firebasejs/8.0.2/firebase-analytics.js"></script>

<script>
  // Your web app's Firebase configuration
  // For Firebase JS SDK v7.20.0 and later, measurementId is optional
  var firebaseConfig = {
    apiKey: "AIzaSyCY2-qo1jP2QT-aTfS75WWdkcUTdJOxb-w",
    authDomain: "mypush-bd47d.firebaseapp.com",
    databaseURL: "https://mypush-bd47d.firebaseio.com",
    projectId: "mypush-bd47d",
    storageBucket: "mypush-bd47d.appspot.com",
    messagingSenderId: "552210583819",
    appId: "1:552210583819:web:f6f62f465bb869017b920e",
    measurementId: "G-3V4REQS6KE"
  };
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);
  firebase.analytics();
</script>
</html>