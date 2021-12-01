<?php
$UiHelpers->lang("en");
//if no data defaults selectedType="adder",selectedTable=""
$UiHelpers->startHead("overwrite","deneme");
?>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap" rel="stylesheet"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.css" rel="stylesheet"/>
    <?php uiStyles("site"); //bu gibi uide kullanılacak fonksiyonları methodlaştır class içerilerine aktar?>
    <title>{metin/10}</title>
<?php $UiHelpers->endHead(); ?>
<header>
    <?php require_once __DIR__."/parts/navbar.php"; ?>
    <?php require_once __DIR__."/parts/sidebar.php"; ?>
</header>
<main id="main">
    <?php require_once __DIR__."/pages/".page."/glue.php"; ?>
</main>
<footer class="bg-light text-center text-lg-start">
    <?php require_once __DIR__."/parts/footer.php"; ?>
</footer>
<?php
//if no data defaults selectedType="adder",selectedTable=""
$UiHelpers->startFoot("adder","deneme"); ?>
    {metin/10}
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/3.3.0/mdb.min.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidebar").style.width = "250px";
            document.getElementById("main").style.marginLeft = "250px";
        }
        function closeNav() {
            document.getElementById("mySidebar").style.width = "0";
            document.getElementById("main").style.marginLeft= "0";
        }
        afterDetail("deneme",function () {
            const myModalEl = document.getElementById('exampleModal')
            const modal = new mdb.Modal(myModalEl)
            modal.show()
        });
        afterDetail("deneme#second",function () {
            const myModalEl2 = document.getElementById('exampleModal2')
            const modal2 = new mdb.Modal(myModalEl2)
            modal2.show()
        });
    </script>
<?php $UiHelpers->endFoot(); ?>