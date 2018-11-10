
<?php
	$host	 = "sql12.freemysqlhosting.net";
	$user	 = "sql12264866";
	$pass	 = "b9t6cQ2hyG";
	$dabname = "sql12264866";
	
	$conn = mysql_connect( $host, $user, $pass) or die('Could not connect to mysql server.' );
	mysql_select_db($dabname, $conn) or die('Could not select database.');
	$baseurl="https://bahankaku.herokuapp.com";
?>