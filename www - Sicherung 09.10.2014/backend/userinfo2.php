<?php
	if ((! isset($_POST["user_id"]))){
		http_response_code(400);
		exit("No POST vars set");  
	}

	$user_id = $_POST["user_id"];
	if ($user_id==null){
		http_response_code(411);
		exit("no data provided");
	}

	include 'config.php';

	$query="SELECT * FROM user LEFT JOIN (current_category, points, category) ON (user.user_id=current_category.user_id AND user.user_id=points.user_id AND current_category.category_id=category.category_id) WHERE user.user_id = $user_id;";
	$result=mysql_query($query);

	$npqquery = "SELECT number_pq FROM user WHERE user_id = '$user_id';";
	$npqquery2 = mysql_query($npqquery);
	$npq = mysql_fetch_array($npqquery2);

	if($result && $line=mysql_fetch_assoc($result)){
		$values=array('username'=>$line['username'],
						'role'=>$line['role'],
						'category_title'=>$line['category_title'],
						'category_description'=>$line['category_description'],
						'category_id'=>$line['category_id'],
						'points'=>$line['user_points'],
						'user_id'=>$line['user_id'],
						'number_pq'=>$npq['number_pq'],
						'avatar'=>$line['avatar'],
						);
		$query_questions="SELECT count(question_set_id) FROM  played_question WHERE user_id='$user_id';";
		$result_questions=mysql_query($query_questions);
		if($result_questions && $line_questions = mysql_fetch_array($result_questions)){
			$values['total_questions'] = $line_questions[0];
		} else {
			$values['total_questions'] = 0;
		}
		echo json_encode($values);
	} else {
		http_response_code(401);
		echo("user not found");
	}

	

	//$valuesnp = array('number_pq'=>$npq['number_pq']);
	//echo json_encode($valuesnp);
	//mysql_close();
?>