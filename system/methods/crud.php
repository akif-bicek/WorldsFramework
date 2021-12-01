<?php
AkifBicek;
trait DatabaseActions{
    use JsonActions;
    private function getDatas($sql,$table,$array=null,$decodeJoinDatas=false) {
        global $db;
        global $data;
        if ($decodeJoinDatas){
            $dataArr = $this->decodeJoinDatas($sql,$array);
        }else{
            $dataArr = $array;
        }
        $query = $db->prepare($sql);
        $query->execute(arrFilter($dataArr));
        $dataResult = $query->fetchAll(2);
        $data[$table] = $dataResult;
        $this->dataAdder();
        return $dataResult;
    }
    // aşağıdaki decodeJoinDatas fonksiyonun sınıfı bura değildir düzenlenmesi şart ara fonksiyon üretir systemin içine atarız
    private function decodeJoinDatas($sql,$datas){
        if ($datas != null){
            $sqlParse = explode("WHERE",$sql);
            $inputsCounts = substr_count($sql,"?");
            $columnsInputsCounts = substr_count($sqlParse[0],"?");
            $ejectColumns = $inputsCounts - $columnsInputsCounts;
            $conditionsDatas = array();
            for ($i = 0;$i < $ejectColumns; $i++){
                $conditionsDatas[] = array_shift($datas);
            }
            return array_merge($datas,$conditionsDatas);
        }
    }
    private function runQueries($sql,$array=null,$errors=false,$decodeJoinDatas=false){
        global $db;
        if ($decodeJoinDatas){
            $dataArr = $this->decodeJoinDatas($sql,$array);
        }else{
            $dataArr = $array;
        }
        $query = $db->prepare($sql);
        $query->execute(arrFilter($dataArr));
        $check = $query->rowCount();
        if ($check>0){
            return true;
        }else{
            if ($errors){
                arrWriter($db->errorInfo(),false);
            }
            return false;
        }
    }
}
trait JsonActions{
    private function dataAdder():void {
        global $data,$ipWithHashAndTime;
        $tempDatasPath = "temp/jsDatas/";
        removeFilesDirectory($tempDatasPath);
        $uniqeFileNameByIp = $ipWithHashAndTime.".js";
        $file = fopen($tempDatasPath.$uniqeFileNameByIp, 'w');
        $js = 'let jsonDatas = '.json_encode($data);
        fwrite($file,$js);
        fclose($file);
    }
}
trait crud{
    use DatabaseActions;
}
class Lister{
    private $table;
    public function start($tableName){
        global $ui;
        $this->table = $tableName;
        $ui .= ob_get_contents();
        ob_end_clean();
        ob_start();
    }
    public function end(){
        $item = ob_get_contents();
        ob_end_clean();
        ob_start();
        $this->listing($item,$this->table);
    }
    public function listing($item,$tableKey,$return=false){
        global $data,$aq;
        $table = $this->getTableName($tableKey);
        if (isset($aq[$tableKey])){
            $column = $aq[$tableKey][0];
            $value = $aq[$tableKey][1];
            $datas = $this->queriesAvalibleDatas($data[$table],$column,$value);
            //arrWriter($aq);
        }else{
            $datas = $data[$table];
        }
        foreach ($datas as $row){
            $columns = array_keys($row);
            $changeItem = $item;
            foreach ($columns as $col){
                $changeItem = $this->helperFuncsDetectAndReplaceAll($changeItem,$col,$row[$col]);
            }
            if ($return){return $changeItem; }else{echo $changeItem; }
        }
    }
    private function queriesAvalibleDatas($rows,$column,$value){
        foreach ($rows as $row){
            $data = $row;
            foreach ($row as $col=>$val){
                if ($col == $column){
                    if ($val == $value){
                        return array(0=>$data);
                        break 2;
                    }
                }
            }
        }
    }
    private function getTableName($tableKey){
        if (strFind($tableKey,"#")){
            $parse = explode("#",$tableKey);
            return $parse[0];
        }else{
            return $tableKey;
        }
    }
    private function helperFuncsDetectAndReplaceAll($text,$column,$data){
        $colCount = substr_count($text, '{'.$column);
        $i = 0;
        while($i<$colCount){
            $find = "{".$column."}";
            if (strpos($text,$find)){
                $text = str_replace($find,$data,$text);
            }else{
                $helpers = array("/","!");
                $separators = array("-",",");
                foreach ($helpers as $key => $help){
                    $newFind = "{".$column.$help;
                    if (strpos($text,$newFind)){
                        $newText = explode($newFind,$text);
                        $newText = explode("}",$newText[1]);
                        $parameters = $newText[0];
                        $newData = $this->setHelpFunctionData($help,$parameters,$separators[$key],$data);
                        $text = str_replace($newFind.$parameters."}",$newData,$text);
                    }
                }
            }
            $i++;
        }
        return $text;
    }
    private function setHelpFunctionData($helper,$params,$separator,$data){
        $params = explode($separator,$params);
        switch($helper) {
            case "/":
                if(empty($params[1])){
                    $params[1] = $params[0];
                    $params[0] = 0;
                }
                return substr($data,$params[0],$params[1]);;
                break;
            case "!":
                return "the test data";
                break;
            default:
                return $data;
        }
    }
}
?>