<?php
	if ((! isset($_GET["user_id"])) ){
		http_response_code(400);
		exit("No POST vars set"); 
	}

	$user_id = $_GET["user_id"];

	include 'config.php';

	$currentpointsquery = "SELECT user_points FROM points WHERE user_id = '$user_id';";
	$currentpointsquery2 = mysql_query($currentpointsquery);
	$currentpoints = mysql_fetch_array($currentpointsquery2);
	//echo $currentpoints['user_points'];
	$pluspoints = "10";
	$endpoints = $currentpoints['user_points'] + "$pluspoints";

	$insertpointsquery = "UPDATE points SET user_points = '$endpoints' WHERE user_id = '$user_id';";
	mysql_query($insertpointsquery);

	$number_pq = "SELECT number_pq FROM user WHERE user_id = '$user_id';";
	$number_pq2 = mysql_query($number_pq);
	$npq = mysql_fetch_array($number_pq2);
	//echo $npq['number_pq'];
	$npqplus = "1";
	$npqend = $npq['number_pq'] + "$npqplus";

	$insertnpqend = "UPDATE user SET number_pq = '$npqend' WHERE user_id = '$user_id';";
	mysql_query($insertnpqend);
?>