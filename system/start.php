<?php
    ob_start();
    require_once "functions.php";
    requires("methods/");
    require_once "globals.php";
    uiImport();
?>
<!doctype html>
<html lang="<?php echo $language; ?>">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport"
              content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <?php
            writeBase();
            echo $head;
            importSystemCss();
        ?>
    </head>
    <body>
        <?php echo $ui.$foot; ?>
    </body>
</html>
<?php ob_end_flush();
//***ui dataya tag olayı getirlecek ve tekil işlem var ise ve zaten o veriler getirilmiş ise ordan çekilmesi için kısa bir yol bulunmalı
?>