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
        private $link2; // TEMPORARY PDO link for transitioning to PDO
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

        public function open_new($host,$database,$username,$password,$port = 3306) {
		//Connects to database.
		try {
			$this->link = new PDO("mysql:host=$host;dbname=$database",$username,$password,
					array(PDO::ATTR_PERSISTENT => true));
                        
			$this->host = $host;
			$this->database = $database;
			$this->username = $username;
			$this->password = $password;
                        
		}
		catch(PDOException $e) {
                    echo "couldn't create db<BR>";
			echo $e->getMessage();
		}
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

                try {
			$this->link2 = new PDO("mysql:host=$host;dbname=$database",$username,$password,
					array(PDO::ATTR_PERSISTENT => true));
                        

                        
		}
		catch(PDOException $e) {
                    echo "couldn't create db<BR>";
			echo $e->getMessage();
		}
                
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

                $result = mysqli_query($this->link, $sql);
		return $this->mysqlToArray($result);
	}

	//getLink
	//returns the mysql resource link
	public function get_link() {
		return $this->link;
	}
        
        // TEMPORARY
        // For transitioning to PDO queries
        public function get_link2() {
		return $this->link2;
	}
        
        public function get_query_result($query_string, $query_array=null) {
            $statement = $this->get_link2()->prepare($query_string, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $statement->execute($query_array);
            $result = $statement->fetchAll();
            return $result;

        }

        public function get_update_result($query_string, $query_params=null) {
            // Update queries should probably only update one record. This will ensure 
            // only one record gets updated in case of a malformed query.
            $query_string .= " LIMIT 1"; 
            $statement = $this->get_link2()->prepare($query_string, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $result = $statement->execute($query_params);
            return $result;
        }

        public function get_insert_result($query_string, $query_array=null) {

            $statement = $this->get_link2()->prepare($query_string, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $stmt = $statement->execute($query_array);
            $result =  $this->get_link2()->lastInsertId();
            return $result;
        }

	/////////////////Private Functions///////////////////

        private function mysqlToArray($mysqlResult) {
		$dataArray = array();
		$i =0;
                
                if($mysqlResult != null) {
		while($row = mysqli_fetch_array($mysqlResult,MYSQLI_ASSOC)){
			foreach($row as $key=>$data) {
				$dataArray[$i][$key] = $data;
			}
			$i++;
		}
                }
                
		return $dataArray;
	}




}

?>
