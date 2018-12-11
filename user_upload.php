<?php

/**
*	Michael Rimmer Catalyst PHP test script.
*
*/

echo "\n------------user_upload------------\n\n";

$user_uploader = new UserUpload();
$user_uploader->menu();
 
echo "\n\n------------Finish------------\n";

/**
*	
*
*/

class UserUpload
{
	//Database defaults
	private	$host="127.0.0.1"; 
	private	$user="root"; 
	private $password="Password1";
	private	$dbname="phptest";
	private	$port=3306;	
	
	/**
	* 		Execution rules: 
	*		1. Will only execute --[command] per input line.
	*		2. Will execute multiple -[command] per input line.
	*													
	*		--create_table -u [USER] -p [PASSWORD] -d [DBNAME] -pt [PORT]		Creates table to provided database destination. Requires --filename. Should update any existing table.
	*		--create_table														Creates table using default database params. Requires --filename. Should update any existing table.
	*		--file [FNAME] e.g --file users.csv									Saves filename to instance
	*		-u [USER] -p [PASSWORD] -d [DBNAME] -pt [PORT]						Sets Database parameters. Otherwise Default is set. 
	*		--help																Displays commands
	*		--dry_run															Creates Table but only prints sanitized values
	*
	*/
	function menu(){
		
		echo "**Current Database Parameters:** \n";
		echo "**HOST: $this->host,	 USER: $this->user, 	DBNAME: $this->dbname, 	PORT: $this->port ** \n\n";
		
		echo "Enter Commands: \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line_arr = explode(" ", $line);
		fclose($handle);
		
		if( sizeof($line_arr) > 0){
			
			$i = 0;
			while($i < sizeof($line_arr)){
			
				echo "\n";
				$command = trim($line_arr[$i]);
				
				//TODO: add if starts with '--' and line_arr > 1 then error with 'one command per entry'. 
				
				try {
					if(strcmp($command,"--file") == 0)
					{
						
					}
					elseif(strcmp($command,"--create_table") == 0)
					{
		
						$this->connectToDatabase();
						//createTable($con);
						echo "\n";
					}			
					elseif(strcmp($command,"--dry_run") == 0)
					{

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
						throw new InvalidArgumentException("Incorrect Input");
					}
				}
				catch (Exception $e){
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				
				$i = $i +1;
			}
			//loop
			$this->menu();
		}
		echo "input passed";	
	}

	function connectToDatabase()
	{
		$port=3306;
		$socket="";

		$con = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port, $socket)
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
}