<?php

class DB {

    private $dbh;   // db handle
    function __construct(){
        try{
            $this->dbh = new PDO("mysql:host={$_SERVER['DB_SERVER']};dbname={$_SERVER['DB']}", 
        $_SERVER['DB_USER'],$_SERVER['DB_PASSWORD']);
        $this->dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {
            echo $e->getMessage();
            die("Bad database connection");
        }
    }

    public function getDBH(){
        return $this->dbh;
    }
}

?>