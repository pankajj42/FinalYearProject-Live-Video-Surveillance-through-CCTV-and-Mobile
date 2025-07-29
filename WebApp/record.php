<?php
$name   = $_POST['center'];
$record   = $_POST['record'];
include('connection.php');

mysqli_query($conn,"update streams set record = '".$record."' where name= '".$name."'");

?>