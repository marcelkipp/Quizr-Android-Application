<?php
	if ((! isset($_POST["username"])) && (! isset($_POST["password"])) && (! isset($_POST["email"]))){
		http_response_code(400);
		exit("No POST vars set"); 
	}

	$username = $_POST["username"];
	$password = $_POST["password"];
	$email = $_POST["email"];

	if ($username==null || $email==null || $password==null){
		http_response_code(411);
		exit("no data provided");
	}
	if (strlen($username) < 5 || strlen($email) < 6 || strlen($password) < 6 ){
		http_response_code(411);
		exit ("Data not sufficent");
	}
	if (strpos($email, "\@") > 0) {
		http_response_code(411);
		exit ("Email $email not valid");
	}

	include 'config.php';

	$query="SELECT username FROM user WHERE username = '$username';";
	$existing_user=mysql_query($query);
	if($existing_user){
			if (mysql_fetch_assoc($existing_user)) {
			http_response_code(409);
			mysql_close();
			exit("username already exist");
		}
	}
	$password_salted=hash('sha256', $password, $username);
	$query="INSERT INTO `user` (`user_id`, `email`, `username`, `password`, `role`) VALUES (NULL, '$email', '$username', '$password_salted', 'user');";
	mysql_query($query); //Prüfen ob wirklich user_id enthalten ist
	$user_id=mysql_insert_id();
	$session_id=uniqid();
	$query="INSERT INTO points (user_id, user_points) VALUES ($user_id, 0)";
	mysql_query($query);
	$query="INSERT INTO current_category (user_id, category_id) VALUES ($user_id, 1)";
	mysql_query($query);
	$query="INSERT INTO session (session_id, user_id) VALUES ('$session_id', $user_id);";
	mysql_query($query);
	$result=array('session_id'=>$session_id,'user_id'=>$user_id);
	echo json_encode($result);
	mysql_close();
?>