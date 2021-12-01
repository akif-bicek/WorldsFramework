<?php
trait GlobalProperties{
    private $actionType = "";
    private $columns = "";
    private $tables = "";
    private $conditions = "";
    private $limits = "";
    private $outSql = "";
    private $inputCounts = 0;
}
trait JoinHelpers{
    use GlobalProperties;
    private function setConditions(){
        $conditionMarks = array(
            "?"=>"= ?",
            ">>"=>"IN (?*)",
            "!>>"=>"NOT IN (?*)",
            "<>"=>"BETWEEN ? AND ?",
            "!<>"=>"NOT BETWEEN ? AND ?",
            "<->"=>"INNER JOIN {jt} ON {t}.{pk} = {jt}.{fk}",
            "<-"=>"LEFT JOIN {jt} ON {t}.{pk} = {jt}.{fk}",
            "->"=>"RIGHT JOIN {jt} ON {t}.{pk}= {jt}.{fk}",
            "!"=>"!= ?",
            "<"=>"< ?",
            ">"=>"> ?",
            "<="=>"<= ?",
            ">="=>">= ?",
            "%"=>"LIKE ?",
            "!%"=>"NOT LIKE ?",
            "#"=>"FIND_IN_SET(?,**)",
            "!#"=>"NOT FIND_IN_SET(?,**)",
            "|"=>"NATURAL JOIN /*"
        );
        if (empty($this->conditions)){
            return "";
        }else{
            $allConditions = "";
            $joins = "";
            $conditions = explode(",",$this->conditions);
            $co = 0;
            foreach ($conditions as $condition){
                if(strtolower(trim($condition)) == "all"){
                    $joins = "";
                    $allConditions = "";
                    break;
                }
                if (trim($condition[0])=="^"){
                    $this->limits = str_replace("^"," LIMIT ",$condition);
                    continue 1;
                }
                if ($co == 0){
                    $replace = "WHERE ";
                    $conjunction = "";
                }else{
                    $conjunction = " AND ";
                    $replace = "";
                }
                foreach ($conditionMarks as $mark=>$conditionQuery){
                    if (finderByHead($mark,$condition)){
                        $decodeCondition = $this->decodeCondition($mark,$replace,$condition,$conditionQuery);
                        if ($this->joinContains($mark)){
                            $joins = $decodeCondition;
                        }else{
                            $allConditions .= $conjunction.$decodeCondition;
                            $co++;
                        }
                        continue 2;
                    }
                }
            }
            return $joins.$allConditions;
        }
    }
    private function decodeCondition($mark,$replace,$condition,$conditionQuery){
        $listIndicator = "**";
        $insideIndicator = "?*";
        $joinsIndicator = "{jt}";
        $colsIndicator = "/*";
        $clearMark = clearStr($mark,$condition);
        if (strFind($conditionQuery,$listIndicator)){
            $mark .= $clearMark;
            $decodeConditionQuery = str_replace($listIndicator,$clearMark,$conditionQuery);
        }elseif (strFind($conditionQuery,$insideIndicator)){
            $repeats = str_repeat("?,", $this->inputCounts);
            $repeats = removeLastChar($repeats);
            $decodeConditionQuery = str_replace($insideIndicator,$repeats,$conditionQuery);
        }elseif(strFind($conditionQuery,$joinsIndicator)){
            $cols = explode(".",$clearMark);
            $tables = explode(",",$this->tables);
            $table = $tables[0];
            $pk  = $cols[0];
            $joinsQuerys = "";
            for ($i = 1; $i < count($cols); $i++){
                $fk  = $cols[$i];
                $joinTable = $tables[$i];
                $finds = array($joinsIndicator,"{t}","{pk}","{fk}");
                $changes = array($joinTable,$table,$pk,$fk);
                $replaceQuery = str_replace($finds,$changes,$conditionQuery);
                $joinsQuerys .= $replaceQuery." ";
            }
            $replace = "";
            $condition = "";
            $decodeConditionQuery = $joinsQuerys;
        }elseif(strFind($conditionQuery,$colsIndicator)){
            $mark .= $clearMark;
            $decodeConditionQuery = str_replace($colsIndicator,$clearMark,$conditionQuery);
            $replace = "";
        }else{
            $decodeConditionQuery = $conditionQuery;
        }
        return str_replace($mark,$replace,$condition)." ".$decodeConditionQuery;
    }
    private function getPrimaryTable(){
        if ($this->joinContains()){
            $parseTables = explode(",",$this->tables);
            return $parseTables[0];
        }else{
            return $this->tables;
        }
    }
    private function inputCounts($inputData){
        if ($inputData == null){
            return 0;
        }else{
            return count($inputData);
        }
    }
    private function joinContains($mark = null){
        if ($mark == null){
            if ((strFind($this->conditions,"<->"))or(strFind($this->conditions,"<-"))or(strFind($this->conditions,"->"))){
                return true;
            }else{
                return false;
            }
        }else{
            if ((strFind($mark,"<->"))or(strFind($mark,"<-"))or(strFind($mark,"->"))){
                return true;
            }else{
                return false;
            }
        }
    }
    private function setUpdateColumns(){
        $columns = $this->columns.",";
        $decodeColumns = str_replace(","," = ?,",$columns);
        return removeLastChar($decodeColumns);
    }
    private function setCreateColumnsAndValues(){
        $columns = $this->columns;
        $inputCounts = $this->inputCounts;
        $colsArr = explode(",",$columns);
        $colsCount = count($colsArr);
        $values = repeatAndLastRemoved("?,",$colsCount);
        $row = "(".$values."),";
        if ($inputCounts != $colsCount){
            if ($inputCounts % $colsCount!=0){
                exit("Unrequited fields found ! [uiData]");
            }
            $valueListCount = $inputCounts/$colsCount;
            $valueList = repeatAndLastRemoved($row,$valueListCount);
        }else{
            $valueList = $row;
        }
        return "(".$columns.") VALUES ".$valueList;
    }
    private function setSqlFuncs($columns){
        $decodingColumns = "";
        if (strFind($columns,".")){
            foreach (explode(",",$columns) as $column){
                if (strFind($column,".")){
                    if(strFind($column,"#")){
                        $parseAlias = explode("#",$column);
                        $function = $parseAlias[0];
                        $alias = "#".trim($parseAlias[1]);
                    }else{
                        $function = $column;
                        $alias = "";
                    }
                    $parseColumn = explode(".",$function);
                    $parameters = str_replace("-",",",$parseColumn[1]);
                    $func = strtoupper($parseColumn[0])."(".$parameters.")";
                    $decodingColumns .= $func.$alias.",";
                }else{
                    $decodingColumns .= $column.",";
                }
            }
            $decodingColumns = removeLastChar($decodingColumns);
        }else{
            $decodingColumns = $columns;
        }
        return $decodingColumns;
    }
    private function setAlias($columns){
        $decodingColumns = "";
        if(strFind($columns,"#")){
            foreach (explode(",",$columns) as $column){
                if (strFind($column,"#")){
                    $assingedCol = str_replace("#"," AS ",$column);
                    $decodingColumns .= $assingedCol.",";
                }else{
                    $decodingColumns .= $column.",";
                }
            }
            $decodingColumns = removeLastChar($decodingColumns);
        }else{
            $decodingColumns = $columns;
        }
        return $decodingColumns;
    }
    private function decodeColumnsForRead(){
        $columns = isEmptySetDefault($this->columns,"*");
        if ($columns != "*"){
            $columns = $this->setSqlFuncs($columns);
            $columns = $this->setAlias($columns);
        }
        return $columns;
    }
}
trait JoinCrud{
    use JoinHelpers;
    private function alert() :void{
        exit("Action Type Not Found ! [uiData]");
    }
    private function read(){
        $this->outSql .= "SELECT ";

        $conditions = $this->setConditions();
        $table = $this->getPrimaryTable();
        $columns = $this->decodeColumnsForRead();

        $this->outSql .= $columns;
        $this->outSql .= " FROM ";
        $this->outSql .= $table;
        $this->outSql .= " ";
        $this->outSql .= $conditions;
        return $this->outSql;
    }
    private function update(){
        $this->outSql .= "UPDATE ";

        $conditions = $this->setConditions();
        $table = $this->tables;
        $columns = $this->setUpdateColumns();

        $this->outSql .= $table;
        $this->outSql .= " SET ";
        $this->outSql .= $columns;
        $this->outSql .= " ";
        $this->outSql .= $conditions;
        return $this->outSql;
    }
    private function delete(){
        $this->outSql .= "DELETE FROM ";

        $conditions = $this->setConditions();
        $table = $this->tables;

        $this->outSql .= $table;
        $this->outSql .= " ";
        $this->outSql .= $conditions;
        return $this->outSql;
    }
    private function create(){
        $this->outSql .= "INSERT INTO ";

        $table = $this->tables;
        $columnsAndValues = $this->setCreateColumnsAndValues();

        $this->outSql .= $table;
        $this->outSql .= " ";
        $this->outSql .= $columnsAndValues;
        return $this->outSql;
    }
}
trait JoinSql{
    use crud,JoinCrud;
    private $joinSql,$inputData,$tag;
    public function j($joinSql,$inputDataOrTag = null,$tag = null){
        $this->setAndRun("j",func_get_args());
    }
    public function jw($joinSql,$inputDataOrTag=null,$tag = null){
        $this->setAndRun("jw",func_get_args());
    }
    public function s($query,$inputDataOrTag=null,$tag=null){
        $this->setAndRun("s",func_get_args());
    }
    public function sw($query,$inputDataOrTag=null,$tag=null){
        $this->setAndRun("sw",func_get_args());
    }
    private function setAndRun($type,...$params){
        $params = $params[0];
        $this->joinSql = $params[0];
        $this->inputData = (isset($params[1]))?$params[1]:null;
        $this->tag = (isset($params[2]))?$params[2]:null;
        $this->inputDataOrTag();
        switch ($type) {
            case "j":
                $this->join();
                break;
            case "jw":
                $this->join(true);
                break;
            case "s":
                $this->sql();
                break;
            case "sw":
                $this->sql(true);
                break;
        }
    }
    private function inputDataOrTag(){
        if ($this->tag == null){
            if (!is_array($this->inputData)){
                $this->tag = $this->inputData;
                $this->inputData = null;
            }
        }else{
            if (!is_array($this->inputData)){
                die("Array Not Found ! [uiData]");
            }
        }
    }
    private function sql($writer = false) :void {
        $queryParse = explode(" ",$this->joinSql);
        $action = strtoupper($queryParse[0]);
        if ($action == "SELECT"){
            $datas = $this->getDatas(
                $this->joinSql,
                $this->tagSelect($this->tag,true,$queryParse[3]),
                $this->inputData
            );
        }else{
            $datas = $this->runQueries($this->joinSql,$this->inputData,true);
        }
        if ($writer){
            echo $this->joinSql;
            arrWriter($datas);
        }
    }
    private function join($writer = false){
        if (strFind($this->joinSql,";")){
            $joinsqls = $this->multiJoinToJoins($this->joinSql);
            $tags = ($this->tag == null)? null: explode(";",$this->tag);
            foreach ($joinsqls as $key=>$join){
                $tagsForRun = ($tags != null)?$tags[$key]:null;
                $inputDatas = ($this->inputData != null)?$this->inputData[$key]:null;
                $decode = $this->decodeAndRun(
                    $join,
                    $this->availableDatasFinder($inputDatas),
                    $tagsForRun,
                    $writer);
                $this->writer($decode,$writer);
            }
        }else{
            $decode = $this->decodeAndRun(
                $this->joinSql,
                $this->availableDatasFinder($this->inputData),
                $this->tag,
                $writer);
            $this->writer($decode,$writer);
        }
    }
    private function writer($decode,$confirmation){
        if($confirmation){
            arrWriter($decode,false);
        }
    }
    private function decodeAndRun($joinSql,$inputData,$tag,$writer = false){
        $this->joinSqlParser($joinSql);
        $this->inputCounts = $this->inputCounts($inputData);
        switch ($this->actionType) {
            case "|":
                return
                    $this->runQueries(
                        $this->joinSqlDecode("update",$writer),
                        $inputData,
                        true,
                        true
                    );
                break;
            case "<":
                return
                    $this->runQueries(
                        $this->joinSqlDecode("delete",$writer),
                        $inputData,
                        true
                    );
                break;
            case ">":
                return
                    $this->runQueries(
                        $this->joinSqlDecode("create",$writer),
                        $inputData,
                        true
                    );
                break;
            default:
                $tagSelect = $this->tagSelect($tag);
               return
                   $this->getDatas(
                    $this->joinSqlDecode("read",$writer),
                    $tagSelect,
                    $inputData,
                    true
                );
        }
    }
    private function availableDatasFinder($dataArray){
        if($dataArray != null){
            $returnArray = array();
            foreach ($dataArray as $key=>$data){
                $returnArray[$key] = $this->getSetAvailableDatas($data);
            }
            return $returnArray;
        }
    }
    private function getSetAvailableDatas($avalibleDataStringOrData){
        $data = $avalibleDataStringOrData;
        if (is_string($avalibleDataStringOrData)){
            if (substr($avalibleDataStringOrData,0,3) == "??:"){
                $avalibleDataString = substr($avalibleDataStringOrData, 3, strlen($avalibleDataStringOrData));
                $data = searchDatas(explode(".",$avalibleDataString));
            }
        }
        return $data;
    }
    private function tagSelect($tag,$sql=false,$tables=""){
        if ($tag == null){
            if (!$sql){
                $tables = $this->tables;
            }
            $parseTables = explode(",",$tables);
            return $parseTables[0];
        }else{
            return $tag;
        }
    }
    private function joinSqlDecode($actionType,$writeSql=false){
        if(method_exists($this, $actionType)){
            $sql  = $this->$actionType();
            $sql .= $this->limits;
            if ($writeSql){ echo $sql; }
            $this->resets();
            return $sql;
        }else{
            $this->alert();
        }
    }
    private function resets(){
        $this->conditions = "";
        $this->tables = "";
        $this->columns = "";
        $this->actionType = "";
        $this->limits = "";
        $this->outSql = "";
        $this->inputCounts = 0;
    }
    private function multiJoinToJoins($joinSql){
        $this->joinSqlParser($joinSql);
        $multiConds = $this->splitMultiJoinParams($this->conditions);
        $multiTables = $this->splitMultiJoinParams($this->tables);
        $multiColumns = $this->splitMultiJoinParams($this->columns);
        $multiActions = $this->splitMultiJoinParams($this->actionType);
        $this->resets();
        $arr = array();
        for ($i = 0; $i < count($multiTables); $i++){
            $arr[$i] =
                $this->getMultiValues($multiConds,$i).
                $this->getMultiValues($multiTables,$i).
                $this->getMultiValues($multiColumns,$i).
                $this->getMultiValues($multiActions,$i,"");
        }
        return $arr;
    }
    private function getMultiValues($arr,$key,$space=" "){
        if (empty($arr)){
            return "";
        }else{
            if (empty($arr[$key])){
                return $arr[0].$space;
            }else{
                return $arr[$key].$space;
            }
        }
    }
    private function splitMultiJoinParams($param){
        if (empty($param)){
            return "";
        }else{
            return explode(";",$param);
        }
    }
    private function joinSqlParser($jointSqlCode){
        $jointSqlParse = explode(" ",trim($jointSqlCode));
        switch (count($jointSqlParse)) {
            case 1:
                $this->tables = $jointSqlParse[0];
                break;
            case 2:
                $parseTwo = $jointSqlParse[1];
                if (strFind($parseTwo,".")){
                    $this->tables = $jointSqlParse[0];
                    $this->columns = $parseTwo;
                }else{
                    $this->conditions = $jointSqlParse[0];
                    $this->tables = $parseTwo;
                }
                break;
            case 3:
                $parseThree = $jointSqlParse[2];
                if ($parseThree == ">"){
                    $this->tables = $jointSqlParse[0];
                    $this->columns = $jointSqlParse[1];
                    $this->actionType = $parseThree;
                }elseif($parseThree == "<"){
                    $this->conditions = $jointSqlParse[0];
                    $this->tables = $jointSqlParse[1];
                    $this->actionType = $parseThree;
                }else{
                    $this->conditions = $jointSqlParse[0];
                    $this->tables = $jointSqlParse[1];
                    $this->columns = $jointSqlParse[2];
                }
                break;
            case 4:
                $this->conditions = $jointSqlParse[0];
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