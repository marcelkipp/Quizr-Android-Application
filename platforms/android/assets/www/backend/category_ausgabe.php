<?php
	if ((! isset($_GET["category_id"]))){
		http_response_code(400);
		exit("No GET vars set");  
	}

	$category_id = $_GET["category_id"];

	if ($category_id==null){
		http_response_code(411);
		exit("no data provided");
	}

	include 'config.php';

	$query="SELECT category_title FROM category WHERE category_id = '$category_id';";
	$result=mysql_query($query);
	if($result && $line=mysql_fetch_assoc($result)){
		$values=array('catgeory_id'=>$category_id,
						'category_title'=>$line['category_title'],
						);
			echo json_encode($values); 
	} else {
		http_response_code(401);
		echo("question not found");
	}
	mysql_close();
?>