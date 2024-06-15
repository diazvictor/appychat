<?php
include_once("php/config.php");

// (A) LOAD GOOGLE CLIENT LIBRARY
require("vendor/autoload.php");

// (B) NEW GOOGLE CLIENT
$goo3 = new Google\Client();
$goo3->setClientId($googleClientId);
$goo3->setClientSecret($googleClientSecret);
$goo3->addScope("email");
$goo3->addScope("profile");
$goo3->setRedirectUri("$googleURL/configuracion");
