<?php

namespace Tests\Models\Cashbox;

use App\Models\Cashbox\PaymentType;
use App\Models\Cashbox\Transaction;
use App\Models\Orders\Orders;
use App\Models\Organizations\Organization;
use App\Models\User;
use TestCase;

class TransactionTest extends TestCase
{


    public function test_create_transaction()
    {

        $transaction = new Transaction([
            'order_id'           => Orders::first()->id,
            'organization_id'    => Organization::first()->id,
            'act_completed_work' => random_int(1, 100),
            'payment_type_id'    => array_rand(PaymentType::all()),
            'amount'             => random_int(100, 10000),
            'discount'           => random_int(1, 100),
            'created_user_id'    => User::all()->random()->id,
            'payment_user_id'    => User::all()->random()->id,
        ]);

        $this->assertNotNull($transaction->organization);

        $this->assertNull($transaction->bill);

        $this->assertNotNull($transaction->createdUser);

        $this->assertNotNull($transaction->paymentUser);

        $this->assertNotNull($transaction->paymentUser);

        $this->assertTrue(in_array($transaction->payment_type_id, array_keys(PaymentType::all())));

        $this->assertEquals(floatval($transaction->amount - $transaction->amount * ($transaction->discount / 100)), $transaction->total_amount);


    }

}