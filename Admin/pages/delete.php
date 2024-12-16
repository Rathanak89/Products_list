<?php 
include_once '../config_db/config_db.php';
	$id =$_GET['id'];
	$sql  = "DELETE FROM products WHERE id=$id";
	$_Result = mysqli_query($conn ,$sql);
	if(!$_Result){
		echo 'Data deleted error!';
	}else{
		header("location: ../index.php?p=product_list");
	}
?>