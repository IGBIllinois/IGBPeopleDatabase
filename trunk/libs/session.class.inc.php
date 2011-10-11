<?php

include_once 'ldap.class.inc.php';
include_once 'user.class.inc.php';

class session {

        ////////////////Private Variables//////////
        private $db; //mysql database object
        private $user; //user object
        private $ldap;
        private $password;
        private $username;
        
        
        ////////////////Public Functions///////////

        public function __construct($db,$ldap,$username,$password) {
                $this->db = $db;
                $this->ldap = $ldap;
                $this->password = $password;
                $this->username = $username;
                //$this->user = new user($this->db,0,$username);
                
        }
        public function __destruct() {}
        
       // public function get_user() { return $this->user; }


        public function authenticate($ldap_people_ou) {
                $this->ldap->set_ldap_people_ou($ldap_people_ou);
                if (($this->ldap->bind($this->user->get_user_name(),$this->get_password())) && ($this->valid_user())) {
                        return true;
                }
                else { return false; }
                return true;
        }

        public function is_admin() {
                
                if ($this->user->get_group() == "Administrators") { return true; }      
                else { return false; }

        }
        
        public function is_user() {
                if ($this->user->get_group() == "Users") { return true; }
                else { return false; }
                
        }
        
        public function is_business() {
                if ($this->user->get_group() == "Business") { return true; }
                else { return false; }
        }       

        public function is_supervisor() {
                return $this->user->is_supervisor();
        
        }

        //////////////Private Functions/////////////

        private function get_password() { return $this->password; }

        private function valid_user() {
                if ($this->user->get_user_id()) { return true; }
                else { return false; }
        }       

}

?>