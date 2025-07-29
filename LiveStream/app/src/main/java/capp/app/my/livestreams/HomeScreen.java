package capp.app.my.livestreams;

import android.Manifest;
import android.app.Activity;
import android.app.DownloadManager;
import android.app.ProgressDialog;
import android.content.Intent;
import android.content.pm.PackageManager;
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

public class HomeScreen extends Activity {
    Button login;
    EditText usernameBox;
    EditText passwordBox;
    ProgressDialog pDialog;
    private SessionManager session;
    private RequestQueue mRequestQueue;
    private static final int PERMISSION_CONSTANT = 999;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.home);
        if(Integer.valueOf(android.os.Build.VERSION.SDK_INT) > 22) {
            if (checkSelfPermission(Manifest.permission.READ_CONTACTS)
                    != PackageManager.PERMISSION_GRANTED) {
                requestPermissions(new String[]{Manifest.permission.INTERNET,
                        Manifest.permission.WRITE_EXTERNAL_STORAGE,
                        Manifest.permission.RECORD_AUDIO,
                        Manifest.permission.CAMERA,
                        Manifest.permission.WAKE_LOCK,
                        Manifest.permission.READ_EXTERNAL_STORAGE,
                        Manifest.permission.READ_PHONE_STATE
                }, PERMISSION_CONSTANT);
            }
        }
        usernameBox = (EditText)findViewById(R.id.username);
        passwordBox = (EditText)findViewById(R.id.password);
        login = (Button)findViewById(R.id.login);
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        // SQLite database handler

        // Session manager
        session = new SessionManager(getApplicationContext());

        // Check if user is already logged in or not
       // if (session.isLoggedIn()) {
       //     // User is already logged in. Take him to main activity
       //     Intent intent = new Intent(HomeScreen.this, SelectRoom.class);
       //     startActivity(intent);
       //     finish();
       // }

        // Login button Click Event
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


    private void checkLogin(final String username, final String password) {
        // Tag used to cancel the request
        String tag_string_req = "req_login";

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
                        Intent intent = new Intent(HomeScreen.this,
                                SelectRoom.class);
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
        if (!pDialog.isShowing())
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

    @Override
    public void onResume() {
        super.onResume();
        if(pDialog == null) {
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
        }
    }

    @Override
    public void onRequestPermissionsResult(int requestCode,
                                           String permissions[], int[] grantResults) {
        switch (requestCode) {
            case PERMISSION_CONSTANT: {
                if (grantResults[0] == PackageManager.PERMISSION_GRANTED) {


                } else {
                    Toast.makeText(getBaseContext(),"You need to Give Permissions for App to Run",Toast.LENGTH_SHORT).show();
                }
                return;
            }

            // other 'switch' lines to check for other
            // permissions this app might request
        }
    }
}

