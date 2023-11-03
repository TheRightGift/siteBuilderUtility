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
    public function addDNSRecord(array $record, String $hostedZoneId)
    {
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

    /**
     * Operation for creating a store
     *
     * @param Array $input
     * @return void
     */
    public function createStoreModule(array $input)
    {
        $defaultStoreName = 'StoreName';
        $storeName = $input['storeName'];
        $themeColor = $input['themeColor'];
        $storeDirectoryName = $input['storeDirectoryName'];
        $storeTypeId = $input['storeTypeId'];
        $countTemplatesForStoreType = count(glob(dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/*", GLOB_ONLYDIR));

        if($this->storeTypeHasTemplateCheck($storeTypeId) === 1) {
            $randomTemplateNumber = rand(1, $countTemplatesForStoreType);
            $defaultColorTheme = '--primary-color: #fff;';
            $storeColorTheme = "--primary-color: $themeColor;";
            
            // Make store directory if the store does not exist
            $siteDirectoryCommand = "sudo mkdir -p /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName";
            
            // MainHomeComponent
            $homeComponent = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/Home.vue";
            $homeComponentContent = $this->storeFileSetup($homeComponent, $defaultColorTheme, $storeColorTheme);
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

            // SHIPPING DETAILS
            $shippingDetails = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/SellingPointComponent.vue";
            $shippingDetailsContent = $this->storeFileSetup($shippingDetails);
            $componentName = 'SellingPointComponent.vue';
            $shippingDetailsCommand = "sudo nano $componentName
                echo '$shippingDetailsContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
            ";

            // FOOTER
            $footer = dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/template" . $randomTemplateNumber . "/FooterComponent.vue";
            $footerContent = $this->storeFileSetup($footer, $defaultStoreName, $storeName);
            $componentName = 'FooterComponent.vue';
            $footerCommand = "sudo nano $componentName
                echo '$footerContent' >>  /var/www/zebralinetest/resources/js/components/websites/$storeDirectoryName/$componentName && sudo mv '$componentName'$'\r' $componentName
            ";

            // 
            $status = $this->runCommand([$siteDirectoryCommand, $homeComponentCommand, $navbarCommand, $heroCommand, $categoryCommand, $featuredProductsCommand, $offersCommand, $blogsCommand, $shippingDetailsCommand, $footerCommand]);
            echo json_encode(['status' => 200, 'message' => $status]);
            
        } else {
            echo json_encode(['status' => 500, 'message' => 'Agent could not generate template. Storetype has no template']);
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
        $jsCompileCommand = 'cd /var/www/zebralinetest && sudo npm run prod';
        try {
            $n1Command = $this->ssmClient->sendCommand([
                'InstanceIds' => ['i-01f1d0e3ed7035edb'],
                'DocumentName' => 'AWS-RunShellScript',
                'Parameters' => [
                    'commands' => [$jsCompileCommand],
                ],
            ]);
            $commandId = $n1Command['Command']['CommandId'];

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
        echo json_encode(['status' => 200, 'message' => $result['CommandInvocations']]);
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

    public function storeTypeHasTemplate(array $input)
    {
        $storeTypeId = $input['storeTypeId'];
        if($this->storeTypeHasTemplateCheck($storeTypeId) === 1) {
            echo json_encode(['status' => 200, 'message' => 'Store type has templates.']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Storetype has no template.']);
        }
    }

    private function storeTypeHasTemplateCheck($storeTypeId)
    {
        $countTemplatesForStoreType = count(glob(dirname(__DIR__) . "/storeTemplates/" . $storeTypeId . "/*", GLOB_ONLYDIR));

        if($countTemplatesForStoreType > 0) {
            return 1;
        } else {
            return 0;
        }
    }

    private function storeFileSetup($fileName, $find = null, $replace = null)
    {
        //read the entire string
        $str = file_get_contents($fileName);

        if($find !== null || $replace !== null) {
            //replace something in the file string - this is a VERY simple example
            $str = str_replace($find, $replace, $str);
        }

        // Write content to EC2 directory
        return $str;
    }

    /**
     * Creates a Hosted Zone on Route 53
     *
     * @param Array $input
     * @return void
     */
    public function createHostedZone(array $input)
    {
        // Create DB with domainame and caller_refernce
        $domainName = $input['DomainName'];
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

            // Update the DNS settings for the domain
            // This code assumes that you are using the AWS Route 53 DNS service
            // Change the resourceRecords
            $this->changeNameServers($domainName, $nameServers);

            $this->changeResourceRecords($domainName, $hostedZoneId);

            // echo 'Hosted zone created, namesilo servers updated and DNS updated successfully!' . $hostedZoneId;
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error creating hosted zone and updating DNS: ' . $e->getMessage();
        }
    }

    public function configureEmail($domainName, $email, $forward1)
    {
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

    /**
     * Changes the nameserver on namesilo
     * Points NS resords to the Route 53 Hosted Zone
     *
     * @param String $domainName
     * @param Array $nameServers
     * @return void
     */
    public function changeNameServers(String $domainName, array $nameServers)
    {
        $key = $_SERVER['NAMESILO_API_KEY'];
        $ns1 = $nameServers[0];
        $ns2 = $nameServers[1];
        $ns3 = $nameServers[2];
        $ns4 = $nameServers[3];
        // $key = '19162fabb5d351a3b3a5';
        // $ns1= 'NS1.NAMESILO.COM ';
        // $ns2= 'NS2.NAMESILO.COM';
        // $ns3= 'NS3.NAMESILO.COM';
        // $ns4= 'NS4.NAMESILO.COM';
        try {
            $URL = "https://www.namesilo.com/api/changeNameServers?version=1&type=xml&key={$key}&domain={$domainName}&ns1={$ns1}&ns2={$ns2}&ns3={$ns3}&ns4={$ns4}";
            // $URL = "https://sandbox.namesilo.com/api/changeNameServers?version=1&type=xml&key={$key}&domain={$domainName}&ns1={$ns1}&ns2={$ns2}&ns3={$ns3}&ns4={$ns4}";
            $response = $this->client->request('GET', $URL);
            $body = $response->getBody();
            $xml = simplexml_load_string($body);
            if (htmlentities((string)$xml->reply->detail) === 'success' && htmlentities((string)$xml->reply->code) === 300) {
                // echo "Message ".htmlentities((string)$xml->reply->detail).';'. "Status".htmlentities((string)$xml->reply->detail);
            } else {
                // echo $body;
            }
        } catch (\Throwable $th) {
            echo $th->getMessage();
            exit;
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
    public function changeResourceRecords(String $domainName, String $hostedZoneId) //
    {// $loadBalancerDns = 'wcd-lb-d7b7e9924c1fc7c5.elb.us-east-1.amazonaws.com';
        // $loadBalancerHostedZoneId = 'Z26RNL4JYFTOTI';
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
                            'Value' => '3.228.136.96',
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
                            'Value' => '3.228.136.96',
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
                            'Value' => '3.228.136.96',
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
            echo 'Error retrieving hosted zone ID for load balancer: ' . $e->getAwsErrorMessage();
        } finally {
            if ($result) {
                $changeId = $result->get('ChangeInfo')['Id'];
                $this->route53->waitUntil('ResourceRecordSetsChanged', [
                    'Id' => $changeId,
                    'WaiterConfig' => [
                        'Delay' => 10, // time delay between each request
                        'MaxAttempts' => 30, // maximum number of attempts to make
                    ],
                ]);
                echo json_encode(['status' => 200, 'message' => 'success on creating hosted zone!']);
            }
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
    public function sendCommand(array $input)
    {
        $domain = $input['DomainName'];
        $uri = '$uri';
        $query_string = '$query_string';
        $realpath_root = '$realpath_root';
        $fastcgi_script_name = '$fastcgi_script_name';
        $command = "sudo nano $domain
            echo 'server {
                listen 80;
                server_name $domain www.$domain;
                root /var/www/whitecoatdomain/public;
                
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
                echo json_encode(['status' => 200, 'message' => 'success on creating server!', 'commandID' => $commandID]);
            }
        } catch (AwsException $e) {
            echo 'AWS Error response: ' . $e->getAwsErrorMessage();
        }
    }


    public function createDocument()
    {
        $json_string = file_get_contents('../resources/content.json');
        $documentContent = json_decode($json_string, true);

        // Define the SSM document name
        $documentName = 'CreateServerBlockAndIssueSSL';

        // Create the SSM document
        $result = $this->ssmClient->updateDocument([
            'Content' => json_encode($documentContent),
            'Name' => $documentName,
            'DocumentType' => 'Command',
            'DocumentVersion' => '1'
        ]);

        $documentId = $result->get('DocumentDescription.DocumentVersion');
        echo $result;
        echo $documentId;
    }

    private function listCommand($commandId)
    {
        $result = $this->ssmClient->listCommandInvocations(
            [
                'CommandId' => $commandId,
                'Details' => true,
                'InstanceId' => 'i-01f1d0e3ed7035edb',
                // "NextToken" => "AAEAASiaH2XV9X6gYzh6sJ201/MOh1ryQY/Ixll+fRvoBwGlAAAAAGRMD9I1IdZhNLKZNX1PHoNwZFKtsE6yJer9otpcugjtbW26TJSXP+C9iwejqL9pdDAsk9fnE7HZwytd4THp+c2ThMxK68JwvXvunVJR0ZE6xUJnqypbqrQ4iAxqKL+hOXUlheh36KoYRk/svRAG7+HwOz1s+3sq737nlgoaAXNIxnpYYT3Uyq7xKmA9aIYIYTCfjZhjcyQ55uAIqucAy+9/LXiTEiNvTcPLxi3XCctQf4a53giAAMFceiDT9Zp420wVTJLt91uQTWSSaZWv8GbE3n6oUbRCon29nigdvbfJm4N/mXvPF4wcRls0xy8SD16ljI9ykCNzXgzLj3iGO2eyAU4hWnUkGRD+0B/JqXkE6954Rs48z3+F+uR2hQcHrSrhXYIbdxfT3ZzbA1F95PKJ/RbSYPtaTwAGI5/fhBSk0JllJOuxGnsO5doa0LCAm79QS+fxIVqfDxStEmB282ldKgOAkiNEOvdi4VOtdpJAzsE6HcihH5KdB9qLRDb9URkcOkssAijrSJO5v8lUuLMIfWeGyb2Qu4zptLC5BFmiIGoY37fBU1aRCBeeoap+ffXirUGsxS9wTz5w4icq8o8Ta9pYfsTMYpSv8YOKM1+IqKFj4GHOXGx39YPexrS2BnI9taArt1YBrG6dCr7GrbbvU5SxcWKRXl9SmiuwYVGO1UJ+mEc3flGnxoDXM6Txgb7ImFDlGLOrLTZyDoTfFIJwVPes1b4LTk1nkr/hTaAB8l8pGwonNANhxX0s6hUMesgsTDKqcKUiA+Xc7LiwHMvxGBAk2KRko39bOUQcOawyee6BjPpmmpyQg0NML9H/uchTqxJfDQWAX5di1yOmKKbK/Ecvf2SrQl17Uw0gJl88PVyWaUnSxIo3dXkhdHac1jPVklVTH7bYMT3sEf73vDGndV+k7pz/sw9kuO6sunhmBAcXcRHPFqQiAIdQA9p33yWiBYHUTKkIhas0nprq8t7FkRP+iVftpAeD1yZcOdo/KbznbDJPTodgGowDn3LG90VMqrHD532Swi/9UM5q7T2Ot2y7fa500RQHo3zSBoBHxi/SE4siHEQ7AikH+V1BaZohoX5i4M6B1RVal/2rpssQyB2jYsdajhEDGVrfEbwAFXIlmyc57hC72+jiC7bF3vDR45avBnix81e0gOfRTVw4a+ssgCkaPSa+rpUSpYu6Rg5dpA==",
            ]
        );
        echo $result;
        // echo $result['CommandInvocations'][0]['Status'];
    }

    /**
     * curl https://api.forwardemail.net/v1/domains/hash.fyi \
     *   -u @api String $user:
     * @param String $domain
     */
    public function createDomain(array $input)
    {
        $domain = $input["DomainName"];
        $email = $input['email'];
        $zoneID = $this->requestZoneID($domain);
        if ($zoneID['status'] == 200) {
            $hostedZoneId = $zoneID['zoneID'];
            $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains';
            $options = [
                'auth' => [$_SERVER['EMAILFORWARD_NET_USER'], ''],
                'form_params' => [
                    'domain' => $domain,
                ],
            ];

            try {
                $response = $this->client->post($url, $options);

                // Get the response body as a string
                $body = $response->getBody()->getContents();
                $alias = "consultancy";
                $responseData = json_decode($body, true);

                if (isset($responseData['created_at'])) {
                    $verification_record = $responseData['verification_record'];
                    $this->createDomainAlias($alias, $domain, $email);
                    $this->updateDNSMXRecordForDomainForwarding($verification_record, $hostedZoneId, $domain);
                } else {
                    echo "The 'created_at' field does not exist in the response: $body";
                }
            } catch (RequestException $e) {
                if ($e->hasResponse()) {
                    $statusCode = $e->getResponse()->getStatusCode();
                    $errorMessage = $e->getResponse()->getBody()->getContents();
                    echo "Error Creating Forwrd: {$statusCode} - {$errorMessage}";
                } else {
                    echo 'Error Creating Forwrd: ' . $e->getMessage();
                }
            }
        }
    }

    /**
     * curl -X POST https://api.forwardemail.net/v1/domains/hash.fyi/aliases \
     * -u @api
     * @param String consultancy@+$domain
     */
    public function createDomainAlias(String $aliasName, String $domain, String $email)
    {
        // Set the request URL and options
        $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains/' . $domain . '/aliases';
        $options = [
            'auth' => [$_SERVER['EMAILFORWARD_NET_USER'], ''], // Replace with your API key
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'name' => $aliasName,
                'recipients' => $email
            ],
        ];

        try {
            $response = $this->client->post($url, $options);

            // $body = $response->getBody()->getContents();
            // echo $body;
        } catch (RequestException $e) {
            // Handle request errors
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorMessage = $e->getResponse()->getBody()->getContents();
                echo "Error Creating Alias: {$statusCode} - {$errorMessage}";
            } else {
                echo 'Error Creating Alias: ' . $e->getMessage();
            }
        }
    }

    /**
     * Update user MX records in AWS Route53
     */
    public function updateDNSMXRecordForDomainForwarding(String $verification_record, String $hostedZoneId, String $domain)
    {
        // MX 10 mx1.forwardemail.net
        // MX 10 mx2.forwardemail.net
        // TXT forward-email-site-verification={$verification_record}

        $mxRecords = [
            [
                'Value' => '10 mx1.forwardemail.net.',
                'Priority' => 10,
            ],
            [
                'Value' => '10 mx2.forwardemail.net.',
                'Priority' => 10,
            ],
        ];

        $txtRecord = [
            'Value' => "\"forward-email-site-verification={$verification_record}\"",

        ];

        try {
            // Update the MX records
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

            // Process the response as needed
            // echo "MX records updated successfully.";
        } catch (AwsException $e) {
            // Handle any errors that occurred
            echo "Error creating MX Records: {$e->getMessage()}";
        } finally {
            if ($response) {
                $changeId = $response->get('ChangeInfo')['Id'];
                $this->route53->waitUntil('ResourceRecordSetsChanged', [
                    'Id' => $changeId,
                    'WaiterConfig' => [
                        'Delay' => 10, // time delay between each request
                        'MaxAttempts' => 30, // maximum number of attempts to make
                    ],
                ]);
                $this->verifyDomainRecords($domain);
                // echo json_encode(['status' => 200, 'message' => 'success on creating MX Records!']);
            }
        }
    }

    /**
     * Verify Domain Records
     * curl https://api.forwardemail.net/v1/domains/hash.fyi/verify-records \
     */
    public function verifyDomainRecords(String $domain)
    {
        $url = $_SERVER['EMAILFORWARD_NET_URL'] . 'domains/' . $domain . '/verify-records';
        $options = [
            'auth' => [$_SERVER['EMAILFORWARD_NET_USER'], ''],
        ];

        try {
            $response = $this->client->get($url, $options);
            // Get the response body as a string
            $body = $response->getBody()->getContents();
            echo $body;
        } catch (RequestException $e) {
            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $errorMessage = $e->getResponse()->getBody()->getContents();
                echo "Error verifying domain: {$statusCode} - {$errorMessage}";
            } else {
                echo 'Error verifying domain: ' . $e->getMessage();
            }
        }
    }

    public function updateMXRecords()
    {
        // Set the updated MX records with priority
        $mxRecords = [
            [
                'Value' => '10 mx1.forwardemail.net.',
                'Priority' => 10,
            ],
            [
                'Value' => '10 mx2.forwardemail.net.',
                'Priority' => 20,
            ],
        ];
        $hostedZoneId = 'Z048341915W6DVYZZF7HM';
        $rootDomain = 'purityvendor.com';
        try {
            // Get the existing record sets
            $response = $this->route53->listResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
            ]);

            // Find the existing MX record
            $existingMxRecord = null;
            foreach ($response['ResourceRecordSets'] as $recordSet) {
                if ($recordSet['Name'] === $rootDomain && $recordSet['Type'] === 'MX') {
                    $existingMxRecord = $recordSet;
                    break;
                }
            }

            // Prepare the changes for MX records
            $changes = [];
            if ($existingMxRecord !== null) {
                $changes[] = [
                    'Action' => 'DELETE',
                    'ResourceRecordSet' => $existingMxRecord,
                ];
            }
            $changes[] = [
                'Action' => 'UPSERT',
                'ResourceRecordSet' => [
                    'Name' => $rootDomain,
                    'Type' => 'MX',
                    'TTL' => 300,
                    'ResourceRecords' => $mxRecords,
                ],
            ];

            // Update the MX records
            $response = $this->route53->changeResourceRecordSets([
                'HostedZoneId' => $hostedZoneId,
                'ChangeBatch' => [
                    'Changes' => $changes,
                ],
            ]);

            // Process the response as needed
            echo "MX records updated successfully.";
        } catch (AwsException $e) {
            // Handle any errors that occurred
            echo "Error: {$e->getMessage()}";
        }

    }

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

    public function getELBID() //String $domainName, String $hostedZoneId
    {// Set the parameters for the describe load balancers call
        $params = [
            'Names' => ['wcd-lb-d7b7e9924c1fc7c5.elb.us-east-1.amazonaws.com.'], // Replace with the name of your load balancer
        ];

        try {
            // Make the describe load balancers call
            $result = $this->ELBClient->describeLoadBalancers($params);

            // Get the hosted zone ID for the load balancer
            $ELBHostedZoneId = $result->get('LoadBalancerDescriptions')[0]['CanonicalHostedZoneNameID'];
            // $this->changeResourceRecords($domainName, $hostedZoneId, $ELBHostedZoneId);
            echo 'Hosted zone ID for load balancer is: ' . $ELBHostedZoneId;
            print_r($result);
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error retrieving hosted zone ID for load balancer: ' . $e->getMessage();
        }
    }

    public function getAllZones()
    {
        try {
            // Make the list hosted zones call
            $result = $this->route53->listHostedZones();

            // Get the hosted zones from the response
            $hostedZones = $result->get('HostedZones');

            // Output the details for each hosted zone
            foreach ($hostedZones as $hostedZone) {
                echo 'ID: ' . $hostedZone['Id'] . PHP_EOL;
                echo 'DNS Name: ' . $hostedZone['Name'] . PHP_EOL;
                echo 'Caller Reference: ' . $hostedZone['CallerReference'] . PHP_EOL;
                echo 'Created On: ' . $hostedZone['Config']['CreatedDate'] . PHP_EOL;
                echo PHP_EOL;
            }
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error retrieving hosted zones: ' . $e->getMessage();
        }
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
        $domainName = 'vendmeqniq.com';
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
                'HostedZoneId' => '/hostedzone/Z104641933KKYZTXEG1H6',
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
                'Id' => '/hostedzone/Z104641933KKYZTXEG1H6' // Z019631217VC5N0L30ZHI
            ]);

            echo 'Hosted zone deleted successfully.' . PHP_EOL;
        } catch (AwsException $e) {
            // Handle any errors that occur
            echo 'Error deleting hosted zone: ' . $e->getMessage();
        }
    }

    /**
     * Returns the certificatecreated for a domain, with records to validate domain ownership
     *
     * @param String $certificateArn
     * @return void
     */
    // public function describeCertificate(String $certificateArn, String $hostedZoneId) {
    //     $certificateArn = $certificateArn;
    //     try {
    //         $result = $this->acmClient->describeCertificate([
    //             'CertificateArn' => $certificateArn,
    //         ]);
    //         $domainValidationOptions = $result->get('Certificate')['DomainValidationOptions'];
    //         // print_r($domainValidationOptions[0]);

    //         // foreach($domainValidationOptions as $value) {
    //             $this->addDNSRecord($domainValidationOptions[0], $hostedZoneId);
    //         //     echo
    //         // }
    //     } catch (AcmException $e) {
    //         $this->sendOutput(json_encode(array('error' => $e->getAwsErrorMessage())),
    //             array('Content-Type: application/json', 'HTTP/1.1 401 Client Not Found')
    //         );
    //     }
    // }

    /**
     * Lists all certificates
     *
     * @return void
     */
    // public function listCertificate() {
    //     $result = $this->acmClient->listCertificates([
    //         // additional options
    //     ]);

    //     $certificates = $result['CertificateSummaryList'];
    //     $certificateArn = "";
    //     foreach ($certificates as $certificate) {
    //         if ($certificate['DomainName'] === 'vendmeqniq.com') {
    //             $certificateArn = $certificate['CertificateArn'];
    //             break;
    //         }
    //     }
    //     $hostedZoneId = '';
    //     $response = $this->describeCertificate($certificateArn, $hostedZoneId);
    //     if ($response['Certificate']['Status'] == 'ISSUED') {
    //         // The certificate has been issued and is ready to use
    //         return true;
    //     } else {
    //         // The certificate is still being validated or there was an error
    //         return false;
    //     }
    // }

    /**
    * Makes a request for an SSL certificate
    *
    * @param Array $input
    * @return void
    */
    // public function requestCertificate(Array $input, String $hostedZoneId) {
    //     $domainName = $input['DomainName']; // Change to your domain name
    //     try {
    //         $request_result = $this->acmClient->requestCertificate([
    //             'DomainName' => $domainName,
    //             'ValidationMethod' => 'DNS',
    //             'SubjectAlternativeNames' => [
    //                 '*.'.$domainName, // Add any additional domains or subdomains here
    //             ],
    //         ]);
    //         $certificateArn = $request_result['CertificateArn'];
    //         if ($certificateArn !== null) {
    //             return $this->describeCertificate($certificateArn, $hostedZoneId);
    //         }
    //         else {
    //             echo ['status' => 404, 'message' => $request_result];
    //         }
    //     } catch (AcmException $e) {
    //         $this->sendOutput(json_encode(array('error' => $e->getAwsErrorMessage())),
    //             array('Content-Type: application/json', 'HTTP/1.1 401 Client Not Found')
    //         );
    //     }

    // }
}
