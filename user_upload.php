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
	
	private	$fname = "";	
	private $fnameIsSet = false;
	
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
						if( sizeof($line_arr) > 1)
						{
							$val =  trim($line_arr[$i+1]);
							$val = strtolower($val);
							
							if(preg_match('/^[a-z0-9-.]+$/', $val)) 
							{
								$this->fname = $val;	
								$this->fnameIsSet = true;
								$i = $i +1;	
							} 
							else 
							{
								throw new InvalidArgumentException("Incorrect File Input");
							}
						}
						else{
							throw new InvalidArgumentException("Incorrect File Input");
						}
						
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
			//loop menu
			$this->menu();
		}
		echo "input passed";	
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
					if(preg_match('/([A-Za-z0-9])+/',$val)) 
					{
						$this->password = $val;	
						echo "New PASSWORD entered \n";
						return true;
					} 
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
	
	function insertFromFile($con){
	
		$row = 1;
		if (($handle = fopen("users.csv", "r")) !== FALSE) 
		{
			
			$col_num = 0; 
			$columns = "";
			$email_arr = array();
			
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
					
					if($value_num == $col_num){
						for ($i=0; $i < $value_num; $i++) 
						{
							$str = trim($data[$i]);
							$str = strtolower($str);
											
							switch ($i) {
								case 0:
									$str = preg_replace("/[^A-Za-z0-9\-']/", "", $str);
									$str = str_replace("'", "''", $str);
									$str = "'".ucfirst($str)."'";
									array_push($values_arr,$str);
									break;
								case 1:
									$str = preg_replace("/[^A-Za-z0-9\-']/", "", $str);
									$str = str_replace("'", "''", $str);
									$str = "'".ucfirst($str)."'";
									array_push($values_arr,$str);
									break;
								case 2:
									if (!filter_var($str, FILTER_VALIDATE_EMAIL) === false) 
									{
										$valid_line = true;
										
										//duplicate check
										if(!in_array($str, $email_arr))
										{
											array_push($email_arr,$str);
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
							echo "\n";
							$sql = "INSERT INTO users ($columns) VALUES ($values); ";
							echo $sql;
							echo "\n";
						
							if (mysqli_query($con, $sql)) {
								echo "Table Users Insertion Success";
							} else {
								echo "Insertion Error: " . mysqli_error($con);
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
			mysqli_close($con);
			fclose($handle);
		}
		echo "\n";
	}
	
	
}