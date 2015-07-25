<?php

/**
 * Generic Collection class
 *
 * @author Lewis Dale
 */
namespace App\Model;
use App\App;

class Collection implements \Iterator, \Countable {
    private $_table;
    private $_model;
    private $checks = [
        'eq' => '=',
        'neq' => '!=',
        'lt' => '<',
        'lteq' => '<=',
        'gt' => '>',
        'gteq' => '>=',
        'not' => 'IS NOT',
        'is' => 'IS'
    ];

    private $filters = [];
    private $limit = 0;
    private $results = [];
    private $position = 0;

    /**
     * Initialises a new Collection
     */
    public function init($_table,$_model) {
        $this->_table = $_table;
        $this->_model = $_model;
        return $this;
    }

    /**
     * Adds filter to the collection
     */
    public function filter($field,$value,$check = "eq") {
        if(!array_key_exists($check,$this->checks)) {
            throw new \Exception("Check {$check} not recognised");
        }
        $this->filters[] = App::db()->escape($field)." ".$this->checks[$check]." ".App::db()->escape($value);

        return $this;
    }

    /**
     * Adds a limit to the number of results in the collection
     */
    public function limit($limit) {
        $this->limit = intval($limit);

        return $this;
    }

    /**
     * Builds the query, for execution
     */
    public function buildQuery() {
        $query = "SELECT * FROM {$this->_table}";

        if(count($this->filters)) {
            $query .= " WHERE";

            if(count($this->filters)) {
                $query .= " " . implode(" AND ", $this->filters);
            }

        }

        if($this->limit) {
            $query .= " LIMIT {$this->limit}";
        }
        return $query;
    }

    /**
     * Gets the first item in the collection
     */
    public function getFirstItem() {
        foreach($this as $item) {
            return $item;
        }
    }

    /**
     * Executes the query
     */
    public function execute() {
        App::db()->query($this->buildQuery());
        return App::db()->getResult();
    }

    /**
     * Converts the collection to json
     */
    public function toJson() {
        $items = [];
        foreach($this as $item) {
            $items[] = $item->getData();
        }

        return json_encode($items);
    }

    public function rewind() {
        $this->position = 0;
        $this->results = $this->execute();
    }

    public function current() {
        $result = $this->results[$this->position];
        $item = new $this->_model();
        $item->setData($result);
        return $item;
    }

    public function key() {
        return $this->position;
    }

    public function next() {
        $this->position++;
    }

    public function valid() {
        return isset($this->results[$this->position]);
    }

    public function count() {
        return count($this->results);
    }
}
