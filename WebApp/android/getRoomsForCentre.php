<?php
 
include('../connection.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
	$centre = $_POST['centre'];
	$sql = "SELECT * FROM streams where centre='".$centre."'";
	$r = mysqli_query($conn,$sql);
	$result = array();
	while( $res = mysqli_fetch_array($r) ) {
		$val = "false";
		if($res['record']==1){
			$val = "true";
		}
		array_push($result,array("name"=>$res['name'],"active"=>$val,"id"=>$res['id'],"port"=>$res['port']));
	}
	echo json_encode(array("result"=>$result));
}
else{
	echo '{"error":"GetLost"}';
}

?>