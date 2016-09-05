<?php

require('vendor/autoload.php');

class ApiTest extends PHPUnit_Framework_TestCase
{    
    protected $client;
    protected function setUp()
    {
        $this->client = new GuzzleHttp\Client([
            'base_uri' => 'https://api.crowdin.com/api'
        ]);
    }

    public function testCreate_Project($login, $name, $key)
    $identifier = 'https://crowdin.com/project/'.$name
    {
        $response = $this->client->post('/account/create-project?account-key={'.$key.'}', [
            'json' => [
                'login' => $login,
                'name' => $name,
                'identifier' => $identifier,
                'source_language' => 'en',
                'languages' => 'ua',
                'join_policy' => 'private'
            ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = simplexml_load_string($response->getBody());
        $this->assertEquals(1, $data['project']['success']);

        $assert_created_project = $this->client->post('project/{'.$identifier.'}/info?key={'.$key.'}')
        $this->assertEquals(200, $assert_new_project->getStatusCode());
        $data = simplexml_load_string($assert_new_project->getBody());
        $this->assertEquals($name, $data['info']['details']['name']);
    }

    public function testEdit_Project($identifier, $key, $new_name)    
    {
        $response = $this->client->post('/project/{'.$identifier.'}/edit-project?key={'.$key.'}', [
            'json' => [
                'name' => $new_name
                ]
        ]);
        $this->assertEquals(200, $response->getStatusCode());
        $data = simplexml_load_string($response->getBody());
        $this->assertEquals(1, $data['project']['success']);

        $assert_edited_project = $this->client->post('project/{'.$identifier.'}/info?key={'.$key.'}')
        $this->assertEquals(200, $assert_edited_project->getStatusCode());
        $data = simplexml_load_string($assert_edited_project->getBody());
        $this->assertEquals($new_name, $data['info']['details']['name']);
    }

    public function testDelete_Project($identifier, $key)    
    {
        $response = $this->client->get('/project/{'.$identifier.'}/delete-project?key={'.$key.'}');
        $this->assertEquals(200, $response->getStatusCode());
        $data = simplexml_load_string($response->getBody());
        $this->assertArrayHasKey('success', $data);

        $assert_edited_project = $this->client->post('project/{'.$identifier.'}/info?key={'.$key.'}')
        $this->assertEquals(200, $assert_edited_project->getStatusCode());
        $data = simplexml_load_string($assert_edited_project->getBody());
        $this->assertArrayNotHasKey($identifier, $data['info']['details']['identifier']);
    }
}
