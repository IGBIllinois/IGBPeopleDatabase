<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class key {
    
    private $db;
    
    private $key_id;
    private $key_room;
    private $key_active;
    private $key_name;
    
    public function __construct($db, $key_id = 0) {
        $this->db = $db;
        if ($key_id != 0) {
            $this->load($key_id);
        }
    }
        
    public function __destruct() {

    }
    
    public function get_key_id() { return $this->key_id; }
    public function get_key_room() { return $this->key_room; }
    public function get_key_active() { return $this->key_active; }
    public function get_key_name() { return $this->key_name; }
    
    /** Determines if a key already exists for a room
     * 
     * @param int $key_room The room to check for
     * @return boolean True if the key exists, else false
     */
    public function key_exists($key_room) {
        $query = "SELECT key_id from key_info where key_room=:key_room LIMIT 1";
        $params = array("key_room", $key_room);
        
        $result = $this->db->get_query_result($query, $params);
        
        if(count($result) > 0) {
            return $result[0]['key_id'];
        } else {
            return false;
        }
    }
    
    
    /** Edit key data
     * 
     * @param string $key_room The room for the key
     * @param boolean $key_active True if the key is active, else false
     * @return type
     */
    public function edit_key($key_room, $key_active) {
        $key_exists = $this->key_exists($key_room);
        if($key_exists !== false &&
            $key_exists != $this->key_id) {
            // A key already exists for this room.
            return;
        }
        
        $query = "UPDATE key_info set key_room=:key_room ".
                "key_active = :key_active ".
                " WHERE ".
                " key_id = :key_id";
        $params = array("key_room"=>$key_room,
                        "key_active"=>$key_active,
                        "key_id"=>$this->get_key_id());
        $result = $this->db->get_update_result($query, $params);
        
        $this->key_room = $key_room;
        $this->key_active = $key_active;
        
    }
    
    // static functions
    
    /**
     * Add a new key to the database
     * 
     * @param db $db The Database object
     * @param string $key_room Room for the key
     * @param boolean $key_active True if the key is active, else false
     * @return int ID of the newly created key
     */
    public static function add_key($db, $key_room, $key_name, $key_active ) {
        $query = "INSERT INTO key_list (key_room, key_name, key_active) "
                . "VALUES(:key_room, :key_name, :key_active)";
        $params = array("key_room"=>$key_room, "key_name"=>$key_name, "key_active"=>$key_active);
        $result = $db->get_insert_result($query, $params);
        return $result;
    }
    
    /** Returns a list of active keys in the database
     * 
     * @param db $db Databas object
     * @return \key An array of active keys in the database
     */
    public static function get_active_keys($db) {
        $query = "SELECT key_id, key_room, key_name FROM key_list WHERE key_active = '1' ORDER BY key_room ASC";
        $search_results = $db->get_query_result($query, null);
        $keys = array();
        foreach($search_results as $key_info) {
            $id = $key_info['key_id'];
            $key = new key($db, $id);
            $keys[] = $key;
            
        }
        return $keys;
    }
    
    /** Returns a list of inactive keys in the database
     * 
     * @param db $db Databas object
     * @return \key An array of inactive keys in the database
     */
    public static function get_inactive_keys($db) {
        $query = "SELECT key_id, key_room, key_name FROM key_list WHERE key_active = '0' ORDER BY key_room ASC";
        $search_results = $db->get_query_result($query, null);
        $keys = array();
        foreach($search_results as $key_info) {
            $id = $key_info['key_id'];
            $key = new key($db, $id);
            $keys[] = $key;
            
        }
        return $keys;
    }
    
    /** Returns a list of all keys in the database
     * 
     * @param db $db Databas object
     * @return \key An array of keys in the database
     */
    public static function get_keys($db) {
        $select_key = "SELECT key_id, key_room, key_name, key_active FROM key_list ORDER BY key_active DESC, key_room ASC";
        $search_results = $db->get_query_result($select_key, null);
        $keys = array();
        foreach($search_results as $key_info) {
            $id = $key_info['key_id'];
            $key = new key($db, $id);
            $keys[] = $key;
            
        }
        return $keys;
    }
            
    // Private functions
    
    /** Load database data into this key object
     * 
     * @param int $key_id ID of the key to load data from
     */
    private function load($key_id) {
        $query = "SELECT * from key_list where key_id = :key_id LIMIT 1";
        
        $params = array("key_id"=>$key_id);
        $result = $this->db->get_query_result($query, $params);
        
        if(count($result) > 0) {
            $key = $result[0];
            $this->key_id = $key['key_id'];
            $this->key_room = $key['key_room'];
            $this->key_active = $key['key_active'];
            $this->key_name = $key['key_name'];
        } 
       
    }
}