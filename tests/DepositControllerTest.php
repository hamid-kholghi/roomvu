<?php

namespace Tests;

use Laravel\Lumen\Testing\DatabaseMigrations;

class DepositControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function test_get_balance_for_unknown_user_should_return_zero()
    {
        $this->get('/api/v1/get-balance/1');
        $this->assertResponseOk();
        $this->seeJson(['balance' => 0]);
    }

    public function test_increase_money_successful()
    {
        $this->post('/api/v1/add-money', [
            'user_id' => 1,
            'amount' => 100000
        ]);

        $this->assertResponseOk();
        $this->seeInDatabase('deposits', [
            'user_id' => 1,
            'credit' => 100000,
            'debit' => 0,
        ]);
    }

    public function test_decrease_money_successful()
    {
        $this->post('/api/v1/add-money', [
            'user_id' => 1,
            'amount' => -100000
        ]);

        $this->assertResponseOk();
        $this->seeInDatabase('deposits', [
            'user_id' => 1,
            'credit' => 0,
            'debit' => 100000,
        ]);
    }
}
