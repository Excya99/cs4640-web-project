<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

spl_autoload_register( function($classname) {
    include "Classes/$classname.php";
});

session_start();

$command = "login";
if (isset($_GET["command"])) {
    $command = $_GET["command"];
}

// Using cookies here because cookies eventually expire which acts as a temporary security feature
if ($command!="signup" && !isset($_COOKIE["email"])) {
    $command = "login";
}

$BFinancial = new BFinancialController($command);
$BFinancial->run();