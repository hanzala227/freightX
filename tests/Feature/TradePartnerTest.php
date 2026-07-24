<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\TradePartner;
use App\Models\Country;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TradePartnerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_render_trade_partner_create_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/trade-partner/create');

        $response->assertOk();
    }

    public function test_can_store_and_update_trade_partner_with_type_mapping()
    {
        $user = User::factory()->create();
        
        $country = Country::firstOrCreate(
            ['code' => 'USA', 'iso_alpha2' => 'US'],
            ['name' => 'United States']
        );

        // 1. Test Store with frontend type 'CS'
        $response = $this
            ->actingAs($user)
            ->postJson('/trade-partner', [
                'type' => 'CS',
                'name' => 'Test Customer LLC',
                'print_name' => 'Test Customer LLC Print',
                'country_id' => $country->id,
                'phone' => '1234567890',
            ]);

        $response->assertStatus(200);
        
        $partner = TradePartner::where('name', 'Test Customer LLC')->first();
        $this->assertNotNull($partner);
        
        // Assert raw database value is 'CLIENT'
        $this->assertEquals('CLIENT', \DB::table('trade_partners')->where('id', $partner->id)->value('type'));
        // Assert Eloquent accessor returns frontend value 'CS'
        $this->assertEquals('CS', $partner->type);

        // 2. Test Update type to Vendor (frontend code 'VR' -> db value 'VENDOR')
        $updateResponse = $this
            ->actingAs($user)
            ->putJson("/trade-partner/{$partner->id}", [
                'type' => 'VR',
                'name' => 'Test Vendor LLC',
                'print_name' => 'Test Vendor LLC Print',
                'country_id' => $country->id,
                'phone' => '0987654321',
            ]);

        $updateResponse->assertOk();
        
        $partner->refresh();
        $this->assertEquals('Test Vendor LLC', $partner->name);
        $this->assertEquals('VENDOR', \DB::table('trade_partners')->where('id', $partner->id)->value('type'));
        $this->assertEquals('VR', $partner->type);
    }
}
