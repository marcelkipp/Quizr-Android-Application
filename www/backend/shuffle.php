<?php 
	if ((! isset($_GET["category_id"]))){
		http_response_code(400);
		exit("No GET vars set");  
	}

	$category_id = $_GET['category_id'];
	if ($category_id==null){
		http_response_code(411);
		exit("no data provided");
	}

	include 'config.php';

	$query="SELECT question_id FROM question WHERE category_id = '$category_id' ORDER BY RAND() LIMIT 1;";
	$result=mysql_query($query);
	if($result && $line=mysql_fetch_assoc($result)){
		$values=array('question_id'=>$line['question_id'],);
			echo json_encode($values); 
	} else {
		http_response_code(401);
		echo("question not found");
	}
	mysql_close();

?>