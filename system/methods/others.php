<?php
class DeveloperTools{

}
class DataBuilder{
    public $db;
    public $table;
    public $addedRowCount;
    public $dataTypes;
    public function __construct($db,$table,$addedRowCount)
    {
        $this->db = $db;
        $this->table = $table;
        $this->addedRowCount = $addedRowCount;
    }
    public function dataTypes()
    {
        $this->dataTypes = func_get_args();
    }
    public function dataBuilder()
    {
        $columns = func_get_args();
        $db = $this->db;
        //buralar değişecek zaten crud methodları ile
        $columnName = "";
        $values = "";
        foreach ($columns as $column){
            $columnName .= $column.",";
            $values .= "?,";
        }
        $columnName = substr($columnName, 0, -1);
        $values = substr($values, 0, -1);
        $createSql = "insert into ".$this->table." (".$columnName.") values(".$values.")";
        //echo $createSql;
        for ($i=0;$i<$this->addedRowCount;$i++){
            $datas = array();
            foreach ($this->dataTypes as $type){
                $datas[] = $this->dataBuild($type);
            }
            $result = $db->prepare($createSql);
            $result->execute($datas);
            //print_r($jsDatas);
            print_r($result->rowCount());
        }
    }
    private function dataBuild($type){
        $type = explode("*",$type);
        $length = $type[1];
        if (($type[0]=="w")or($type[0]=="s")or($type[0]=="p")){
            $data = $this->lorem($type[0],$length);
        }elseif($type[0]=="i"){
            $data = mt_rand(0,$length);
        }elseif ($type[0]=="d"){
            $data = mt_rand (0*10, $length*10) / 10;
        }elseif ($type[0]=="v"){
            $data = $this->getPicture();
        }elseif($type[0] == "u"){
            //benzersiz string oluşturulacak
        }elseif($type[0]=="w"){
            $data = readableRandomString($length);
        }
        return $data;
    }
    private function getPicture(){
        $lock = rand(0,1000);
        $i = rand(0,1);
        $server = array("loremflickr.com","picsum.photos");
        $data = "https://".$server[$i]."/800/600?lock=$lock";
        return $data;
    }
    private function lorem($type,$length){
        require_once "system/materials/frameworks/lipsum.php";
        $lipsum = new joshtronic\LoremIpsum();
        switch ($type[0]) {
            case "w":
                $data = $lipsum->words($length);
                break;
            case "s":
                $data = $lipsum->sentences($length);
                break;
            case "p":
                $data = $lipsum->paragraphs($length);
                break;
            default:
                echo "Your favorite color is neither red, blue, nor green!";
        }
        return $data;
    }
    private function readableRandomString($length = 6)
    {
        $string = '';
        $vowels = array("a","e","i","o","u");
        $consonants = array(
            'b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'l', 'm',
            'n', 'p', 'r', 's', 't', 'v', 'w', 'x', 'y', 'z'
        );

        $max = $length / 2;
        for ($i = 1; $i <= $max; $i++)
        {
            $string .= $consonants[rand(0,19)];
            $string .= $vowels[rand(0,4)];
        }

        return $string;
    }
}
?>

