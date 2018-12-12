<?php

/**
*	Michael Rimmer Catalyst PHP test script.
*
*/

echo "\n------------user_upload------------\n\n";

$user_uploader = new UserUpload();
$user_uploader->init();
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
	
	private	$fname = "";	
	private $fnameIsSet = false;
	
	/**
	*
	*/
	function init(){
			define("NAME", 0);
			define("SURNAME", 1);
			define("EMAIL", 2);
	}
	
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
		
		echo "\n**Current Database Parameters:** \n";
		echo "**HOST: $this->host,	 USER: $this->user, 	DBNAME: $this->dbname, 	PORT: $this->port ** \n\n";
		if($this->fnameIsSet)
		{
			echo "**File Path: $this->fname ** \n\n";
		}
		else
		{
			echo "**File Path: Unset ** \n\n";
		}
		
		echo "Enter Commands: \n";
		$handle = fopen ("php://stdin","r");
		$line = fgets($handle);
		$line_arr = explode(" ", $line);
		fclose($handle);
		
		if( sizeof($line_arr) > 0){
			
			$i = 0;
			$creatingTable = false;
			$creatingTableSQL = false;
			
			while($i < sizeof($line_arr)){
			
				echo "\n";
				$command = trim($line_arr[$i]);
				
				//TODO: add if starts with '--' and line_arr > 1 then error with 'one command per entry'. 
				
				try {
					if(strcmp($command,"--file") == 0)
					{
						if( sizeof($line_arr) > 1)
						{
							$val =  trim($line_arr[$i+1]);
							$val = strtolower($val);
							
							if (file_exists($val))
							{
								echo "File found. File path set.\n";
								$this->fname = $val;	
								$this->fnameIsSet = true;
								$i = $i +1;	
							} 
							else 
							{
								$i = $i +1;	
								throw new InvalidArgumentException("Incorrect file path input");
							}
						}
						else{
							throw new InvalidArgumentException("No file path provided.");
						}
					}
					elseif(strcmp($command,"--create_table") == 0)
					{
						//defer create table until line is fully read. 
						$creatingTable = true; 
						$creatingTableSQL = true; 
					}			
					elseif(strcmp($command,"--dry_run") == 0)
					{
						//defer create table until line is fully read. 
						$creatingTable = true; 
						$creatingTableSQL = false; 
					}			
					elseif(strcmp($command,"-u") == 0)
					{
						//Set User
						if ($this->readValue($command,$line_arr, $i))
						{
							$i = $i +1;
						}
						else
						{
							$i = $i +1;
							throw new InvalidArgumentException("Incorrect User Input");		
						}
					}				
					elseif(strcmp($command,"-p") == 0)
					{
						//Set Password
						if ($this->readValue($command,$line_arr, $i))
						{
							$i = $i +1;
						}
						else
						{
							$i = $i +1;
							throw new InvalidArgumentException("Incorrect Password Input");	
						}
					}			
					elseif(strcmp($command,"-h") == 0)
					{
						//Set Host
						if ($this->readValue($command,$line_arr, $i))
						{
							$i = $i +1;
						}
						else
						{
							$i = $i +1;
							throw new InvalidArgumentException("Incorrect Host Input");
						}	
					}					
					elseif(strcmp($command,"-d") == 0)
					{
						//Set DBNAME
						if ($this->readValue($command,$line_arr, $i))
						{
							$i = $i +1;
						}
						else
						{
							$i = $i +1;
							throw new InvalidArgumentException("Incorrect DBname Input");
						}	
					}					
					elseif(strcmp($command,"-pt") == 0)
					{
						//Set PORT
						if ($this->readValue($command,$line_arr, $i))
						{
							$i = $i +1;
						}
						else
						{
							$i = $i +1;
							throw new InvalidArgumentException("Incorrect port Input");
						}
					}			
					elseif(strcmp($command,"--help") == 0)
					{
						//Display Directives and Descriptions
						$this->displayHelp();
						echo "\n";
					}						
					elseif(strcmp($command,"--exit") == 0 || strcmp($command,"x") == 0)
					{
						echo "Exiting \n";
						exit;
					}
					else
					{
						throw new InvalidArgumentException("Incorrect Command Input");
					}
				}
				catch (Exception $e){
					echo 'Caught exception: ',  $e->getMessage(), "\n";
				}
				$i = $i +1;
			}
			
			if($creatingTable){
				if(!$this->fnameIsSet){
					echo "File name needs to be set first. Set it with '--file' command. \n";
				}else{
					$this->insertFromFile($creatingTableSQL);
				}
				echo "\n";
			}
			
			//loop menu
			$this->menu();
		}	
	}

	/**
	*	Looks ahead in the line then reads the input value for single dash '-' inputs. If correct db param is set. 
	*	Input line index is incremented after read. 
	*	@returns boolean on success/fail.
	*/
	function readValue($command,$line_arr, $ind){
			
		if( sizeof($line_arr) > 1)
		{
			$val =  trim($line_arr[$ind+1]);
			$val = strtolower($val);
			
			switch ($command) {
				case '-u':
					if(preg_match('/^[a-z0-9-]+$/', $val)) 
					{
						$this->user = $val;	
						echo "New User: $val \n";
						return true;
					} 
					break;
				case '-p':
					$this->password = $val;	
					echo "New PASSWORD entered \n";
					return true;
					break;					
				case '-h':
					if(filter_var($val, FILTER_VALIDATE_IP)) 
					{
						$this->host = $val;	
						echo "New Host: $val \n";
						return true;
					}
					break;				
				case '-d':
					if(preg_match('/([A-Za-z0-9])+/',$val))
					{
						$this->dbname = $val;	
						echo "New DBNAME: $val \n";
						return true;
					}
					break;	
				case '-pt':					
					if(is_numeric($val))
					{
						$this->port = $val;	
						echo "New PORT: $val \n";
						return true;
					}
					break;
				default:
					echo "Incorrect command input";
			}	
		}	
		return false;
	}
	
	/**
	*
	*
	*/
	function displayHelp(){
		echo "*****Script Command Directives***** \n\n";
		echo "Command:		Description: \n";
		echo "--create_table		Creates table using default database params. Requires --file. Should update any existing table.\n";
		echo "--dry_run		Creates Table printing sanitized values with no table insertion.\n";
		echo "--file [FNAME]		Set the file path for the input file.\n";
		echo "-u [USER]		Sets respective database  parameters. Otherwise the default parameter value is set.\n";
		echo "-p [PASSWORD]		Sets respective database  parameters. Otherwise the default parameter value is set.\n";
		echo "-d [DBNAME]		Sets respective database  parameters. Otherwise the default parameter value is set.\n";
		echo "-h [HOST]		Sets respective database  parameters. Otherwise the default parameter value is set.\n";
		echo "-pt [PORT]		Sets respective database  parameters. Otherwise the default parameter value is set.\n";
		echo "--help			Displays commands.\n";
		echo "--exit OR x		Closes script.\n";
		
	}

	/**
	*
	*/
	function connectToDatabase()
	{
		$port=3306;
		$socket="";

		$con = new mysqli($this->host, $this->user, $this->password, $this->dbname, $this->port, $socket)
			or die ('Could not connect to the database server' . mysqli_connect_error());
			
		echo "connecton established \n";

		return $con;
	}
	
	function tableExists($con, $table){
		if ($result = $con->query("SHOW TABLES LIKE '".$table."'")) {
			if($result->num_rows == 1) {
				return true;
			}
		}
		else {
			return false;
		}	
	}
	
	function entryExists($con, $email){
		if ($result = $con->query("SELECT * FROM users WHERE email='".$email."'")) {
			if($result->num_rows > 0) {
				return true;
			}
		}
		else {
			return false;
		}
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
	
	function insertFromFile($updateSQLTable){
	
		$row = 1;
		$con = NULL;
		
		if (($handle = fopen($this->fname, "r")) !== FALSE) 
		{
			
			$col_num = 0; 
			$columns = "";
			$email_arr = array();
			
			//if updateSQLTable connect to db
			if($updateSQLTable)
			{
				$con = $this->connectToDatabase();
				if($this->tableExists($con, 'users'))
				{
					echo "Database has table.\n";
				}
				else
				{
					$this->createTable($con);
				}
			}
			
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE)
			{
				
				if($row == 1){
					//reads column names
					$col_num = count($data);
					$columns = trim(implode(",", $data));
					//echo $columns."\n";	
				}
				else
				{
					$value_num = count($data);
					$values_arr = array();
					$valid_line = false;
					$entry_email = "";
					
					if($value_num == $col_num){
						for ($i=0; $i < $value_num; $i++) 
						{
							$str = trim($data[$i]);
							$str = strtolower($str);
											
							switch ($i) {
								case NAME:
									$str = preg_replace("/[^A-Za-z0-9\-']/", "", $str);
									$str = str_replace("'", "''", $str);
									$str = "'".ucfirst($str)."'";
									array_push($values_arr,$str);
									break;
								case SURNAME:
									$str = preg_replace("/[^A-Za-z0-9\-']/", "", $str);
									$str = str_replace("'", "''", $str);
									$str = "'".ucfirst($str)."'";
									array_push($values_arr,$str);
									break;
								case EMAIL:
									if (!filter_var($str, FILTER_VALIDATE_EMAIL) === false) 
									{
										$valid_line = true;
										
										//duplicate check
										if(!in_array($str, $email_arr))
										{
											array_push($email_arr,$str);
											$entry_email = $str;
										}
										else
										{
											echo("\n\nDetected: Duplicate email address - $str. Skipping insertion. \n");
											$valid_line = false;
										}
										$str = str_replace("'", "''", $str);
										$str = "'".$str."'";
										array_push($values_arr,$str);
									}
									else 
									{
										$valid_line = false;
										echo("\n\nDetected:  $str is not a valid email address. Skipping insertion. \n");
									}
									break;
								default:
									//should'nt occur
									$valid_line = false;
									echo "";
							} 
							
							//assign to values str
							$values = trim(implode(",", $values_arr));
						}
						
						if($valid_line)
						{
							if($updateSQLTable){
								
								$sql = "";
								//check entryExists if exist use update SQL 
								if($this->entryExists($con, $entry_email)){
									$sql = "REPLACE INTO users ($columns) VALUES ($values);";
								}else{
									$sql = "INSERT INTO users ($columns) VALUES ($values); ";
								}
								echo "\n$sql \n";
								if (mysqli_query($con, $sql)) {
									echo "Table Users Insertion Success";
								} else {
									echo "Insertion Error: " . mysqli_error($con);
								}	
							}else{
				
								$sql = "INSERT INTO users ($columns) VALUES ($values);";
								echo "\n$sql \n";
							
							}							
						} 
					}
					else
					{
						echo "File Format Error. Incorrect number of values for this row.";
						exit;
					}
				}
				
				$row++;	
			}
			if($updateSQLTable){mysqli_close($con);}
			fclose($handle);
		}
		echo "\n";
	}
	
	
}