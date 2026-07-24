<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OceanBooking;
use App\Models\WorkOrder;
use App\Models\TradePartner;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkOrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_work_order_for_ocean_booking()
    {
        $user = User::factory()->create();
        
        $booking = OceanBooking::create([
            'booking_no' => 'BKG-TEST-123',
            'booking_date' => now()->format('Y-m-d'),
            'status' => 'OPEN',
        ]);
        
        $response = $this
            ->actingAs($user)
            ->get(route('ocean-export.work-order.create', [
                'workable_type' => 'App\Models\OceanBooking',
                'workable_id' => $booking->id
            ]));
            
        $response->assertOk();
        $response->assertSee('BKG-TEST-123');
    }

    public function test_can_store_and_retrieve_work_order()
    {
        $user = User::factory()->create();
        
        $booking = OceanBooking::create([
            'booking_no' => 'BKG-TEST-456',
            'booking_date' => now()->format('Y-m-d'),
            'status' => 'OPEN',
        ]);
        
        $trucker = TradePartner::create([
            'type' => 'TRUCKER',
            'code' => 'TEST_TRUCKER',
            'name' => 'Test Trucking LLC',
            'status' => 'BUSINESS',
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('ocean-export.work-order.store'), [
                'work_order_no' => 'WO-TEST-999',
                'workable_type' => 'App\Models\OceanBooking',
                'workable_id' => $booking->id,
                'vendor_id' => $trucker->id,
                'issue_date' => now()->format('Y-m-d'),
                'subject' => 'PICKUP & DELIVERY ORDER',
                'status' => 'PENDING',
                'carrier_bkg_no' => 'BKG-TEST-456',
                'total_packages' => 10,
                'package_unit' => 'CARTON(S)',
            ]);

        $response->assertRedirect();
        
        $this->assertDatabaseHas('work_orders', [
            'work_order_no' => 'WO-TEST-999',
            'workable_type' => 'App\Models\OceanBooking',
            'workable_id' => $booking->id,
            'vendor_id' => $trucker->id,
        ]);
        
        // Test API Index retrieves it
        $apiResponse = $this
            ->actingAs($user)
            ->getJson("/api/work-orders?workable_type=App%5CModels%5COceanBooking&workable_id={$booking->id}");
            
        $apiResponse->assertOk();
        $apiResponse->assertJsonFragment([
            'no' => 'WO-TEST-999',
            'trucker' => 'Test Trucking LLC',
        ]);
    }
}
