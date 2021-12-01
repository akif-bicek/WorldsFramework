<?php
const AkifBicek = "Muhammed Akif Biçek";
class Database {
    private static $db;
    private $connection;
    private function __construct() {
        try {
            $this->connection = new PDO(DATABASE.":host=".HOST.";port=3308;dbname=".DBNAME.";charset=UTF8",USER,PASS);
        }catch (PDOException $error){
            $this->connection = "DataBase Connect Error <br />".$error->getMessage();
        }
    }
    function __destruct() {
        $this->connection = null;
    }
    public static function getConnection() {
        if (self::$db == null) {
            self::$db = new Database();
        }
        return self::$db->connection;
    }
    //başka bir traite atılacak bundan sonrasi

    //trait 1
    private $dbinfo = Project."dbInfo.php";
    private function buildDsnString(){

    }
    private function getUser(){

    }
    private function getPassword(){

    }
    //trait 2 buildDsnString
    private function decodeDatabaseType(){
        $dbinfo = fopen($this->dbinfoPath,"r");

    }
}
?>