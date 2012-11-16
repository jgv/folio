<?php
error_reporting(E_ALL);
require_once(dirname(__FILE__) . "/src/folio.php");
$folio = new Folio();
$folio->load_app();
?>
