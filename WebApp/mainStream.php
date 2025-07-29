<?php
	include('connection.php');
	$viewn = $_GET['viewer'];
	$ipAddr = '172.16.52.72';
	$stream = $_GET['stream'];
	$one  = 1;
	$result = mysqli_fetch_array(mysqli_query($conn,'select * from streams where id='.$stream));
	$centre = $result['centre'];
//$ipAddr = $ipAddr.':'.($result['port']+1);
	if(isset($_POST['searc'])){
		$t = $_POST['title'];
		header("Location: search.php?title=".$t."&viewer=".$viewn);
	}
	echo '<!DOCTYPE html>
	<html lang="en">
	<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="CoreUI Bootstrap 4 Admin Template">
        <meta name="author" content="Lukasz Holeczek">
        <meta name="keyword" content="CoreUI Bootstrap 4 Admin Template">
        <!-- <link rel="shortcut icon" href="assets/ico/favicon.png"> -->
				  <title>Live Streams</title>
        <!-- Icons -->
        <link href="./helper/font-awesome.min.css" rel="stylesheet">
        <link href="./helper/simple-line-icons.css" rel="stylesheet">
		<link rel="stylesheet" href="helper/bootstrap.min.css">
        <!-- Main styles for this application -->
        <link href="./helper/style.css" rel="stylesheet">
		<link href="styles.css" rel="stylesheet">
		<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
        <script src="js/bootstrap.min.js"></script>
		<script type="text/javascript" src="js/jwplayer.js"></script>
		<script src="js/player.js" type="text/javascript"></script>
		<script>jwplayer.key = ""</script>
		
		<style>
			.btn_view{
				display: inline-block;
				line-height: 1.25;
				text-align: center;
				vertical-align: middle;
				cursor: pointer;
				user-select: none;
				border: 1px solid transparent;
				padding: .5rem 1rem;
				width:120px;
				transition: all .2s ease-in-out;
				color: #fff;
				background-color: #f8cb00;
				margin-right:20px;
			}
			.btn_edit{
				display: inline-block;
				line-height: 1.25;
				text-align: center;
				vertical-align: middle;
				cursor: pointer;
				user-select: none;
				border: 1px solid transparent;
				padding: .5rem 1rem;
				width:120px;
				transition: all .2s ease-in-out;
				color: #fff;
				background-color: #4dbd74;
				margin-right:20px;
			}
			.btn_delete{
				display: inline-block;
				line-height: 1.25;
				text-align: center;
				vertical-align: middle;
				cursor: pointer;
				user-select: none;
				border: 1px solid transparent;
				padding: .5rem 1rem;
				width:120px;
				transition: all .2s ease-in-out;
				color: #fff;
				background-color: #f86c6b;
			}
		</style>
    </head>
    <body class="navbar-fixed sidebar-nav fixed-nav  pace-done" onload="setPlayers([';
		$alln = mysqli_query($conn,"select * from streams where centre='".$centre."'");
		$all = mysqli_fetch_array($alln);
		if($all) {
			echo "'main_player','1085','500' ";
		}
		$allndvr = mysqli_query($conn,"select * from dvrs where centre='".$centre."'");
		while($alldvr = mysqli_fetch_array($allndvr) ) {
			echo ",'".$alldvr['id']."','',' '";
		}
		
		while($all = mysqli_fetch_array($alln) ) {
			echo ",'".$all['id']."','',' '";
		}
		echo '])"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
  <div class="pace-progress-inner"></div>
</div>
<div class="pace-activity"></div></div>
        <header class="navbar">
            <div class="container-fluid">
				<h style="font-size:20px;float:left;margin-left:5%"><b>Live Streams</b></h>';
				?>
				<form  autocomplete="off" style="float:left" id="contact-form" method="post" >
				<input type="text" name="title" id="title" onclick="search()" onkeydown="search()" onkeypress="search()" onkeyup="search()" autocomplete="off" class="search_url" placeholder="Search..."  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 2px solid #2c3e50;" />
				
				<ul style="max-height:300px;overflow:auto;width:600px;top:50px;margin-left:340px"  class="dropdown-menu" aria-labelledby="title" id="result">
    
				</ul>
				<button style="background: #2c3e50;" name='searc' class="search">
				Search
				<span class="glyphicon glyphicon-search"></span>
				</button>
				<?php
				
                echo '
				
				<script>
					function search(){
						
						var title=$("#title").val();
						if(title=="")
						$("#result").html("");
						if(title!=""&&title.length>=1){
							$.ajax({
								type:"post",
								url:"search1.php",
								data:"title= "+title.trim(" ")+" '.$viewn.'", 
								success:function(datav){
									$("#result").html(datav);
								 }     
								});
							}
					}  
				</script>
				</form>
				
				<ul class="nav navbar-nav pull-right hidden-md-down" style="margin-right:20px">
                    <li class="nav-item dropdown">
                        <a href="login.php">
                            <span class="hidden-md-down">'.$viewn.'</span>
                        </a>
                    </li>
                </ul>
            </div>
        </header>
		<div class="sidebar">
            <nav class="sidebar-nav open" style="height: 558px;">
                <ul class="nav">
					<li class="nav-item">
                        <a class="nav-link" href="viewHome.php?viewer='.$viewn.'"><i class="icon-speedometer"></i> Home </a>
                    </li>
                    <li class="nav-title">
                        Centre List
                    </li>';
					$result = mysqli_query($conn,"SELECT * FROM centres");
					while($run = mysqli_fetch_assoc($result)){
						$row = mysqli_query($conn,"SELECT min(id) as streamnum FROM streams where name = 'Room_1' AND centre = '".$run['username']."'");
						$num = mysqli_fetch_assoc($row);
						$row1 = mysqli_query($conn,"SELECT count(record) as count FROM streams where record = 1 AND centre = '".$run['username']."'");
						$numrec = mysqli_fetch_assoc($row1);
						if($numrec['count'] == 0)
						{
							echo '<li class="nav-item nav-dropdown">
								<a class="nav-link" href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$viewn.'"><i class="icon-puzzle"></i>'.$run['username'].'<img src="Offline_dot.png" width="10" height="10" style="margin-left:60px"></img></a>
							</li>
						';}
						else
						{
							echo '<li class="nav-item nav-dropdown">
								<a class="nav-link" href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$viewn.'"><i class="icon-puzzle"></i>'.$run['username'].'<img src="green-dot.png" width="9" height="9" style="margin-left:60px"></img></a>
							</li>
					';}}
					echo '
					<li class="nav-item">
                        <a class="nav-link" href="previous.php?viewer='.$viewn.'" style="font-size:15px"><i class="icon-speedometer"></i> Previous Files </a>
                    </li>
                </ul>
            </nav>
        </div>
        <main class="main">
            <!-- Breadcrumb -->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Admin</a></li>
                <li class="breadcrumb-item active">'.$centre.'</li>
            </ol>
            <div class="container-fluid">
                <div class="animated fadeIn">
                    <div class="row">';
						$rows = mysqli_query($conn,'select * from streams where id='.$stream);
						$row=mysqli_fetch_array($rows);
						if($row) {
							echo '
							<div style="float: left; margin-left: 17px; margin-top:5px; margin-right: 5px">
								<div id="video_preview1" class="social-box">
									<div id="main_player" class="player"></div><div class="clear"></div>
									<input type="button" id="start|main_player" onclick="startStream(\'main_player\',\'500\',\'1085\')" class="btn_start_stop btn_start" value="Start" />
									<input type="button" id="stop|main_player" onclick="stopStream()" class="btn_start_stop btn_stop" value="Stop"/>
									<input type="text" class="stream_url" id="urimain_player" value="rtmp://'.$ipAddr.':'.(intval($row['port'])+$one).'/live/'.$row['name'].'"/><br/>
								
										<ul style="color:#34495e;vertical-align:center;">
											<li style="margin-top:-2.7%">
												<strong>'.$row['name'].'</strong>
												<span style="color:#34495e">Room</span>
											</li>
											<li style="margin-top:-2.7%">
												<strong>'.$row['id'].'</strong>
												<span style="color:#34495e">Stream ID</span>
											</li>
										</ul>								
								</div>
								
						
							</div>
						';
						}echo'
						</div>
							<div class="row">';
							
								$rows = mysqli_query($conn,'select * from dvrs where centre="'.$centre.'"');
								while($row=mysqli_fetch_array($rows))
								{
									echo'<div class="col-sm-6 col-lg-3">
										<div class="social-box" style="cursor:pointer" id="video_preview2" onclick="swapPlayer(\''.$row['id'].'\')">
											<div id="'.$row['id'].'" class="player"></div><div class="clear"></div>
										  <input hidden type="text" class="stream_url" id="uri'.$row['id'].'" value="rtmp://'.$ipAddr.':'.(intval($row['port'])+$one).''.'/live/'.$row['id'].'"/><br/>
										  
										  <ul style="color:#34495e;vertical-align:center;">
											<li style="margin-top:-12%">
												<strong>'.$row['name'].'</strong>
												<span style="color:#34495e">Room</span>
											</li>
											<li style="margin-top:-12%">
												<strong>'.$row['id'].'</strong>
												<span style="color:#34495e">Stream ID</span>
											</li>
										</ul>
										  
										</div>
										</div>';
								}
							
								$rows = mysqli_query($conn,'select * from streams where centre="'.$centre.'" and id <> '.$stream);
								while($row=mysqli_fetch_array($rows))
								{
									echo'<div class="col-sm-6 col-lg-3">
										<div class="social-box" style="cursor:pointer" id="video_preview2" onclick="swapPlayer(\''.$row['id'].'\')">
											<div id="'.$row['id'].'" class="player"></div><div class="clear"></div>
										  <input hidden type="text" class="stream_url" id="uri'.$row['id'].'" value="rtmp://'.$ipAddr.':'.(intval($row['port'])+$one).''.'/live/'.$row['name'].'"/><br/>
										  
										  <ul style="color:#34495e;vertical-align:center;">
											<li style="margin-top:-12%">
												<strong>'.$row['name'].'</strong>
												<span style="color:#34495e">Room</span>
											</li>
											<li style="margin-top:-12%">
												<strong>'.$row['id'].'</strong>
												<span style="color:#34495e">Stream ID</span>
											</li>
										</ul>
										  
										</div>
										</div>';
								}
							echo '
					</div>
                </div>
            </div>
            <!-- /.conainer-fluid -->
        </main>
        <!-- Bootstrap and necessary plugins 
        <script src="./Table_files/jquery.min.js.download"></script>
        <script src="./Table_files/tether.min.js.download"></script>
        <script src="./Table_files/bootstrap.min.js.download"></script>
        <script src="./Table_files/pace.min.js.download"></script>
        <script src="./Table_files/Chart.min.js.download"></script>
        <script src="./Table_files/app.js.download"></script> -->
    
</body></html>';?>
