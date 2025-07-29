<?php
	include('connection.php');
	if(isset($_POST['searc'])){
		$t = $_POST['title'];
		header("Location: searchad.php?title=".$t);
	}
	function render($error)
	{
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
				  <title>Admin Control Panel</title>
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
					<h style="font-size:20px;float:left;margin-left:5%"><b>Admin Control Panel</b></h>';
				?>
				<form  autocomplete="off" style="float:left" id="contact-form" method="post" >
				<input type="text" name="title" id="title" onclick="search()" onkeydown="search()" onkeypress="search()" onkeyup="search()" autocomplete="off" class="search_url" placeholder="Search..."  data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="border: 2px solid #2c3e50;" />
				
				<ul style="max-height:300px;overflow:auto;width:600px;top:50px;margin-left:415px"  class="dropdown-menu" aria-labelledby="title" id="result">
    
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
								url:"searchad1.php",
								data:"title="+title.trim(" "), 
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
                        <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="login.php" role="button" aria-haspopup="true" aria-expanded="false">
                            <span class="hidden-md-down">admin</span>
                        </a>
                    </li>
                </ul>
				</div>
			</header>
			<div class="sidebar">
				<nav class="sidebar-nav open" style="height: 558px;">
					<ul class="nav">
						<li class="nav-title">
							Options
						</li>
						<li class="nav-item nav-dropdown">
							<a class="nav-link" href="form1.php?choice=Add New Centre"><i class="icon-puzzle"></i>Add New Centre</a>
						</li>
						<li class="nav-item nav-dropdown">
							<a class="nav-link" href="form2.php?choice=Add New Viewer"><i class="icon-star"></i>Add New Viewer</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="form3.php?choice=Add New DVR"><i class="icon-pie-chart"></i>Add New DVR</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="table.php?choice=View All Centres"><i class="icon-calculator"></i>View All Centres</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="table.php?choice=View All Viewers"><i class="icon-pie-chart"></i>View All Viewers</a>
						</li>
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
										<table class="table table-bordered table-striped table-condensed">';?>
											<?php
												include('connection.php');
												if($choice=="Add New DVR")
												{
													echo'<div class="container-fluid">
															<div class="animated fadeIn">
																<div class="card">
																	<div class="card-block">
																		<form method="post" class="form-horizontal ">
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-centre">Select Centre</label>
																				<div class="col-md-9">
																					<select type="text" id="hf-centre" name="hf-centre" autocomplete="off" class="form-control">';
																					$result = mysqli_query($conn,"SELECT * FROM centres");
																					while($res = mysqli_fetch_assoc($result)) {
																						echo '<option value="'.$res['username'].'">'.$res['name'].'</option>';
																					}
																					echo '</select>
																					<span class="help-block">Please select a centre from the dropdown</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-username">Username</label>
																				<div class="col-md-9">
																					<input type="text" id="hf-username" name="hf-username" autocomplete="off" class="form-control" placeholder="Enter Username..">
																					<span class="help-block">Please enter your username</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-password">Password</label>
																				<div class="col-md-9">
																					<input type="password" id="hf-password" name="hf-password" autocomplete="off" class="form-control" placeholder="Enter Password..">
																					<span class="help-block">Please enter your password</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-ipaddr">IP Address</label>
																				<div class="col-md-9">
																					<input type="text" id="hf-ipaddr" name="hf-ipaddr" autocomplete="off" class="form-control" placeholder="Enter IP Address..">
																					<span class="help-block">Please enter the IP Address</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-port">Port Number</label>
																				<div class="col-md-9">
																					<input type="number_format" id="hf-port" name="hf-port" autocomplete="off" class="form-control" placeholder="Enter Port Number..">
																					<span class="help-block">Please enter the Port Number</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-cams">Number of Cameras</label>
																				<div class="col-md-9">
																					<input type="number_format" id="hf-cams" name="hf-cams" autocomplete="off" class="form-control" placeholder="Enter Number of Cameras..">
																					<span class="help-block">Please enter the Number of Cameras</span>
																				</div>
																			</div>
																			<div class="form-group row">
																				<label class="col-md-3 form-control-label" for="hf-name">Unique Name</label>
																				<div class="col-md-9">
																					<input type="text" id="hf-name" name="hf-name" autocomplete="off" class="form-control" placeholder="Enter a Unique Name..">
																					<span class="help-block">Please enter a Unique Name</span>
																				</div>
																			</div>
																			<p style="color:red; size:12px">'.$error.'</p>
																			<div class="card-footer">
																				<input type="submit" name="adddvr" class="btn btn-sm btn-primary" value="Add DVR">
																			</div>
																		</form>
																	</div>
																</div>
															</div>
														</div>';
												}
											echo '
											</tbody>
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
	if(isset($_POST['adddvr'])){
				$user = $_REQUEST['hf-username'];
				$pass = $_REQUEST['hf-password'];
				$centre = $_REQUEST['hf-centre'];
				$name = $_REQUEST['hf-name'];
				$ipaddr = $_REQUEST['hf-ipaddr'];
				$port = $_REQUEST['hf-port'];
				$cams = $_REQUEST['hf-cams'];
				if(empty($user) || empty($pass) || empty($name) || empty($ipaddr) || empty($port) || empty($cams))
				{
					render("Please Fill All The Information");
				}
				else
				{
					for($i=1;$i<=$cams;$i++) {
						$id = $name.$i;
						$url = 'rtsp://'.$user.':'.$pass.'@'.$ipaddr.'/MPEG4/ch1/main/av_stream';
						$q = "INSERT INTO dvrs VALUES('$id','$centre','$id',$port,0,'$url')";
						$result = mysqli_query($conn,$q);
						if($result){
							render("Successful");
						}
						else{
							render("Enter Again");
						}
					}
				}
			}
			else{
				render("");
			}
?>