<?php
	include('connection.php');
	function render($error)
	{
		echo'
		<!DOCTYPE html>
		<html >
		<head>
		  <meta charset="UTF-8">
		  <title>Viewer Login Panel</title>
		  <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,700" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
		  <link rel="stylesheet" href="style1.css">

		  <script src="https://cdnjs.cloudflare.com/ajax/libs/prefixfree/1.0.7/prefixfree.min.js"></script>

		</head>

		<body>
		  <div class="wrapper">
		  <form class="login" method="post">
			<p class="title">Viewer Login</p>
			<input type="text" placeholder="Username" name="username" autocomplete="off" autofocus/>
			<i class="fa fa-user"></i>
			<input type="password" placeholder="Password" name="password" />
			<p style="color:red; size:12px">'.$error.'</p>
			<input type="submit" name="login" value="Login">
		  </form>
		</div>
		  <script src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>

			<script src="js/index.js"></script>

		</body>
		</html>';
	}
	
	if(isset($_POST['login'])){
		$user = $_REQUEST['username'];
		$pass = $_REQUEST['password'];
		$result = mysqli_query($conn,"SELECT * FROM viewers WHERE username = '".$user."'");
		if(!$result)
		{
			render("Username or Password is Incorrect");
		}
		else
		{
			$row = mysqli_fetch_array($result);
			if($row["password"]==sha1($pass)){
				header("Location:viewHome.php?viewer=$user");
			}
			else{
				render("Username or Password is Incorrect");
			}
		}
	}
	else{
		render(''); 
	}
	?>