package com.example.pankaj.videostreamplayer;

import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.util.Log;
import android.view.MotionEvent;
import android.view.View;
import android.view.WindowManager;
import android.view.animation.Animation;
import android.view.animation.AnimationUtils;
import android.widget.Button;
import android.widget.TextView;
import android.widget.Toast;

import io.vov.vitamio.MediaPlayer;
import io.vov.vitamio.widget.VideoView;

public class PlayStream extends AppCompatActivity {

    private VideoView mVideoView;
    private String centre;
    private String name;
    private Integer id;
    private Integer port;
    private String url;
    private Button playPause;
    private Animation scale;
    final private String TAG = "PlayStream";
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getWindow().addFlags(WindowManager.LayoutParams.FLAG_KEEP_SCREEN_ON);
//        requestWindowFeature(Window.FEATURE_NO_TITLE);
        setContentView(R.layout.activity_play_stream);
        playPause = (Button)findViewById(R.id.playpause);
        scale = AnimationUtils.loadAnimation(this,R.anim.scale_down);
//        this.setRequestedOrientation(ActivityInfo.SCREEN_ORIENTATION_LANDSCAPE);
        Bundle b = getIntent().getExtras();
        centre = b.getString("centre");
        name = b.getString("name");
        id = b.getInt("id");
        port = b.getInt("port")+1;
        TextView textView = (TextView) findViewById(R.id.center);
        textView.setText(centre);
        textView = (TextView) findViewById(R.id.room);
        textView.setText(name);
        url = "rtmp://"+AppConfig.IP_ADDR +":"+port.toString()+"/live/"+name;
        Log.d(TAG, "onCreate: Stream:"+url);
        mVideoView = (VideoView) findViewById(R.id.vitamio_videoView);
        mVideoView.setVideoPath(url);
        mVideoView.requestFocus();
        mVideoView.setOnPreparedListener(new MediaPlayer.OnPreparedListener() {

            @Override
            public void onPrepared(MediaPlayer mp) {
                mVideoView.start();
                Toast.makeText(PlayStream.this, "selected", Toast.LENGTH_SHORT).show();
                Log.d("Video:",mVideoView.getVideoHeight()+"  "+mVideoView.getVideoWidth());
            }
        });
        playPause.setOnTouchListener(new View.OnTouchListener() {
            @Override
            public boolean onTouch(View v, MotionEvent event) {
                switch (event.getAction()) {
                    case MotionEvent.ACTION_DOWN:
                        playPause.startAnimation(scale);
                        break;

                    case MotionEvent.ACTION_UP:
                        playPause.clearAnimation();
                        break;
                }
                return false;
            }
        });
        playPause.setOnClickListener(new View.OnClickListener() {
            @Override
            public void onClick(View v) {
                if(playPause.getText()=="pause") {
                    playPause.setText("play");
                    playPause.setBackground(getResources().getDrawable(R.drawable.play));
                    mVideoView.pause();
                } else {
                    playPause.setText("pause");
                    playPause.setBackground(getResources().getDrawable(R.drawable.pause));
                    mVideoView.start();
                }
            }
        });
    }

    @Override
    protected void onResume() {
        super.onResume();
    }

    @Override
    protected void onPause() {
        super.onPause();
        playPause.setText("play");
        playPause.setBackground(getResources().getDrawable(R.drawable.play));
        mVideoView.pause();
    }
}
