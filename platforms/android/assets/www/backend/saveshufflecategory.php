<?php
	$database = "quiz";
	include 'config.php';

	$user_id = $_GET["user_id"];
	$session_id = $_GET["session_id"];
	$category_id = $_GET["category_id"];

	$query="UPDATE current_category SET category_id = '$category_id' WHERE user_id = '$user_id';";
	mysql_query($query);
	mysql_close();
?>