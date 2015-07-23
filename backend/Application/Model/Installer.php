<?php

/**
 * Installer abstract class
 */

namespace App\Model;

use \App\App;

class Installer {
    protected $table = [];

    public function createTable($tableName) {
        $this->table = [];

        $this->table['name'] = $tableName;
        $this->table['columns'] = [];

        return $this;
    }

    public function addColumn($name, $type, $length, array $constraints) {
        $this->table['columns'][] = [
                'name' => $name,
                'type' => $type,
                'length' => $length,
                'constraints' => $constraints,
        ];
        return $this;
    }

    public function save() {
        if($this->table['name'] && count($this->table['columns'])) {
            $db = App::db();
            $query = "CREATE TABLE {$db->escape($this->table['name'])} (";

            $constraints = [];
            foreach($this->table['columns'] as $column) {
                $constString = "{$db->escape($column['name'])} {$column['type']}";

                if($column['length']) {
                    $constString .= "({$db->escape($column['length'])})";
                }
                foreach($column['constraints'] as $constraint) {
                    $constraint = strtoupper($constraint);
                    $constString .= " {$db->escape($constraint)}";
                }

                $constraints[] = $constString;
            }

            $query .= implode(",",$constraints);

            $query .= ")";

            try {
                $db->query($query);
            } catch(\Exception $e) {
                echo $e->getMessage();
            }
        }
    }
}
