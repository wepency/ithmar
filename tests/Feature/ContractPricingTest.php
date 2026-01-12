<?php

namespace Tests\Feature;

use App\Models\Contract;
use App\Models\Sector;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\User;
use App\Models\Beach;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ContractPricingTest extends TestCase
{
    // use RefreshDatabase; 
    // Using RefreshDatabase might wipe the existing DB which seems to be used. 
    // I'll rely on creating temporary data and cleaning it up or using transactions if possible.
    // Given the context, I will create data and assume the DB is persistent.

    protected function setUp(): void
    {
        parent::setUp();
        
        // Ensure settings exist for default test
        if (!Setting::first()) {
            Setting::create([
                'price_before_vat' => '100',
                'price_after_vat' => '115'
            ]);
        }
    }

    public function test_contract_uses_unit_pricing_when_set()
    {
        $user = User::factory()->create();
        $this->actingAs($user); // Assuming authentication is needed, though middleware might be mocked or bypassed if needed.
        // However, the controller uses 'sectorAdmin' middleware. I might need to mock a sector admin user.
        
        // Create a user with sector admin role or similar if needed. 
        // For now, I'll mock the auth user.
        
        $sector = Sector::create([
            'sector_name' => 'Test Sector Unit Pricing',
            'user_id' => $user->id,
            'percentage' => 10,
            'price' => 200,
            'vat' => 30, 
            'total' => 230
        ]);

        $beach = Beach::create([
            'beach' => 'Test Beach',
            'sector_id' => $sector->id
        ]);

        $unit = Unit::create([
            'unit_number' => rand(1000, 9999),
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'user_id' => $user->id,
            'price' => 300,
            'vat' => 45,
            'total' => 345,
            'attachment_1' => 'test.jpg',
            'attachment_2' => 'test2.jpg'
        ]);

        // Simulate request data
        $data = [
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'unit_id' => $unit->id,
            'from' => '2026-01-20',
            'to' => '2026-01-25',
            'tenant_name' => 'Test Tenant',
            'tenant_name_code' => '1234567890',
            'with_tenant_title' => 'Mr',
            'with_tenant_name' => 'Friend',
            'with_tenant_name_code' => '0987654321',
            'rent_value' => 1000,
            'tenant_nationality' => 'Saudi',
            'with_tenant_nationality' => 'Saudi',
            'insurance_value' => 500,
            'phonenumber' => '0500000000',
            'car' => [],
            'tenant_title' => 'Mr'
        ];
        
        // We need to hit the store endpoint or call the logic directly. 
        // Calling the endpoint is better for integration testing.
        // Assuming route is 'admin.contract.store'
        
        // Mocking middleware/auth if needed. 
        // Ideally, we should set up a user with proper permissions.
        // For this environment, I'll try to act as a user who can create contracts.
        
        // Using a direct call to the controller logic via a route might fail due to middleware. 
        // I will try to call the route.
        
        $response = $this->post(route('admin.contract.store'), $data);

        // Assert contract was created
        $contract = Contract::where('unit_id', $unit->id)->latest('id')->first();
        
        $this->assertNotNull($contract);
        $this->assertEquals(300, $contract->price);
        $this->assertEquals(45, $contract->vat);
        $this->assertEquals(345, $contract->total);
        
        // Cleanup
        $contract->delete();
        $unit->delete();
        $beach->delete();
        $sector->delete();
        $user->delete();
    }

    public function test_contract_uses_sector_pricing_when_unit_price_is_zero()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $sector = Sector::create([
            'sector_name' => 'Test Sector Pricing',
            'user_id' => $user->id,
            'percentage' => 10,
            'price' => 200,
            'vat' => 30,
            'total' => 230
        ]);

        $beach = Beach::create([
            'beach' => 'Test Beach Sector',
            'sector_id' => $sector->id
        ]);

        $unit = Unit::create([
            'unit_number' => rand(1000, 9999),
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'user_id' => $user->id,
            'price' => 0, // Zero price
            'vat' => 0,
            'total' => 0,
            'attachment_1' => 'test.jpg',
            'attachment_2' => 'test2.jpg'
        ]);

        $data = [
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'unit_id' => $unit->id,
            'from' => '2026-02-20',
            'to' => '2026-02-25',
            'tenant_name' => 'Test Tenant',
            'tenant_name_code' => '1234567890',
            'with_tenant_title' => 'Mr',
            'with_tenant_name' => 'Friend',
            'with_tenant_name_code' => '0987654321',
            'rent_value' => 1000,
            'tenant_nationality' => 'Saudi',
            'with_tenant_nationality' => 'Saudi',
            'insurance_value' => 500,
            'phonenumber' => '0500000000',
            'car' => [],
            'tenant_title' => 'Mr'
        ];

        $this->post(route('admin.contract.store'), $data);

        $contract = Contract::where('unit_id', $unit->id)->latest('id')->first();

        $this->assertNotNull($contract);
        $this->assertEquals(200, $contract->price);
        $this->assertEquals(30, $contract->vat);
        $this->assertEquals(230, $contract->total);

        // Cleanup
        $contract->delete();
        $unit->delete();
        $beach->delete();
        $sector->delete();
        $user->delete();
    }

    public function test_contract_uses_settings_pricing_when_unit_and_sector_prices_are_zero()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $settings = Setting::first();
        // Assuming settings are 100, calculated vat, 115 (or similar based on vat calc)

        $sector = Sector::create([
            'sector_name' => 'Test Default Pricing',
            'user_id' => $user->id,
            'percentage' => 10,
            'price' => 0,
            'vat' => 0,
            'total' => 0
        ]);

        $beach = Beach::create([
            'beach' => 'Test Beach Default',
            'sector_id' => $sector->id
        ]);

        $unit = Unit::create([
            'unit_number' => rand(1000, 9999),
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'user_id' => $user->id,
            'price' => 0,
            'vat' => 0,
            'total' => 0,
            'attachment_1' => 'test.jpg',
            'attachment_2' => 'test2.jpg'
        ]);

        $data = [
            'sector_id' => $sector->id,
            'beach_id' => $beach->id,
            'unit_id' => $unit->id,
            'from' => '2026-03-20',
            'to' => '2026-03-25',
            'tenant_name' => 'Test Tenant',
            'tenant_name_code' => '1234567890',
            'with_tenant_title' => 'Mr',
            'with_tenant_name' => 'Friend',
            'with_tenant_name_code' => '0987654321',
            'rent_value' => 1000,
            'tenant_nationality' => 'Saudi',
            'with_tenant_nationality' => 'Saudi',
            'insurance_value' => 500,
            'phonenumber' => '0500000000',
            'car' => [],
            'tenant_title' => 'Mr'
        ];

        $this->post(route('admin.contract.store'), $data);

        $contract = Contract::where('unit_id', $unit->id)->latest('id')->first();

        $this->assertNotNull($contract);
        $this->assertEquals($settings->price_before_vat, $contract->price);
        $this->assertEquals($settings->price_after_vat, $contract->total);
        // VAT calculation might vary, checking price and total is usually enough for logic verification

        // Cleanup
        $contract->delete();
        $unit->delete();
        $beach->delete();
        $sector->delete();
        $user->delete();
    }
}
