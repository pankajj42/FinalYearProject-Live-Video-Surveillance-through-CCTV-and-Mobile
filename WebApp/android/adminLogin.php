<?php
 
include('../connection.php');
if($_SERVER['REQUEST_METHOD']=='POST'){
 $username = $_POST['username'];
 $password = $_POST['password'];

 $sql = "SELECT * FROM admin";
 
 $r = mysqli_query($conn,$sql);
 $str = "false";
 while( $res = mysqli_fetch_array($r) ) {
  if( $username==$res['username'] ) {
   if( $password==$res['password'] ) {
    $str = "true";
   }
   break;
  }
 }
    $str = "true";
 echo json_encode(array("result"=>$str));
}
else{
echo '{"error":"GetLost"}';
}

?>
