<?php
require "../bootstrap.php";
use Src\Controller\AWSUtilityController;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: OPTIONS,GET,POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$request = $_SERVER['REQUEST_URI'];

// pass the request method and user ID to the PersonController and process the HTTP request:
$controller = new AWSUtilityController($acmClient, $request, $client, $route53, $ELBClient, $ssmClient);
$controller->processRequest();