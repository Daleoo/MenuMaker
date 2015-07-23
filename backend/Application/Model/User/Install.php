<?php

namespace App\Model\User;
use App\Model\Installer;

class Install extends Installer
{
    public function install() {
        $this->createTable('user')
             ->addColumn('user','INT',0,array(
                'NOT NULL',
                'AUTO_INCREMENT',
                'PRIMARY KEY'
             ))
             ->addColumn('firstname','VARCHAR',255,array(
                 'NOT NULL'
             ))
             ->addColumn('lastname','VARCHAR',255,array(
                 'NOT NULL'
             ))
             ->addColumn('email','VARCHAR',255,array(
                 'NOT NULL'
             ))
             ->addColumn('password','VARCHAR',512,array(
                 'NOT NULL'
             ))
             ->save();
    }
}
