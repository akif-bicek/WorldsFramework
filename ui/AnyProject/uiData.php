<?php
class uiData{
    use crud;
    public function user(){
        global $data;
        $jsqlTest = new testjsql();
        $jsqlTest->j("deneme;navbar");
        //$jsqlTest->jw("deneme upper.metin,replace.sayi-5-0");
        /*$jsqlTest->jw("?id deneme",[8]);
        $jsqlTest->jw("?id,>sayi deneme",[8,50]);
        $jsqlTest->jw(">sayi deneme",[50]);
        $jsqlTest->jw("#metin deneme",["ipsu"]);*/
        //$jsqlTest->jw(">>id deneme",[5,3,2,1]);
        //$jsqlTest->jw("?id deneme id",[3]);
        //$jsqlTest->jw("<>id deneme",[3,6]);
        //$jsqlTest->jw("!<>id deneme",[3,6]);
        //$jsqlTest->jw("%metin deneme",["%lorem%"]);
        //$jsqlTest->jw("<-id.uyeid.uyeid uyeler,istatistikler,siparisler");
        //$jsqlTest->jw("|navbar deneme");
        //$jsqlTest->j("?id navbar text |",["akiflik",5]);
        //burda inputların sıralaması biraz şaşırtabilir bunu düzeltmek lazım
        //$jsqlTest->j("?id navbar text,link |",[2,"fatma","biçek"]);
        //$jsqlTest->j("^1 navbar text,link >",["akif","yunus"]); sorun var limit fonksiyonu yazılmadan inset into deyimine giriş yapılmıyor ayrıca limit fonksiyonu sql de gözükmedi
        //$jsqlTest->j("navbar text,link >",["akif","yunus"]);
        //$jsqlTest->j("?id navbar <",[8]);
        //$jsqlTest->jw("?id deneme;navbar",[[5],["??:deneme.id"]]);
        //$jsqlTest->jw("deneme;navbar");
        //$jsqlTest->jw(">sayi;?id deneme;navbar",[[50],[3]]);
        //$jsqlTest->jw("?id deneme;navbar ;text,link r;|",[[5],[5,"akif","uyuus"]]);
        //$jsqlTest->jw("?id deneme;navbar *;text,link r;|",[[5],[5,"akif","uyuus"]]);
        //$jsqlTest->jw("navbar text,link >",["monster","apple","php","asp"]);
        //$jsqlTest->jw("navbar text,link >",["monster","apple","php"]);//error
        //$jsqlTest->jw("all navbar text |",["akif"]);
        //$jsqlTest->jw("all test <");
        //$jsqlTest->jw("navbar replace.text-'akif'-?#AKIF",["a-k-i-f"]);
        //$jsqlTest->jw("navbar replace.text-'akif'-?",["a-k-i-f"]);
        //$jsqlTest->jw("^3,<id navbar",[5]);
        //$jsqlTest->jw("^3 navbar");
        //$jsqlTest->jw("navbar text#metin");
        //$jsqlTest->sw("select * from navbar","navbar",);
        //$jsqlTest->sw("update navbar set text=?","",["yunus"]);
        //$jsqlTest->jw("navbar",null,"navigasyon");
        //$jsqlTest->jw("deneme",null,"test");
        //$jsqlTest->j("navbar;deneme",null,"navigasyon;test");
        //$jsqlTest->jw("?id,>sayi,<id deneme",[5,50,8]);
        //$jsqlTest->jw("?id,>sayi,<id deneme replace.metin-'Lorem'-?#YUNUS",[5,50,8,"AKIF"]);
        //$jsqlTest->jw("?id,>sayi,<id deneme replace.metin-'Lorem'-?#YUNUS,replace.resim-?-?#ABI",[5,50,8,"#GTA5;","loremflickr.com","birşeyler"]);
        //$jsqlTest->j("deneme","test");
        //$jsqlTest->j("?id deneme",[5],"test");
        //$jsqlTest->j("?id deneme","akif");//hatalı ama mesaj vermez
        //$jsqlTest->j("?id deneme","akif","yunus");//error
        //arrWriter($data);
        //limit fonksiyonunda sorun var yazdırılmıyor++
        //j fonksyonunda jw dede decodeJoinDatas olayını etkinleştir null inputdatasda decodeJoinDatas (crud.php) deki sorun çıkarıyor


        /*$this->getDatas("select * from deneme","deneme");
        $this->getDatas("select * from navbar","navbar");*/
        //$data->deneme;$data["deneme"]; tabi bunu arkaplan kullanımında yapacağız listelemede sadece veritabanındaki tablo adı kullanılacak ve bir as işlemide kullanılabilir tablolar için
        //bu fonksiyon veriyi hem json hemde array modunda tutacak ama istendiğinde tekbir moddada tutulabilinecek
        //isteğe göre bu fonksiyonların çalışması gerek eval fonksiyonu bir işe yarayabilr
    }
    public function admin(){
        $this->getDatas("select * from navbar limit 2","alibar");
    }
    public function contact(){
        $this->getDatas("select * from deneme limit 1","contactsdsd");
    }
    public function member(){
        $this->getDatas("select * from deneme","deneme");
        $this->getDatas("select * from navbar","navbar");
    }
    public function detail($id){
        aq("deneme",["id",$id]);
        //$aq["deneme"] = ["id",$id];
        //aq("deneme",$id); //çalışmıyor çünkü değişken adını almayı başaramadık
    }
    /*public function detail($id){
        $this->getDatas("select * from deneme where id=?","denemeDetail",[$id]);
    }*/
    /*public function detail($id,$number){
        $aq["deneme"] = vc($id);
        $aq["deneme"] = ["sayi",$number];
        aq("deneme",$id);
        aq("deneme",["sayi",$number]);
        $aq["deneme"] = $id;
        $aq["deneme"] = $metin;
    }*/
}
?>