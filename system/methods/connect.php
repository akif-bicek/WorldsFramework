<?php
const AkifBicek = "Muhammed Akif Biçek";
class Database {
    private static $db;
    private $connection;
    private $dbinfo = Project."dbInfo.php";
    private function __construct() {
        $dbInfoSecurityRow = fileReadContent($this->dbinfo,0);
        if (!strFind($dbInfoSecurityRow,'die("dbInfo")')){
            die("Please Insert Php Command 'die' First Row ! This Very Important Your's Security.");
        }
        $this->connection = $this->buildConnectionObject();
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

    //trait 1 ConnectHelpers
    private function buildConnectionObject(){
        $dbType = $this->databaseType();
        $dbs = array("my#","postgre#","lite#","ms#","oracle#");
        if (($dbType == $dbs[0]) or ($dbType == $dbs[1]) or ($dbType == $dbs[3])){
            $host = $this->host();
            $dbname = filter(fileRead($this->dbinfo,4));
            $user = $this->getUser();
            $pass = $this->getPassword();
            switch ($dbType) {
                case $dbs[0]:
                    if (empty($host[1])){
                        $dsn = "mysql:host=".$host[0].";dbname=".$dbname.";";
                    }else{
                        $dsn = "mysql:host=".$host[0].";port=".$host[1].";dbname=".$dbname.";";
                    }
                    $pdo = connectPdo($dsn,$user,$pass);
                    break;
                case $dbs[1]:
                    if ($pass == ""){
                        $dsn = "pgsql:host=".$host[0].";port:".$host[1].";dbname=".$dbname.";user=".$user.";";
                    }else{
                        $dsn = "pgsql:host=".$host[0].";port:".$host[1].";dbname=".$dbname.";user=".$user.";password=".$pass.";";
                    }
                    $pdo = connectPdo($dsn);
                    break;
                case $dbs[3]:
                    if (empty($host[1])){
                        $dsn = "sqlsrv:Server=".$host[0].";Database=".$dbname.";";
                    }else{
                        $dsn = "sqlsrv:Server=".$host[0].",".$host[1].";Database=".$dbname.";";
                    }
                    $pdo = connectPdo($dsn,$user,$pass);
                    break;
                default:
                    die("Please Insert Database Information In Project 'dbInfo.php' File's !");
            }
            return $pdo;
        }elseif($dbType == $dbs[2]){
            $path = fileRead($this->dbinfo,2,true);
            $dsn = "sqlite:";
            if ($path == null){
                $dsn .= Project."db/lite.sq3";
            }elseif($path == "m"){
                $dsn = ":memory:";
            }else{
                $path = (strFind($path,".sq3"))?$path:$path.".sq3";
                $dsn = Project."db/".$path;
            }
            $pdo = connectPdo($dsn);
            return $pdo;
        }elseif($dbType == $dbs[4]){
            $loginInfo = explode(" ",fileRead($this->dbinfo,2));
            $user = $loginInfo[0];
            $pass = $loginInfo[1];
            $dbname = fileRead($this->dbinfo,3);
            $dsn = "oci:dbname=".$dbname;
            $pdo = connectPdo($dsn,$user,$pass);
            return $pdo;
        }
    }
    //trait 2 buildConnectionObject
    private function databaseType(){
        $databaseTypes = fileRead($this->dbinfo,1);
        foreach(explode(" ",$databaseTypes) as $database){
            if (strFind($database,"#")){
                return filter($database);
            }
        }
    }
    private function host(){
        $host = fileRead($this->dbinfo,2);
        $hostParse = explode(" ",$host);
        return arrFilter($hostParse);
    }
    private function getUser($row=3){
        $user = fileRead($this->dbinfo,$row);
        $userParse = explode(" ",$user);
        return filter($userParse[0]);
    }
    private function getPassword($row=3){
        $pass = fileRead($this->dbinfo,$row);
        $passParse = explode(" ",$pass);
        if (count($passParse) > 1){
            return filter($passParse[1]);
        }else{
            return "";
        }
    }
}
?>