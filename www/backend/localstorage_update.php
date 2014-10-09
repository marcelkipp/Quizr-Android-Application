<?php
	//if ((! isset($_GET["username"]))){
	//	http_response_code(400);
	//	exit("No GET vars set");  
	//}

//	$username = $_GET['username'];

	if ((! isset($_GET["user_id"]))){
		http_response_code(400);
		exit("No GET vars set");  
	}

	$user_id=$_GET['user_id'];

	include 'config.php';

	//$queryid=mysql_query("SELECT user_id FROM user WHERE username = '$username';");
	//$resultid=mysql_fetch_array($queryid);
	//$valuesid=$resultid["user_id"];

	//$querycid=mysql_query("SELECT category_id FROM current_category WHERE user_id = $user_id;");
	//$resultcid=mysql_fetch_array($querycid);
	//$valuescid=$resultcid["category_id"];

	$query=mysql_query("SELECT category_id FROM current_category WHERE user_id = $user_id;");
	if($query && $line=mysql_fetch_assoc($query)){
		$values=array('category_id'=>$line['category_id'],);
			echo json_encode($values); 
	} else {
		http_response_code(401);
		echo("question not found");
	}
	mysql_close();
?>