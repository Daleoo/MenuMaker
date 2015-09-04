<?php

namespace App\Model;
use App\Model\Model;

class Menu extends Model {
    public function __construct() {
        $this->_table = 'menu';
        $this->_primaryKey = 'menu';

        parent::__construct();
    }
}
?>
