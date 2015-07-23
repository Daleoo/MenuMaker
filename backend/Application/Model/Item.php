<?php

namespace App\Model;

use App\Model\Model;
use App\Model\Collection;

class Item extends Model {
    public function __construct() {
        $this->_table = 'item';
        $this->_primaryKey = 'item';
        parent::__construct();
    }
}
?>
