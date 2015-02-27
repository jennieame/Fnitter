<?php

	define('DBUSER', 'root');
	define('DBPASS', 'password');
	define('DBSERVER', 'localhost');
	define('DBSCHEMA', 'jeer');

function  connectToDb(){
	$connect = mysqli_connect(DBSERVER, DBUSER,  DBPASS, DBSCHEMA);

	if(!$connect){
		print "Du kommer inte åt databasen";
	}
	else{
		return $connect;
	}
}

