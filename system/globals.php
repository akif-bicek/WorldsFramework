<?php
$db = Database::getConnection();
$data = array();
$lister = new Lister();
$UiHelpers = new UiHelpers();
$language = "";
$head = "";
$ui = "";
$foot = "";
$ipWithHashAndTime = getIp(true,true);
$aq = array();
?>