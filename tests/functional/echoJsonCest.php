<?php


class echoJsonCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    // tests
    public function tryToTest(FunctionalTester $I)
    {
        $I->wantTo('test get token');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendPOST('/api/token', [
            'data' => [
                'attributes' => [
                    'username' => '',
                    'password' => '',
                ]
            ]
        ]);
//        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }
}
