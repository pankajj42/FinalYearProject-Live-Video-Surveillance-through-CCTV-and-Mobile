<?php
	include('connection.php');
	$choice = $_GET['choice'];
	
	if(isset($_POST['searc'])){
		$t = $_POST['title'];
		header("Location: search.php?title=".$t."&viewer=".$viewn);
	}
	
	function render($error)
	{
		include('connection.php');
		$choice = $_GET['choice'];
		echo '<!DOCTYPE html>
		<!-- saved from url=(0056)http://coreui.io/demo/Static_Demo/components-tables.html -->
		<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
			
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
				width:150px;
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
				width:150px;
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
				width:150px;
				transition: all .2s ease-in-out;
				color: #fff;
				background-color: #f86c6b;
			}
			.btn_stream{
				display: inline-block;
				line-height: 1.25;
				text-align: center;
				vertical-align: middle;
				cursor: pointer;
				user-select: none;
				border: 1px solid transparent;
				padding: .5rem 1rem;
				width:150px;
				transition: all .2s ease-in-out;
				color: #fff;
				background-color: #2980b9;
			}
		</style>
		</head>
		<body class="navbar-fixed sidebar-nav fixed-nav  pace-done"><div class="pace  pace-inactive"><div class="pace-progress" data-progress-text="100%" data-progress="99" style="transform: translate3d(100%, 0px, 0px);">
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
								data:"title="+title.trim(" ")+" '.$viewn.'", 
								success:function(datav){
									$("#result").html(datav);
								 }     
								});
							}
					}  
				</script>
				</form>
				
				<ul class="nav navbar-nav pull-right hidden-md-down" style="margin-right:20px;margin-top:17px">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="viewerLog.php" role="button" aria-haspopup="true" aria-expanded="false">
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
                    echo '<li class="nav-item nav-dropdown">
                        <a class="nav-link" href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$viewn.'"><i class="icon-puzzle"></i>'.$run['username'].'</a>
                    </li>
					';}
					echo '
                </ul>
				</nav>
			</div>
			<main class="main">
				<!-- Breadcrumb -->
				<ol class="breadcrumb">
					<li class="breadcrumb-item">Home</li>
					<li class="breadcrumb-item">Admin</a></li>
					<li class="breadcrumb-item active">'.$choice.'</li>
				</ol>
				<div class="container-fluid">
					<div class="animated fadeIn">
						<div class="row">
							<div class="col-lg-12">
								<div class="card">
									<div class="card-header"><i class="fa fa-align-justify"></i>'.$choice.'</div>
									<div class="card-block">
										<table class="table table-bordered table-striped table-condensed">
											<div class="container-fluid">
												<div class="animated fadeIn">
													<div class="card">
														<div class="card-block">';
															$result = mysqli_query($conn,"SELECT * FROM centres where username = '".$choice."'");
															$run = mysqli_fetch_assoc($result);
																echo '
															<form method="post" class="form-horizontal ">
																<div class="form-group row">
																	<label class="col-md-3 form-control-label" for="hf-password">New Password</label>
																	<div class="col-md-9">
																		<input type="password" id="hf-password" name="hf-password" autocomplete="off" class="form-control">
																		<span class="help-block">Please enter your password</span>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-3 form-control-label" for="hf-rooms">Rooms</label>
																	<div class="col-md-9">
																		<input type="number" id="hf-rooms" name="hf-rooms" autocomplete="off" class="form-control" value="'.$run['rooms'].'" required>
																		<span class="help-block">Please enter number of rooms</span>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-3 form-control-label" for="hf-phone">Phone Number</label>
																	<div class="col-md-9">
																		<input type="number_format" id="hf-phone" name="hf-phone" autocomplete="off" class="form-control" value="'.$run['phone'].'" required>
																		<span class="help-block">Please enter your phone number</span>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-3 form-control-label" for="hf-name">School Name</label>
																	<div class="col-md-9">
																		<input type="number_format" id="hf-name" name="hf-name" autocomplete="off" class="form-control" value="'.$run['name'].'" required>
																		<span class="help-block">Please enter your school name</span>
																	</div>
																</div>
																<div class="form-group row">
																	<label class="col-md-3 form-control-label" for="hf-address">Address</label>
																	<div class="col-md-9">
																		<input type="number_format" id="hf-address" name="hf-address" autocomplete="off" class="form-control" value="'.$run['address'].'" required>
																		<span class="help-block">Please enter your address of the school</span>
																	</div>
																</div>
																<p style="color:red; size:12px">'.$error.'</p>
																<div class="card-footer">
																	<input type="submit" name="addc" class="btn btn-sm btn-primary" value="Edit Centre">
																</div>
															</form>';
															echo '
														</div>
													</div>
												</div>
											</div>
										</table>
									</div>
								</div>
							</div>
							<!--/col-->
						</div>
						<!--/row-->
					</div>
				</div>
				<!-- /.conainer-fluid -->
			</main>
			<!-- Bootstrap and necessary plugins -->
			<script src="./Table_files/jquery.min.js.download"></script>
			<script src="./Table_files/tether.min.js.download"></script>
			<script src="./Table_files/bootstrap.min.js.download"></script>
			<script src="./Table_files/pace.min.js.download"></script>
			<!-- Plugins and scripts required by all views -->
			<script src="./Table_files/Chart.min.js.download"></script>
			<!-- GenesisUI main scripts -->
			<script src="./Table_files/app.js.download"></script>
		
	</body></html>';
	}
	
	if(isset($_POST['addc'])){
				$pass = $_REQUEST['hf-password'];
				$roomn = $_REQUEST['hf-rooms'];
				$phone = $_REQUEST['hf-phone'];
				$name = $_REQUEST['hf-name'];
				$address = $_REQUEST['hf-address'];
				
				if($pass == "")
				{
					$result = mysqli_query($conn,"UPDATE centres SET rooms = '".$roomn."', phone = '".$phone."', name = '".$name."', address = '".$address."' where username = '".$choice."'");
				}
				
				else{
					
					$shapass = sha1($pass);
					$result = mysqli_query($conn,"UPDATE centres SET password = '".$shapass."', rooms = '".$roomn."', phone = '".$phone."', name = '".$name."', address = '".$address."' where username = '".$choice."'");
				}
					if($result){
						render("Successful");
					}
					else{
						render("Enter Again");
					}
			}
			else{
				render("");
			}
?>