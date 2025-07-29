package com.example.pankaj.videostreamplayer;

import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.view.View;
import android.widget.AdapterView;
import android.widget.AdapterView.OnItemClickListener;
import android.widget.ListView;
import android.widget.Toast;

import io.vov.vitamio.LibsChecker;
import io.vov.vitamio.widget.VideoView;

import static android.R.attr.path;

public class MainActivity extends AppCompatActivity {

//    private VideoView mVideoView,mVideoView1;
    ListView list;
    String streams[] = {"Room1",
            "Room2"};
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        if (!LibsChecker.checkVitamioLibs(this))  //Important!
            return;

        setContentView(R.layout.activity_main);
        CustomListAdapter adapter=new CustomListAdapter(this, streams);
        list=(ListView)findViewById(R.id.list);
        list.setAdapter(adapter);

        list.setOnItemClickListener(new OnItemClickListener() {

            @Override
            public void onItemClick(AdapterView<?> parent, View view,
                                    int position, long id) {
                // TODO Auto-generated method stub
                String Slecteditem= streams[position];
                Intent intent = new Intent(MainActivity.this,PlayStream.class);
                Bundle b = new Bundle();
                b.putString("stream",streams[position]);
                intent.putExtras(b);
                startActivity(intent);
            }
        });
//        mVideoView = (VideoView) findViewById(R.id.vitamio_videoView);
//        String path = "rtmp://172.16.52.72/live/Center1_1";
//        mVideoView.setVideoPath(path);
//        //mVideoView.requestFocus();
//        mVideoView1 = (VideoView) findViewById(R.id.vitamio_videoView1);
//        mVideoView1.setVideoPath(path);
//        //mVideoView1.requestFocus();
    }
}
