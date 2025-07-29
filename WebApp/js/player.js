var data = [];


function setDimension(height ,width) {
    jw_width = width;
    jw_height = height;
}

function setPlayers(players) {
  for(i=0;i<players.length;i+=3) {
      setPlayer(players[i], players[i+2], players[i+1]);
  }
}

// Outputs some logs about jwplayer
function print(t, obj) {
    for (var a in obj) {
        if (typeof obj[a] === "object")
            print(t + '.' + a, obj[a]);
        else
            data[t + '.' + a] = obj[a];
    }
}


function setPlayer(player, height, width) {

  jwplayer(player).setup({
      wmode: 'transparent',
      width: parseInt(width),
      height: parseInt(height),
      stretching: 'exactfit'
  });
  startPlayer($('#uri' + player).val(), player, height, width);

}

function swapPlayer(player) {
  main_uri = $('#urimain_player').val();
  small_uri = $('#uri' + player).val();
  document.getElementById('urimain_player').value = small_uri;
  document.getElementById('uri'+player).value = main_uri;
  startStream('main_player','500','1085');
  startStream(player,'','');

}

function startStream(player, height, width) {
  startPlayer($('#uri' + player).val(), player, height, width);
}

function stopStream(player) {
  jwplayer(player).stop();
}

// Starts the flash player
function startPlayer(stream, player, height, width) {
    jwplayer(player).setup({
        width: parseInt(width),
        height: parseInt(height),
        stretching: 'exactfit',
        sources: [{
                file: stream
            }],
        rtmp: {
            bufferlength: 0.05
        }
    });

  /*  jwplayer(player).onMeta(function(event) {
        var info = "";
        for (var key in data) {
            info += key + " = " + data[key] + "<BR>";
        }
        print("event", event);
    });*/

    jwplayer(player).play();
}
