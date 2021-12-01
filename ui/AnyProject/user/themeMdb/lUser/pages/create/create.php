<?php
//create olayları
/*
AMAÇLAR;
sadece html form kullanılarak birden fazla tabloya
birbiriyle bağlantılı olacak şekildede kayıt girdisi yapabilmek

form göndermeden önce olayını js de kodlamak

consept a
 5 ayrı tablo var ve bu tablolara birbirleriyle bağlantılı veri yazdırılacak
 bağımsız ama çoklu veri kaydetme
 tekil tablo kaydetme
*/
?>
<form method="post/get" id="adderTest(for afterevent)" class="adder"
      data-ajax="false/def:true" data-tables="test1;test2;test3;test4;test5">
    <label>First name:</label><br>
    <input type="text" name="0.column1/test1.column1" ><br>
    <label for="lname">Last name:</label><br>
    <input type="text" name="1.column2" ><br><br>

    <label>First name:</label><br>
    <input type="text" name="0.column1/test1.column1" ><br>
    <label for="lname">Last name:</label><br>
    <input type="text" name="1.column2" ><br><br>

    <label>First name:</label><br>
    <input type="text" name="0.column1/test1.column1" ><br>
    <label for="lname">Last name:</label><br>
    <input type="text" name="1.column2" ><br><br>

    <label>First name:</label><br>
    <input type="text" name="0.column1/test1.column1" ><br>
    <label for="lname">Last name:</label><br>
    <input type="text" name="1.column2" ><br><br>

    <label>First name:</label><br>
    <input type="text" name="0.column1/test1.column1" ><br>
    <label for="lname">Last name:</label><br>
    <input type="text" name="1.column2" ><br><br>
    <input type="submit" value="Submit">
</form>