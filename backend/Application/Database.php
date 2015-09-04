<?php

/**
 * Database Object
 *
 * @author Lewis Dale
 */

namespace App;
use App\LogFactory as Log;
class Database {
    private $db_host;
    private $db_name;
    private $db_user;
    private $db_pass;
    private $db;
    private $result;

    function __construct( $host , $name , $user , $pass ) {
        $this->db_host = $host;
        $this->db_name = $name;
        $this->db_pass = $pass;
        $this->db_user = $user;

        $this->db = new \mysqli( $host , $user , $pass , $name);
    }

    public function query( $query ) {
        Log::debug($query);
        $this->result = $this->db->query( $query );

        if(!$this->result || $this->db->errno) {
            die($this->db->error);
            throw new \Exception($this->db->error);
        }
    }

    public function fetch() {
        if($this->result) {
            return $this->result->fetch_assoc();
        }
    }

    public function getResult() {
        $results = array();
        if($this->result) {
            while($row = $this->result->fetch_assoc()) {
                $results[] = $row;
            }
        }

        return $results;
    }

    public function escape( $string ) {
        return $this->db->real_escape_string( $string );
    }

    /*Use to hash passwords*/
    public function hash( $string ) {
        return hash('sha512',$string);
    }

    public function insert_id() {
        return $this->db->insert_id;
    }
}
