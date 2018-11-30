<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class department {
    
    private $db;
    
    private $dept_id;
    private $dept_code;
    private $name;

    
    
    public function __construct($db, $dept_id = 0) {
        $this->db = $db;
        if ($theme_id != 0) {
            $this->load($dept_id);
        }

	}
        
        
	public function __destruct() {

	}
        
        /** Edit a department
         * 
         * @param string $dept_code New department code
         * @param string $name New department name
         */
        public function edit_dept($name,$dept_code) {
            $query = "UPDATE department set"
                    . " dept_code=:dept_code, "
                    . " name=:name where "
                    . " dept_id = :dept_id";
            
            $params = array("dept_code"=>$dept_code,
                            "name"=>$name,
                            "dept_id"=>$dept_id);
            
            $this->db->get_update_result($query, $params);
            
            $this->dept_code = $dept_code;
            $this->name = $name;
        }

        /** Determines if a department already exists with the given name
         * 
         * @param string $name Name of the department to check for
         * @return boolean ID of the department if it exists, else false
         */
        public function dept_name_exists($name) {
            $query = "SELECT dept_id from department where ".
                    "name = :name";
            $params = array("name"=>$name);
            
            $result = $this->db->get_query_result($query, $params);
            
            if(count($result) > 0) {
                return $result[0]['dept_id'];
            } else {
                return false;
            }
        }
        
        /** Determines if a department already exists with the given code
         * 
         * @param string $code Code to check for
         * @return boolean ID of the department if it exists, else false
         */
        public function dept_code_exists($code) {
            $query = "SELECT dept_id from department where ".
                    "dept_code = :code";
            $params = array("code"=>$code);
            
            $result = $this->db->get_query_result($query, $params);
            
            if(count($result) > 0) {
                return $result[0]['dept_id'];
            } else {
                return false;
            }
        }
        
        // static functions
        
        /** Add a new department
         * 
         * @param db $db Database object
         * @param string $dept_code Department code
         * @param string $name Department name
         * @return int ID of newly created department
         */
        public static function add_dept($db, $name, $dept_code) {
            $query = "INSERT INTO department "
                    . "(dept_code, name) "
                    . " VALUES "
                    . "(:dept_code, :name)";
            $params = array("dept_code"=>$dept_code, "name"=>$name);
            
            $result = $db->get_insert_result($query, $params);
            
            return $result;
                
        }
        
        
        // Private functions
        private function load($dept_id) {
            
            $query = "SELECT * from department where dept_id=:dept_id LIMIT 1";
            $params = array("dept_id"=>$dept_id);
            
            $result = $this->db->get_query_result($query, $params);
            if(count($result) > 0) {
                $dept = $result[0];
                $this->dept_id = $dept_id;
                $this->dept_code = $dept['dept_code'];
                $this->name = $dept['name'];
            }
        }
           
}