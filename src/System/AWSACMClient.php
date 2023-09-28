<?php
namespace Src\System;

use Aws\Acm\Exception\AcmException;
use Aws\Acm\AcmClient; // Import the ACM client class

class AWSACMClient {

    private $acmClient = null;
    
    public function __construct() {
        try {
            $this->acmClient = new AcmClient([
                'version' => 'latest',
                'region' => 'us-east-1', // Change to your desired region
                'credentials' => [
                    'key' => $_SERVER['AWS_ACCESS_KEY_ID'],
                    'secret' => $_SERVER['AWS_SECRET_ACCESS_KEY'],
                ],
            ]);
        } catch (AcmException $e) {
            echo $e->getAwsErrorMessage();
        }   
    }

    public function getAcmClient()
    {
        return $this->acmClient;
    }

}