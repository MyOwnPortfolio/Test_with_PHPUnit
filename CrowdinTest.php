<?php

require('vendor/autoload.php');

class ApiTest extends PHPUnit_Framework_TestCase {
    
    protected $client;
    
    protected function setUp() {
        $this->client = new GuzzleHttp\Client();
    }

    public function testCreate_Project() {
    // data which require to create our project
        $post_data = [
                'login' => 'nazarenko_volodia',
                'name' => 'testcrowd',
                'identifier' => 'testcrowdtestcrowd',
                'source_language' => 'en',
                'languages[]' => 'fr',
                'join_policy' => 'private'
            ];
        // request that create our project
        $response = $this->client->post('https://api.crowdin.com/api/account/create-project?account-key=a41ce435be5e3e7566b6e3a0f158179b', [
            'form_params'=>$post_data 
        ]);
        // asset whether request was successful
        $this->assertEquals(200, $response->getStatusCode());
        $data = new SimpleXMLElement($response->getBody());
        $this->assertEquals(1, (integer)$data->success);

        // request that show us our projects
        $response_list_assert = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');
        
        // asset whether request was successful
        $this->assertEquals(200, $response_list_assert->getStatusCode());
        
        // assert whether there is created project in list of our projects
        $information = new SimpleXmlElement($response_list_assert->getBody());
        $proj_name = false;        
        foreach ($information as $item) {
            if ($item->name == 'testcrowd') {
                $proj_name = true;     
                break;
            };
        };
        $this->assertEquals(true, $proj_name);
    }

    public function testEdit_Project() {

        // request that show us our projects
        $assert_list = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');
        
        // asset whether request was successful
        $this->assertEquals(200, $assert_list->getStatusCode());
        
        // take the project key
        $information_list = new SimpleXmlElement($assert_list->getBody());
        $proj_key = 'smth';
        foreach ($information_list as $item) {
            if ($item->name == 'testcrowd') {
                $proj_key = $item->key;                
                break;
            };
        };

        // data which we use to edit our project
        $post_data = ['name' => 'testcrowd_new_name'];

        // request that create our project
        $response = $this->client->post('https://api.crowdin.com/api/project/testcrowdtestcrowd/edit-project?key='.$proj_key, ['form_params'=>$post_data]);

        // asset whether request was successful
        $this->assertEquals(200, $response->getStatusCode());
        $data = new SimpleXMLElement($response->getBody());
        $this->assertEquals(1, (integer)$data->success);

        // request that show us our projects
        $response_list_assert = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');

        // asset whether request was successful
        $this->assertEquals(200, $response_list_assert->getStatusCode());
        
        // assert whether there is edited project in list of our projects
        $information_assert = new SimpleXmlElement($response_list_assert->getBody());
        $proj_name = false;
        foreach ($information_assert as $item) {
            if ($item->name == 'testcrowd_new_name') {
                $proj_name = true;
                break;
            };
        };
        $this->assertEquals(true, $proj_name);
    }

    public function testInfo_Project() {

        // request that show us our projects
        $response_list = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');
        
        // asset whether request was successful
        $this->assertEquals(200, $response_list->getStatusCode());
        
        // take the project key
        $information_list = new SimpleXmlElement($response_list->getBody());
        $proj_key = 'smth';
        foreach ($information_list as $item) {
            if ($item->name == 'testcrowd_new_name') {
                $proj_key = $item->key;                
                break;
            };
        };
        
        // request that get info about our project
        $response = $this->client->post('https://api.crowdin.com/api/project/testcrowdtestcrowd/info?key='.$proj_key);

        // asset whether request was successful
        $this->assertEquals(200, $response->getStatusCode());
        $data = new SimpleXMLElement($response->getBody());
        $this->assertEquals('testcrowd_new_name', $data->details->name);
    }      

    public function testDelete_Project() {
        $response_list = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');
        
        // asset whether request was successful
        $this->assertEquals(200, $response_list->getStatusCode());
        
        // take the project key
        $information_list = new SimpleXmlElement($response_list->getBody());
        $proj_key = 'smth';
        foreach ($information_list as $item) {
            if ($item->name == 'testcrowd_new_name') {
                $proj_key = $item->key;                
                break;
            };
        };

        // request that delete our project
        $response = $this->client->get('https://api.crowdin.com/api/project/testcrowdtestcrowd/delete-project?key='.$proj_key);
        
        // asset whether request was successful
        $this->assertEquals(200, $response->getStatusCode());
        $data = new SimpleXmlElement($response->getBody());
        $this->assertEquals(1, (integer)$data->success);

        // request that show us our projects
        $response_list_assert = $this->client->post('https://api.crowdin.com/api/account/get-projects?account-key=a41ce435be5e3e7566b6e3a0f158179b&login=nazarenko_volodia');

        // asset whether request was successful
        $this->assertEquals(200, $response_list_assert->getStatusCode());
        
        // assert whether there isn't deleted project in list of our projects
        $information_assert = new SimpleXmlElement($response_list_assert->getBody());
        $proj_name = false;
        foreach ($information_assert as $item) {
            if ($item->name == 'testcrowd_new_name') {
                $proj_name = true;
                break;
            };
        };
        $this->assertEquals(false, $proj_name);
    }
}