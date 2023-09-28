<?php
namespace Src\System;

use Aws\Exception\AwsException;
use Aws\Route53\Route53Client;

// Set up the Route53 client

class AWSRoute53 {

    private $route53 = null;
    
    public function __construct() {
        try {
            $this->route53 = new Route53Client([
                'region' => 'us-east-1', // Replace with your region
                'version' => 'latest',
                'credentials' => [
                    'key' => $_SERVER['AWS_ACCESS_KEY_ID'],
                    'secret' => $_SERVER['AWS_SECRET_ACCESS_KEY'],
                ],
            ]);
        } catch (AwsException $e) {
            echo $e->getAwsErrorMessage();
            exit;
        }   
    }

    public function getRoute53()
    {
        return $this->route53;
    }

}