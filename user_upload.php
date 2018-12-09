<?php

/**
*	Michael Rimmer Catalyst PHP test script.
*
*/

echo "\n------------user_upload------------\n\n";

if ($argc > 1) 
{
	if($argv[1] == 'input') 
	{
		echo "Type 'yes' to continue: ";
		
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		
		if(trim($line) != 'yes')
		{
			echo "ABORTING!\n";
			exit;
		}
		fclose($handle);
		
		echo "input passed";
	}
	elseif($argv[1] == 'sql') 
	{
		
		//MySQL Enviroment Setup
		connectToDatabase();
	
		//$con->close();
		
		createTable($con);
	}
} 
else 
{
	echo "No argument passed\n";
	exit;
}

echo "\n\n------------Finish------------\n";


function connectToDatabase()
{
	$host="127.0.0.1";
	$port=3306;
	$socket="";
	$user="root";
	$password="Password1";
	$dbname="phptest";

	$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());
		
	echo 'connecton established \n';

	return $con;
}

function createTable($con)
{
	$sql = "CREATE TABLE users (
			email VARCHAR(50) PRIMARY KEY, 
			name VARCHAR(30) NOT NULL,
			surname VARCHAR(30) NOT NULL
			)";
		
	if (mysqli_query($con, $sql)) {
		echo "Table Users created successfully";
	} else {
		echo "Error creating table: " . mysqli_error($con);
	}

	mysqli_close($con);
	
	
}