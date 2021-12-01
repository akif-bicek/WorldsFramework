<?php

$databases = array("mysql", "pgsql", "mssql");
define("dsn", array("mysql", "localhost", "3308", ""));
define("login", array());
define("DATABASE", "mysql");
define("HOST", "localhost");
define("USER", "root");
define("PASS", "");
define("DBNAME", "cmsframework");

function filter($val){
    return trim(strip_tags(htmlspecialchars($val, ENT_QUOTES)));
}
function arrFilter($arr)
{
    $array = array();
    foreach ($arr as $key => $val) {
        $array[filter($key)] = filter($val);
    }
    return $array;
}
function getVarName($var) {
    foreach($GLOBALS as $varName => $value) {
        if ($value === $var) {
            return $varName;
        }
    }
    return false;
}
function vc($var){
    return array(getVarName($var),$var);
}
function aq($col){
  return vc($col);
}
$id = "akif";
print_r(aq($id));
exit();
function decodeJoinDatas($sql,$datas){
    $sqlParse = explode(" ",$sql);
    $inputsCounts = substr_count($sql,"?");
    if ($sqlParse[0] == "SELECT"){
        $columns  = $sqlParse[1];
        $columnsInputsCounts = substr_count($columns,"?");
        $ejectColumns = $inputsCounts - $columnsInputsCounts;
        $conditionsDatas = array();
        for ($i = 0;$i < $ejectColumns; $i++){
            $conditionsDatas[] = array_shift($datas);
        }
        return array_merge($datas,$conditionsDatas);
    }elseif($sqlParse[0] == "UPDATE"){
        $columns  = $sqlParse[3];
        $columnsInputsCounts = substr_count($columns,"?");
        $ejectColumns = $inputsCounts - $columnsInputsCounts;

    }else{
        return $datas;
    }
}

function searchArrays($wantedKeysArray,$array = null){
    if ($array == null){
        global $data;
        $table = array_shift($wantedKeysArray);
        $array = $data[$table];
        $isAvailableData = true;
    }else{
        $isAvailableData = false;
    }
    foreach ($wantedKeysArray as $wantedKey){
        if ($isAvailableData){
            // while(){
            //
            // }
        }else{
            if (!empty($array[$wantedKey])){
                $array = $array[$wantedKey];
            }
        }
    }
    return $array;
}
function dataIndexesRemoved($table){
    global $data;
    $returnArr = array();
    foreach ($data[$table] as $arr){
        $primaryColumn = array_values($arr)[0];
        $returnArr[$primaryColumn] = $arr;
    }
    return $returnArr;
}

//akif
//$arr= array("sql'injeqsiyon0","fatma","<script>hekledim</script>");
//print_r(arrFilter($arr));
//define("DSN",array("bilgi1"));
//echo DSN[0];
//yedek
/*$columns = ((empty($this->columns))or($this->columns=="|"))?
    exit("The columns to adjust were not found."):
    $this->setUpdateColumns();*/

//YARIN BU FİND İN SET VE JOİN OLAYLARI HALLEDİLECEK

// ve yardımcı fonksiyonlar columslara eklenecek
// koşullarda all hepsi koşulu eklenecektir
// delete metodunda bağlı olan tabloları silme durumunu kolaylaştırma
// yada çoklu sql sorgusu sadece CUD sorgulurana entegre edilmesi bu read sorgularındada olabilecek bir durum
// yani multiple sorgu sistemi
// limit komutu ekle ve as takma adı ekleme de ekle
// KOLAYLAŞTIRICI UNSURLAR VARDI QUERİES.PHP DE YAZILI ONLAR HALLEDİLECEK

//yardımcı fonksiyonlardaki - muhabbeti olayını çöz (inputlara giriş ekleyebilirsin)(input sıralaması ile tam olarak çözülecektir)++
//inputların sıralaması biraz şaşırtabilir bunu düzeltmek lazım(uidatada) input dataya bir metod bulabilirizz++

//crud fonksiyolarını basit olarak dene++
//çoklu query de mesela bir read sorgusunu işledikten hemen sonra o read sorgusunun verileri ile başka bir sorgu kurulabilinsin++
//multi query dene++
//multi create sorgusu özel++
//all koşulunu dene++
//yardımcı func test et koşullar ile beraber ve soru işareti muhabetini dene++
//limits test et as test et ++
//sql (s) fonksiyonunu test et ++
//tagleme mevzusunu test et ++
//inputların sıralamasını test et ++
//kolaylaştırıcı unsurlar test et(özellikle kolay erişim veriyi 2.si(queries.phpdeki))
//tag ile data parametresini birleştir çünkü tag sadece selectte oluyor

// ARDINDAN DİĞER ACTİONLAR HALLEDİLECEK+++

//BAĞLANTI İŞİ HALLEDİLECEK mysql postagresql sqllite mssql oracledb   eklenecek bağlantı olaylarına++
//STARTTAKİ SORUNLAR ÇÖZÜLECEK++

//firstrundb eklenecek //cruddan sonra girişilecek
//joinsql için group by ve order by ekle

//avalibleDatas(for detail):
// amaçlar; mevcuta bulunan datayı detail sayfasına db ile sorgusuz şekilde ilgili satırı çekmek

// globalda bir değişken bulunacak avalibleQueries
// kullanım şekli
// $aq["deneme"] = $id; //değişken adı istenilen kolon adı ile aynı olmak zorunda


//CUD PLANLARI YAPILACAK
//CUD BİTİRİLECEK
//formbuilder yapılacak

//uidatadaki columnname ve variablename sorunlarını çöz

//bağlantı sistemleri için bir diğer veritabanı sistemlerinin ek dosya ihtiyaclarını otomatik olarak yapılması
//cruddaki ve systemdeki benzer fonksiyonların birleştirlmesi ve ordaki decodeJoinDatasın system tarafına geçirilmesi
//join sqldeki fonksiyonların paremetreleri çok tekrar ediyor tekilleştir++
//void fonksiyonlarının belrtilmesi methodlarda dahil
//KLASÖR DÜZENLENMESİ
//ui de kullanılacak metodları bir dizi de depolayacaz $tools dizisi $tools["lister"]->
//framework js ide toparla
//detail uniqe js listelemeyide yap yani uiDatadaki d fonksiyonu ile birlikte kullanılacak client taraflı detail listelemesi
//imza atılması
//Çıktılama düzeni sağlanması



/*TEST
1. JOİNSQL in crud işlemlerindeki bütün komutları tek tek denenecek
2. JOİNSQL diğer yan işlevselliği test edilecek
3. PROJE Geneli bütün crud işlemleri test edilecek
4. firstrun test edilecek mysql de
5. bütün veritabanları sistemlerinin bağlantı, firstrun, crud(joinsql de) testedilecek
7. sistemin front end bakımından tüm dinamiklerinin testi
*/


//SMOPRO-05-622367-0530-48-1120-5355-5069634281304982-C6EEEEC5EAAC9B3A3CDF87BD9580713F
