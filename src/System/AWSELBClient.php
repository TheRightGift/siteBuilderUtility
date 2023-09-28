<?php
namespace Src\System;

use Aws\Exception\AwsException;
use Aws\ElasticLoadBalancing\ElasticLoadBalancingClient;

// Set up the Route53 client

class AWSELBClient {

    private $ELBClient = null;
    
    public function __construct() {
        try {
            $this->ELBClient = new ElasticLoadBalancingClient([
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

    public function getELBClient()
    {
        return $this->ELBClient;
    }

}