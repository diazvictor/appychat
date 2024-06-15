<?php
include_once("php/config.php");

// (A) LOAD GOOGLE CLIENT LIBRARY
require("vendor/autoload.php");

// (B) NEW GOOGLE CLIENT
$goo = new Google\Client();
$goo->setClientId($googleClientId);
$goo->setClientSecret($googleClientSecret);
$goo->addScope("email");
$goo->addScope("profile");
$goo->setRedirectUri("$googleURL/home");
