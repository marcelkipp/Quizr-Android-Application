<?php
	include 'config.php';

	$query="SELECT * FROM category WHERE category_id > 1;";
	$categories=mysql_query($query);
	$result=array();
	$i=0;
	while($category=mysql_fetch_assoc($categories)) {
		$entry = array("category_id" => $category["category_id"],
			"category_title" => $category["category_title"]);
		$result[$i++]=$entry;
	}
	echo json_encode($result);
	mysql_close();
?>