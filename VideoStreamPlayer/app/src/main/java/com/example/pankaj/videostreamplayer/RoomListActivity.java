package com.example.pankaj.videostreamplayer;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
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

import io.vov.vitamio.LibsChecker;

public class RoomListActivity extends AppCompatActivity {
    private ListView list;
    private ProgressDialog pDialog;
    private RequestQueue mRequestQueue;
    private CustomListAdapter adapter;
    private ArrayList<String> names;
    private ArrayList<Integer> ports;
    private ArrayList<Integer> ids;
    private ArrayList<Boolean> active;
    private String centre;
    private String centreUsername;
    private final Integer[] icons = {R.drawable.roomicon1,R.drawable.roomicon2,
            R.drawable.roomicon3,R.drawable.roomicon4,R.drawable.roomicon5};
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (!LibsChecker.checkVitamioLibs(this))  //Important!
            return;
        Intent intent = getIntent();
        centre = intent.getStringExtra("centre");
        centreUsername = intent.getStringExtra("username");
        setContentView(R.layout.activity_room_list);
        list=(ListView)findViewById(R.id.roomlist);
        Log.d("response", "Login Response: " + "rooms");
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        pDialog.setMessage("Loading in ...");
        showDialog();
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
    public void onResume() {
        super.onResume();
        if(pDialog==null) {
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
                    names = new ArrayList<String>();
                    active = new ArrayList<Boolean>();
                    ports = new ArrayList<Integer>();
                    ids = new ArrayList<Integer>();
                    // Check for error node in json
                    if (array!=null) {
                        for (int item = 0; item < array.length(); item++) {
                            JSONObject object = array.getJSONObject(item);
                            String name = object.getString("name");
                            Boolean act = object.getBoolean("active");
                            Integer port = Integer.parseInt(object.getString("port"));
                            Integer id = Integer.parseInt(object.getString("id"));
                            names.add(name);
                            active.add(act);
                            ports.add(port);
                            ids.add(id);
                        }
                    }
                    adapter=new CustomListAdapter(RoomListActivity.this, names, active, icons);
                    list.setAdapter(adapter);
                    adapter.notifyDataSetChanged();
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
                params.put("centre",centreUsername);
                Log.d("Refresh list : ", "list");
                return params;
            }
        };

        if(adapter!=null) {
            adapter.clear();
        }
        mRequestQueue.add(strReq);

        list.setOnItemClickListener(new AdapterView.OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                // TODO Auto-generated method stub
                if(active.get(position)) {
                    Intent intent = new Intent(RoomListActivity.this, PlayStream.class);
                    Bundle b = new Bundle();
                    b.putString("name", names.get(position));
                    b.putInt("id", ids.get(position));
                    b.putInt("port", ports.get(position));
                    b.putString("centre", centre);
                    intent.putExtras(b);
                    startActivity(intent);
                } else {
                    Toast.makeText(getBaseContext(),"No Active Video in "+names.get(position),
                            Toast.LENGTH_SHORT).show();
                }
            }
        });
    }

    @Override
    public void onPause() {
        super.onPause();
        if ((pDialog != null) && pDialog.isShowing())
            pDialog.dismiss();
        pDialog = null;
    }
}
