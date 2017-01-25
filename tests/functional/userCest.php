<?php


class userCest
{
    public function _before(FunctionalTester $I)
    {
    }

    public function _after(FunctionalTester $I)
    {
    }

    public function getList(FunctionalTester $I)
    {
        $I->wantTo('test get user list');
        $I->haveHttpHeader('Authorization', $I->getAccessToken($I));
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/user');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function getOne(FunctionalTester $I)
    {
        $I->wantTo('test get one user');
        $I->haveHttpHeader('Authorization', $I->getAccessToken($I));
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/user/1');

        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
    }

    public function getListAccessDenied(FunctionalTester $I)
    {
        $I->wantTo('test get user list without auth');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/user');

        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//errors');
    }

    public function getOneAccessDenied(FunctionalTester $I)
    {
        $I->wantTo('test get one user without auth');
        $I->haveHttpHeader('Content-Type', 'application/json');
        $I->sendGET('/api/user/1');

        $I->seeResponseCodeIs(401);
        $I->seeResponseIsJson();
        $I->seeResponseJsonMatchesXpath('//errors');
    }
}
