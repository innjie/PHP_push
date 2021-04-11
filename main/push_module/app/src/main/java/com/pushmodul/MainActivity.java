package com.pushmodul;
import androidx.appcompat.app.AppCompatActivity;
import androidx.appcompat.widget.SwitchCompat;
import android.os.Bundle;
import android.util.Log;
import android.widget.CompoundButton;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.Volley;
import com.google.android.gms.tasks.OnSuccessListener;
import com.google.firebase.iid.FirebaseInstanceId;
import com.google.firebase.iid.InstanceIdResult;

import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends AppCompatActivity {
    static RequestQueue requestQueue;
    static String Token;
    static int state=1;
    static int id;
    SwitchCompat sw;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        sw=findViewById(R.id.sw);
        requestQueue = Volley.newRequestQueue(this);


        sw.setOnCheckedChangeListener(new CompoundButton.OnCheckedChangeListener() {
            @Override
            public void onCheckedChanged(CompoundButton buttonView, boolean isChecked) {
                Response.Listener<String> on_off =new Response.Listener<String>() {
                    @Override
                    public void onResponse(String response) {
                        try {
                            JSONObject jsonObject =new JSONObject(response);
                            boolean success=jsonObject.getBoolean("success");
                            Log.d("s",success+"12");
                            if(success) {
                                Toast.makeText(getApplicationContext(), "수신상태가 갱신 되었습니다.", Toast.LENGTH_LONG).show();
                            }
                            else Toast.makeText(getApplicationContext(), "갱신 실패.", Toast.LENGTH_LONG).show();
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                };
                if(isChecked){
                    state=1;
                    Request open_close=new request_check(Integer.toString(state),Token,on_off);
                    requestQueue.add(open_close);
                    //Toast.makeText(getApplicationContext(),"수신 활성화 상태입니다.",Toast.LENGTH_SHORT).show();
                            Log.d("사용자 상태:", "수신상태");
                    }
                else {
                    state=3;
                    Request open_close=new request_check(Integer.toString(state),Token,on_off);
                    requestQueue.add(open_close);
                    //Toast.makeText(getApplicationContext(),"수신 거부 상태입니다.",Toast.LENGTH_SHORT).show();
                    Log.d("사용자 상태:", "수신거부 상태");
                }
            }
        });

        FirebaseInstanceId.getInstance().getInstanceId().addOnSuccessListener(this,
                new OnSuccessListener<InstanceIdResult>() {
                    @Override
                    public void onSuccess(InstanceIdResult instanceIdResult) {
                        Token =instanceIdResult.getToken();
                        Log.d("token", Token);
                    }
                });

    }
}
