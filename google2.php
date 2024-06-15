<?php
include_once("php/config.php");

// (A) LOAD GOOGLE CLIENT LIBRARY
require("vendor/autoload.php");

// (B) NEW GOOGLE CLIENT
$goo2 = new Google\Client();
$goo2->setClientId($googleClientId);
$goo2->setClientSecret($googleClientSecret);
$goo2->addScope("email");
$goo2->addScope("profile");
$goo2->setRedirectUri("$googleURL/home?register");

