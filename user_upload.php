<?php

/**
*	Michael Rimmer Catalyst PHP test script.
*
*/

echo "\n------------user_upload------------\n\n";

$userIsSet = false;
$passIsSet = false;
$hostIsSet = false;
$dbnameIsSet = false;

//defaults
$host="127.0.0.1"; 
$user="root"; 
$password="Password1";
$dbname="phptest"; 

if ($argc > 1) 
{	
	$i = 1;
	while($i < $argc){
		if($argv[$i] == '-u') 
		{
			echo "-u ";
			if ($argc > ($i+1)) 
			{
				$val = $argv[$i+1];
				$user = $val;
				$userIsSet = true;
				$i = $i + 1;
			}
			else
			{
				echo "Input Error: Incomplete Argument";
			}
		}
		elseif($argv[$i] == '-p') 
		{
			echo "-p ";
			if ($argc > ($i+1)) 
			{
				$val = $argv[$i+1];
				$password = $val;
				$passIsSet = true;
				$i = $i + 1;
			}
			else
			{
				echo "Input Error: Incomplete Argument";
			}
		}	
		elseif($argv[$i] == '-h') 
		{
			echo "-h ";
			if ($argc > ($i+1)) {
				$val = $argv[$i+1];
				$host = $val;
				$hostIsSet = true;
				$i = $i + 1;
			}
			else
			{
				echo "Input Error: Incomplete Argument";
			}
		}		
		elseif($argv[$i] == '-d') 
		{
			echo "-d ";
			if ($argc > ($i+1)) {
				$val = $argv[$i+1];
				$dbname= $val;
				$dbnameIsSet = true;
				$i = $i + 1;
			}
			else
			{
				echo "Input Error: Incomplete Argument";
			}
		}				
		$i = $i + 1;
	}
	echo "\n";
	
	$con = completeUserDatabaseInputs($userIsSet, $passIsSet, $hostIsSet, $dbnameIsSet, $host, $user, $password, $dbname);

		
	//Display Directive Menu 
	menu($con);
} 
else 
{
	echo "No Database arguments passed.\n";
	echo "Type: 'default' to connect with default database params. \n";
	echo "Type: 'new' to enter new database params. \n";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	
	if(trim($line) == 'default'){

		$con = connectToDatabase($host, $user, $password, $dbname);
		
		//Display Directive Menu 
		menu($con);
		
	}else if(trim($line) == 'new'){
		
		$con =completeUserDatabaseInputs($userIsSet, $passIsSet, $hostIsSet, $dbnameIsSet, $host, $user, $password, $dbname);
		 
		
		//Display Directive Menu 
		menu($con);
		
	}else{
		echo "Input Error: Exiting";
		exit;
	}
	
	fclose($handle);
}


 
echo "\n\n------------Finish------------\n";

/**
*	
*
*/
function menu($con){
	
	echo "Enter Commands: \n";
	$handle = fopen ("php://stdin","r");
	$line = fgets($handle);
	$line_arr = explode(" ", $line);
	fclose($handle);
	
	if( sizeof($line_arr) > 0){
		
		echo "\n";
		$command = trim($line_arr[0]);
		
		if(strcmp($command,"--file") == 0)
		{
			
		}
		elseif(strcmp($command,"--create_table") == 0)
		{
			if( sizeof($line_arr) > 1){
				
				$i = 1;
				while($i < sizeof($line_arr))
				{
					if(strcmp(trim($line_arr[$i]),"--file") == 0)
					{
						
					}					
				}
			}
			
			createTable($con);
			echo "\n";
		}			
		elseif(strcmp($command,"--dry_run") == 0)
		{
			//Request file input
			echo "Enter File: \n";	
			$handle = fopen ("php://stdin","r");
			$line = fgets($handle);
			$fname =  trim($line);
			fclose($handle);
		}			
		elseif(strcmp($command,"-u") == 0)
		{
			//Set User
			echo "\n";
		}				
		elseif(strcmp($command,"-p") == 0)
		{
			//Set Password
			echo "\n";
		}			
		elseif(strcmp($command,"-h") == 0)
		{
			//Set Host
			echo "\n";
		}			
		elseif(strcmp($command,"--help") == 0)
		{
			//Display Directives and Descriptions
			echo "\n";
		}		
		elseif(strcmp($command,"--exit") == 0 || strcmp($command,"x") == 0)
		{
			echo "Exiting \n";
			exit;
		}
		else
		{
			echo "Incorrect Input. \n";
		}
		
		//loop
		menu();
	}
	echo "input passed";	
}

function connectToDatabase($host, $user, $password, $dbname)
{
	$port=3306;
	$socket="";

	$con = new mysqli($host, $user, $password, $dbname, $port, $socket)
		or die ('Could not connect to the database server' . mysqli_connect_error());
		
	echo "connecton established \n";

	return $con;
}


/**
*/
function completeUserDatabaseInputs($userIsSet, $passIsSet, $hostIsSet, $dbnameIsSet, $host, $user, $password, $dbname){

	if(!$userIsSet)
	{
		echo "Type: 'Default' to connect with default user name: 'root'. \n";
		echo "Type: 'new' to enter new user name. \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		fclose($handle);
		
		if(trim($line) == 'new'){
			echo "Enter new value: \n";
			$handle = fopen ("php://stdin","r");
			$val = fgets($handle);
			$user = $val;
		}else{
			echo "Using default value \n";
		}
	}
	if(!$passIsSet)
	{
		echo "Type: 'Default' to connect with default password. \n";
		echo "Type: 'new' to enter new password. \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		fclose($handle);
		if(trim($line) == 'new'){
			echo "Enter new value: \n";
			$handle = fopen ("php://stdin","r");
			$val = fgets($handle);
			$password = $val;
		}else{
			echo "Using default value \n";
		}
	}
	if(!$hostIsSet)
	{
		echo "Type: 'Default' to connect with default host: 127.0.0.1  \n";
		echo "Type: 'new' to enter new host. \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		fclose($handle);
		if(trim($line) == 'new'){
			echo "Enter new value: \n";
			$handle = fopen ("php://stdin","r");
			$val = fgets($handle);
			$host = $val;
		}else{
			echo "Using default value \n";
		}
	}
	if(!$dbnameIsSet)
	{
		echo "Type: 'Default' to connect with default Database Name: 'phptest'  \n";
		echo "Type: 'new' to enter Database Name. \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		fclose($handle);
		if(trim($line) == 'new'){
			echo "Enter new value: \n";
			$handle = fopen ("php://stdin","r");
			$val = fgets($handle);
			$dbname = $val;
		}else{
			echo "Using default value \n";
		}
	}

	return connectToDatabase($host, $user, $password, $dbname);
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