<?php

namespace Tests\Controllers\Dictionaries;

use App\Models\User;
use TestCase;

class TrailersTypesTest extends TestCase
{

    public function test_index_page_unauthorized()
    {
        $this->visit('/dictionaries/trailers_types');
        $this->see('Login');
    }

    public function test_index_page_authorized()
    {
        $user = User::findOrFail(1);
        $this->actingAs($user);
        $this->visit('/dictionaries/trailers_types');
        $this->assertResponseOk();
    }

}