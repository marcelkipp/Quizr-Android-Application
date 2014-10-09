<?php
	if ((! isset($_POST["username"])) && (! isset($_POST["password"]))){
		http_response_code(400);
		exit("No POST vars set");  
	}

	$username = $_POST["username"];
	$password = $_POST["password"];
	if ($username==null || $password==null){
		http_response_code(411);
		exit("no data provided");
	}
	if (strlen($username) < 5 ||strlen($password) < 6 ){
		http_response_code(411);
		exit ("Data not sufficent");
	}
	
	include 'config.php';

	$password_salted=hash('sha256', $password, $username);
	$query="SELECT user_id FROM user WHERE username = '$username' AND password = '$password_salted';";
	$result=mysql_query($query);
	$user_id = null;
	if($result && $line=mysql_fetch_assoc($result)){
		$user_id = $line['user_id'];
	} else {
		http_response_code(401);
		mysql_close();
		exit("username or password is wrong");
	}
	
	$query="SELECT * FROM session WHERE user_id =$user_id;";
	$result=mysql_query($query);
	if ($result) {
		$queryplayed="SELECT * FROM current_question WHERE user_id =$user_id;";
		$resultplayed=mysql_query($queryplayed);
		if ($resultplayed) {
			$question_set_id=$resultplayed['question_set_id'];
			$queryquestion="INSERT INTO played_question (question_set_id, user_id) VALUES ('$question_set_id', $user_id);";
		}
		$querycurrent="DELETE FROM current_question WHERE user_id =$user_id;";
		$resultcurrent=mysql_query($querycurrent);
		// Für unbeantwortete Fragen wird derzeit keine Punkte abgezogen.
		$querysession="DELETE FROM session WHERE user_id = $user_id;";
		mysql_query($querysession);
	}

	$session_id=uniqid();
	$query="INSERT INTO session (session_id, user_id) VALUES ('$session_id', $user_id);";
	mysql_query($query);
	$result=array('session_id'=>$session_id,'user_id'=>$user_id);
	echo json_encode($result);
	mysql_close();
?>