<?php
namespace Src\System;

use Throwable;
use GuzzleHttp\Exception\ConnectException;

class GuzzleClient {

    private $client = null;

    public function __construct() {
        $this->client = new \GuzzleHttp\Client();
    }

    public function getClient()
    {
        return $this->client;
    }

}