<?php

namespace App\Model;
use App\Model\Model;

class User extends Model {
    public function __construct() {
        $this->_table = "user";
        $this->_primaryKey = "user";

        parent::__construct();
    }
}
