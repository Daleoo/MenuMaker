<?php

namespace App\Model\Menu;
use App\Model\Installer;

class Install extends Installer
{
    public function install() {
        $this->createTable('menu')
             ->addColumn('menu','INT',0,array(
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY'
             ))
             ->addColumn('title','VARCHAR',255,array(
                 'NOT NULL'
             ))
             ->addColumn('subheading','VARCHAR',2500,array())
             ->save();
    }
}
