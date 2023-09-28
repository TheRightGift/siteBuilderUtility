<?php

require 'vendor/autoload.php';
use Dotenv\Dotenv;
use Src\System\AWSRoute53;
use Src\System\AWSACMClient;
use Src\System\AWSELBClient;
use Src\System\AWSSSMClient;
use Src\System\GuzzleClient;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->safeLoad();
$acmClient = (new AWSACMClient())->getAcmClient();
$client = (new GuzzleClient())->getClient();
$route53 = (new AWSRoute53())->getRoute53();
$ELBClient = (new AWSELBClient())->getELBClient();
$ssmClient = (new AWSSSMClient())->getSsmClient();
// test code, should output:
// api://default
// when you run $ php bootstrap.php
// echo getenv('AWS_TEST');