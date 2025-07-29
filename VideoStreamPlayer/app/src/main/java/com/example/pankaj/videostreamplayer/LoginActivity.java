package com.example.pankaj.videostreamplayer;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.HashMap;
import java.util.Map;

public class LoginActivity extends AppCompatActivity {
    Button login;
    EditText usernameBox;
    EditText passwordBox;
    ProgressDialog pDialog;
    private SessionManager session;
    private RequestQueue mRequestQueue;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_login);
        usernameBox = (EditText)findViewById(R.id.username);
        passwordBox = (EditText)findViewById(R.id.password);
        login = (Button)findViewById(R.id.login);
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        session = new SessionManager(getApplicationContext());
        login.setOnClickListener(new View.OnClickListener() {

            public void onClick(View view) {
                String username = usernameBox.getText().toString();
                String password = passwordBox.getText().toString().trim();

                // Check for empty data in the form
                if (!username.isEmpty() && !password.isEmpty()) {
                    // login user
                    checkLogin(username, password);
                } else {
                    // Prompt user to enter credentials
                    Toast.makeText(getApplicationContext(),
                            "Please enter the credentials!", Toast.LENGTH_LONG)
                            .show();
                }
            }

        });


    }

    @Override
    protected void onResume() {
        super.onResume();
        if(pDialog==null) {
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
        }
    }

    private void checkLogin(final String username, final String password) {
        // Tag used to cancel the request
        String tag_string_req = "req_login";
        if(pDialog!=null)
            pDialog.setMessage("Logging in ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                AppConfig.URL_LOGIN, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.d("response", "Login Response: " + response.toString());
                hideDialog();

                try {
                    JSONObject jObj = new JSONObject(response);
                    boolean res = jObj.getBoolean("result");
                    // Check for error node in json
                    if (res) {
                        Log.d("response", "Login Response: " + res);
                        session.setLogin(true);
                        Bundle bundle = new Bundle();
                        Intent intent = new Intent(LoginActivity.this,
                                CentreListActivity.class);
                        intent.putExtra("username",username);
                        startActivity(intent);
                        finish();
                    } else {
                        String errorMsg = jObj.getString("error_msg");
                        Toast.makeText(getApplicationContext(),
                                errorMsg, Toast.LENGTH_LONG).show();
                    }
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(getApplicationContext(), "Json error: " + e.getMessage(), Toast.LENGTH_LONG).show();
                }
            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("Error", "Login Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(),
                        error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {

            @Override
            protected Map<String, String> getParams() {
                // Posting parameters to login url
                Map<String, String> params = new HashMap<String, String>();
                params.put("username", username);
                params.put("password", password);
                return params;
            }

        };
        mRequestQueue.add(strReq);
    }

    private void showDialog() {
        if (pDialog!=null && !pDialog.isShowing())
            pDialog.show();
    }

    private void hideDialog() {
        if (pDialog!=null && pDialog.isShowing())
            pDialog.dismiss();
    }

    @Override
    public void onPause() {
        super.onPause();
        if ((pDialog != null) && pDialog.isShowing())
            pDialog.dismiss();
        pDialog = null;
    }

}

