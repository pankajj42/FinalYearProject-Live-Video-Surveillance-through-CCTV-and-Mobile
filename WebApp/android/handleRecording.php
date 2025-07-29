<?php
 
include('../connection.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
 $record = $_POST['record'];
 $centre = $_POST['username'];
 $room = $_POST['room'];
 $sql = "UPDATE streams set record=".$record." where centre='".$centre."' and name='".$room."'";
 
 $r = mysqli_query($conn,$sql);
 $result = "false";
 if($r) {
	 $result = "true";
 }
 echo json_encode(array("result"=>$result));
}
else{
echo '{"error":"GetLost"}';
}

?>