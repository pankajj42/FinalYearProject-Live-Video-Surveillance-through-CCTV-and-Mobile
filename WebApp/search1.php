<?php
	$value = $_POST["title"];
	$pieces = explode(" ",$value);
	include('connection.php');
	$rows1 = mysqli_query($conn,"select * from centres where username like '".$pieces[1]."%'");
	$rows2 = mysqli_query($conn,"select * from centres where name like '".$pieces[1]."%'");
	$rows3 = mysqli_query($conn,"select * from streams where name like '".$pieces[1]."%'");
	
	$i=20;
    $count=0;
	
	while($run = mysqli_fetch_assoc($rows1)){
				
		$row = mysqli_query($conn,"SELECT min(id) as streamnum FROM streams where name = 'Room_1' AND centre = '".$run['username']."'");
		$num = mysqli_fetch_assoc($row);
		
		$nam = $run['username'];
		$pos = strpos($nam, $pieces[1]);
		$ch="";
		for($x=strlen($pieces[1]);$x<strlen($nam);$x++){
			$ch = $ch.$nam[$x];
		}
		
		echo '<li><a href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$pieces[2].'"><b>Centre></b>Code: <b style="color:red">'.$pieces[1].'</b>'.$ch.'<b style="float:right">Name: '.$run['name'].'</b></a></li>';
		$i--;
		$count++;
	}
	
	while($run = mysqli_fetch_assoc($rows2)){
				
		$row = mysqli_query($conn,"SELECT min(id) as streamnum FROM streams where name = 'Room_1' AND centre = '".$run['username']."'");
		$num = mysqli_fetch_assoc($row);
		
		$nam = $run['name'];
		$pos = strpos($nam, $pieces[1]);
		$ch="";
		for($x=strlen($pieces[1]);$x<strlen($nam);$x++){
			$ch = $ch.$nam[$x];
		}
		
		echo '<li><a href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$pieces[2].'"><b>Centre></b>Code: '.$run['username'].'<b style="float:right">Name: <b style="color:red">'.$pieces[1].'</b>'.$ch.'</b></a></li>';
		$i--;
		$count++;
    }
	
	while($run = mysqli_fetch_assoc($rows3)){
				
		$row = mysqli_query($conn,"SELECT min(id) as streamnum FROM streams where name = 'Room_1' AND centre = '".$run['centre']."'");
		$num = mysqli_fetch_assoc($row);
		
		$nam = $run['name'];
		$pos = strpos($nam, $pieces[1]);
		$ch="";
		for($x=strlen($pieces[1]);$x<strlen($nam);$x++){
			$ch = $ch.$nam[$x];
		}
		
		echo '<li><a href="mainStream.php?stream='.$num['streamnum'].'&viewer='.$pieces[2].'"><b>Stream></b>Centre Name: '.$run["centre"].'<b style="float:right">Room No.: <b style="color:red">'.$pieces[1].'</b>'.$ch.'</b></a></li>';
		$i--;
		$count++;
    }
	
	if($count>0)
    echo ' <li><a href="searchres.php?title='.$pieces[1].'&viewer='.$pieces[2].'"><center>Total '.$count.' Results, Click here to see all.</center></a></li>';
    else echo'<li><a href="#"><center>No Results Found</center></a></li>';
	
?>