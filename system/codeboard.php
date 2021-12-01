<?php
$queries = array(
    "table",
    "conditions table",
    "conditions table columns",
    "conditions table columns action");
$query = $queries[0];
$exploding = explode(" ",$query);
echo count(explode(" ",$query));
echo $exploding[0];
function exampleFunction($exploding){
    foreach ($exploding as $argument){
        echo $argument;
    }
}
$nuller = "";
echo empty($nuller);
?>
yedek
<?php
trait GlobalProperties{
    private $actionType = "";
    private $columns = "";
    private $tables = "";
    private $conditions = "";
    private $outSql = "";
}
trait JoinHelper{
    use GlobalProperties;
    private function conditions(){

    }
    private function action(){
        $typesArr = array("SELECT","INSERT INTO","UPDATE","DELETE");
        switch ($this->actionType) {
            case "|":
                $this->outSql .= $typesArr[2];
                break;
            case "<":
                $this->outSql .= $typesArr[4];
                break;
            case ">":
                $this->outSql .= $typesArr[1];
                break;
            default:
                $this->outSql .= $typesArr[0];
        }
    }
    private function columns(){
        if (empty($this->columns)){
            $this->outSql .= " *";
        }else{
            $this->outSql .= " ".$this->columns;
        }
    }
    private function tables(){
        $this->outSql .= " FROM ".$this->tables;
    }
    private function defaultDecode(){
        $this->action();
        $this->columns();
        $this->tables();
        return $this->outSql;
    }
}
/*
NOTLAR
columns metodunu sqlin yardımcı fonksiyonlarına göre düzenle// bu direkt olarak mssql gibi öbür veritabanı sistemleriyle birlikte kodlanacak

*/
/*trait DefaultDecoder{
    private function runner(){
        $action = $this->actionType;
        if(method_exists($this, $action)){
            $this->$action();
        }else{
            $this->read();
        }
    }
    private function read(){

    }
}*/
trait DecodeByDatabaseSystem{
    use JoinHelper;
    private function mysql(){
        return $this->defaultDecode();
    }
    private function mssql(){

    }
}
trait JoinSql{
    use crud,DecodeByDatabaseSystem,GlobalProperties;
    public function j($jointSql,$inputData=null,$tag = null){
        $this->jointSqlParser($jointSql);
        switch ($this->actionType) {
            case "|":
                break;
            case "<":
                break;
            case ">":
                break;
            default:
                $this->getDatas($this->jointSqlDecode(),$this->tagSelect($tag),$inputData);
        }
    }
    public function jw($jointSql,$inputData=null,$tag = null,$exit = true){
        $this->jointSqlParser($jointSql);
        if ($this->actionType == ""){
            arrWriter($this->getDatasForReturn($this->jointSqlDecode(),$this->tagSelect($tag),$inputData),$exit);
        }
    }
    private function tagSelect($tag){
        if ($tag == null){
            $parseTables = explode(",",$this->tables);
            return $parseTables[0];
        }else{
            return $tag;
        }
    }
    private function jointSqlDecode(){
        $databaseSystem = Database;
        if(method_exists($this, $databaseSystem)){
            return $this->$databaseSystem();
        }else{
            return $this->mysql();
        }
    }
    private function jointSqlParser($jointSqlCode){
        $jointSqlParse = explode(" ",$jointSqlCode);
        switch (count($jointSqlParse)) {
            case 1:
                $this->tables = $jointSqlParse[0];
                break;
            case 2:
                $this->conditions = $jointSqlCode[0];
                $this->tables = $jointSqlParse[1];
                break;
            case 3:
                $this->conditions = $jointSqlCode[0];
                $this->tables = $jointSqlParse[1];
                $this->columns = $jointSqlParse[2];
                break;
            case 4:
                $this->conditions = $jointSqlCode[0];
                $this->tables = $jointSqlParse[1];
                $this->columns = $jointSqlParse[2];
                $this->actionType = $jointSqlParse[3];
                break;
        }
    }
}
class testjsql{
    use JoinSql;
}
class UiHelpers{
    private $type;
    private $fType;
    private $table;
    public function lang($lang){
        global $language;
        $language = $lang;
    }
    public function startHead($selectedType = "adder",$selectedTable = ""){
        global $ui;
        $this->type = $selectedType;
        $this->table = $selectedTable;
        $ui .= ob_get_contents();
        ob_end_clean();
        ob_start();
    }
    public function endHead(){
        global $head;
        $headItems = ob_get_contents();
        ob_end_clean();
        ob_start();
        $headItems = $this->dataWrite($headItems);
        if($this->type == "overwrite"){
            $head = $headItems;
        }else{
            $head .= $headItems;
        }
    }
    private function dataWrite($item){
        global $lister;
        if ($this->table != ""){
            $item = $lister->listing($item,$this->table,true);
        }
        return $item;
    }
    public function startFoot($selectedType = "adder",$selectedTable = ""){
        global $ui;
        $this->fType = $selectedType;
        $ui .= ob_get_contents();
        ob_end_clean();
        ob_start();
    }
    public function endFoot(){
        global $foot;
        $footItems = ob_get_contents();
        ob_end_clean();
        ob_start();
        $footItems = $this->dataWrite($footItems);
        if($this->fType == "overwrite"){
            $foot = $footItems;
        }else{
            $foot .= $footItems;
        }
    }
}
?>
