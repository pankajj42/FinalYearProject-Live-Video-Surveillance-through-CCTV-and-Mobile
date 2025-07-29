package com.example.pankaj.videostreamplayer;

import android.app.ProgressDialog;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
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

public class CentreListActivity extends AppCompatActivity {

    private static final String TAG = "CentreListAvtivity" ;
    private ListView list;
    private ProgressDialog pDialog;
    private RequestQueue mRequestQueue;
    private CustomListAdapter adapter;
    private ArrayList<String> names;
    private ArrayList<String> userNames;
    private ArrayList<Boolean> active;
    private final Integer[] icons = {R.drawable.centericon1,R.drawable.centericon2,
            R.drawable.centericon3,R.drawable.centericon4,R.drawable.centericon5};
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (!LibsChecker.checkVitamioLibs(this))  //Important!
            return;
        setContentView(R.layout.activity_centre_list);
        list=(ListView)findViewById(R.id.list);
        Log.d("response", "Login Response: " + "centres");
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        pDialog.setMessage("Loading in ...");
        showDialog();
        Log.d(TAG, "onCreate: Completed");
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
        Log.d(TAG, "onResume: Started");
        StringRequest strReq = new StringRequest(Request.Method.POST,
                AppConfig.URL_GET_CENTRE_LIST, new Response.Listener<String>() {
            @Override
            public void onResponse(String response) {
                Log.d("RefreshList", "refresh: " + response.toString());
                hideDialog();

                try {
                    JSONObject res = new JSONObject(response);
                    JSONArray array = res.getJSONArray("result");
                    userNames = new ArrayList<String>();
                    names = new ArrayList<String>();
                    active = new ArrayList<Boolean>();
                    // Check for error node in json
                    if (array!=null) {
                        for (int item = 0; item < array.length(); item++) {
                            JSONObject object = array.getJSONObject(item);
                            String userName = object.getString("username");
                            String name = object.getString("name");
                            Boolean act = object.getBoolean("active");
                            userNames.add(userName);
                            names.add(name);
                            active.add(act);
                        }
                    }
                    adapter=new CustomListAdapter(CentreListActivity.this, names, active, icons);
                    list.setAdapter(adapter);
                    adapter.notifyDataSetChanged();
                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(getApplicationContext(), "Json error: " + e.getMessage(), Toast.LENGTH_LONG).show();
                }
            }
        }, new Response.ErrorListener() {

            @Override
            public void onErrorResponse(VolleyError error) {
                Log.e("Error", "Centre List Error: " + error.getMessage());
                Toast.makeText(getApplicationContext(),
                        error.getMessage(), Toast.LENGTH_LONG).show();
                hideDialog();
            }
        }) {
            @Override
            protected Map<String, String> getParams() {
                // Posting parameters to login url
                Map<String, String> params = new HashMap<String, String>();
                params.put("getallcentres", "centres");
                Log.d("Refresh list : ", "list");
                return params;
            }
        };
        if(adapter!=null) {
            adapter.clear();
        }
        mRequestQueue.add(strReq);
        Log.d("List Adapter", "Adapter set");
        list.setOnItemClickListener(new OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                // TODO Auto-generated method stub
                if(active.get(position)) {
                    Intent intent = new Intent(CentreListActivity.this, RoomListActivity.class);
                    Bundle b = new Bundle();
                    b.putString("centre", names.get(position));
                    b.putString("username", userNames.get(position));
                    intent.putExtras(b);
                    startActivity(intent);
                } else {
                    Toast.makeText(getBaseContext(),"No Active Video in Center "+names.get(position),
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
