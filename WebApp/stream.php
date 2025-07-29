<!doctype html>

<html lang="en">
	<head>

		<meta charset="utf-8">

		<title>Online Android Streaming</title>

		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
		<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
		<link rel="stylesheet" href="https://code.getmdl.io/1.2.1/material.cyan-light_blue.min.css">
		<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
		<script type="text/javascript" src="js/jwplayer.js"></script>
		<script src="js/player.js" type="text/javascript"></script>
		<script>jwplayer.key = ""</script>
		<link rel="stylesheet" href="styles.css">
		<?php include('connection.php');
			$ipAddr = '172.16.52.72';
			$stream = $_GET['stream'];
			$result = mysqli_fetch_array(mysqli_query($conn,'select * from streams where id='.$stream));
			$centre = $result['centre'];
			$ipAddr = $ipAddr.':'.($result['port']+1);
		 ?>
		<style>
			#view-source {
			position: fixed;
			display: block;
			right: 0;
			bottom: 0;
			margin-right: 30px;
			margin-bottom: 30px;
			z-index: 800;
			}
		</style>

	</head>

	<body onload="setPlayers([
	<?php
		$alln = mysqli_query($conn,"select * from streams where centre='".$centre."'");
		$all = mysqli_fetch_array($alln);
		if($all) {
			echo "'main_player','1040','500' ";
		}
		while($all = mysqli_fetch_array($alln) ) {
			echo ",'".$all['id']."','247','160s'";
		}
	 ?>])">
		<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">

			<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-500">

				<div class="mdl-layout__header-row">

					<span class="mdl-layout-title">Live Stream</span>

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
				<?php
					$centres = mysqli_query($conn,'select * from centres');
					while( $cent = mysqli_fetch_array($centres) ) {
						$temp = mysqli_fetch_array(mysqli_query($conn,'select min(id) as min from streams where centre="'.$cent['username'].'"'));
						$id = $temp["min"];
						echo '<a class="mdl-navigation__link" href="stream.php?stream='.$id.'">'.$cent['username'].'</a>';
					}
				 ?>
					<div class="mdl-layout-spacer"></div>
					<a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
				</nav>
			</div>

			<main class="mdl-layout__content mdl-color--grey-100">

				<div class="mdl-grid demo-content">
					<?php
					$rows = mysqli_query($conn,'select * from streams where id='.$stream);
					$row=mysqli_fetch_array($rows);
					if($row) {
						echo '
						<div style="float: left; margin-left: 1px; margin-top:5px; margin-right: 5px">
							<div id="video_preview1">
								<div id="main_player" class="player"></div><div class="clear"></div>
									<input type="button" id="start|main_player" onclick="startStream(\'main_player\',\'500\',\'1040\')" class="btn_start_stop btn_start" value="Start" />
									<input type="button" id="stop|main_player" onclick="stopStream()" class="btn_start_stop btn_stop" value="Stop"/>
									<input type="text" class="stream_url" id="urimain_player" value="rtmp://'.$ipAddr.'/live/'.$row['id'].'"/><br/>
									<p style="width:975px" class="stream_url">Stream Coming from a room of '.$centre.'</p>
								</div>';
						}
					?>
					</div>



				</div>

				<div style="padding-left:28px;"class="mdl-grid demo-content">

					<?php
					  $rows = mysqli_query($conn,'select * from streams where centre="'.$centre.'" and id <> '.$stream);
						while($row=mysqli_fetch_array($rows))
						{
							echo'<div id="video_preview2" onclick="swapPlayer(\''.$row['id'].'\')" style="cursor:pointer;border: 1px solid #2c3e50; padding: 5px; margin:2px; background:#2c3e50">
									<div id="'.$row['id'].'" class="player"></div><div class="clear"></div>
								  <input hidden type="text" class="stream_url" id="uri'.$row['id'].'" value="rtmp://'.$ipAddr.'/live/'.$row['id'].'"/><br/>
									<p style="margin-left:80px;color:#bdc3c7;margin-top:-10px;font-size:20px;"> '.$row['name'].' </p>
								</div>';
						}


					?>

				</div>

			</main>

			<script src="https://code.getmdl.io/1.2.1/material.min.js"></script>

		</div>

	</body>

</html>
