<?php

namespace Src\API;

use Aws\Exception\AwsException;
use GuzzleHttp\Exception\RequestException;

class AWSUtility
{
    private $acmClient = null;

    private $client = null;

    private $route53 = null;

    private $ELBClient = null;

    private $ssmClient = null;

    public function __construct($acmClient, $client, $route53, $ELBClient, $ssmClient)
    {
        $this->acmClient = $acmClient;

        $this->client = $client;

        $this->route53 = $route53;

        $this->ELBClient = $ELBClient;

        $this->ssmClient = $ssmClient;
    }

    private function uniqueNumber($domainName)
    {
        $prefix = $domainName;
        $more_entropy = true;

        $unique_id = uniqid($prefix, $more_entropy);
        return $unique_id;
    }

    /**
     * Makes request to namesilo.net to Add new DNS records for
     * AWS to validate ownership of Domain
     * Relates to issuing certificate DNS challenge
     *
     * @param Object $record
     * @return void
     */
    public function addDNSRecord(array $record, String $hostedZoneId) {
        // echo $record['ResourceRecord']['Value'], $record['ResourceRecord']['Type'], $record['ResourceRecord']['Name'];
        $cnameRecord = [
            'Action' => 'CREATE',
            'ResourceRecordSet' => [
                'Name' => $record['ResourceRecord']['Name'],
                'Type' => 'CNAME',
                'SetIdentifier' => 'cname-record',
                'Weight' => 100,
                'TTL' => 300,
                'ResourceRecords' => [
                    [
                        'Value' => $record['ResourceRecord']['Value'],
                    ]
                ]
            ]
        ];
        $changeBatch = [
            'Comment' => "Create {$record->Type} {$record->ValidationDomain}",
            'Changes' => [
                $cnameRecord,
            ]
        ];
        try {
            // Create the records in Route53
            $result = $this->route53->changeResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
                'ChangeBatch' => $changeBatch
            ]);
            echo 'Resquest is Successful -> ' . $result;
        } catch (AwsException $th) {
            echo 'Error with the requested method ' . $th->getMessage();
            exit;
        }
    }

    public function setUpVendorWebsite($input){
        $projectIp = $input['projectIp'];
        $projectDir = $input['projectDir'];
        $email = $input['email'];
        $domain = $input['domainName'];
        $stripeId = $input['stripeId'];
        $websiteCreationId = $input['websiteCreationId'];
        

        $domainPurchaseRes = $this->purchaseDomainFromNameSilo($domain);
                    
        if($domainPurchaseRes['status'] === 200){
            $createdZoneData = ["DomainName" => $domain, "projectIp" => $projectIp, "projectDir" => $projectDir, "websiteCreationId" => $websiteCreationId, "email" => $email];
            $createHostedZoneRes = $this->createHostedZone($createdZoneData);  
            return $createHostedZoneRes;
        } else {
            // Send mail to goziechukwu@gmail.com
            $mailData = [
                "msg" => 'Domain purchase failed for '.$domain
            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
        }
    }

    /**
     * Creates a Hosted Zone on Route 53
     *
     * @param Array $input
     * @return void
     */
    public function createHostedZone(array $input) {
        
        // Create DB with domainame and caller_refernce
        $domainName = $input['DomainName'];
        $projectIp = $input['projectIp'];
        $projectDir = $input['projectDir'];
        $websiteCreationId = $input['websiteCreationId'];
        $email = $input['email'];
        // websiteCreationId
        $params = [
            'Name' => $domainName,
            'CallerReference' => $this->uniqueNumber($domainName),
            'HostedZoneConfig' => [
                'Comment' => 'My hosted zone for my domain',
            ],
        ];
        
        try {

            // Create the hosted zone
            $result = $this->route53->createHostedZone($params);

            // Get the ID of the new hosted zone
            $hostedZoneId = $result->get('HostedZone')['Id'];

            // Get the name servers of the new hosted zone
            $nameServers = $result->get('DelegationSet')['NameServers'];
            
            if(count($nameServers) > 0){
                $res =  $this->changeResourceRecords($domainName, $hostedZoneId, $projectIp, $projectDir, $websiteCreationId, $email);
                return $res;
            } else {
                // Send email to gozie
                $mailData = [
                    "msg" => 'Route53 hosted zone couldnt be created for '.$domainName

                ];
                $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            }
        } catch (AwsException $e) {
            // Handle any errors that occur
            $error['status'] = 501;
            $error['message'] = 'Error creating hosted zone and updating DNS: ' . $e->getMessage();
            return $error;
        }
    }

    
    

    /**
     * Creates an A record on the Amazon Route 53
     * This points to the ipaddress of the parent site.
     *
     * @param String $domainName
     * @param String $hostedZoneId
     * @return void
     */
    public function changeResourceRecords(String $domainName, String $hostedZoneId, String $projectIp, String $projectDir, String $websiteCreationId, String $email) {
        try {
            $awwwRecord = [
                'Action' => 'CREATE',
                'ResourceRecordSet' => [
                    'Name' => 'www.' . $domainName,
                    'Type' => 'A',
                    'SetIdentifier' => 'aww-record',
                    'TTL' => 300,
                    'Weight' => 100,
                    'ResourceRecords' => [
                        [
                            'Value' => $projectIp,
                        ]
                    ]
                ]
            ];

            $aRecord = [
                'Action' => 'CREATE',
                'ResourceRecordSet' => [
                    'Name' => $domainName,
                    'Type' => 'A',
                    'SetIdentifier' => 'a-record',
                    'TTL' => 300,
                    'Weight' => 100,
                    'ResourceRecords' => [
                        [
                            'Value' => $projectIp,
                        ]
                    ]
                ]
            ];
            $aWildRecord = [
                'Action' => 'CREATE',
                'ResourceRecordSet' => [
                    'Name' => '*.' . $domainName,
                    'Type' => 'A',
                    'SetIdentifier' => 'awild-record',
                    'TTL' => 300,
                    'Weight' => 100,
                    'ResourceRecords' => [
                        [
                            'Value' => $projectIp,
                        ]
                    ]
                ]
            ];
            $changeBatch = [
                'Comment' => 'Create A records for domain',
                'Changes' => [
                    $aRecord,
                    $awwwRecord,
                    $aWildRecord
                ]
            ];

            // Create the records in Route53
            $result = $this->route53->changeResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
                'ChangeBatch' => $changeBatch
            ]);
        } catch (AwsException $e) {
            $mailData = [
                "msg" => 'Error Updating Hosted zone resource records for $domainName: ' . $e->getAwsErrorMessage()

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
        } finally {
            if ($result) {
                // $changeId = $result->get('ChangeInfo')['Id'];
                // $this->route53->waitUntil('ResourceRecordSetsChanged', [
                //     'Id' => $changeId,
                //     'WaiterConfig' => [
                //         'Delay' => 5, // time delay between each request
                //         'MaxAttempts' => 6, // maximum number of attempts to make
                //     ],
                // ]);
                $arr = ["DomainName" => $domainName, "projectDir" => $projectDir, "websiteCreationId" => $websiteCreationId, "email" => $email];
                $sendCommandRes = $this->sendCommand($arr);
                return $sendCommandRes;
            }
        }
    }

    /**
     * Changes the nameserver on namesilo
     * Points NS resords to the Route 53 Hosted Zone
     *
     * @param String $domainName
     * @param Array $nameServers
     * @return void
     */
    public function changeNameServers(array $input) {
        $domainName = $input['domainName'];
        $nameServers = $input['nameServers'];
        $key = $_SERVER['NAMESILO_API_KEY'];
        
        // Construct the nameserver parameters dynamically
        $nsParams = [];
        foreach ($nameServers as $index => $ns) {
            $nsParams["ns" . ($index + 1)] = $ns;
        }
        
        try {
            $params = http_build_query(array_merge([
                'version' => 1,
                'type' => 'xml',
                'key' => $key,
                'domain' => $domainName
            ], $nsParams));
    
            $URL = "https://www.namesilo.com/api/changeNameServers?" . $params;
            $response = $this->client->request('GET', $URL);
            $body = $response->getBody();
            $xml = simplexml_load_string($body);
            
            if ((string)$xml->reply->detail === 'success' && (int)$xml->reply->code === 300) {
                return [
                    'status' => 200,
                    'message' => 'success'
                ];
            } else {
                return json_decode(json_encode($xml), true);
            }
        } catch (\Throwable $th) {
            return [
                'error' => $th->getMessage()
            ];
        }
    }


    /**
     * Creates a Server block to the nginx available-sites
     * Initiates a certbot command
     * And append the ssl path block
     *
     * @param Array $input
     * @return void
     */
    public function sendCommand(array $input) {
        $domain = $input['DomainName'];
        $projectDir = $input['projectDir'];
        $websiteCreationId = $input['websiteCreationId'];
        $email = $input['email'];
        
        $uri = '$uri';
        $query_string = '$query_string';
        $realpath_root = '$realpath_root';
        $fastcgi_script_name = '$fastcgi_script_name';
        $command = "sudo nano $domain
            echo 'server {
                listen 80;
                server_name $domain www.$domain;
                root /var/www/$projectDir/public;
                
                add_header X-XSS-Protection \"1; mode=block\";
                add_header X-Content-Type-Options \"nosniff\";
                index index.html index.htm index.php;
                charset utf-8;
                location / {
                    try_files $uri $uri/ /index.php?$query_string;
                }
                location = /favicon.ico { access_log off; log_not_found off; }
                location = /robots.txt  { access_log off; log_not_found off; }
                error_page 404 /index.php;
                location ~ \\.php$ {
                    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
                    fastcgi_index index.php;
                    fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
                    include fastcgi_params;
                }
                location ~ /\\.(?!well-known).* {
                    deny all;
                }
            }' >>  /etc/nginx/sites-available/$domain && sudo dos2unix -n $domain
        ";
        $linkFileCommand = "ln -s /etc/nginx/sites-available/$domain /etc/nginx/sites-enabled/";
        $certbot = "sudo certbot run -n --nginx --agree-tos -d $domain,www.$domain  -m  goziechukwu@gmail.com  --redirect";
        
        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => [$command, $linkFileCommand, $certbot, 'sudo nginx -t' , 'sudo systemctl restart nginx'],
                ],
            ]);
            $commandID = $n1Command['Command']['CommandId'];
            if ($commandID !== '') {
                // TODO: update zebraline.web_creation for this website
                
                // Setup email 
                $arr = [
                    'domainName' => $domain,
                    'email' => $email,
                    'websiteCreationId' => $websiteCreationId,
                    'commandID' => $commandID
                ];
                $emailSetupRes = $this->createDomain($arr);
                return $emailSetupRes;
                
                
            }
        } catch (AwsException $e) {
            
            $mailData = [
                "msg" => 'Error settign up server file (sites-available) and SSL for '.$domain.': ' . $e->getAwsErrorMessage()

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            $error['status'] = 501;
            $error['message'] = 'AWS Error response: ' . $e->getAwsErrorMessage();
            return $error;
        }
    }

    /**
     * curl https://api.forwardemail.net/v1/domains/hash.fyi \
     *   -u @api String $user:
     * @param String $domain
     */
    public function createDomain(array $input) {
        $domain = $input["domainName"];
        $email = $input['email'];
        $websiteCreationId = $input['websiteCreationId'];
        $commandID = $input['commandID'];
        
        $zoneID = $this->requestZoneID($domain);
        
        if ($zoneID['status'] == 200) {
            $hostedZoneId = $zoneID['zoneID'];
            $responseData = $this->createDomainForwarding($domain);
            
            if (isset($responseData['created_at'])) {
                $verification_record = $responseData['verification_record'];
                return $this->updateDNSMXAndTXTRecords($verification_record, $hostedZoneId, $domain, $email, $websiteCreationId, $commandID);
                             
            } else {
                $mailData = [
                    "msg" => 'Email forwarding not created for '.$domain
    
                ];
                $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);

                $error['status'] = 501;
                $error['message'] = 'Email forwarding not created for '.$domain;
                return $error;
            }
        }
    }

    public function createDomainForwarding(string $domain) {
        $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains';
        $options = [
            'auth' => [$_SERVER['EMAILFORWARD_NET_USER'], ''],
            'form_params' => ['domain' => $domain],
        ];

        try {
            $response = $this->client->post($url, $options);
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {            
            $mailData = [
                "msg" => 'Error Creating Email Forwarding for '.$domain

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            $error['status'] = 501;
            $error['message'] = 'Email forwarding not created for '.$domain.'. '.$e->getMessage();
            return $error;
        }
    }

    /**
     * curl -X POST https://api.forwardemail.net/v1/domains/hash.fyi/aliases \
     * -u @api
     * @param String consultancy@+$domain
     */
    public function createDomainAlias(string $aliasName, string $domain, string $email) {
        $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains/' . $domain . '/aliases';
        $options = [
            'auth' => [$_SERVER['EMAILFORWARD_NET_USER'], ''],
            'headers' => ['Content-Type' => 'application/json'],
            'json' => ['name' => $aliasName, 'recipients' => $email],
        ];

        try {
            $this->client->post($url, $options);
        } catch (RequestException $e) {
            $mailData = [
                "msg" => 'Error Creating Email Forwarding Alias for '.$domain

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            $error['status'] = 501;
            $error['message'] = 'Email forwarding not created for '.$domain.'. '.$e->getMessage();
            return $error;
        }
    }

    /**
     * Update user MX records in AWS Route53
     */
    public function updateDNSMXAndTXTRecords(string $verification_record, string $hostedZoneId, string $domain, String $email, String $websiteCreationId, String $commandID) {
        $mxRecords = [
            ['Value' => '10 mx1.forwardemail.net'],
            ['Value' => '10 mx2.forwardemail.net']
        ];

        $txtRecord = ['Value' => "\"forward-email-site-verification={$verification_record}\""];

        try {
            $response = $this->route53->changeResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
                'ChangeBatch' => [
                    'Changes' => [
                        [
                            'Action' => 'UPSERT',
                            'ResourceRecordSet' => [
                                'Name' => $domain,
                                'Type' => 'MX',
                                'TTL' => 300,
                                'ResourceRecords' => $mxRecords,
                            ],
                        ],
                        [
                            'Action' => 'UPSERT',
                            'ResourceRecordSet' => [
                                'Name' => $domain,
                                'Type' => 'TXT',
                                'TTL' => 300,
                                'ResourceRecords' => [$txtRecord],
                            ],
                        ],
                    ],
                ],
            ]);
            $this->createDomainAlias('consultancy', $domain, $email);   
            $this->waitForChangesAndVerify($response, $domain);

            $data = [
                "websiteCreationId" => $websiteCreationId,
                'domain' => $domain,
                'email' => $email
            ];
            $siteCreationCompleteRes = json_decode($this->getRecordsFromZebralinePOST('https://zebraline.ai/api/pendingWebisteCreationComplete', $data), true);
            
            // return response
            $res['status'] = 200;
            $res['commandID'] = $commandID;
            $res['message'] = $domain.' successfully set up!';
            return $res;
        } catch (AwsException $e) {
            // echo "Error creating MX Records: {$e->getMessage()}";
            $mailData = [
                "msg" => 'Error creating MX Records for '.$domain

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            $error['status'] = 501;
            $error['message'] = 'Email forwarding not created for '.$domain.'. '.$e->getMessage();
            return $error;
        }
    }

    /**
     * Update user MX records in AWS Route53
     */
    public function updateSMTPCNAMEAndTXTRecords(array $input) {
        $domain = $input['domainName'];
        $allChanges = [];
    
        foreach ($input['data'] as $key => $value) {
            // Extract values
            $recordName = rtrim($value[2]).".$domain";
            $recordType = rtrim($value[3]);
            $trimmedRecordValue = rtrim($value[4]);
            // If type === CNAME don't add a quoted value
            if ($value[3] !== 'CNAME') {
                $recordValue = "\"$trimmedRecordValue\"";
            } else {
                $recordValue = $trimmedRecordValue;
            }
    
            // Define changes for each record
            $change = [
                'Action' => 'UPSERT',
                'ResourceRecordSet' => [
                    'Name' => $recordName,
                    'Type' => $recordType,
                    'TTL' => 300,
                    'ResourceRecords' => [
                        [
                            'Value' => $recordValue,
                        ]
                    ],
                ],
            ];
    
            $allChanges[] = $change;
        }
        $zoneID = $this->requestZoneID($domain);
        if ($zoneID['status'] == 200) {
            try {
                $response = $this->route53->changeResourceRecordSets([
                    'HostedZoneId' => $zoneID['zoneID'],
                    'ChangeBatch' => [
                        'Changes' => $allChanges,
                    ],
                ]);
                if ($response['@metadata']['statusCode'] == 200) {
                    echo json_encode([
                        'status' => 200,
                        'message' => 'SMTP settings saved!',
                    ]);
                }
            } catch (AwsException $e) {
                echo json_encode(['status' => 501, 'message' => $e->getMessage()]);
            }
        } else {
            // Return an appropriate response if the zone ID retrieval fails
            echo json_encode([
                'status' => 501,
                'message' => 'Error getting hosted zone ID',
            ]);
        }
    }
    

    // public function pendingWebsiteSetup($input){
    //     $projectIp = $input['projectIp'];
    //     $projectDir = $input['projectDir'];
    //     // get 100 pending records from zebraline.web_creation TB & id of last record processed
    //     $res = json_decode($this->getRecordsFromZebralinePOST('https://zebraline.ai/api/getpendingwebsite'), true);
    //     $siteLen = count($res['data']);
        
    //     if($siteLen > 0) {            
    //         foreach ($res['data'] as $key => $value) {
    //             $domain = $value['domainName'];
    //             $stripeId = $value['customer_id'];
    //             $websiteCreationId = $value['id'];
    //             $data = [
    //                 'stripeId' => $stripeId,
    //                 'domain' => $domain,
    //             ];

                
    //             $userDetailRes = json_decode($this->getRecordsFromZebralinePOST('https://zebraline.ai/api/webisteCreationGetUserDetails', $data), true);
                
    //             if($userDetailRes['status'] == 200){
    //                 $domainPurchaseRes = $this->purchaseDomainFromNameSilo($domain);
                    
    //                 if($domainPurchaseRes['status'] === 200){
    //                     $createdZoneData = ["DomainName" => $domain, "projectIp" => $projectIp, "projectDir" => $projectDir, "websiteCreationId" => $websiteCreationId, "email" => $userDetailRes['data']['email']];
    //                     $createHostedZoneRes = $this->createHostedZone($createdZoneData);  
    //                 } else {
    //                     // Send mail to goziechukwu@gmail.com
    //                     $mailData = [
    //                         "msg" => 'Domain purchase failed for '.$domain
    //                     ];
    //                     $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
    //                 }
    //             } else {
    //                 // Send mail to goziechukwu@gmail.com; this domain has no user attached
    //                 $mailData = [
    //                     "msg" => $domain.' has no user attached'
    //                 ];
    //                 $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
    //             }

    //     //         $siteLen--;
    //         }

    //     //     if($siteLen == 0){
    //     //         return ['status' => 200, 'message' => 'Website creation operations completed.'];
    //     //         // TODO: send request to zebraline to update lastUpdatedIndex
    //     //     }
            
    //     } else {
    //         // Send email to admin
    //         $msg = 'No outstanding website to create!';
    //         Mail::to('goziechukwu@gmail.com')->send(new CronResponseMail($msg));
    //     }
    // }

    private function purchaseDomainFromNameSilo($domain) {
        $years = 1;
        $key = $_SERVER['NAMESILO_API_KEY']; //env('NAMESILO_API_KEY');
        $api = $_SERVER['NAMESILO_API_URL']; //env('NAMESILO_API_URL');
        try {
            // $URL = "{$api}/registerDomain?version=1&type=xml&key={$key}&domain={$domain}&years={$years}&private=1&auto_renew=1";
            // $client = new \GuzzleHttp\Client();

            // $response = $client->request('GET', $URL);
            // $body = $response->getBody();
            // $xml = simplexml_load_string($body);
            // if (htmlentities((string)$xml->reply->code) == 300) { // && htmlentities((string)$xml->reply->detail) == 'success'
                return ['status' => 200, 'message' => 'Domain successfully purchased.'];
            // } else {
            //     echo json_encode(['message' => htmlentities((string)$xml->reply->detail), 'status' => htmlentities((string) $xml->reply->code)]);
            // }
        } catch (RequestException $th) {
            return $th->getMessage();
        }
    }

    private function getRecordsFromZebralinePOST($url, $data = []) {
        // create a new cURL resource
        $ch = curl_init();

        $token = 'e7bf6b05198b3909c0dad366eb1573cdb7f0fb36';

        $auth_header = 'Authorization: Basic ' . base64_encode($token);

        // set the URL to fetch
        curl_setopt($ch, CURLOPT_URL, $url);

        // curl_setopt($ch, CURLOPT_URL, 'localhost:8000/api/getpendingwebsite');

        curl_setopt($ch, CURLOPT_HTTPHEADER, array($auth_header));

        curl_setopt($ch, CURLOPT_POST, true);

        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // $data = [];
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

        // send the request and store the response in a variable
        $response = curl_exec($ch);

        if(curl_error($ch)) {
            return curl_error($ch);
        }

        // output the response
        return $response;

        // close cURL resource
        curl_close($ch);        
    }

    /**
     * Operation for creating a store
     *
     * @param Array $input
     * @return void
     */
    public function createStoreModule(array $input){
        $defaultStoreName = 'StoreName';
        $storeName = $input['storeName'];
        $themeColor = $input['themeColor'];
        $storeDirectoryName = $input['storeDirectoryName'];
        $storeTypeId = $input['storeTypeId'];
        $countTemplatesForStoreType = count(glob(dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/*", GLOB_ONLYDIR));

        $threshold = $this->relativeLuminanceW3C($themeColor);
        if($threshold === 'error'){
            echo json_encode(['status' => 500, 'message' => 'Theme color should be a color in hex format.']);
        } else {
            if($threshold >= 0.5){
                // dark
                $secColor = '#ffffff';
            } else {
                // light
                $secColor = '#000000';
            }
    
            if($this->storeTypeHasTemplateCheck($storeTypeId) === 1) {
                $randomTemplateNumber = rand(1, $countTemplatesForStoreType);
                $defaultColorTheme = '--primary-color: #fff;';
                $storeColorTheme = "--primary-color: $themeColor;";
                $defaultSecColor = "--secondary-color: #000;";
                $storeSecColor = "--secondary-color: $secColor;";
                
                // Make store directory if the store does not exist
                $siteDirectoryCommand = "sudo mkdir -p /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName";
                
                // MainHomeComponent
                $homeComponent = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/Home.vue";
                $homeComponentContent = $this->storeFileSetup($homeComponent, $defaultColorTheme, $storeColorTheme, $defaultSecColor, $storeSecColor);
                $componentName = 'Home.vue';
                $homeComponentCommand = "sudo nano $componentName
                    echo '$homeComponentContent' >> /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // NAVBAR
                $navbar = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/NavbarComponent.vue";
                $navbarContent = $this->storeFileSetup($navbar, $defaultStoreName, $storeName);
                $componentName = 'NavbarComponent.vue';
                $navbarCommand = "sudo nano $componentName
                    echo '$navbarContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // HERO
                $hero = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/HeroComponent.vue";
                $heroContent = $this->storeFileSetup($hero);
                $componentName = 'HeroComponent.vue';
                $heroCommand = "sudo nano $componentName
                    echo '$heroContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // CATEGORY
                $category = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/CategoryComponent.vue";
                $categoryContent = $this->storeFileSetup($category);
                $componentName = 'CategoryComponent.vue';
                $categoryCommand = "sudo nano $componentName
                    echo '$categoryContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // FeaturedProducts
                $featuredProducts = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/FeaturedProductsComponent.vue";
                $featuredProductsContent = $this->storeFileSetup($featuredProducts);
                $componentName = 'FeaturedProductsComponent.vue';
                $featuredProductsCommand = "sudo nano $componentName
                    echo '$featuredProductsContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // OFFERS
                $offers = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/OffersComponent.vue";
                $offersContent = $this->storeFileSetup($offers);
                $componentName = 'OffersComponent.vue';
                $offersCommand = "sudo nano $componentName
                    echo '$offersContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // BLOG
                $blogs = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/BlogComponent.vue";
                $blogsContent = $this->storeFileSetup($blogs);
                $componentName = 'BlogComponent.vue';
                $blogsCommand = "sudo nano $componentName
                    echo '$blogsContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";

                // Reviews
                $reviews = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/ReviewsComponent.vue";
                $reviewsContent = $this->storeFileSetup($reviews);
                $componentName = 'ReviewsComponent.vue';
                $reviewsCommand = "sudo nano $componentName
                    echo '$reviewsContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";

    
                // SHIPPING DETAILS
                $shippingDetails = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/SellingPointComponent.vue";
                $shippingDetailsContent = $this->storeFileSetup($shippingDetails);
                $componentName = 'SellingPointComponent.vue';
                $shippingDetailsCommand = "sudo nano $componentName
                    echo '$shippingDetailsContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // FOOTER
                $footer = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/FooterComponent.vue";
                // $footerContent = $this->storeFileSetup($footer, $defaultStoreName, $storeName);
                $footerContent = $this->storeFileSetup($footer);
                $componentName = 'FooterComponent.vue';
                $footerCommand = "sudo nano $componentName
                    echo '$footerContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
                ";
    
                // 
                $status = $this->runCommand([$siteDirectoryCommand, $homeComponentCommand, $navbarCommand, $heroCommand, $categoryCommand, $featuredProductsCommand, $offersCommand, $blogsCommand, $reviewsCommand, $shippingDetailsCommand, $footerCommand]);
                echo json_encode(['status' => 200, 'message' => $status]);
                
            } else {
                echo json_encode(['status' => 500, 'message' => 'Agent could not generate template. Storetype has no template']);
            }
        }
    }

    public function deleteStoreDirectory(array $input){
        $storeDIrectoryName = $input['storeDirectoryName'];
        $directoryPath = '/var/www/zebralinetest/resources/js/components/website/'.$storeDIrectoryName;

        $deleteCommand = 'sudo rm -R '.$directoryPath; 

        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => [$deleteCommand],
                ],
            ]);
            $commandId = $n1Command['Command']['CommandId'];

            if ($commandId !== '') {
                // Poll for command status
                $status = null;
                $maxAttempts = 30; // Maximum number of attempts
                $attempts = 0;

                while ($status !== 'Success' && $attempts < $maxAttempts) {
                    // Wait for a few seconds before checking the status again
                    sleep(10);

                    $result = $this->ssmClient->listCommandInvocations([
                        'CommandId' => $commandId,
                        'InstanceId' => 'i-01f1d0e3ed7035edb', // Replace with your instance ID
                    ]);

                    if (!empty($result['CommandInvocations'])) {
                        $status = $result['CommandInvocations'][0]['Status'];
                    }

                    $attempts++;
                }

                if ($status === 'Success') {
                    echo json_encode(['status' => 200, 'message' => 'Command execution is successful.', 'commandID' => $commandId]);
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Command execution failed.', 'commandID' => $commandId]);
                }
            }
        } catch (AwsException $e) {
            echo 'AWS Error response: ' . $e->getAwsErrorMessage();
        }
    }

    public function updateStoreThemeColor(array $input){
        $oldThemeColor = $input['oldThemeColor'];
        $newThemeColor = $input['newThemeColor'];
        $storeDirectoryName = $input['storeDirectoryName'];
        $filename = '/var/www/zebralinetest/resources/js/components/website/'.$storeDirectoryName.'/Home.vue';

        $searchString = '--primary-color: '.$oldThemeColor.';';
        $replaceString = '--primary-color: '.$newThemeColor.';';

        $updateCommand = "sed -i 's/$searchString/$replaceString/'$filename";
        $jsCompileCommand = 'cd /var/www/zebralinetest && npm run prod';

        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => [$jsCompileCommand, $jsCompileCommand],
                ],
            ]);
            $commandId = $n1Command['Command']['CommandId'];

            if ($commandId !== '') {
                // Poll for command status
                $status = null;
                $maxAttempts = 30; // Maximum number of attempts
                $attempts = 0;

                while ($status !== 'Success' && $attempts < $maxAttempts) {
                    // Wait for a few seconds before checking the status again
                    sleep(10);

                    $result = $this->ssmClient->listCommandInvocations([
                        'CommandId' => $commandId,
                        'InstanceId' => 'i-01f1d0e3ed7035edb', // Replace with your instance ID
                    ]);

                    if (!empty($result['CommandInvocations'])) {
                        $status = $result['CommandInvocations'][0]['Status'];
                    }

                    $attempts++;
                }

                if ($status === 'Success') {
                    echo json_encode(['status' => 200, 'message' => 'Command execution is successful.', 'commandID' => $commandId]);
                } else {
                    echo json_encode(['status' => 500, 'message' => 'Command execution failed.', 'commandID' => $commandId]);
                }
            }
        } catch (AwsException $e) {
            echo 'AWS Error response: ' . $e->getAwsErrorMessage();
        }
    }

    public function runJScompileCommand() {
        // Use this to run watch in production
        // Zebraline no longer makes calls here or to check command status
        $jsCompileCommand = 'cd /var/www/zebralinetest && sudo npm run build';
        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => [$jsCompileCommand],
                ],
            ]);
            $commandId = $n1Command['Command']; // ['CommandId']

            echo json_encode(['status' => 200, 'message' => $commandId]);
            
        } catch (AwsException $e) {
            echo 'AWS Error response: ' . $e->getAwsErrorMessage();
        }
    }

    public function listCommandStatus(array $input) {
        $commandId = $input['commandId'];
        
        $result = $this->ssmClient->listCommandInvocations(
            [
                'CommandId' => "$commandId",
                'Details' => true,
                'InstanceId' => 'i-01f1d0e3ed7035edb',
                // "NextToken" => "AAEAASiaH2XV9X6gYzh6sJ201/MOh1ryQY/Ixll+fRvoBwGlAAAAAGRMD9I1IdZhNLKZNX1PHoNwZFKtsE6yJer9otpcugjtbW26TJSXP+C9iwejqL9pdDAsk9fnE7HZwytd4THp+c2ThMxK68JwvXvunVJR0ZE6xUJnqypbqrQ4iAxqKL+hOXUlheh36KoYRk/svRAG7+HwOz1s+3sq737nlgoaAXNIxnpYYT3Uyq7xKmA9aIYIYTCfjZhjcyQ55uAIqucAy+9/LXiTEiNvTcPLxi3XCctQf4a53giAAMFceiDT9Zp420wVTJLt91uQTWSSaZWv8GbE3n6oUbRCon29nigdvbfJm4N/mXvPF4wcRls0xy8SD16ljI9ykCNzXgzLj3iGO2eyAU4hWnUkGRD+0B/JqXkE6954Rs48z3+F+uR2hQcHrSrhXYIbdxfT3ZzbA1F95PKJ/RbSYPtaTwAGI5/fhBSk0JllJOuxGnsO5doa0LCAm79QS+fxIVqfDxStEmB282ldKgOAkiNEOvdi4VOtdpJAzsE6HcihH5KdB9qLRDb9URkcOkssAijrSJO5v8lUuLMIfWeGyb2Qu4zptLC5BFmiIGoY37fBU1aRCBeeoap+ffXirUGsxS9wTz5w4icq8o8Ta9pYfsTMYpSv8YOKM1+IqKFj4GHOXGx39YPexrS2BnI9taArt1YBrG6dCr7GrbbvU5SxcWKRXl9SmiuwYVGO1UJ+mEc3flGnxoDXM6Txgb7ImFDlGLOrLTZyDoTfFIJwVPes1b4LTk1nkr/hTaAB8l8pGwonNANhxX0s6hUMesgsTDKqcKUiA+Xc7LiwHMvxGBAk2KRko39bOUQcOawyee6BjPpmmpyQg0NML9H/uchTqxJfDQWAX5di1yOmKKbK/Ecvf2SrQl17Uw0gJl88PVyWaUnSxIo3dXkhdHac1jPVklVTH7bYMT3sEf73vDGndV+k7pz/sw9kuO6sunhmBAcXcRHPFqQiAIdQA9p33yWiBYHUTKkIhas0nprq8t7FkRP+iVftpAeD1yZcOdo/KbznbDJPTodgGowDn3LG90VMqrHD532Swi/9UM5q7T2Ot2y7fa500RQHo3zSBoBHxi/SE4siHEQ7AikH+V1BaZohoX5i4M6B1RVal/2rpssQyB2jYsdajhEDGVrfEbwAFXIlmyc57hC72+jiC7bF3vDR45avBnix81e0gOfRTVw4a+ssgCkaPSa+rpUSpYu6Rg5dpA==",
            ]
        );
        echo json_encode(['status' => 200, 'message' => $result['CommandInvocations'][0]['Status']]); //[0]['Status']
    }

    private function relativeLuminanceW3C($color) {

        if(strlen($color) !== 7 || substr($color, 0, 1) !== '#'){
            return 'error';
        } else {                
            list($R8bit, $G8bit, $B8bit) = sscanf($color, "#%02x%02x%02x");
    
            $RsRGB = $R8bit/255;
            $GsRGB = $G8bit/255;
            $BsRGB = $B8bit/255;
        
            $R = ($RsRGB <= 0.03928) ? $RsRGB/12.92 : pow(($RsRGB+0.055)/1.055, 2.4);
            $G = ($GsRGB <= 0.03928) ? $GsRGB/12.92 : pow(($GsRGB+0.055)/1.055, 2.4);
            $B = ($BsRGB <= 0.03928) ? $BsRGB/12.92 : pow(($BsRGB+0.055)/1.055, 2.4);
        
            // For the sRGB colorspace, the relative luminance of a color is defined as: 
            $L = 0.2126 * $R + 0.7152 * $G + 0.0722 * $B;
    
            return $L;
        }        
    }

    private function runCommand(array $arg) {
        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => $arg,
                ],
            ]);
            $commandId = $n1Command['Command']['CommandId'];

            if ($commandId !== '') {
                // Poll for command status
                // $status = null;
                // $maxAttempts = 30; // Maximum number of attempts
                // $attempts = 0;

                // while ($status !== 'Success' && $attempts < $maxAttempts) {
                //     // Wait for a few seconds before checking the status again
                //     sleep(10);

                //     $result = $this->ssmClient->listCommandInvocations([
                //         'CommandId' => $commandId,
                //         'InstanceId' => 'i-01f1d0e3ed7035edb', // Replace with your instance ID
                //     ]);

                //     if (!empty($result['CommandInvocations'])) {
                //         $status = $result['CommandInvocations'][0]['Status'];
                //     }

                //     $attempts++;
                // }

                return $commandId;
            }
        } catch (AwsException $e) {
            echo 'AWS Error response: ' . $e->getAwsErrorMessage();
        }
    }

    public function storeTypeHasTemplate(array $input) {
        $storeTypeId = $input['storeTypeId'];
        if($this->storeTypeHasTemplateCheck($storeTypeId) === 1) {
            echo json_encode(['status' => 200, 'message' => 'Store type has templates.']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Storetype has no template.']);
        }
    }

    private function storeTypeHasTemplateCheck($storeTypeId) {
        $countTemplatesForStoreType = count(glob(dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/*", GLOB_ONLYDIR));

        if($countTemplatesForStoreType > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function storeFileSetup($fileName, $findThemeColor = null, $replaceThemeColor = null, $findSecColor = null, $replaceSecColor = null) {
        //read the entire string
        $str = file_get_contents($fileName);

        if($findThemeColor !== null || $replaceThemeColor !== null) {
            //replace something in the file string - this is a VERY simple example
            $str = str_replace($findThemeColor, $replaceThemeColor, $str);

            if($findSecColor !== null || $replaceSecColor !== null) {
                //replace something in the file string - this is a VERY simple example
                $str = str_replace($findSecColor, $replaceSecColor, $str);
            }
        }

        // Write content to EC2 directory
        return $str;
    }

    

    public function configureEmail($domainName, $email, $forward1) {
        $api = 'https://api.forwardemail.net/v1';
        $data = [
            'domain' => $domainName,
        ];
        try {
            // Create domain
            $createDomain = $this->client->request("POST", "$api/domains", [
                'json' => $data,
            ]);
            // Create Alias
            if ($createDomain) {
                $data = ['name' => "consultancy@$domainName"];
                $createAliase = $this->client->request("POST", "$api/domains/$domainName/aliases", [
                    "json" => $data
                ]);
            }
            // Update DNS on Route53
            // $createDomain = "{$api}/domains?version=1&type=xml&key={$key}&domain={$domainName}&email={$email}&forward1={$forward1}";

        } catch (\Throwable $th) {
            echo $th->getMessage();
            exit;
        }
    }

    private function waitForChangesAndVerify($response, $domain) {
        if ($response) {
            // $changeId = $response->get('ChangeInfo')['Id'];
            // $this->route53->waitUntil('ResourceRecordSetsChanged', [
            //     'Id' => $changeId,
            //     'WaiterConfig' => ['Delay' => 10, 'MaxAttempts' => 30],
            // ]);
            return $this->verifyDomainRecords($domain);
        }
    }

    /**
     * Verify Domain Records
     * curl https://api.forwardemail.net/v1/domains/hash.fyi/verify-records \
     */
    public function verifyDomainRecords(string $domain) {
        $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains/' . $domain . '/verify-records';
        $options = ['auth' => [$_SERVER['EMAILFORWARD_NET_USER'], '']];
        
        try {
            $response = $this->client->get($url, $options);
            return $response->getBody()->getContents();
        } catch (RequestException $e) {
            // $this->handleRequestException($e, 'Error verifying domain');
            // return '';
            $mailData = [
                "msg" => 'MX Records for '.$domain. ' yet to update'

            ];
            $this->getRecordsFromZebralinePOST('https://zebraline.ai/api/sendMailWebsiteCreationError', $mailData);
            $error['status'] = 501;
            $error['message'] = 'Email forwarding not created for '.$domain.'. '.$e->getMessage();
            return $error;
        }
    }

    private function handleRequestException(RequestException $e, string $errorMessagePrefix) {
        if ($e->hasResponse()) {
            $statusCode = $e->getResponse()->getStatusCode();
            $errorMessage = $e->getResponse()->getBody()->getContents();
            echo "{$errorMessagePrefix}: {$statusCode} - {$errorMessage}";
        } else {
            echo "{$errorMessagePrefix}: {$e->getMessage()}";
        }
    }


    // public function updateMXRecords()
    // {
    //     // Set the updated MX records with priority
    //     $mxRecords = [
    //         [
    //             'Value' => '10 mx1.forwardemail.net.',
    //             'Priority' => 10,
    //         ],
    //         [
    //             'Value' => '10 mx2.forwardemail.net.',
    //             'Priority' => 20,
    //         ],
    //     ];
    //     $hostedZoneId = 'Z048341915W6DVYZZF7HM';
    //     $rootDomain = 'purityvendor.com';
    //     try {
    //         // Get the existing record sets
    //         $response = $this->route53->listResourceRecordSets([
    //             'HostedZoneId' => $hostedZoneId,
    //         ]);

    //         // Find the existing MX record
    //         $existingMxRecord = null;
    //         foreach ($response['ResourceRecordSets'] as $recordSet) {
    //             if ($recordSet['Name'] === $rootDomain && $recordSet['Type'] === 'MX') {
    //                 $existingMxRecord = $recordSet;
    //                 break;
    //             }
    //         }

    //         // Prepare the changes for MX records
    //         $changes = [];
    //         if ($existingMxRecord !== null) {
    //             $changes[] = [
    //                 'Action' => 'DELETE',
    //                 'ResourceRecordSet' => $existingMxRecord,
    //             ];
    //         }
    //         $changes[] = [
    //             'Action' => 'UPSERT',
    //             'ResourceRecordSet' => [
    //                 'Name' => $rootDomain,
    //                 'Type' => 'MX',
    //                 'TTL' => 300,
    //                 'ResourceRecords' => $mxRecords,
    //             ],
    //         ];

    //         // Update the MX records
    //         $response = $this->route53->changeResourceRecordSets([
    //             'HostedZoneId' => $hostedZoneId,
    //             'ChangeBatch' => [
    //                 'Changes' => $changes,
    //             ],
    //         ]);

    //         // Process the response as needed
    //         echo "MX records updated successfully.";
    //     } catch (AwsException $e) {
    //         // Handle any errors that occurred
    //         echo "Error: {$e->getMessage()}";
    //     }

    // }

    /**
    * Send API output.
    *
    * @param mixed $data
    * @param string $httpHeader
    */
    protected function sendOutput($data, $httpHeaders = array())
    {
        header_remove('Set-Cookie');
        if (is_array($httpHeaders) && count($httpHeaders)) {
            foreach ($httpHeaders as $httpHeader) {
                header($httpHeader);
            }
        }
        echo $data;
        exit;
    }

    public function requestZoneID(String $domainName)
    {
        try {
            // Retrieve the hosted zones by name
            $response = $this->route53->listHostedZonesByName([
                'DNSName' => $domainName,
            ]);

            // Check if a hosted zone exists for the specified domain
            if (!empty($response['HostedZones'])) {
                $hostedZoneId = $response['HostedZones'][0]['Id'];
                $identifier = basename($hostedZoneId);
                return ['zoneID' => $identifier, 'status' => 200];
            } else {
                return ['status' => "No hosted zone found for domain '{$domainName}'"];
            }
        } catch (AwsException $e) {
            // Handle any errors that occurred
            echo "Error No Domain found: {$e->getMessage()}";
        }
    }


    public function deleteHostedZone()
    {

        // $recordSetName = 'www.vendmeqniq.com'; // Replace with the name of the record set you want to delete
        $domainName = 'drdionneibekiemd.com';
        // Set up the changes for the record set
        $awwwRecord = [
            'Action' => 'DELETE',
            'ResourceRecordSet' => [
                'Name' => 'www.' . $domainName,
                'Type' => 'A',
                'SetIdentifier' => 'aww-record',
                'TTL' => 300,
                'Weight' => 100,
                'ResourceRecords' => [
                    [
                        'Value' => '3.228.136.96',
                    ]
                ]
            ]
        ];

        $aRecord = [
            'Action' => 'DELETE',
            'ResourceRecordSet' => [
                'Name' => $domainName,
                'Type' => 'A',
                'SetIdentifier' => 'a-record',
                'TTL' => 300,
                'Weight' => 100,
                'ResourceRecords' => [
                    [
                        'Value' => '3.228.136.96',
                    ]
                ]
            ]
        ];
        $aWildRecord = [
            'Action' => 'DELETE',
            'ResourceRecordSet' => [
                'Name' => '*.' . $domainName,
                'Type' => 'A',
                'SetIdentifier' => 'awild-record',
                'TTL' => 300,
                'Weight' => 100,
                'ResourceRecords' => [
                    [
                        'Value' => '3.228.136.96',
                    ]
                ]
            ]
        ];
        $changes = [
            $awwwRecord,
            $aRecord,
            $aWildRecord,
        ];
        // Delete the record set
        try {
            $result = $this->route53->changeResourceRecordSets([
                'HostedZoneId' => '/hostedzone/Z008721817O5NKMU5CPE0',
                'ChangeBatch' => [
                    'Changes' => $changes,
                ],
            ]);

            echo 'Record set deleted successfully.' . PHP_EOL;
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error deleting record set: ' . $e->getMessage();
        }

        try {
            // Delete the hosted zone
            $this->route53->deleteHostedZone([
                'Id' => '/hostedzone/Z008721817O5NKMU5CPE0' // Z019631217VC5N0L30ZHI
            ]);

            echo 'Hosted zone deleted successfully.' . PHP_EOL;
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error deleting hosted zone: ' . $e->getMessage();
        }
    }

    
}
