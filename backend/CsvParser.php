<?php

/*
 * CSV Parsing class, to help the parsing of CSV files.
 *
 * @author Lewis Dale lewis@lewisdale.co.uk
 */

class CsvParser implements Iterator, Countable {
	private $_file;
	private $_keys;
	private $_lines;
    private $_filters = [
        'match' => [],
        'not' => [],
    ];
    private $_results = [];
    private $position = 0;

    public function __construct() {}

	/**
	 * Read a csv file into the object, applying keys to the lines if necessary
	 *
	 * @param $file The filename to read
	 * @param $keys Boolean value denoting if the first line of the file contains the array keys
	 * @param $delim Field delimeter of the CSV
	 */
	public function read($file,$keys=true,$delim=',') {
		$this->_file = $file;
		$fp = fopen($file,"r");

		if(!$fp) {
			throw new Exception("File Not Found");
		}
		if($keys) {
			$this->_keys = array_values(fgetcsv($fp));
		}

		$this->_lines=array();

		while($line=fgetcsv($fp)) {
			$new=array();

			//Prevent reading malformed lines by assuming that the first line of data is correct
			if(count($this->_lines) && count($line) < count($this->_lines[0])) {
				continue;
			}
			for($i=0;$i<count($line); $i++) {
				$key = $i;
				if($this->_keys && isset($this->_keys[$i])) {
					$key = $this->_keys[$i];
				}
				$new[$key] = $line[$i];
			}
			$this->_lines[] = $new;
		}

		fclose($fp);

		return $this;
	}

	/**
	 * Apply a function to each line in the current results list
	 *
	 * Function must:
	 * 	 -take a single argument; A key-value array which represents a single line in the CSV
	 *	 -return the modified line
	 *
	 * @param $func The function name
	 */
	public function apply($func) {

		foreach($this as $line) {
			$index = array_search($line,$this->_lines);
			$this->_lines[$index] = $func($line);

		}
		return $this;
	}

	/**
	 * Write the new CSV to a file
	 *
	 * @param $filename The new filename
	 */
	public function write($filename, $use_keys = true) {
		$fp = fopen($filename,"w");

		if(!$fp) {
			throw new Exception("File {$filename} could not be opened");
		}
		if($this->_keys) {
			if($use_keys) {
				fputcsv($fp,array_values($this->_keys));
			} else {
				fputcsv($fp,array_keys($this->_lines[0]));
			}
		}

		foreach($this as $line) {
			fputcsv($fp,array_values($line));
		}

		fclose($fp);

		return $this;
	}

	/**
	 * Return the CSV data
	 *
	 * @param $match An array of keys and values to match
	 * @return The CSV parser
	 */
	public function match(array $match = []) {
        foreach($match as $key => $value) {
            $this->_filters['match'][] = [$key => $value];
        }

        return $this;
    }

    /**
     * Filter CSV data where keys do not equal values given
     *
     * @param An array of key-value pairs of values not to match
     * @return The CSV Parser
     */
    public function not(array $not = []) {
        foreach($not as $key => $value) {
            $this->_filters['not'][] = [$key => $value];
        }

        return $this;
    }

	/**
	 * Update the CSV data
	 *
	 * @param $values A array of key-value pairs for the new values to update
	 */
	public function update(array $values) {
        foreach($this->run() as $line) {
            $index = array_search($line,$this->_lines);

            if($index !== false) {
                foreach($values as $key => $value) {
                    $line[$key] = $value;
                }

                $this->_lines[$index] = $line;
            }
        }
		return $this;
	}

	/**
	 * Compare two values
	 *
	 * @param value that needs matching
	 * @param value to match with - can be any value or a regex string
	 */
    private function compare($value, $match) {
		if($match == $value) {
			return true;
		}
		$is_regex = @preg_match("/^\/[\s\S]+\/$/", $match);

		//Floats can pass the regex
		if(intval($match) || floatval($match)) {
			$is_regex = false;
		}

		$matches = false;
		if($is_regex !== false) {
			return @preg_match($match,$value);
		}

		return false;
	}
    /**
     * Runs the filters on the dataset
     *
     * @return An array of the results of the query
     */
    public function run() {
        $results = [];

		//TODO Different filters - less than, greater than etc
		foreach($this->_lines as $line) {
            //Match
            foreach($this->_filters['match'] as $match) {
                foreach($match as $key => $value) {
					$matches = $this->compare($line[$key], $value);

                    if(!$matches) {
                        continue 3;
                    }
                }
            }

            foreach($this->_filters['not'] as $not) {
                foreach($not as $key => $value) {
					$matches = $this->compare($line[$key], $value);

                    if($matches) {
                        continue 3;
                    }
                }
            }

            $results[] = $line;
        }
        return $results;
    }
	/**
	 * Returns an array containing the keys on each line of the CSV
	 *
	 * @return Array containing keys
	 */
	public function getKeys() {
		return $this->_keys;
	}

	/**
	 * Resets the keys using the first line of the _lines variable
	 */
	public function resetKeys() {
		$this->_keys = array_keys(current($this));
    }

    /**
     * Reset the iterator
     */
    public function rewind() {
        $this->position = 0;
        $this->_results = $this->run();
    }

    /**
     * Get current state of iterator
     *
     * @return the current iterator state
     */
    public function current() {
        return $this->_results[$this->position];
    }

    /**
     * Get the current position
     *
     * @return The current key
     */
    public function key() {
        return $this->position;
    }

    /**
     * Get the next position
     *
     */
    public function next() {
        $this->position++;
    }

    /**
     * Checks that the current position is a valid one
     *
     * @return true if the position is valid
     * @return false if the position is not valid
     */
    public function valid() {
        return isset($this->_results[$this->position]);
    }

	/**
	 * Returns the number of items in the results
	 *
	 * @return The number of items
	 */
	public function count() {

		return count($this->run());
	}
}
?>
