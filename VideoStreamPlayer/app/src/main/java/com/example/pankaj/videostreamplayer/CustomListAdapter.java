package com.example.pankaj.videostreamplayer;

import android.app.Activity;
import android.content.Context;
import android.graphics.drawable.Drawable;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.support.v7.app.AppCompatActivity;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ImageView;
import android.widget.TextView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.Random;

import io.vov.vitamio.MediaPlayer;
import io.vov.vitamio.widget.VideoView;

/**
 * Created by pankaj on 31/3/17.
 */

public class CustomListAdapter extends ArrayAdapter {
    private final AppCompatActivity context;
    private ArrayList<String> names;
    private ArrayList<Boolean> active;
    private final Integer[] icons;
    private Random random;
    private String TAG = "CustomListAdapter";

    public CustomListAdapter(Context context, ArrayList<String> names, ArrayList<Boolean> active, Integer[] icons) {
        super(context, R.layout.list_item, names);
        this.names = names;
        this.active = active;
        this.context = (AppCompatActivity) context;
        this.icons = icons;
        random = new Random();
    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {
        LayoutInflater inflater=context.getLayoutInflater();
        View rowView=inflater.inflate(R.layout.list_item, null,true);
        TextView textView = (TextView) rowView.findViewById(R.id.center);
        textView.setText(names.get(position));
        ImageView imageView = (ImageView) rowView.findViewById(R.id.imageIcon);
        imageView.setImageResource(icons[random.nextInt(icons.length)]);
        Log.d(TAG, "getView: "+active.toString());
        if(active.get(position)) {
            imageView = (ImageView) rowView.findViewById(R.id.videoActive);
            imageView.setImageResource(android.R.drawable.presence_video_online);
        }
        return rowView;
    }
}
