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
			$ipAddr = '192.168.0.1';
			$center = $_GET['center'];
			$stream = $_GET['stream'];
			$rows = mysqli_query($con,'select * from streams where center='.$center);
			$centerName = mysqli_fetch_array(mysqli_query($con,"select * from centers where ID='".$center."'"))['NAME'];
		 ?>
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
		<script>
		$(document).ready(function () {
			$("#search_url").on('keyup',function () {
				var key = $(this).val();
	 
				$.ajax({
					url:'search1.php',
					type:'GET',
					data:'keyword='+key,
					success:function (data) {
						$("#drop-search").html(data);
						$("#drop-search").slideDown('default');
					}
				});
			});
		});
		</script>
		<script>
			$(document).click(function() {

				if( this.id != 'drop-search') {
					$("#drop-search").hide();
				}

			});
		</script>
	</head>

	<body onload="setPlayers([
	<?php
		$alln = mysqli_query($con,'select * from streams where center='.$center);
		$all = mysqli_fetch_array($alln);
		if($all) {
			echo "'main_player','720','360' ";
		}
		while($all = mysqli_fetch_array($alln) ) {
			echo ",'".$all['NAME']."','247','160'";
		}
	 ?>])">

		<div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">

			<header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">

				<div class="mdl-layout__header-row">

					<span class="mdl-layout-title">Live Stream</span>

					<div class="mdl-layout-spacer"></div>

					<div class="sw">
					
						<form action="search.php" method="get">
							<input type="'text" name="keyword" id="search_url" class="search_url" placeholder="Search..." onclick="document.getElementById('drop-search').style.display='block';"/>
							<input type="submit" id="search" class="search" value="Search" />
						</form>

					</div>
					

				</div>
				
				<div style="width:50%;margin-left:21.95%;margin-top:4%;background-color:#e6fff2;display:none;position:fixed;" id="drop-search"></div>
				
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
					$centers = mysqli_query($con,'select * from centers');
					while( $center = mysqli_fetch_array($centers) ) {
						echo '<a class="mdl-navigation__link" href="stream.php?center='.$center['ID'].'&stream=1">'.$center['NAME'].'</a>';
					}
				 ?>
					<div class="mdl-layout-spacer"></div>
					<a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i><span class="visuallyhidden">Help</span></a>
				</nav>
			</div>

			<main class="mdl-layout__content mdl-color--grey-100">

				<div class="mdl-grid demo-content">
					<?php
					$row=mysqli_fetch_array($rows);
					if($row) {
						echo '
						<div style="float: left; margin-left: 20px; margin-top:25px; margin-right: 50px">
							<div id="video_preview1">
								<div id="main_player" class="player"></div><div class="clear"></div>
									<input type="button" id="start|main_player" onclick="startStream(\'main_player\',\'360\',\'720\')" class="btn_start_stop btn_start" value="Start" />
									<input type="button" id="stop|main_player" onclick="stopStream()" class="btn_start_stop btn_stop" value="Stop"/>
									<input type="text" class="stream_url" id="urimain_player" value="rtmp://'.$ipAddr.':1935/live/'.$row['NAME'].'"/><br/>
									<p style="width:695px" class="stream_url">Stream Coming from a room of '.$centerName.'</p>
								</div>';
						}
					?>
					</div>

					<div style="float: right; margin-left: 10px; margin-top:25px">

						<?php
						$row=mysqli_fetch_array($rows);
						if($row) {
							echo'<div id="video_preview2" onclick="swapPlayer(\''.$row['NAME'].'\')" style="cursor:pointer;border: 1px solid #2c3e50; padding: 5px; margin:2px; background:#2c3e50">
									<div id="'.$row['NAME'].'" class="player"></div><div class="clear"></div>
								  <input hidden type="text" class="stream_url" id="uri'.$row['NAME'].'" value="rtmp://'.$ipAddr.':1935/live/'.$row['NAME'].'"/><br/>
									<p style="margin-left:80px;color:#bdc3c7;margin-top:-10px;font-size:20px;"> '.$row['NAME'].' </p>
								</div>';
							}
							$row=mysqli_fetch_array($rows);
							if($row) {
								echo'<div id="video_preview2" onclick="swapPlayer(\''.$row['NAME'].'\')" style="cursor:pointer;border: 1px solid #2c3e50; padding: 5px; margin:2px; background:#2c3e50">
										<div id="'.$row['NAME'].'" class="player"></div><div class="clear"></div>
									  <input hidden type="text" class="stream_url" id="uri'.$row['NAME'].'" value="rtmp://'.$ipAddr.':1935/live/'.$row['NAME'].'"/><br/>
										<p style="margin-left:80px;color:#bdc3c7;margin-top:-10px;font-size:20px;"> '.$row['NAME'].' </p>
									</div>';
								}
						?>
					</div>

				</div>

				<div style="padding-left:28px;"class="mdl-grid demo-content">

					<?php

						while($row=mysqli_fetch_array($rows))
						{
							echo'<div id="video_preview2" onclick="swapPlayer(\''.$row['NAME'].'\')" style="cursor:pointer;border: 1px solid #2c3e50; padding: 5px; margin:2px; background:#2c3e50">
									<div id="'.$row['NAME'].'" class="player"></div><div class="clear"></div>
								  <input hidden type="text" class="stream_url" id="uri'.$row['NAME'].'" value="rtmp://'.$ipAddr.':1935/live/'.$row['NAME'].'"/><br/>
									<p style="margin-left:80px;color:#bdc3c7;margin-top:-10px;font-size:20px;"> '.$row['NAME'].' </p>
								</div>';
						}


					?>

				</div>

			</main>

			<script src="https://code.getmdl.io/1.2.1/material.min.js"></script>

		</div>

	</body>

</html>
