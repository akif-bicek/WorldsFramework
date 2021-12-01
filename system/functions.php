<?php
function defineGlobalValues() :void {
    $settings = json_decode(file_get_contents("../settings.json"),true);
    define("Theme",$settings["Theme"]."/");
    define("Project","../ui/".$settings["Project"]."/");
}
function fileReadContent($path,$type="all"){
    $file = file_get_contents($path);
    $return = "";
    if($type === "all"){
        $return = $file;
    }elseif (($type == "array") or (is_numeric($type))){
        $rows = explode("\n",$file);
        if (is_numeric($type)){
            $return = $rows[$type];
        }else{
            $return = $rows;
        }
    }
    return $return;
}
function fileRead($path,$type="all",$nullCheck=false){
    $file = fopen($path,"r");
    $return = "";
    if($type == "all"){
        $fileSize = filesize($path);
        $read = fread($file,$fileSize);
        $return = $read;
    }elseif (($type == "array") or (is_numeric($type))){
        while($read = fgets($file)){
            $rows[] = $read;
        }
        if (is_numeric($type)){
            if ($nullCheck){
                if (empty($rows[$type])){
                    $return = null;
                }else{
                    $return = $rows[$type];
                }
            }else{
                $return = $rows[$type];
            }
        }else{
            $return = $rows;
        }
    }
    fclose($file);
    return $return;
}
function pathLastCharCheck($path){
    if(!((substr($path, -1) == "/")or(substr($path, -1) == "\\"))){
        $path .= "/";
    }
    return $path;
}
function requires($path,$reverse=false):void {
    global $lister;
    $path = pathLastCharCheck($path);
    if($path == "methods/"){
        defineGlobalValues();
    }
    $files = glob("$path*.php");
    if ($reverse){
     $files = array_reverse($files);
    }
    foreach ($files as $filename) {
        require_once $filename;
    }
}
function createScriptTags($files){
    $returnedHtml = "";
    if (is_array($files)){
        foreach ($files as $file){
            $returnedHtml.='<script type="text/javascript" src="system/'.$file.'"></script>';
        }
    }else{
        $returnedHtml = '<script type="text/javascript" src="system/'.$files.'"></script>';
    }
    return $returnedHtml;
}
function createStyleTags($files){
    $returnedHtml = "";
    if (is_array($files)){
        foreach ($files as $file){
            $returnedHtml.='<link href="system/'.$file.'" rel="stylesheet" />';
        }
    }else{
        $returnedHtml = '<link href="system/'.$files.'" rel="stylesheet" />';
    }
    return $returnedHtml;
}
function importSystemJs() {
    global $ipWithHashAndTime;
    echo createScriptTags(array(
        "temp/jsDatas/".$ipWithHashAndTime.".js",
        "materials/frameworks/jquery.js",
        "materials/scripts/framework.js"
    ));
}
function importSystemCss():void {
    echo createStyleTags(array(
        "materials/styles/framework.css",
    ));
}
function writeBase():void {
    $base = dirname($_SERVER['PHP_SELF'])."/";
    $base = str_replace("system/","",$base);
    echo '<base href="'.$base.'">';
}
function filter($val){
    return trim(strip_tags(htmlspecialchars($val, ENT_QUOTES)));
}
function filter_decode($val){
    return htmlspecialchars_decode($val,ENT_QUOTES);
}
function numberreqex($val){
    return preg_replace("/[^0-9]/","",$val);
}
function numberfilter($val){
    return filter(numberreqex($val));
}
function getReqOne(){
    if (empty($_REQUEST["reqone"])){
        return "user";
    }else{
        return filter($_REQUEST["reqone"]);
    }
}
function getReqTwo(){
    if (empty($_REQUEST["reqtwo"])){
        return null;
    }else{
        return filter($_REQUEST["reqtwo"]);
    }
}
function getRequireStringSetPageAndDatas(){
    $reqOne = getReqOne();
    $reqTwo = getReqTwo();
    $root = Project."user/".Theme;
    $rootByLayout = clearStr("../",$root);
    $user = $root."lUser/user.php";
    $returned = "";
    if (strtolower(trim($reqOne)) == "admin"){
        //system admin panel
    }elseif (strtolower(trim($reqOne)) == "user"){
        define("page","index");
        $returned = $user;
        uiDataMethodsRun("user");
        define("thisLayout",$rootByLayout."lUser/");
        define("thisPage",$root."lUser/index/");
    }else{
        $layout = $root."l".ucfirst($reqOne)."/";
        if (is_dir($layout)){
            $page = (empty($reqTwo))?"index":$reqTwo;
            if (!is_dir($layout."pages/".$reqTwo)){
                exit("404 not found Layout");
            }
            define("page",$page);
            $returned = $layout."/".$reqOne.".php";
            uiDataMethodsRun($reqOne,$reqTwo);
            define("thisLayout",$rootByLayout."l".ucfirst($reqOne)."/");
            define("thisPage",$root."l".ucfirst($reqOne)."/pages/".$page."/");
        }else{
            $pageDatas = pageFinder($reqOne);
            $userPage = $root."lUser/pages/".$pageDatas[0]."/";
            if (is_dir($userPage)){
                //olmayan bir oluşturulmuş sayfa olursa onu 404e yönlendir burda yapmadık
                //değiştirilecek tamamiyle tekil listelemeyi tamamlamak için şimdilik böyle yapıyoruz framework.js,homephp,functions.php sayfalarında değişiklik olacaktur
                define("page",$pageDatas[0]);
                $returned = $user;
                uiDataMethodsRun("user",$pageDatas);
                define("thisLayout",$rootByLayout."lUser/");
                define("thisPage",$root."lUser/pages/".$pageDatas[0]."/");
            }else{
                exit("404 not found user");
                //ERROR 404
            }
        }
    }
    return $returned;
}
function pageFinder($page){
    //değiştirilecek tamamiyle tekil listelemeyi tamamlamak için şimdilik böyle yapıyoruz framework.js,homephp,functions.php sayfalarında değişiklik olacaktur
    if (is_numeric($page)){
        return array("detail",$page);
    }else{
        return $page;
    }
}
function arrWriter($array,$die=true){
    echo "<pre>";
    print_r($array);
    echo "</pre>";
    if ($die){
        exit();
    }
}
function isEmptySetDefault($value,$default){
    return (empty($value))?$default:$value;
}
function finderByHead($find,$value){
    $length = strlen($find);
    $newValue = substr($value,0,$length);
    if (trim($find) == trim($newValue)){
        return true;
    }else{
        return false;
    }
}
function finderByFoot($find,$value){
    $length = strlen($find)*-1;
    $newValue = substr($value,$length);
    if (trim($find) == trim($newValue)){
        return true;
    }else{
        return false;
    }
}
function strFind($string,$find){
    if (strpos($string,$find)!==false){
        return true;
    }else{
        return false;
    }
}
function uiImport() :void{
    global $lister,$data,$UiHelpers,$ui;
    $requireString = getRequireStringSetPageAndDatas();
    require_once $requireString;
    importSystemJs();
    $ui .= ob_get_contents();
    ob_end_clean();
    ob_start();
}
function clearStr($clearStr,$string){
    return str_replace($clearStr,"",$string);
}
function removeLastChar($string){
    return substr($string,0,-1);
}
function arrFilter($arr){
    if ($arr != null){
        $array = array();
        foreach ($arr as $key=>$val){
            $array[filter($key)] = filter($val);
        }
        return $array;
    }
}
function connectPdo($dsn,$user=null,$pass=null){
    try {
        $pdo = new PDO($dsn,$user,$pass);
    }catch (PDOException $error){
        exit("DataBase Connect Error <br />".$error->getMessage());
    }
    return $pdo;
}
function uiDataMethodsRun(): void{
    require_once Project."uiData.php";
    $uiData = new uiData();
    foreach (func_get_args() as $method) {
        //değiştirilecek tamamiyle tekil listelemeyi tamamlamak için şimdilik böyle yapıyoruz framework.js,homephp,functions.php sayfalarında değişiklik olacaktur
        $methodName = (is_array($method))?$method[0]:$method;
        if (method_exists('uiData', $methodName)) {
            if (is_array($method)) {
                $uiData->$methodName($method[1]);
            } else {
                $uiData->$method();
            }
        }
    }
}
function buildUniqeHash($value=null){
    if ($value == null){
        return substr(md5(uniqid(time())), 0, 25);
    }else{
        return substr(md5(uniqid($value)), 0, 25);
    }
}
function getIp($withTimeStamp=false,$withHash=false){
    $ip = filter($_SERVER["REMOTE_ADDR"]);
    $value = $ip;
    if ($withTimeStamp){
        $time = time();
        $value .= $time;
    }
    if($withHash){
        $value = buildUniqeHash($value);
    }
    return $value;
}
function removeFilesDirectory($path){
    $path = pathLastCharCheck($path);
    $files = glob($path.'*');
    foreach($files as $file){
        if(is_file($file)) {
            unlink($file);
        }
    }
}
function uiStyles() :void{
    $styles = func_get_args();
    foreach ($styles as $style){
        echo '<link href="'.thisLayout.$style.'.css" rel="stylesheet"/>';
    }
}
function searchArrays($wantedKeysArray, $array){
    foreach ($wantedKeysArray as $wantedKey){
        if (!empty($array[$wantedKey])){
            $array = $array[$wantedKey];
        }
    }
    return $array;
}
function searchDatas($wantedKeysArray){
    global $data;
    $table = array_shift($wantedKeysArray);
    $record = $data[$table][0];
    return searchArrays($wantedKeysArray,$record);
}
function repeatAndLastRemoved($input,$count){
    return removeLastChar(str_repeat($input,$count));
}
function getVarName($var) {
    // read backtrace
    $bt   = debug_backtrace();
    // read file
    $file = file($bt[0]['file']);
    // select exact print_var_name($varname) line
    $src  = $file[$bt[0]['line']-1];
    // search pattern
    $pat = '#(.*)'.__FUNCTION__.' *?\( *?(.*) *?\)(.*)#i';
    // extract $varname from match no 2
    $var  = preg_replace($pat, '$2', $src);
    // return the var name
    return trim($var);
}
function vc($var){
    return array(getVarName($var),$var);
}
function aq($tableKey,$col) : void {
    global $aq;
    $aq[$tableKey] = (!is_array($col))?vc($col):$col;
}
?>