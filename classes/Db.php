<?php
class DB {
    private static $_instance = null;
    private $_pdo, 
            $_query, 
            $_error=false,
            $_results,
            $_count=0;

    private function __construct(){
       
        try {
            $this->_pdo = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'),
                Config::get('mysql/username'),
                Config::get('mysql/password'));
            //  echo "Connected successfully";
            } catch (PDOException $e) {
                echo "Connection failed: " . $e->getMessage();
                die(); // Add this line to stop the execution if the connection fails
            }
    }
    
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance= new DB();
        }
        return self::$_instance;
    }

    public function query($sql, $prams= array()){
        $this->_error= false;
        if($this->_query = $this->_pdo->prepare($sql)){
            $x=1;
            if(count($prams)){
                foreach ($prams as $pram){
                    $this->_query->bindValue($x, $pram);
                    $x++;
                }
            }
            if($this->_query->execute()){
                echo 'Success';
            }
        }
    }

}