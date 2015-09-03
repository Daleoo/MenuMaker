<?php

namespace App\Model\Item;
use App\Model\Installer;

class Install extends Installer
{
    public function install() {
        $this->createTable('item')
             ->addColumn('item','INT',0,array(
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY'
             ))
             ->addColumn('title','VARCHAR',255,array(
                 'NOT NULL'
             ))
             ->addColumn('eatinprice','FLOAT',0,array(
                 'NOT NULL'
             ))
             ->addColumn('takeoutprice','FLOAT',0,array())
             ->addColumn('description','VARCHAR',2500,array())
             ->addColumn('menu','INT',0,array(
                'NOT NULL',
             ))
             ->addColumn('parent','INT',0,array(
                 'DEFAULT 0'
             ))
             ->addColumn('takeout','INT',1,array(
                 'DEFAULT 0'
             ))
             ->addColumn('eatin','INT',1,array(
                 'DEFAULT 0',
             ))
             ->save();
    }
}
