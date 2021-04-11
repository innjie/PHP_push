package com.pushmodul;

import com.android.volley.AuthFailureError;
import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.toolbox.StringRequest;

import java.util.HashMap;
import java.util.Map;

public class request_check extends StringRequest {
    //앱 설치 시 서버측에 데이터 등록 요청
    private static String url="http://skawns27.dothome.co.kr/reject_check.php";
    private Map<String,String> map;
    public request_check(String state, String token, Response.Listener<String> listener){
        super(Method.POST, url , listener,null);
        map=new HashMap<>();
        map.put("state",state);
        map.put("Token",token);
    }
    protected  Map<String,String> getParams() throws AuthFailureError {
        return map;
    }

}
