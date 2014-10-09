<?php
	$database = "quiz";
	include 'config.php';

	if ( isset($_GET["user_id"]) &&  isset($_GET["session_id"]) &&  isset($_GET["category_id"])){	
		$user_id = $_POST["user_id"];
		$session_id = $_POST["session_id"];
		$category_id = $_POST["category_id"];

		$query="SELECT * FROM session WHERE user_id = $user_id";
		$result=mysql_query($query);
		if ($result && $line = mysql_fetch_array($result)){
			if ($line['session_id'] == $session_id){
				$query_del_cat="DELETE FROM current_category WHERE user_id = $user_id";
				mysql_query($query_del_cat);
				$query_insert="INSERT INTO current_category (category_id, user_id, number_of_played_question) VALUES ($category_id, $user_id, 0)";
				mysql_query($query_insert);
				$query_del_question="DELETE FROM current_question WHERE user_id = $user_id";
				mysql_query($query_del_question);
				mysql_close();
				$entry = array("category_id" => $category_id,
					"user_id" => $user_id);
				echo json_encode($entry);
				http_response_code(200);
				exit();
			}
		}
		echo("Not logged in");
		http_response_code(401);
		mysql_close();
		exit;

	}
	echo("No data provided");
	http_response_code(400);
	mysql_close();
?>