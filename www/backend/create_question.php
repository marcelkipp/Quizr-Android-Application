<?php
	if ((! isset($_POST["question_text"])) && (! isset($_POST["answer_id1"])) && (! isset($_POST["answer_id2"])) && (! isset($_POST["answer_id3"]))  && (! isset($_POST["answer_id4"])) && (! isset($_POST["answer_explanation"])) && (! isset($_POST["category_id"])) && (! isset($_POST["difficulty"]))){
		http_response_code(400);
		exit("No POST vars set"); 
	}
	
	$question_text = $_POST["question_text"];
	$answer_id1 = $_POST["answer_id1"];
	$answer_id2 = $_POST["answer_id2"];
	$answer_id3 = $_POST["answer_id3"];
	$answer_id4 = $_POST["answer_id4"];
	$answer_explanation = $_POST["answer_explanation"];
	$category_id = $_POST["category_id"];
	$difficulty = $_POST["difficulty"];
	$filename = $_FILES['datei']['name'];
	$filesize = $_FILES['datei']['size'];
	$link = 'http://localhost/html/menu.html';
	$linkerror = 'http://localhost/html/editor_question_error.html';
	$linkerror2 = 'http://localhost/html/editor_question_error_felder.html';

	include 'config.php';

	if ($question_text==null || $answer_id1==null || $answer_id2==null || $answer_id3==null || $answer_id4==null || $answer_explanation==null || $category_id==null || $difficulty==null){
		http_response_code(411);
		header('Location: http://localhost/html/editor_question_error_felder.html');
		exit("no data provided"); 
	}

	$query="SELECT question_text FROM question WHERE question_text = '$question_text';";
	$existing_question=mysql_query($query);
	if($existing_question){
		if (mysql_fetch_assoc($existing_question)) {
		http_response_code(409);
		mysql_close();
		exit("question already exist");
		}
	}

	$querypicturename="SELECT  media FROM question WHERE media ='$filename';";//Prüft ob Dateiname schon vorhanden ist.
	$existing_filename=mysql_query($querypicturename);
	if($existing_filename){
		if(mysql_fetch_assoc($existing_filename)) {
			http_response_code(409);
			mysql_close();
			exit("filename already exist");
		}
	}

	if($filesize != 0) { // Prüft ob überhaupt eine Datei hochgeladen wurde.
		if($_FILES['datei']['size'] < 10000000) { // erlaubte Maximalgröße
			$info = GetImageSize($_FILES['datei']['tmp_name']);
			if($info[2] != 0) { // Prüft den Bildtyp
				$filenameid = uniqid();
				move_uploaded_file($_FILES['datei']['tmp_name'], "upload/$filenameid.jpg"); // Verschiebt Bilddatei in Upload-Ordner
				$file 		= "upload/$filename";
				$target 	= $filename;
				$max_width 	= "300"; //Breite ändern
				$max_height	= "300"; //Höhe ändern
				$quality	= "90"; //qualität ändern
				$src_img	= imagecreatefromjpeg($file);
				$picsize 	= GetImageSize($file);
				$src_width	= $picsize[0];
				$src_height = $picsize[1];

				if($src_width > $src_height) { 
	                if($src_width > $max_width) { 
		                $convert = $max_width/$src_width; 
		                $dest_width = $max_width; 
		                $dest_width = $max_width; 
		                $dest_height = ceil($src_height*$convert);
	                } else { 
		                $dest_width = $src_width; 
		                $dest_height = $src_height; 
	                } 
	            } else { 
	                if($src_height > $max_height) { 
		                $convert = $max_height/$src_height; 
		                $dest_height = $max_height; 
		                $dest_width = ceil($src_width*$convert); 
	                } else { 
			                $dest_height = $src_height; 
			                $dest_width = $src_width; 
		            } 
		        } 

                $dst_img = imagecreatetruecolor($dest_width,$dest_height); 
                imagecopyresampled($dst_img, $src_img, 0, 0, 0, 0, $dest_width, $dest_height, $src_width, $src_height); 
                imagejpeg($dst_img, $file, $quality); 

				$question_id=uniqid();//Fügt Question in die Datenbank ein
				$queryquestion="INSERT INTO question (question_text, question_id, category_id, difficulty, media) VALUES ('$question_text', '$question_id', '$category_id', '$difficulty', '$filenameid')";
				mysql_query($queryquestion);
				$queryquestionset="INSERT INTO question_set (question_id, answer_id1, answer_id2, answer_id3, answer_id4) VALUES ('$question_id', '$answer_id1', '$answer_id2', '$answer_id3', '$answer_id4')";
				mysql_query($queryquestionset);
				$queryanswer="INSERT INTO answer (question_id, answer_text, answer_explanation, difficulty) VALUES ('$question_id', '$answer_id1', '$answer_explanation', '$difficulty')";
				mysql_query($queryanswer);
				$result=array('question_id'=>$question_id,'question_text'=>$question_text, 'answer_id1'=>$answer_id1, 'answer_id2'=>$answer_id2, 'answer_id3'=>$answer_id3, 'answer_id4'=>$answer_id4, 'answer_explanation'=>$answer_explanation, 'filename'=>$filename,);
				echo json_encode($result);
				echo $filesize;
				mysql_close();
				header("Location: $link");
			} else {
				header("Location: $linkerror");
				exit ("Bitte laden Sie nur Bilder vom Typ .gif .jpg oder .png hoch!");
				$errortype=array('error'=>'Bitte laden Sie nur Bilder vom Typ .gif .jpg oder .png hoch!');
				echo json_encode($errortype);
			}
		} else {
			header("Location: $linkerror");
			exit ("Die Maximalgröße für Uploads liegt bei 10MB");
			$errorsize=array('error'=>'Die Maximalgröße für Uploads liegt bei 10MB');
			echo json_encode($errorsize);
		}
	} else {
		echo ('Keine Datei hochgeladen');
		$question_id=uniqid();
		$queryquestion="INSERT INTO question (question_text, question_id, category_id, difficulty, media) VALUES ('$question_text', '$question_id', '$category_id', '$difficulty', '$filename')";
		mysql_query($queryquestion);
		$queryquestionset="INSERT INTO question_set (question_id, answer_id1, answer_id2, answer_id3, answer_id4) VALUES ('$question_id', '$answer_id1', '$answer_id2', '$answer_id3', '$answer_id4')";
		mysql_query($queryquestionset);
		$queryanswer="INSERT INTO answer (question_id, answer_text, answer_explanation, difficulty) VALUES ('$question_id', '$answer_id1', '$answer_explanation', '$difficulty')";
		mysql_query($queryanswer);           
		$result=array('question_id'=>$question_id,'question_text'=>$question_text, 'answer_id1'=>$answer_id1, 'answer_id2'=>$answer_id2, 'answer_id3'=>$answer_id3, 'answer_id4'=>$answer_id4, 'answer_explanation'=>$answer_explanation);
		echo json_encode($result);
		echo $filesize;
		mysql_close();
		header("Location: $link");
}
?>