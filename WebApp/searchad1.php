<?php
	$value = $_POST["title"];
	include('connection.php');
	$rows1 = mysqli_query($conn,"select * from centres where username like '".$value."%'");
	$rows2 = mysqli_query($conn,"select * from centres where name like '".$value."%'");
	
	$i=20;
    $count=0;
	
	if($rows1){
        if(mysqli_num_rows($rows1) > 0){
            while($run = mysqli_fetch_assoc($rows1)){
				$nam = $run['username'];
				$pos = strpos($nam, $value);
				$ch="";
				for($x=strlen($value);$x<strlen($nam);$x++){
					$ch = $ch.$nam[$x];
				}
				
				echo '<li><a href="viewInfo.php?choice='.$run["username"].'"><b>Centre></b>Code: <b style="color:red">'.$value.'</b>'.$ch.'<b style="float:right">Name: '.$run['name'].'</b></a></li>';
				$i--;
				$count++;
			}
        }
    }
	
	if($rows2){
        if(mysqli_num_rows($rows2) > 0){
            while($run = mysqli_fetch_assoc($rows2)){
				$nam = $run['name'];
				$pos = strpos($nam, $value);
				$ch="";
				for($x=strlen($value);$x<strlen($nam);$x++){
					$ch = $ch.$nam[$x];
				}
				
				echo '<li><a href="viewInfo.php?choice='.$run["username"].'"><b>Centre></b>Code: '.$run['username'].'</b><b style="float:right">Name: <b style="color:red">'.$value.'</b>'.$ch.'</b></a></li>';
				$i--;
				$count++;
			}
        }
    }
	
	if($count>0)
    echo ' <li><a href="searchres1.php?title='.$value.'"><center>Total '.$count.' Results, Click here to see all.</center></a></li>';
    else echo'<li><a href="#"><center>No Results Found</center></a></li>';
	
?>