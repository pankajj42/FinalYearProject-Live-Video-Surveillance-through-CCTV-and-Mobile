<?php
 
include('../connection.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
	if($_POST['getallcentres']=='centres'){
		$sql = "SELECT * FROM centres";
		$r = mysqli_query($conn,$sql);
		$result = array();
		while( $res = mysqli_fetch_array($r) ) {
			$q = mysqli_fetch_array(mysqli_query($conn,"SELECT max(record) as max FROM streams where centre='".$res['username']."'"))['max'];
			$val = "false";
			if($q==1){
				$val = "true";
			}
			array_push($result,array("name"=>$res['name'],"username"=>$res['username'],"active"=>$val));
		}
		echo json_encode(array("result"=>$result));
	}
}
else{
	echo '{"error":"GetLost"}';
}

?>