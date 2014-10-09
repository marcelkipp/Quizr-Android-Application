<?php
	if ((! isset($_GET["question_id"]))){
		http_response_code(400);
		exit("No GET vars set");  
	}

	$question_id = $_GET["question_id"];

//	if ($question_id==null){
//		http_response_code(411);
//		exit("no data provided");
//	}

	include 'config.php';

	$query="SELECT answer_id1, answer_id2, answer_id3, answer_id4 FROM question_set WHERE question_id ='$question_id';";
	$result=mysql_query($query);

	$query2="SELECT question_text, media FROM question WHERE question_id = '$question_id';";
	$result2=mysql_query($query2);
	$line2=mysql_fetch_assoc($result2);

	$query3="SELECT question_id, answer_id, answer_text, answer_explanation, difficulty FROM answer WHERE question_id = '$question_id';";
	$result3=mysql_query($query3);
	$line3=mysql_fetch_assoc($result3);

	if($result && $line=mysql_fetch_assoc($result)){
		$values=array('question_id'=>$question_id,
						'question_text'=>$line2['question_text'],
						'answer_id1'=>$line['answer_id1'],
						'answer_id2'=>$line['answer_id2'],
						'answer_id3'=>$line['answer_id3'],
						'answer_id4'=>$line['answer_id4'],
						'answer_id'=>$line3['answer_id'],
						'answer_text'=>$line3['answer_text'],
						'answer_explanation'=>$line3['answer_explanation'],
						'difficulty'=>$line3['difficulty'],
						'media'=>$line2['media'],
						);
		echo json_encode($values); 
	} else {
		http_response_code(401);
		echo("question not found");
	}
	mysql_close();
?>