<?php
 
include('../connection.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
 $username = $_POST['username'];
 $sql = "SELECT * FROM streams where centre='".$username."' and lockRoom=0";
 
 $r = mysqli_query($conn,$sql);
 $result = array();
 while( $res = mysqli_fetch_array($r) ) {
  array_push($result,array("name"=>$res['name'],"port"=>$res['port']));
 }
 echo json_encode(array("result"=>$result));
}
else{
echo '{"error":"GetLost"}';
}

?>