<?php
	if ((! isset($_POST["category_title"])) && (! isset($_POST["category_description"]))){
			http_response_code(400);
			exit("No POST vars set"); 
	}

	$category_description = $_POST["category_description"];
	$category_title = $_POST["category_title"];
	if ($category_title==null || $category_description==null){
		http_response_code(411);
		exit("no data provided");
	}

//	if (strlen($category_title) < 2 || strlen($category_description)){
//		http_response_code(411);
//		exit ("Data not sufficent");
//	}

	include 'config.php';

	$query="SELECT category_title FROM category WHERE category_title = '$category_title';";
	$existing_category=mysql_query($query);
	if($existing_category){
			if (mysql_fetch_assoc($existing_category)) {
			http_response_code(409);
			mysql_close();
			exit("category already exist");
		}
	}

	$query="INSERT INTO category (category_title, category_description) VALUES ('$category_title', '$category_description')";
	mysql_query($query);
	$result=array('category_title'=>$category_title,'category_description'=>$category_description);
	echo json_encode($result);
	mysql_close();
?>