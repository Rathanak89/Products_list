<?php 
include_once 'config_db/config_db.php';
include_once 'pages/header/header.php';
?>

<?php 
	session_start();
	include_once 'config_db/config_db.php';
	if(!isset($_SESSION['user_login'])){
		header("location:login.php");
	}
 ?>

<?php

	$p_menu='';
	if(isset($_GET['p'])){
		$p_menu = $_GET['p'];
		if($p_menu != "login" && $p_menu != "register"){
			include_once 'pages/Header/menu.php';
		}
		
	}else{
		include_once 'pages/Header/menu.php';
	}
	
?>

<?php
	if(isset($_GET['p'])){
		require "pages/".$_GET['p'].".php";
	}else{
		require 'pages/homepage.php';
	}
?>


<?php include_once 'pages/footer/footer.php';?>




