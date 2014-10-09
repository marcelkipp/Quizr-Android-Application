<?php
	$dbh = mysql_connect("rdbms.strato.de", "U1844058", "marcelkipp2506");
	$query = "use DB1844058";
	if (!mysql_query($query, $dbh)) die("Datenbank existiert nicht.n");
?>