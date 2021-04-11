package com.pushmodul;

import android.app.Notification;
import android.app.NotificationManager;
import android.app.PendingIntent;
import android.content.Context;
import android.content.Intent;
import android.media.RingtoneManager;
import android.net.Uri;
import android.util.Log;


import com.android.volley.RequestQueue;
import com.google.firebase.messaging.FirebaseMessagingService;
import com.google.firebase.messaging.RemoteMessage;

import org.json.JSONException;
import org.json.JSONObject;
import java.util.Map;

import okhttp3.FormBody;
import okhttp3.OkHttpClient;
import okhttp3.Request;
import okhttp3.RequestBody;
import okhttp3.Response;
/*fcm 앱 연동코드*/
public class MyFirebaseMessagingService extends FirebaseMessagingService {
    int id;
    private static final String TAG="FirebaseMsgService";
    OkHttpClient client;
    @Override
    //처음 설치할 때 토근 생성
    public void onNewToken(String token) {
        super.onNewToken(token);
        // Get updated InstanceID token.
        Log.d(TAG, "Refreshed token: " + token);
                sendRegistrationToServer(token);
    }

    /* 서버에 토근 등록 */
    protected void sendRegistrationToServer(String token) {
        String Token=token;
        client = new OkHttpClient();
        Log.d("서버전송-", "전송토큰: " + Token);

        RequestBody body = new FormBody.Builder()
                .add("Token", Token)
                .build();
        /*서버 url 입력하기*/
        Request request = new Request.Builder()
                .url("http://skawns27.dothome.co.kr/register.php") //DB 서버 url
                .post(body)
                .build();

        try {
            Response response=client.newCall(request).execute();
            Log.d("resq","1"+response.toString());
        } catch (Exception e) {
            e.printStackTrace();
        }

    }
    /*매세지를 받았을 때 동작*/
    public void onMessageReceived(RemoteMessage remoteMessage){
        sendNotification(remoteMessage.getData().get("message"));
    }

    public void getID(String Token){
        RequestBody body = new FormBody.Builder()
                .add("Token", Token)
                .build();
        /*서버 url 입력하기*/
        Request request = new Request.Builder()
                .url("http://skawns27.dothome.co.kr/reject_check.php") //DB 서버 url
                .post(body)
                .build();
        try {
            client.newCall(request).execute();
        } catch (Exception e) {
            e.printStackTrace();
        }
    }

    /*알림 띄우기*/
    private void sendNotification(String messageBody){
        Intent intent=new Intent(this,MainActivity.class);
        intent.addFlags(Intent.FLAG_ACTIVITY_CLEAR_TOP);
        PendingIntent pendingIntent = PendingIntent.getActivity(this,0, intent,
                PendingIntent.FLAG_ONE_SHOT);

        //알림을 띄우는 설정
        Uri defaultSoundUri=RingtoneManager.getDefaultUri(RingtoneManager.TYPE_NOTIFICATION);

        Notification.Builder notificationBuilder=new Notification.Builder(this)
                .setSmallIcon(R.mipmap.bell)
                .setContentTitle("PushPull")
                .setNumber(999)
                .setContentText(messageBody)
                .setAutoCancel(true)
                .setSound(defaultSoundUri)
                .setContentIntent(pendingIntent);

        NotificationManager notificationManager=
                (NotificationManager)getSystemService(Context.NOTIFICATION_SERVICE);

            notificationManager.notify(0,notificationBuilder.build());

    }
}
