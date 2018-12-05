<?php

/* 
 * Details who owns which keys, their condition and status, etc.
 */

class key_info {
    
    private $db;
    
    private $key_info_id;
    private $key_id;
    private $user_id;
    private $date_issued;
    private $date_returned;
    private $return_condition;
    private $paid;
    private $payment_returned;
    private $key_active;
    
    public function get_key_info_id() { return $this->key_info_id; }
    public function get_key_id() { return $this->key_id; }
    public function get_user_id() { return $this->user_id; }
    public function get_date_issued() { return $this->date_issued; }
    public function get_date_returned() { return $this->date_returned; }
    public function get_paid() { return $this->paid; }
    public function get_payment_returned() { return $this->payment_returned; }
    public function get_return_condition() { return $this->return_condition; }
    
    public function __construct($db, $key_id = 0) {

        $this->db = $db;
        if ($key_id != 0) {
            $this->load($key_id);
        }
    }
        
    public function __destruct() {

    }    
    

    /** Updates a key to be returned
     * 
     * @param type $deposit True if deposit has been returned, else false
     * @param type $date_returned Date the key was returned
     * @param type $condition Condition of the key 
     * (Returned, Lost, Broken, Cancelled)
     */
    public function return_key(
            $deposit,
            $date_returned,
            $condition) {
        
        $query = "UPDATE key_info set payment_returned=:deposit, ".
                " date_returned=:date_returned,".
                " return_condition=:condition,".
                " key_active = false ".
                " where ".
                "keyinfo_id = :keyinfo_id";
        $params = array("deposit"=>$deposit,
                    "date_returned"=>$date_returned,
                    "condition"=>$condition,
                    "keyinfo_id"=>$this->get_key_info_id());

        $this->db->get_update_result($query, $params);

    }
    
    /** Gets the key object for this key_info
     *
     * @return key the Key object for this key_info data
     */
    public function get_key() {
        return new key($this->db, $this->get_key_id());
    }
    
    
    
    // Static functions
    
    /**
    * Returns result of keys in posession of given user
    * 
    * @param db $db Database object
    * @param int $user_id ID of the user to get keys for
    * @param boolean $active True if getting active keys, else false
    * @return type array of key_info objects
    */
    public static function get_keys($db, $user_id, $active) { 

        $key_query = "SELECT keyinfo_id, key_room, key_name, date_issued, date_returned, return_condition,
                        paid, payment_returned
                        FROM key_info LEFT JOIN key_list
                        ON key_info.key_id = key_list.key_id 
                        WHERE user_id = :user_id 
                        AND key_info.key_active = :active
                        ORDER BY date_issued";
        $params = array("user_id"=>$user_id, "active"=>$active);
        $result = $db->get_query_result($key_query, $params);	

        $keyinfos = array();
        foreach($result as $keyinfodata) {
            $keyinfos[] = new key_info($db, $keyinfodata['keyinfo_id']);
           
        }
        return $keyinfos;

    }
    
    /** Determines if a user has a specfic key
     * 
     * @param db $db Database object
     * @param type $user_id User ID to check for    
     * @param type $key_id Key ID to check for
     * @return type ID of the key_info data if the user has
     * been issued this key, else false
     */
    public static function key_exists($db, $user_id, $key_id) { 
            $key_query = "SELECT keyinfo_id
                                            FROM key_info 
                                        WHERE user_id = :user_id 
                                        AND key_active = '1' 
                                        AND key_id = :key_id";
            $params = array("user_id"=>$user_id, "key_id"=>$key_id);
            $result = $db->get_query_result($key_query, $params);

            if(count($result) > 0) {
                return $result[0]['keyinfo_id'];
            } else {
                return false;
            }
    }
    
    
    /** Adds a new key_info data to the database
     * 
     * @param db $db The Database object
     * @param int $key_id ID of the key to issue    
     * @param int $user_id ID of the user given the key
     * @param string $date_issued Date the key was issued
     * @param boolean $paid True if the user has paid for the key, else false
     * @return int The ID of the newly created key_info
     */
    public static function add_key_info(
            $db,
            $key_id,
            $user_id,
            $date_issued,
            $paid) {
        
        $query = "INSERT INTO key_info ("
                . "key_id, user_id, date_issued, paid, key_active) VALUES ("
                . ":key_id, :user_id, :date_issued, :paid, 1)";
        $params = array("key_id"=>$key_id, 
            "user_id"=>$user_id, 
            "date_issued"=>$date_issued, 
            "paid"=>$paid);
        
        $result = $db->get_insert_result($query, $params);
        return $result;

    }
            
    // Private functions
    
    
    /** Loads database data into this key_info object
     * 
     * @param type $key_info_id ID of the key_info data to load
     */
    private function load($key_info_id) {
        $query = "SELECT * from key_info where keyinfo_id = :keyinfo_id LIMIT 1";
        $params = array("keyinfo_id"=>$key_info_id);

        $result = $this->db->get_query_result($query, $params);
        
        if(count($result)> 0) {
            $keyinfo = $result[0];
            $this->key_info_id = $keyinfo['keyinfo_id'];
            $this->user_id = $keyinfo['user_id'];
            $this->key_id = $keyinfo['key_id'];
            $this->date_issued = $keyinfo['date_issued'];
            $this->date_returned = $keyinfo['date_returned'];
            $this->paid = $keyinfo['paid'];
            $this->payment_returned = $keyinfo['payment_returned'];
            $this->key_active = $keyinfo['key_active'];
            $this->return_condition = $keyinfo['return_condition'];

        }
    }
    
    
    
}