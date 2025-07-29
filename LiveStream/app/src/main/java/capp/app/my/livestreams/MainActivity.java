package capp.app.my.livestreams;

import java.util.HashMap;
import java.util.Map;
import java.util.regex.Matcher;
import java.util.regex.Pattern;

import net.majorkernelpanic.streaming.MediaStream;
import net.majorkernelpanic.streaming.Session;
import net.majorkernelpanic.streaming.SessionBuilder;
import net.majorkernelpanic.streaming.audio.AudioQuality;
import net.majorkernelpanic.streaming.gl.SurfaceView;
import net.majorkernelpanic.streaming.rtsp.RtspClient;
import net.majorkernelpanic.streaming.video.VideoQuality;
import android.app.Activity;
import android.app.AlertDialog;
import android.app.ProgressDialog;
import android.content.DialogInterface;
import android.content.Intent;
import android.content.SharedPreferences;
import android.content.SharedPreferences.Editor;
import android.content.pm.ActivityInfo;
import android.hardware.Camera.CameraInfo;
import android.os.Bundle;
import android.preference.PreferenceManager;
import android.util.Log;
import android.view.SurfaceHolder;
import android.view.View;
import android.view.View.OnClickListener;
import android.view.Window;
import android.view.WindowManager;
import android.widget.Button;
import android.widget.EditText;
import android.widget.FrameLayout;
import android.widget.ImageButton;
import android.widget.ProgressBar;
import android.widget.RadioButton;
import android.widget.RadioGroup;
import android.widget.RadioGroup.OnCheckedChangeListener;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

public class MainActivity extends Activity implements OnClickListener, RtspClient.Callback, Session.Callback, SurfaceHolder.Callback, OnCheckedChangeListener {

    public final static String TAG = "MainActivity";
    ProgressDialog pDialog;
    private RequestQueue mRequestQueue;
    private Button mButtonSave;
    private Button mButtonVideo;
    private Button mButtonStart;
    private ImageButton mButtonFlash;
    private ImageButton mButtonCamera;
    private ImageButton mButtonSettings;
    private RadioGroup mRadioGroup;
    private FrameLayout mLayoutVideoSettings;
    private FrameLayout mLayoutServerSettings;
    private SurfaceView mSurfaceView;
    private TextView mTextBitrate;
    private EditText mEditTextURI;
    private EditText mEditTextPassword;
    private EditText mEditTextUsername;
    private ProgressBar mProgressBar;
    private Session mSession;
    private RtspClient mClient;
    private boolean startRecording = false;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.activity_main);
        this.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
        mButtonSave = (Button) findViewById(R.id.save);
        mButtonStart = (Button) findViewById(R.id.start);
        mButtonFlash = (ImageButton) findViewById(R.id.flash);
        mButtonSettings = (ImageButton) findViewById(R.id.settings);
        mSurfaceView = (SurfaceView) findViewById(R.id.surface);
        mEditTextURI = (EditText) findViewById(R.id.uri);
        mTextBitrate = (TextView) findViewById(R.id.bitrate);
        mLayoutVideoSettings = (FrameLayout) findViewById(R.id.video_layout);
        mLayoutServerSettings = (FrameLayout) findViewById(R.id.server_layout);
        mRadioGroup =  (RadioGroup) findViewById(R.id.radio);
        mProgressBar = (ProgressBar) findViewById(R.id.progress_bar);
        pDialog = new ProgressDialog(this);
        pDialog.setCancelable(false);
        mRequestQueue = Volley.newRequestQueue(getBaseContext());
        mRadioGroup.setOnCheckedChangeListener(this);
        mRadioGroup.setOnClickListener(this);

        mButtonStart.setOnClickListener(this);
        mButtonSave.setOnClickListener(this);
        mButtonFlash.setOnClickListener(this);
        //mButtonCamera.setOnClickListener(this);
        mButtonSettings.setOnClickListener(this);
        mButtonFlash.setTag("off");

        SharedPreferences mPrefs = PreferenceManager.getDefaultSharedPreferences(MainActivity.this);
        mLayoutServerSettings.setVisibility(View.GONE);
        mSession = SessionBuilder.getInstance()
                .setContext(getApplicationContext())
                .setAudioEncoder(SessionBuilder.AUDIO_AAC)
                .setAudioQuality(new AudioQuality(8000,16000))
                .setVideoEncoder(SessionBuilder.VIDEO_H264)
                .setSurfaceView(mSurfaceView)
                .setPreviewOrientation(0)
                .setCallback(this)
                .build();

        // Configures the RTSP client
        mClient = new RtspClient();
        mClient.setSession(mSession);
        mClient.setCallback(this);
        mClient.setTransportMode(RtspClient.TRANSPORT_UDP);
        mSurfaceView.getHolder().addCallback(this);
        selectQuality();
    }

    @Override
    public void onClick(View v) {
        switch (v.getId()) {
            case R.id.start:
                mLayoutServerSettings.setVisibility(View.GONE);
                toggleStream();
                break;
            case R.id.flash:
                if (mButtonFlash.getTag().equals("on")) {
                    mButtonFlash.setTag("off");
                    mButtonFlash.setImageResource(R.drawable.ic_flash_on_holo_light);
                } else {
                    mButtonFlash.setImageResource(R.drawable.ic_flash_off_holo_light);
                    mButtonFlash.setTag("on");
                }
                mSession.toggleFlash();
                break;
           // case R.id.camera:
             //   mSession.switchCamera();
               // break;
            case R.id.settings:
                if (mLayoutVideoSettings.getVisibility() == View.GONE) {
                    mLayoutVideoSettings.setVisibility(View.VISIBLE);
                }
                break;

            case R.id.save:
                selectQuality();
                mLayoutVideoSettings.setVisibility(View.GONE);
                Toast.makeText(this, ((RadioButton) findViewById(mRadioGroup.getCheckedRadioButtonId())).getText(), Toast.LENGTH_SHORT).show();
        }
    }

    @Override
    public void onDestroy(){
        super.onDestroy();
        mClient.release();
        mSession.release();
        mSurfaceView.getHolder().removeCallback(this);
        Log.d("Destroy", "UNlock");
    }

    private void selectQuality() {
        int id = mRadioGroup.getCheckedRadioButtonId();
        RadioButton button = (RadioButton) findViewById(id);
        if (button == null) return;

        String text = button.getText().toString();
        Pattern pattern = Pattern.compile("(\\d+)x(\\d+)\\D+(\\d+)\\D+(\\d+)");
        Matcher matcher = pattern.matcher(text);

        matcher.find();
        int width = Integer.parseInt(matcher.group(1));
        int height = Integer.parseInt(matcher.group(2));
        int framerate = Integer.parseInt(matcher.group(3));
        int bitrate = Integer.parseInt(matcher.group(4))*1000;

        mSession.setVideoQuality(new VideoQuality(width, height, framerate, bitrate));
        Log.d(TAG, "Selected resolution: " + width + "x" + height);
    }

    private void enableUI() {
        mButtonStart.setEnabled(true);
    }

    // Connects/disconnects to the RTSP server and starts/stops the stream
    public void toggleStream() {
        mProgressBar.setVisibility(View.VISIBLE);
        if (!mClient.isStreaming()) {
            String ip, port, path;
            SharedPreferences mPrefs = PreferenceManager.getDefaultSharedPreferences(MainActivity.this);
            Editor editor = mPrefs.edit();
            editor.putString("uri", mEditTextURI.getText().toString());
            editor.commit();
            Pattern uri = Pattern.compile("rtsp://(.+):(\\d*)/(.+)");
            Matcher m = uri.matcher(mEditTextURI.getText());
            m.find();
            ip = AppConfig.URL_IP;
            port = getIntent().getIntExtra("port", 80) + "";
            path = "live/" + getIntent().getStringExtra("room");
            mClient.setServerAddress(ip, Integer.parseInt(port));
            Log.d("uri", ip + ":" + port);
            mClient.setStreamPath("/" + path);
            Log.d("path", path);
            pDialog.setMessage("Starting  Recording ...");
            startRecord(getIntent().getStringExtra("username"), getIntent().getStringExtra("room"), "record", AppConfig.URL_HANDLE_RECORDING, "1");
            showDialog();
            mClient.startStream();
        } else {
            pDialog.setMessage("Stopping  Recording ...");
            startRecord(getIntent().getStringExtra("username"), getIntent().getStringExtra("room"), "record", AppConfig.URL_HANDLE_RECORDING, "0");
            showDialog();
            mClient.stopStream();
        }
    }

    private void logError(final String msg) {
        final String error = (msg == null) ? "Error unknown" : msg;
        // Displays a popup to report the eror to the user
        AlertDialog.Builder builder = new AlertDialog.Builder(MainActivity.this);
        builder.setMessage(msg).setPositiveButton("OK", new DialogInterface.OnClickListener() {
            public void onClick(DialogInterface dialog, int id) {
            }
        });
        AlertDialog dialog = builder.create();
        dialog.show();
    }

    @Override
    public void onPreviewStarted() {
        if (mSession.getCamera() == CameraInfo.CAMERA_FACING_FRONT) {
            mButtonFlash.setEnabled(false);
            mButtonFlash.setTag("off");
            mButtonFlash.setImageResource(R.drawable.ic_flash_on_holo_light);
        }
        else {
            mButtonFlash.setEnabled(true);
        }
    }

    @Override
    public void onSessionConfigured() {

    }

    @Override
    public void onSessionStarted() {
        enableUI();
       // mButtonStart.setImageResource(R.drawable.ic_switch_video_active);
        if (startRecording == true) {
            mButtonStart.setBackground(getResources().getDrawable(R.drawable.stopstream));
        }
        mProgressBar.setVisibility(View.GONE);
    }

    @Override
    public void onSessionStopped() {
        enableUI();
        mButtonStart.setBackground(getResources().getDrawable(R.drawable.startstream));

       // mButtonStart.setImageResource(R.drawable.ic_switch_video);

        mProgressBar.setVisibility(View.GONE);
    }


    @Override
    public void onBitrateUpdate(long l) {
        mTextBitrate.setText("" + l / 1000 + " kbps");
    }

    @Override
    public void onSessionError(int reason, int streamType, Exception e) {
        mProgressBar.setVisibility(View.GONE);
        switch (reason) {
            case Session.ERROR_CAMERA_ALREADY_IN_USE:
                break;
            case Session.ERROR_CAMERA_HAS_NO_FLASH:
                mButtonFlash.setImageResource(R.drawable.ic_flash_on_holo_light);
                mButtonFlash.setTag("off");
                break;
            case Session.ERROR_INVALID_SURFACE:
                break;
            case Session.ERROR_STORAGE_NOT_READY:
                break;
            case Session.ERROR_CONFIGURATION_NOT_SUPPORTED:
                VideoQuality quality = mSession.getVideoTrack().getVideoQuality();
                logError("The following settings are not supported on this phone: " +
                        quality.toString() + " " +
                        "(" + e.getMessage() + ")");
                e.printStackTrace();
                return;
            case Session.ERROR_OTHER:
                break;
        }

        if (e != null) {
            logError(e.getMessage());
            e.printStackTrace();
        }
    }

    @Override
    public void onRtspUpdate(int message, Exception e) {
        switch (message) {
            case RtspClient.ERROR_CONNECTION_FAILED:
            case RtspClient.ERROR_WRONG_CREDENTIALS:
                mProgressBar.setVisibility(View.GONE);
                enableUI();
                logError(e.getMessage());
                e.printStackTrace();
                break;
        }
    }


    @Override
    public void surfaceChanged(SurfaceHolder holder, int format, int width, int height) {

    }

    @Override
    public void surfaceCreated(SurfaceHolder holder) {
        mSession.startPreview();
    }

    @Override
    public void surfaceDestroyed(SurfaceHolder holder) {
        mClient.stopStream();
    }

    private void startRecord(final String username, final String room, final String recordOrLock, final String URL, final String recordOrLockVal) {


        StringRequest strReq = new StringRequest(Request.Method.POST,
                URL, new Response.Listener<String>() {

            @Override
            public void onResponse(String response) {
                Log.d("response", "Record Response: " + response.toString());
                hideDialog();

                try {
                    JSONObject startRec = new JSONObject(response);
                    boolean res = startRec.getBoolean("result");
                    // Check for error node in json
                    if (res) {
                        startRecording = res;
                        Log.d("response", "Login Response: " + res);
                    } else {
                        String errorMsg = startRec.getString("error_msg");
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
                params.put("room", room);
                params.put(recordOrLock,recordOrLockVal);
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
        if(MainActivity.this.isFinishing())
            startRecord(getIntent().getStringExtra("username"), getIntent().getStringExtra("room"), "lock", AppConfig.URL_HANDLE_LOCK, "0");
        startRecord(getIntent().getStringExtra("username"), getIntent().getStringExtra("room"), "record", AppConfig.URL_HANDLE_RECORDING, "0");
        Log.d("Pause","stopRec");
    }

    @Override
    public void onCheckedChanged(RadioGroup group, int checkedId) {

    }

    @Override
    public void onResume() {
        super.onResume();
        if(pDialog == null) {
            pDialog = new ProgressDialog(this);
            pDialog.setCancelable(false);
        }
    }
}