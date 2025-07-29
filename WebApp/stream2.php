<!doctype html>

<html lang="en">
	<head>

		<meta charset="utf-8">

		<title>Online Android Streaming</title>

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.cyan-light_blue.min.css">
		<link rel="stylesheet" href="styles.css">

		<style>
			#view-source {
			position: fixed;
			display: block;
			right: 0;
			bottom: 0;
			margin-right: 40px;
			margin-bottom: 40px;
			z-index: 900;
			}
		</style>

	</head>

	<body onload="setPlayers(['playerid','700','360','playerid2','247','212','playerid3','247','212','playerid4','247','212','playerid5','247','212','playerid6','247','212','playerid7','247','212','playerid8','247','212','playerid9','247','212','playerid10','247','212','playerid11','247','212','playerid12','247','212'])">

		<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">

			<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">

				<div class="mdl-layout__header-row">

					<span class="mdl-layout-title">Home</span>

					<div class="mdl-layout-spacer"></div>

					<div class="sw">

						<input type="search" id="search_url" class="search_url" placeholder="Search..." />
						<input type="button" id="search" class="search" value="Search" />

					</div>

				</div>

			</header>

			<div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">

				<header class="demo-drawer-header">

					<a href=index.html class="mdl-navigation__link" style="color: white; font-size: 20px;">

						<img src="images/510611_1994_376816.jpg" class="demo-avatar">

						<div class="demo-avatar-dropdown">
							<span>Live Streams</span>
						</div>

					</a>

				</header>

				<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
					<a class="mdl-navigation__link" href="liveStreaming.php?value=15">Rajasthan</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=17">Punjab</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=10">Gujarat</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=2">Delhi</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=12">U.P.</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=20">M.P.</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=8">Maharashtra</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=5">Kerala</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=4">Tamilnadu</a>
					<a class="mdl-navigation__link" href="liveStreaming.php?value=15">Haryana</a>
					<div class="mdl-layout-spacer"></div>
					<a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
				</nav>
			</div>

			<main class="mdl-layout__content mdl-color--grey-100">

				<div class="mdl-grid demo-content">

					<div style="float: left; margin-left: 60px; margin-top:25px; margin-right: 50px">
						<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
						<script type="text/javascript" src="js/jwplayer.js"></script>
						<script src="js/player.js" type="text/javascript"></script>
						<script>jwplayer.key = ""</script>

						<div id="video_preview1">
							<div id="playerid" class="player"></div><div class="clear"></div>
								<input type="button" id="start|playerid" onclick="startStream('playerid','360','700')" class="btn_start_stop btn_start" value="Start" />
								<input type="button" id="stop|playerid" onclick="stopStream()" class="btn_start_stop btn_stop" value="Stop"/>
								<input type="text" class="stream_url" id="uriplayerid" value="rtmp://192.168.201.1:1935/live/android_test2"/><br/>
								<p style="width:675px" class="stream_url">Stream Coming from a room of centre in Punjab</p>
							</div>
					</div>

					<div style="float: right; margin-left: 20px; margin-top:25px">

						<?php

							

							echo'<div id="video_preview2">
									<div id="playerid2" class="player"></div><div class="clear"></div>
								  <input hidden type="text" class="stream_url" id="uriplayerid2" value="rtmp://192.168.201.1:1935/live/android_test2"/><br/>
								</div>';
								echo'<div id="video_preview2">
										<div id="playerid3" class="player"></div><div class="clear"></div>
									  <input hidden type="text" class="stream_url" id="uriplayerid3" value="rtmp://192.168.201.1:1935/live/android_test2"/><br/>
									</div>';

						?>
					</div>

				</div>

				<div style="padding-left:68px;"class="mdl-grid demo-content">

					<?php

						

						for($x=0; $x<8; $x++)
						{
							echo'<div id="video_preview2" style="margin:5px">
									<div id="playerid'.($x+4).'" class="player"></div><div class="clear"></div>
								  <input type="text" hidden class="stream_url" id="uriplayerid'.($x+4).'" value="rtmp://192.168.201.1:1935/live/android_test1"/><br/>
								</div>';
						}


					?>

				</div>

			</main>

			<script src="https://code.getmdl.io/1.2.1/material.min.js"></script>

		</div>

	</body>

</html>
