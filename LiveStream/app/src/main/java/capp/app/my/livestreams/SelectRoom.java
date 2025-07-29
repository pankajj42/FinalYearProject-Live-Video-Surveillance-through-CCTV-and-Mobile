package capp.app.my.livestreams;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class SelectRoom extends AppCompatActivity {
    private ListView listView;
    private ProgressDialog pDialog;
    private RequestQueue mRequestQueue;
    AppCompatActivity activity = this;
    ArrayAdapter<String> adapter;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_select_room);
        listView = (ListView)findViewById(R.id.listView);
        String tag_string_req = "req_login";
        Log.d("response", "Login Response: " + "rooms");
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        pDialog.setMessage("Logging in ...");
        showDialog();

        StringRequest strReq = new StringRequest(Request.Method.POST,
                AppConfig.URL_GET_ROOM_LIST, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.d("response", "Login Response: " + response.toString());
                hideDialog();

                try {
                    JSONObject res = new JSONObject(response);
                    JSONArray array = res.getJSONArray("result");
                    final ArrayList<String> names = new ArrayList<String>();
                    final ArrayList<Integer> ports = new ArrayList<Integer>();
                    // Check for error node in json
                    if (array!=null) {
                        for(int item = 0; item < array.length(); item++) {
                            JSONObject object = array.getJSONObject(item);
                            String name = object.getString("name");
                            int port = Integer.parseInt(object.getString("port"));
                            names.add(name);
                            ports.add(port);
                        }
                        Log.d("response", "Login Response: " + names);
                        adapter = new ArrayAdapter<String>(activity,R.layout.select_room_list_item,R.id.room_item,names);
                        listView.setAdapter(adapter);
                        listView.setOnItemClickListener(new AdapterView.OnItemClickListener() {
                            @Override
                            public void onItemClick(AdapterView<?> parent, View view, final int position, long id) {
                                Toast.makeText(activity,"Locking Room : " + names.get(position),Toast.LENGTH_LONG).show();
                            //    pDialog.setMessage("Locking room ...");
                            //    showDialog();
                                StringRequest strReq = new StringRequest(Request.Method.POST,
                                        AppConfig.URL_HANDLE_LOCK, new Response.Listener<String>() {
                                    @Override
                                    public void onResponse(String response) {
                                        Log.d("locking", "locking Response: " + response.toString());
                                        hideDialog();

                                        try {
                                            JSONObject res = new JSONObject(response);
                                            Boolean result = res.getBoolean("result");
                                            if (result) {
                                                Log.d("Lock", "Locked");
                                                Toast.makeText(activity,"Locking Room : " + names.get(position),Toast.LENGTH_LONG).show();
                                                Intent intent = new Intent(SelectRoom.this, MainActivity.class);
                                                intent.putExtra("username",getIntent().getExtras().getString("username"));
                                                intent.putExtra("room", names.get(position));
                                                intent.putExtra("port", ports.get(position));
                                                startActivity(intent);
                                            } else {

                                            }
                                        } catch (JSONException e) {

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
                                            params.put("username",getIntent().getExtras().getString("username"));
                                            params.put("room",names.get(position));
                                            params.put("lock", "1");
                                            Log.d("Lock room : ", names.get(position));
                                            return params;
                                        }

                                    };
                                    mRequestQueue.add(strReq);
                            } //onitemclick
                        }); //setonitemclick

                    } else {
                        String errorMsg = res.getString("error_msg");
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
                params.put("username",getIntent().getExtras().getString("username"));
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
    public void onResume() {
        super.onResume();
       
        if(pDialog == null) {
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
        }

        StringRequest strReq = new StringRequest(Request.Method.POST,
                AppConfig.URL_GET_ROOM_LIST, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d("RefreshList", "refresh: " + response.toString());
                hideDialog();

                try {
                    JSONObject res = new JSONObject(response);
                    JSONArray array = res.getJSONArray("result");
                    final ArrayList<String> names = new ArrayList<String>();
                    final ArrayList<Integer> ports = new ArrayList<Integer>();
                    // Check for error node in json
                    if (array!=null) {
                        for (int item = 0; item < array.length(); item++) {
                            JSONObject object = array.getJSONObject(item);
                            String name = object.getString("name");
                            int port = Integer.parseInt(object.getString("port"));
                            names.add(name);
                            ports.add(port);
                        }
                        adapter.clear();
                        adapter.addAll(names);
                        adapter.notifyDataSetChanged();
                    }
                } catch (JSONException e) {

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
                params.put("username",getIntent().getExtras().getString("username"));
                Log.d("Refresh list : ", "list");
                return params;
            }

        };
        mRequestQueue.add(strReq);
    }

    @Override
    public void onPause() {
        super.onPause();
        if ((pDialog != null) && pDialog.isShowing())
            pDialog.dismiss();
        pDialog = null;
    }
}
