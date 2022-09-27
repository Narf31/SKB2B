<?php

namespace Tests\Integrations\Integration;

use App\Models\Settings\Integrations;
use App\Models\Settings\IntegrationsVersions;
use App\Models\Settings\IntegrationsVersionsMainFormValues;
use App\Models\Settings\IntegrationsVersionsSupplierFormValues;
use TestCase;

class IntegrationTest extends TestCase {

    public function test_integrations() {

        $integration = new Integrations([
            'title' => 'Test',
            'description' => 'Test Description',
            'active' => '0',
        ]);

        $this->assertNotNull($integration->title);
        $this->assertNotNull($integration->description);
        $this->assertEquals($integration->active, 0);
        $integration->active = 1;
        $this->assertEquals($integration->active, 1);
        $this->assertEquals($integration->title, 'Test');
        $this->assertEquals($integration->description, 'Test Description');
    }

}
