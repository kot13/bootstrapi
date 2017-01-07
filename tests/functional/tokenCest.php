<?php


class tokenCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function success(FunctionalTester $I)
    {
        $I->wantTo('test success get token');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/token', [
            'data' => [
                'attributes' => [
                    'username' => 'admin@example.com',
                    'password' => 'qwerty',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//access_token');
    }

    public function fail(FunctionalTester $I)
    {
        $I->wantTo('test fail get token');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/token', [
            'data' => [
                'attributes' => [
                    'username' => 'admin@example.com',
                    'password' => 'xxx',
                ]
            ]
        ]);
        $I->seeResponseCodeIs(400);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//errors');
    }
}
