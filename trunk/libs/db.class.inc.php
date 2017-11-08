<?php
//////////////////////////////////////////
//
//	db.class.inc.php
//
//	Class to create easy to use
//	interface with the database
//
//	By David Slater
//	June 2009
//
//////////////////////////////////////////


class db {

	////////////////Private Variables//////////

	private $link; //mysql database link
	private $host;	//hostname for the database
	private $database; //database name
	private $username; //username to connect to the database
	private $password; //password of the username

	////////////////Public Functions///////////

	public function __construct($host,$database,$username,$password) {
		$this->open($host,$database,$username,$password);


	}
	public function __destruct() {
		$this->close();

	}

	//open()
	//$host - hostname
	//$database - name of the database
	//$username - username to connect to the database with
	//$password - password of the username
	//opens a connect to the database
	public function open($host,$database,$username,$password) {
		//Connects to database.
		//$this->link = mysql_connect($host,$username,$password);
                $this->link = mysqli_connect($host,$username,$password) or die("No link");
                if (mysqli_connect_errno()) {
                //printf("Connect failed: %s\n", mysqli_connect_error());
                exit();
                }
		//@mysql_select_db($database,$this->link) or die("Unable to select database " . $database . mysql_error());
		@mysqli_select_db($this->link, $database) or die("Unable to select database " . $database . mysqli_error($this->link));
                $this->host = $host;
		$this->database = $database;
		$this->username = $username;
		$this->password = $password;

	}

	//close()
	//closes database connection
	public function close() {
		//mysql_close($this->link);
                mysqli_close($this->link);
	}

	//insert_query()
	//$sql - sql string to run on the database
	//returns the id number of the new record, 0 if it fails
	public function insert_query($sql) {
		//if (mysql_query($sql,$this->link)) {
		//	return mysql_insert_id($this->link);
                //        
		//}
                if (mysqli_query($this->link, $sql)) {
			return mysqli_insert_id($this->link);
		}
		else {
			return 0;
		}

	}

	//non_select_query()
	//$sql - sql string to run on the database
	//For update and delete queries
	//returns true on success, false otherwise
	public function non_select_query($sql) {
		//return mysql_query($sql,$this->link);
            return mysqli_query($this->link, $sql);
	}

	//query()
	//$sql - sql string to run on the database
	//Used for SELECT queries
	//returns an associative array of the select query results.
	public function query($sql) {
            //echo("<BR>query = $sql <BR>");
		//$result = mysql_query($sql,$this->link);
                $result = mysqli_query($this->link, $sql);
		return $this->mysqlToArray($result);
	}

	//getLink
	//returns the mysql resource link
	public function get_link() {
		return $this->link;
	}

	/////////////////Private Functions///////////////////
/*
	//mysqlToArray
	//$mysqlResult - a mysql result
	//returns an associative array of the mysql result.
	private function mysqlToArray($mysqlResult) {
		$dataArray = array();
		$i =0;
		while($row = mysql_fetch_array($mysqlResult,MYSQL_ASSOC)){
			foreach($row as $key=>$data) {
				$dataArray[$i][$key] = $data;
			}
			$i++;
		}
		return $dataArray;
	}
        */
        private function mysqlToArray($mysqlResult) {
		$dataArray = array();
		$i =0;
                //print_r($mysqlResult);
                
                if($mysqlResult != null) {
		while($row = mysqli_fetch_array($mysqlResult,MYSQLI_ASSOC)){
			foreach($row as $key=>$data) {
                            //echo("key, data = ($key, $data)<BR>");
				$dataArray[$i][$key] = $data;
			}
			$i++;
		}
                }
                
		return $dataArray;
	}




}

?>
