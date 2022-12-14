<?php

namespace Tests\Controllers\Settings;

use App\Models\User;
use App\Models\Settings\FinancialPolicy;
use TestCase;

class FinancialPoliciesTest extends TestCase
{

    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $this->user = User::findOrFail(1);
    }

    public function test_index_page()
    {

        $this->actingAs($this->user);

        $this->visit("/settings/financial_policy")->assertResponseOk();

    }
    public function test_edit_page()
    {

        $this->actingAs($this->user);

        $financialPolicy = FinancialPolicy::first();

        $this->visit("/settings/financial_policy/$financialPolicy->id/edit");

        $this->assertResponseOk();

    }
}