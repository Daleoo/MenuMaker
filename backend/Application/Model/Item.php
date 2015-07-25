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

    /**
     * Load children of the item
     */
    public function loadChildren() {
        $this->set('children',json_decode(
            $this->getCollection()
                ->filter('parent',$this->getId())
                ->toJson()
        ));

        return $this;
    }
}
?>
