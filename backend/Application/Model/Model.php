<?php

/**
 * Abstract Class for Model Object
 *
 * @author Lewis Dale
 */

namespace App\Model;

use App\App;
abstract class Model
{
    protected $_table;
    protected $_primaryKey;
    protected $_data = [];
    protected $_columns = [];

    public function __construct() {

        if(!$this->tableExists()) {
            //Do some magic to get the SQL and generate the table
            $class = "\\".get_class($this);
            $class .= "\\Install";

            $installer = new $class();
            $installer->install();
        }

        $this->columnCache();

    }

    /**
     * Access or modify a data item
     */
    public function __data($property, $value = null) {
        if($value) {
            $this->_data[$property] = $value;
        }
        if(isset($this->_data[$property])) {
            return $this->_data[$property];
        }
    }

    /**
     * Get the model ID
     */
    public function getId() {
        return $this->__data($this->_primaryKey);
    }

    /**
     * Set the model ID
     */
    public function setId($id) {
        $this->__data($this->_primaryKey,$id);
        return $this;
    }

    /**
     * Get all of the model data
     */
    public function getData() {
        return $this->_data;
    }

    /**
     * Set a data item
     */
    public function set($key,$value) {
        $this->_data[$key] = $value;
        return $this;
    }

    /**
     * Get a data item
     */
    public function get($key) {
        return isset($this->_data[$key]) ? $this->_data[$key] : false;
    }

    /**
     * Set the data Object
     */
    public function setData($data) {
        $this->_data = $data;

        return $this;

    }

    /**
     * load a model from the database
     */
    public function load($id) {
        $item = $this->getCollection()
                    ->filter($this->_primaryKey, $id)
                    ->limit(1)
                    ->getFirstItem();
        $this->setData($item->getData());

        return $this;
    }

    /**
     * Save the current model to the database
     * Updates the row if it already exists
     * Inserts the row if it does not
     */
    public function save() {
        if(!$this->getId()) {
            //Insert
            $this->insert();
        } else {
            //Update
        }

        return $this;
    }

    public function getCollection() {
        $collection = new \App\Model\Collection();
        return $collection->init($this->_table,get_class($this));
    }

    public function select(array $fields) {
        $collection = $this->getCollection();
        foreach($fields as $field => $value) {
            $collection->filter($field,$value);
        }

        return $collection->limit(1)->execute()[0];
    }

    protected function tableExists() {

        try {
            $this->getCollection()->limit(1)->execute();
        } catch(\Exception $e) {
            return false;
        }

        return true;
    }

    protected function columnCache() {
        $file = str_replace('App\\Model\\','',get_class($this));
        $file = str_replace('\\','_',$file);
        $file = __DIR__.DS.'cache'.DS.$file;

        $modtime = @filemtime($file);
        $cacheTime = 900;
        $refresh = $modtime ? (time() - $modtime) > $cacheTime : true;
        if($refresh) {
            //Get columns from database
            //Put them into cache folder
            $query = "SHOW COLUMNS FROM {$this->_table}";
            App::db()->query($query);
            $results = App::db()->getResult();
            $columns = [];
            foreach($results as $column) {
                $columns[] = $column['Field'];
            }
            file_put_contents($file,json_encode($columns));
        }

        $this->_columns = json_decode(file_get_contents($file));
    }

    public function delete(array $fields) {

    }

    public function insert() {
        $data = [];
        $db = App::db();

        foreach($this->_columns as $column) {
            if($this->get($column)) {

                $data[$db->escape($column)] = "'".$this->get($db->escape($column))."'";

            }
        }

        $fields = implode(',',array_keys($data));
        $values = implode(',',array_values($data));
        $query = "INSERT INTO {$this->_table} ({$fields}) VALUES ({$values})";

        $db->query($query);
    }

    public function update() {

    }
    public function query($string) {

    }

    //TODO: Select, Delete, Insert functions
    //TODO: Generic query function
}
