<?php

namespace Src\Controller;
use voku\db\DB;
use Src\API\AWSUtility;

class AWSUtilityController
{
    private $acmClient;
    private $request;
    private $awsUtilityGateway;
    private $client;
    private $route53;
    private $ELBClient;
    private $ssmClient;

    public function __construct($acmClient, $request, $client, $route53, $ELBClient, $ssmClient)
    {
        $this->acmClient = $acmClient;
        $this->request = $request;
        $this->client = $client;
        $this->route53 = $route53;
        $this->ELBClient = $ELBClient;
        $this->ssmClient = $ssmClient;

        $this->awsUtilityGateway = new AWSUtility($acmClient, $client, $route53, $ELBClient, $ssmClient);
    }

    public function processRequest()
    {
        switch ($this->request) {
            case '/createzone':
                $response = $this->createZone();
                break;
            case '/sendcommand':
                $response = $this->sendCommand();
                break;
            case '/listcommands':
                $response = $this->listcommands();
                break;
            case '/getzones':
                $response = $this->getcreatedZone();
                break;
            case '/createdomainforwarding':
                $response = $this->createDomainForwarding();
                break;
            case '/createTemplate':
                $response = $this->createTemplate();
                break;
            case '/allTemplates':
                $response = $this->allTemplates();
                break;
            case '/createStore':
                $response = $this->createStore();
                break;
            case '/storeTypeHasTemplate':
                $response = $this->storeTypeHasTemplate();
                break;
            case '/updateThemeColor':
                $response = $this->updateThemeColor();
                break;
            case '/deleteStoreDirectory':
                $response = $this->deleteStoreDirectory();
                break;
            case '/runJScompileCommand':
                $response = $this->runJScompileCommand();
                break;            
            default: header("HTTP/1.1 404 Not Found");
            exit();
        }
        // header($response['status_code_header']);
        // if ($response['body']) {
        //     echo $response['body'];
        // }
    }

    private function dbConnection(){
        $db = DB::getInstance('localhost', 'root', '', 'wcdawsutility');
        // $db = new mysqli('localhost', 'root', '', 'wcdawsutility');
        // if($db->connect_errno)
        // {
        //     die('Sorry! Could not connect to database at the moment. Try later.');
        // }

        return $db;
    }

    private function cleanInput($data) {
        // Address Magic Quotes.
       if (ini_get('magic_quotes_gpc')) {
       $data = stripslashes($data);
       }
       // Check for mysql_real_escape_string() support.
       if (function_exists('mysql_real_escape_string')){
          global $db; // Need the connection.
          $data = mysqli_real_escape_string ($db, trim($data));
       } else {
          $data = mysqli_real_escape_string ($db, trim($data));
       }
       // Return the escaped value.    
       return $data;
    }


    private function createTemplate() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $db = $this->dbConnection();
            // $result = $db->query("SELECT * FROM templates");
            // $templates  = $result->fetchAll();
            // echo COUNT($templates);
        } else {
            echo 'No';
        }
    }

    private function allTemplates() {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            $db = $this->dbConnection();
            $result = $db->query("SELECT * FROM templates");
            $templates  = $result->fetchAll();

            return json_encode($templates);
        } else {
            echo 'No';
        }
    }

    // private function template() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    //         $templateId = htmlspecialchars($_GET["id"]);
    //         if(isset($templateId) && is_numeric($templateId)){
    //             $where = [
    //                 'id =' => $templateId
    //             ];

    //             $template = $db->select('templates', $where);

    //             if(count($template < 1)){
    //                 return 'Template with the provided template ID does not exist.';
    //             } else {
    //                 return json_encode($template);
    //             }
    //         } else {
    //             return 'Expecting the parameter id to have numeric value.';
    //         }
    //     } else {
    //         echo 'No';
    //     }        
    // }

    // private function updateTemplate() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'PUT' || $_SERVER['REQUEST_METHOD'] === 'PATCH') {
    //         echo 'Yes';
    //     } else {
    //         echo 'No';
    //     }
    // }

    // private function deleteTemplate() {
    //     if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    //         echo 'Yes';
    //     } else {
    //         echo 'No';
    //     }
    // }
    private function updateThemeColor(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST; //(array) json_decode(file_get_contents('php://input'), true);
            
            if (count($input) < 1 || count($this->validateStoreThemeColorUpdateData($input)) > 0) {
                return $this->unprocessableStoreCreationResponse($this->validateStoreThemeColorUpdateData($input));
            } 

            $result = $this->awsUtilityGateway->updateStoreThemeColor($input);
            $response['body'] = json_encode($result);
            // print_r($response);
            return $response;
        } else {
            echo json_encode(['status' => 404, 'message' => 'This endpoint does not support '.$_SERVER['REQUEST_METHOD'].' requests.']);
        }
    }

    private function deleteStoreDirectory(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST; //(array) json_decode(file_get_contents('php://input'), true);
            
            if (count($input) < 1 || count($this->validateStoreDeleteData($input)) > 0) {
                return $this->unprocessableStoreCreationResponse($this->validateStoreDeleteData($input));
            } 

            $result = $this->awsUtilityGateway->deleteStoreDirectory($input);
            $response['body'] = json_encode($result);
            // print_r($response);
            return $response;
        } else {
            echo json_encode(['status' => 404, 'message' => 'This endpoint does not support '.$_SERVER['REQUEST_METHOD'].' requests.']);
        }
    }

    private function runJScompileCommand(){
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            
            $result = $this->awsUtilityGateway->runJScompileCommand();
            $response['body'] = json_encode($result);
            
            return $response;
        } else {
            echo json_encode(['status' => 404, 'message' => 'This endpoint does not support '.$_SERVER['REQUEST_METHOD'].' requests.']);
        }
    }

    private function createStore(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST; //(array) json_decode(file_get_contents('php://input'), true);
            
            if (count($input) < 1 || count($this->validateStoreCreationData($input)) > 0) {
                return $this->unprocessableStoreCreationResponse($this->validateStoreCreationData($input));
                // exit();
            } 

            $result = $this->awsUtilityGateway->createStoreModule($input);
            $response['body'] = json_encode($result);
            // print_r($response);
            return $response;
        } else {
            echo json_encode(['status' => 404, 'message' => 'This endpoint does not support '.$_SERVER['REQUEST_METHOD'].' requests.']);
        }
        
    }

    private function storeTypeHasTemplate(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = $_POST;
            $result = $this->awsUtilityGateway->storeTypeHasTemplate($input);
            $response['body'] = json_encode($result);
            return $response;
        } else {
            echo json_encode(['status' => 404, 'message' => 'This endpoint does not support '.$_SERVER['REQUEST_METHOD'].' requests.']);
        }
    }

    private function listcommands(){
        $result = $this->awsUtilityGateway->listCommand();
        $response['body'] = json_encode($result);
        print_r($response);
    }

    private function validateStoreThemeColorUpdateData($input){
        $errorMsg = [];

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['oldThemeColor']) || empty($input['themeColor'])  || !is_string($input['themeColor'])){
            array_push($errorMsg, 'Store theme color is required and must be a string.');
        }

        if(!isset($input['newThemeColor']) || empty($input['themeColor'])  || !is_string($input['themeColor'])){
            array_push($errorMsg, 'Store theme color is required and must be a string.');
        }

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['storeDirectoryName']) || empty($input['storeDirectoryName'])  || !is_string($input['storeDirectoryName'])){
            array_push($errorMsg, 'Store directory name is required and must be a string.');
        }
        
        return $errorMsg;
    }

    private function validateStoreDeleteData($input){
        $errorMsg = [];

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['storeDirectoryName']) || empty($input['storeDirectoryName'])  || !is_string($input['storeDirectoryName'])){
            array_push($errorMsg, 'Store directory name is required and must be a string.');
        }
        
        return $errorMsg;
    }

    private function validateStoreCreationData($input){
        $errorMsg = [];

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['storeName']) || empty($input['storeName'])  || !is_string($input['storeName'])){
            array_push($errorMsg, 'Store name is required and must be a string.');
        }

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['themeColor']) || empty($input['themeColor'])  || !is_string($input['themeColor'])){
            array_push($errorMsg, 'Store theme color is required and must be a string.');
        }

        //must be text only
        // clean input using $this->cleanInput()
        if(!isset($input['storeDirectoryName']) || empty($input['storeDirectoryName'])  || !is_string($input['storeDirectoryName'])){
            array_push($errorMsg, 'Store directory name is required and must be a string.');
        }

        //must be numeric only
        // clean input using $this->cleanInput()
        if(!isset($input['storeTypeId']) || empty($input['storeTypeId'])  || !is_numeric($input['storeTypeId'])){
            array_push($errorMsg, 'Store type is required and must be an integer.');
        }
        
        return $errorMsg;
    }

    private function createZone()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateDomain($input)) {
            return $this->unprocessableEntityResponse();
        }
        $result = $this->awsUtilityGateway->createHostedZone($input);
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createDomainForwarding()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateDomain($input)) {
            return $this->unprocessableEntityResponse();
        }
        $result = $this->awsUtilityGateway->createDomain($input);
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getcreatedZone()
    {
        // $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        // if (! $this->validateDomain($input)) {
        //     return $this->unprocessableEntityResponse();
        // }
        $result = $this->awsUtilityGateway->listCommand([]);
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function sendCommand()
    {
        $input = (array) json_decode(file_get_contents('php://input'), true);
        if (! $this->validateDomain($input)) {
            return $this->unprocessableEntityResponse();
        }
        $result = $this->awsUtilityGateway->sendCommand($input);
        // $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function validateDomain($input)
    {
        if (! isset($input['DomainName'])) {
            return false;
        }
        return true;
    }

    private function unprocessableStoreCreationResponse($errorMsg)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => $errorMsg
        ]);
        return $response;
    }

    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }



    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}
