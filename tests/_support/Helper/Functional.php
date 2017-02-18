<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class Functional extends \Codeception\Module
{
    const prefix = 'Bearer ';

    private $access_token = null;

    function getAccessToken(\FunctionalTester $I)
    {
        if (is_null($this->access_token)) {
            $I->haveHttpHeader('Content-Type', 'application/json');
            $I->sendPOST('/api/token', [
                'data' => [
                    'attributes' => [
                        'username' => 'admin@example.com',
                        'password' => 'qwerty',
                    ]
                ]
            ]);

            $response = json_decode($I->grabResponse(), true);

            $this->access_token = isset($response['access_token']) ? $response['access_token'] : null;
        }

        return self::prefix.$this->access_token;
    }
}
