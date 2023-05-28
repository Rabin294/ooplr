<?php
class DB {
    private static $_instance = null;
    private $_pdo, 
            $_query, 
            $_error = false,
            $_results,
            $_count = 0;

    private function __construct(){
        try {
            // Create a new PDO instance with database configuration values
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                Config::get('mysql/username'),
                Config::get('mysql/password'));
            //  echo "Connected successfully";
        } catch (PDOException $e) {
            // If connection fails, display the error message and stop execution
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
    
    public static function getInstance(){
        if (!isset(self::$_instance)){
            // Create a new instance if it doesn't exist
            self::$_instance = new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $params = array()){
        $this->_error = false;
        if ($this->_query = $this->_pdo->prepare($sql)){
            $x = 1;
            if (count($params)){
                foreach ($params as $param){
                    // Bind values to the prepared statement
                    $this->_query->bindValue($x, $param);
                    $x++;
                }
            }
            if ($this->_query->execute()){
                // Fetch the results and count the rows
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else {
                // Set error flag to true if query execution fails
                $this->_error = true;
            }
        }
        return $this;
    }

    public function action($action, $table, $where = array()){
        if (count($where) === 3){
            $operators = array('=', '>', '<', '>=', '<=');

            $field    = $where[0];
            $operator = $where[1];
            $value    = $where[2];

            if (in_array($operator, $operators)){
                // Construct the SQL query with action, table, field, operator, and placeholder for value
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if (!$this->query($sql, array($value))->error()){
                    // If query execution is successful, return the current instance
                    return $this;
                }  
            }
        }
        return false;
    }

    public function get($table, $where){
        // Helper method for SELECT queries, calls the action() method with action set to SELECT *
        return $this->action('SELECT *', $table, $where);
    }

    public function delete($table, $where){
        // Helper method for DELETE queries, calls the action() method with action set to DELETE
        return $this->action('DELETE', $table, $where);
    }

    public function insert($table, $fields=array()){
        if(count($fields)){
            $keys = array_keys($fields);
            $values = '';
            $x = 1;

            foreach ($fields as $field){
                $values .= "?";
                if($x < count($fields)){
                    $values .= ', ';
                }
                $x++;
            }

            $sql = "INSERT INTO {$table} (`" . implode('`,`', $keys) . "`) VALUES ({$values})";
            echo $sql;
            if(!$this->query($sql, $fields)->error()){
                return true;
            }
        }
        return false;
    }

    public function update($table, $id, $fields){
        $set = '';
        $x = 1;

        foreach($fields as $name => $value){
            $set .= "{$name} = ?";
            if ($x < count($fields)){
                $set .= ', ';
            }
            $x++;
        }

        
        $sql = " UPDATE {$table} SET {$set} WHERE id = {$id}";

        if(!$this->query($sql, $fields)->error()){
            return true;
        }
        return false;
    }

    public function results(){
        return $this->_results;
    }

    public function first(){
        return $this->results()[0];
    }

    public function error(){
        // Returns the error flag
        return $this->_error;
    }

    public function count(){
        // Returns the count of rows
        return $this->_count;
    }
}
