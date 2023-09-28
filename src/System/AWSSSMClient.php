<?php
namespace Src\System;

use Aws\Acm\Exception\AcmException;
use Aws\Ssm\SsmClient; // Import the ACM client class

class AWSSSMClient
{
    private $ssmClient = null;
    
    public function __construct() {
        try {
            $this->ssmClient = new SsmClient([
                'region' => 'us-east-1',
                'version' => 'latest',
                'credentials' => [
                    'key' => $_SERVER['AWS_ACCESS_KEY_ID'],
                    'secret' => $_SERVER['AWS_SECRET_ACCESS_KEY'],
                ],
            ]);
        } catch (AcmException $e) {
            echo $e->getAwsErrorMessage();
        }   
    }

    public function getSsmClient()
    {
        return $this->ssmClient;
    }
}