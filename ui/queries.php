<?php
class uiData{
    use crud;
    public function consept1(){
        $this->getDatas($q->select("test"));//select * from test;
        $this->getDatas($q->who(5,"id")->select("test"));//select * from test where id=?; [5];
        $this->getDatas($q->select("test","column1,column2"));//select column1,column2 from test;
        $this->getDatas($q->whoies("5,2,3,1","id")->select("test"));//select * from test where (id) in (?); ["5,2,3,1"];
        $this->getDatas($q->);
    }
    //sql sorgusu İŞLEM HANGİKOLONLAR TABLO KOŞUL consept2
    public function consept2(){
        $this->getDatas("test");//select * from test;
        $this->getDatas("test",["id"],[$id]);//select * from test where id=?; [5];
        $this->getDatas("test column1,column2");//select column1,column2 from test;
        $this->getDatas("test",["id>>"],["5,2,3,1"]);//select * from test where (id) in (?); ["5,2,3,1"];
        $getData[] = "test";
    }
    public function create(){
        $this->getDatas("c:test name,surname",[$text,$text]);//insert into test (name,surname) values(?,?)
    }
    public function delete(){
        $this->getDatas("d:test",[$text,$text]);//delete form test where id = ?
    }
    public function update(){
        $this->getDatas("u:test",[$text,$text]);//insert into test (name,surname) values(?,?)
    }
    //conspet 2 end
    //consepts
    public function user(){
        $db["test"];//select * from test
        $db["test"] = wh("id",[$id]);//select * from test where id=?; [5];
        $db["test"]("wh","id",[$id]);
        $dn["test"](wh("id",[$id]).bs("sdsa"));
        $db["test"]("wh id",[$id]);
        $db["test"]["wh id"][$id];
        dbs("test","wh id",[$id]);
        dbs("vh id test",[$id]);
        dbs("?id test colon1,colon2,conol3");
        q("?id,>age");//and
        q("?id*>age");//or
        q("?id,>age(?id*<age(?id,<age))");//multi
    }
    public function lastconspet(){
        // q fonksiyonuna ek olaak üç farklı özellik eklenecek
        // 1.si tagleme etiketleme aynı tablo adına ait başka bir tabloyu farklı bir isim ile etiketleme
        // 2.si zaten var olan verilerden ilgili sütünü çekebilme yani diyelim user da zaten çekilen veriyi başka bir sayfada bir satırını tek kullanacakisek onun için tekrar veritabnına bağlanmaksızın veriye ulaşılabilinmesi
        // 3.sü normal olarak sql cümleciklerinin girilmesi
        //not hiçbirzaman boş değer göndermek yok boş olması gereken yere null yazılması gerek
        $this->q("KOŞULLAR TABLO KOLONLAR İŞLEM");//İŞLEMLER: < delete, > create(insert), | update, belitrilmemiş boş ise read(select)
        $this->q("test");//select * from test
        $this->q("?id test",array($id));//select * from test where id=?; [5];
        $this->q("?id test colon1,colon2,conol3",array($id));//select column1,column2 from test;
        $this->q(">>id test",["5,4,3,2,1"]);//select * from test where (id) in (?); ["5,2,3,1"];
        $this->q(">>id.col1.texts test",["5,4,3,2,1"]);//select * from test where (id,col1,texts) in (?); ["5,2,3,1"];
        $this->q("%id test",["%$text%$retext"]);// select * from test where like ?
        $this->q("<->id.uyeid.uyeid uyeler,istatistikler,siparisler");// SELECT * FROM uyeler INNER JOIN istatistikler ON uyeler.id = istatistikler.uyeid INNER JOIN sipariler ON uyeler.id = sipariler.uyeid
        $this->q("?id,<->id.uyeid.uyeid uyeler,istatistikler,siparisler",[$id]);// SELECT * FROM uyeler INNER JOIN istatistikler ON uyeler.id = istatistikler.uyeid INNER JOIN sipariler ON uyeler.id = sipariler.uyeid where id = ?
        $this->q("<-id.uyeid uyeler,siparisler");//SELECT * FROM uyeler LEFT JOIN sipariler ON uyeler.id = sipariler.uyeid
        $this->q("<->id.uyeid.uyeid uyeler,istatistikler,siparisler");
        $this->q("?id test column1,column2,column3 |",[5,"akif","yunus","deneme"]);
        $this->q("?id test <",[5]);
        $this->q("test column1,column2 >",["akif","yunus"]);
        $this->s("select * from",[5]);
        // select * from table koşullar
        // işlem kolonlar table koşullar n
        // koşullar table kolonlar işlem j
        // [d,a,s,c,v,b]


        // SELECT * FROM uyeler
        // INNER JOIN istatistikler ON uyeler.id = istatistikler.uyeid
        // INNER JOIN sipariler ON uyeler.id = sipariler.uyeid
        // INNER JOIN jt ON t.pk = jt.fk
        /*
        KOŞULLAR: (id is an example)
        KOŞULSUZ DİĞER İŞLEMLER HALLET altakileri halletikten ssonta tabisi
        ? : id = ?
        >> : where id in  (?)
        !>> : where id in  (?)
        ! : where id != ?
        < : where id < ?
        > : where id > ?
        <= : where id <= ?
        >= : where id >= ?
        % : where id like
        !% : where id not like
        #: where find_in_set(?, id)
        !#: where not find_in_set(?, id)
        <> :where id beetween ? and ?
        !<> : where id not between ? and ?
        <-> : inner join
        <- : left join
        -> : right join
        | : natural join
        */
        q("test f.column");
        q("test fp.column-column2-column3");
        q("test ascii.column1,char.colum1,CHAR.colum1");

        q("test replace.colum1-0--1");
        q("test replace.colum1-'akif'-'a-k-i-f'");
        q("test replace.colum1-'akif'-?",["a-k-i-f"]);
        //yukardaki - olayı sistem ile çakışmaktadır çöz
        /*
        FUNCS
        KARAR Fonskiyon ismi küçük olarak girilir ve bir nokta bırakılır
        MULTİPLE QUERIES
        */
        q("test,test2");//Read this normal
        q("test;test2");//Read this multiple: select * from test1 ; select * from test2; multi runner
        q("?id test;test2");// aynı colonlar koşulda
        q("?id;?sayi test;test2"); // farklı kolonlar koşulda
        q("?id;?sayi test;test2 ascii.column1"); //aynı colonlar colonda
        q("?id;?sayi test;test2 ascii.column1;char.column2"); //farklı colonlar colonda
        q("?id test;deneme col1;col2 r;|");//farklı işlemler
        q("?id test;deneme col1;col2 r;|",[[$d1,$d2,$d3],[$d1,$d2,$d3]],"tag1;tag2;tag3");

        //AS(TAKMA AD)
        q("test column1#NEWNAME"); //select column1 as NEWNAME from test
        //LIMIT
        q("^5 test"); // select * from test limit 5
        //multiple create queries
        q("deneme col1,col2,col3 >",[["akif","yunus"],["yunus0","fatma"],["test","test"]]);
        q("deneme col1,col2,col3 >",["akif","yunus","fehime","akif","yunus","fehime"]);
        //insert into deneme (col1,col2,col3) values(
        //"akif","yunus","fehime","akif","yunus","fehime")
        //created: 2 rows


        //

    }
    //yedek
    private function jointSqlParser($jointSqlCode){
        $jointSqlParse = explode(" ",$jointSqlCode);
        $this->actionType = (empty($jointSqlParse[3]))?"":$jointSqlParse[3];
        $this->columns = $jointSqlParse[2];
        $this->tables = $jointSqlParse[1];
        $this->conditions = $jointSqlCode[0];
    }
}
?>