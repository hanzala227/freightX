<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\OceanImport;
use App\Models\OceanImportHbl;
use App\Models\OceanImportContainer;
use App\Models\Office;
use App\Models\TradePartner;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class OceanImportTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(\Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class);
    }

    public function test_all_fields_persist_on_create_and_update()
    {
        $user = User::factory()->create();
        $office = Office::create(['code' => 'NYC', 'name' => 'New York Office', 'is_active' => true]);
        $port = \App\Models\Port::first();
        if (!$port) {
            \App\Models\Port::create(['code' => 'USNYC', 'name' => 'New York', 'country_id' => 1]);
            $port = \App\Models\Port::first();
        }
        $vessel = \App\Models\Vessel::first();
        if (!$vessel) {
            \App\Models\Vessel::create(['code' => 'VSL01', 'name' => 'Test Vessel']);
            $vessel = \App\Models\Vessel::first();
        }

        $storeData = [
            'file_no' => 'MOI-TEST-ALL-FIELDS-1',
            'mbl_no' => 'MBL-TEST-ALL-FIELDS-1',
            'post_date' => '2026-06-01',
            'office_id' => $office->id,
            'op_id' => $user->id,
            'forwarding_agent_id' => null,
            'oversea_agent_id' => null,
            'co_loader_id' => null,
            'carrier_id' => null,
            'acct_carrier_id' => null,
            'business_referred_by_id' => null,
            'is_direct_master' => true,
            'dm_customer_id' => null,
            'dm_shipper_id' => null,
            'dm_consignee_id' => null,
            'dm_notify_id' => null,
            'dm_bill_to_id' => null,
            'dm_sales_person_id' => $user->id,
            'agent_ref_no' => 'AGENT-REF-001',
            'contract_no' => 'CONTRACT-001',
            'sub_bl_no' => 'SUB-BL-001',
            'bl_type' => 'MEMO',
            'cargo_type' => 'GENERAL CARGO',
            'ship_mode' => 'LCL',
            'vessel_id' => $vessel->id,
            'voyage' => 'VOY-001',
            'pol_id' => $port->id,
            'pod_id' => $port->id,
            'del_id' => $port->id,
            'fdest_id' => $port->id,
            'receipt_id' => $port->id,
            'etd' => '2026-06-10',
            'eta' => '2026-06-20',
            'atd' => '2026-06-11',
            'ata' => '2026-06-19',
            'etb' => '2026-06-12',
            'final_eta' => '2026-06-22',
            'receipt_etd' => '2026-06-08',
            'cy_location_id' => null,
            'cfs_location_id' => null,
            'return_location_id' => null,
            'service_term_from_id' => null,
            'service_term_to_id' => null,
            'freight_term' => 'Prepaid',
            'obl_type' => 'ORIGINAL BILL OF LADING',
            'obl_received_date' => '2026-06-15',
            'is_obl_received' => true,
            'released_date' => '2026-06-18',
            'is_released' => true,
            'latest_gate_in' => '2026-06-16',
            'is_ecommerce' => true,
            'ams_no' => 'AMS123456',
            'isf_no' => 'ISF123456',
            'isf_matched_date' => '2026-06-17',
            'is_isf_3rd_party' => true,
            'entry_no' => 'ENTRY-001',
            'entry_doc_sent_date' => '2026-06-14',
            'go_date' => '2026-06-21',
            'available_date' => '2026-06-19',
            'c_released_date' => '2026-06-20',
            'released_by_id' => $user->id,
            'is_ror' => true,
            'is_hold' => true,
            'door_delivery_date' => '2026-06-25',
            'trucker_id' => null,
            'expiry_date' => '2026-07-01',
            'sales_type' => 'NORMAL',
            'incoterm_id' => '1',
            'internal_remark' => 'Test internal remark',
        ];

        // STORE
        $response = $this->actingAs($user)->post('/ocean-import', $storeData);
        $response->assertRedirect();

        $shipment = OceanImport::where('file_no', 'MOI-TEST-ALL-FIELDS-1')->first();
        $this->assertNotNull($shipment);

        // Verify every field persisted on CREATE
        $expectedCreate = array_merge($storeData, [
            'is_direct_master' => true,
            'is_ecommerce' => true,
            'is_obl_received' => true,
            'is_released' => true,
            'is_isf_3rd_party' => true,
            'is_ror' => true,
            'is_hold' => true,
            'incoterm_id' => '1',
        ]);
        foreach ($expectedCreate as $field => $value) {
            if (in_array($field, ['post_date', 'etd', 'eta', 'atd', 'ata', 'etb', 'final_eta', 'receipt_etd', 'obl_received_date', 'released_date', 'latest_gate_in', 'isf_matched_date', 'entry_doc_sent_date', 'go_date', 'available_date', 'c_released_date', 'door_delivery_date', 'expiry_date'])) {
                continue; // dates need special handling
            }
            $this->assertEquals($value, $shipment->$field, "Field $field failed on CREATE. Expected: " . json_encode($value) . ", Got: " . json_encode($shipment->$field));
        }

        // Verify dates on CREATE
        $this->assertEquals('2026-06-01', $shipment->post_date->format('Y-m-d'));
        $this->assertEquals('2026-06-10', $shipment->etd->format('Y-m-d'));
        $this->assertEquals('2026-06-20', $shipment->eta->format('Y-m-d'));
        $this->assertEquals('2026-06-11', $shipment->atd->format('Y-m-d'));
        $this->assertEquals('2026-06-19', $shipment->ata->format('Y-m-d'));
        $this->assertEquals('2026-06-12', $shipment->etb->format('Y-m-d'));
        $this->assertEquals('2026-06-22', $shipment->final_eta->format('Y-m-d'));
        $this->assertEquals('2026-06-08', $shipment->receipt_etd->format('Y-m-d'));
        $this->assertEquals('2026-06-15', $shipment->obl_received_date->format('Y-m-d'));
        $this->assertEquals('2026-06-18', $shipment->released_date->format('Y-m-d'));
        $this->assertEquals('2026-06-16', $shipment->latest_gate_in->format('Y-m-d'));
        $this->assertEquals('2026-06-17', $shipment->isf_matched_date->format('Y-m-d'));
        $this->assertEquals('2026-06-14', $shipment->entry_doc_sent_date->format('Y-m-d'));
        $this->assertEquals('2026-06-21', $shipment->go_date->format('Y-m-d'));
        $this->assertEquals('2026-06-19', $shipment->available_date->format('Y-m-d'));
        $this->assertEquals('2026-06-20', $shipment->c_released_date->format('Y-m-d'));
        $this->assertEquals('2026-06-25', $shipment->door_delivery_date->format('Y-m-d'));
        $this->assertEquals('2026-07-01', $shipment->expiry_date->format('Y-m-d'));

        // UPDATE - change all values
        $updateData = [
            'file_no' => 'MOI-TEST-ALL-FIELDS-1',
            'mbl_no' => 'MBL-TEST-ALL-FIELDS-1',
            'post_date' => '2026-06-02',
            'office_id' => $office->id,
            'op_id' => $user->id,
            'is_direct_master' => false,
            'dm_sales_person_id' => $user->id,
            'agent_ref_no' => 'AGENT-REF-002',
            'contract_no' => 'CONTRACT-002',
            'sub_bl_no' => 'SUB-BL-002',
            'bl_type' => 'NORMAL',
            'cargo_type' => 'GENERAL CARGO',
            'ship_mode' => 'FCL',
            'vessel_id' => $vessel->id,
            'voyage' => 'VOY-002',
            'pol_id' => $port->id,
            'pod_id' => $port->id,
            'del_id' => $port->id,
            'fdest_id' => $port->id,
            'receipt_id' => $port->id,
            'etd' => '2026-07-10',
            'eta' => '2026-07-20',
            'atd' => '2026-07-11',
            'ata' => '2026-07-19',
            'etb' => '2026-07-12',
            'final_eta' => '2026-07-22',
            'receipt_etd' => '2026-07-08',
            'freight_term' => 'Collect',
            'obl_type' => 'SEA WAYBILL',
            'obl_received_date' => '2026-07-15',
            'is_obl_received' => false,
            'released_date' => '2026-07-18',
            'is_released' => false,
            'latest_gate_in' => '2026-07-16',
            'is_ecommerce' => false,
            'ams_no' => 'AMS654321',
            'isf_no' => 'ISF654321',
            'isf_matched_date' => '2026-07-17',
            'is_isf_3rd_party' => false,
            'entry_no' => 'ENTRY-002',
            'entry_doc_sent_date' => '2026-07-14',
            'go_date' => '2026-07-21',
            'available_date' => '2026-07-19',
            'c_released_date' => '2026-07-20',
            'released_by_id' => $user->id,
            'is_ror' => false,
            'is_hold' => false,
            'door_delivery_date' => '2026-07-25',
            'expiry_date' => '2026-08-01',
            'sales_type' => 'CO-LOAD',
            'incoterm_id' => '2',
            'internal_remark' => 'Updated internal remark',
        ];

        $updateResponse = $this
            ->actingAs($user)
            ->put("/ocean-import/{$shipment->id}", $updateData);
        $updateResponse->assertRedirect();

        $shipment->refresh();

        // Verify every field updated
        $expectedUpdate = array_merge($updateData, [
            'is_direct_master' => false,
            'is_ecommerce' => false,
            'is_obl_received' => false,
            'is_released' => false,
            'is_isf_3rd_party' => false,
            'is_ror' => false,
            'is_hold' => false,
            'incoterm_id' => '2',
        ]);
        foreach ($expectedUpdate as $field => $value) {
            if (in_array($field, ['post_date', 'etd', 'eta', 'atd', 'ata', 'etb', 'final_eta', 'receipt_etd', 'obl_received_date', 'released_date', 'latest_gate_in', 'isf_matched_date', 'entry_doc_sent_date', 'go_date', 'available_date', 'c_released_date', 'door_delivery_date', 'expiry_date'])) {
                continue;
            }
            $this->assertEquals($value, $shipment->$field, "Field $field failed on UPDATE. Expected: " . json_encode($value) . ", Got: " . json_encode($shipment->$field));
        }

        // Verify dates on UPDATE
        $this->assertEquals('2026-06-02', $shipment->post_date->format('Y-m-d'));
        $this->assertEquals('2026-07-10', $shipment->etd->format('Y-m-d'));
        $this->assertEquals('2026-07-20', $shipment->eta->format('Y-m-d'));
        $this->assertEquals('2026-07-11', $shipment->atd->format('Y-m-d'));
        $this->assertEquals('2026-07-19', $shipment->ata->format('Y-m-d'));
        $this->assertEquals('2026-07-12', $shipment->etb->format('Y-m-d'));
        $this->assertEquals('2026-07-22', $shipment->final_eta->format('Y-m-d'));
        $this->assertEquals('2026-07-08', $shipment->receipt_etd->format('Y-m-d'));
        $this->assertEquals('2026-07-15', $shipment->obl_received_date->format('Y-m-d'));
        $this->assertEquals('2026-07-18', $shipment->released_date->format('Y-m-d'));
        $this->assertEquals('2026-07-16', $shipment->latest_gate_in->format('Y-m-d'));
        $this->assertEquals('2026-07-17', $shipment->isf_matched_date->format('Y-m-d'));
        $this->assertEquals('2026-07-14', $shipment->entry_doc_sent_date->format('Y-m-d'));
        $this->assertEquals('2026-07-21', $shipment->go_date->format('Y-m-d'));
        $this->assertEquals('2026-07-19', $shipment->available_date->format('Y-m-d'));
        $this->assertEquals('2026-07-20', $shipment->c_released_date->format('Y-m-d'));
        $this->assertEquals('2026-07-25', $shipment->door_delivery_date->format('Y-m-d'));
        $this->assertEquals('2026-08-01', $shipment->expiry_date->format('Y-m-d'));

        // Verify Filing endpoint works
        $filingResponse = $this
            ->actingAs($user)
            ->put("/ocean-import/{$shipment->id}/filing", [
                'ams_no' => 'AMS-FILING',
                'isf_no' => 'ISF-FILING',
                'isf_matched_date' => '2026-09-01',
                'is_isf_3rd_party' => true,
                'entry_no' => 'ENTRY-FILING',
                'entry_doc_sent_date' => '2026-09-02',
                'go_date' => '2026-09-03',
                'available_date' => '2026-09-04',
                'c_released_date' => '2026-09-05',
                'released_by_id' => $user->id,
                'is_ror' => true,
                'is_hold' => true,
                'door_delivery_date' => '2026-09-06',
                'expiry_date' => '2026-10-01',
                'sales_type' => 'NORMAL',
                'incoterm_id' => '1',
            ]);
        $filingResponse->assertJson(['success' => true]);
        $shipment->refresh();
        $this->assertEquals('AMS-FILING', $shipment->ams_no);
        $this->assertEquals('ISF-FILING', $shipment->isf_no);
        $this->assertEquals('2026-09-01', $shipment->isf_matched_date->format('Y-m-d'));
        $this->assertTrue($shipment->is_isf_3rd_party);
        $this->assertEquals('ENTRY-FILING', $shipment->entry_no);
        $this->assertEquals('2026-09-02', $shipment->entry_doc_sent_date->format('Y-m-d'));
        $this->assertEquals('2026-09-03', $shipment->go_date->format('Y-m-d'));
        $this->assertEquals('2026-09-04', $shipment->available_date->format('Y-m-d'));
        $this->assertEquals('2026-09-05', $shipment->c_released_date->format('Y-m-d'));
        $this->assertEquals($user->id, $shipment->released_by_id);
        $this->assertTrue($shipment->is_ror);
        $this->assertTrue($shipment->is_hold);
        $this->assertEquals('2026-09-06', $shipment->door_delivery_date->format('Y-m-d'));
        $this->assertEquals('2026-10-01', $shipment->expiry_date->format('Y-m-d'));
        $this->assertEquals('NORMAL', $shipment->sales_type);
        $this->assertEquals('1', $shipment->incoterm_id);
    }

    public function test_can_render_ocean_import_create_form()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/ocean-import/create');

        $response->assertOk();
    }

    public function test_can_store_and_update_ocean_import_with_hbls_and_containers()
    {
        $user = User::factory()->create();
        
        $office = Office::create([
            'code' => 'NYC',
            'name' => 'New York Office',
            'is_active' => true,
        ]);

        $agent = TradePartner::create([
            'type' => 'CLIENT',
            'code' => 'AGENT01',
            'name' => 'Agent One LLC',
            'status' => 'BUSINESS',
        ]);

        // 1. Test Store
        $response = $this
            ->actingAs($user)
            ->post('/ocean-import', [
                'file_no' => 'MOI-TEST-1234',
                'mbl_no' => 'MBL-TEST-1234',
                'office_id' => $office->id,
                'post_date' => now()->format('Y-m-d'),
                'is_direct_master' => '1',
                'is_ecommerce' => '0',
                'hbls' => [
                    [
                        'hbl_no' => 'HBL-STORED-01',
                        'lc_no' => 'LC111',
                        'is_express_bl' => '1',
                        'is_door_move' => '0',
                    ]
                ],
                'containers' => [
                    [
                        'container_no' => 'CONT-1111',
                        'seal_no' => 'SEAL-STORED-1',
                        'weight_kg' => '2500',
                    ]
                ]
            ]);

        $response->assertRedirect();
        
        $shipment = OceanImport::where('mbl_no', 'MBL-TEST-1234')->first();
        $this->assertNotNull($shipment);
        $this->assertTrue($shipment->is_direct_master);
        $this->assertFalse($shipment->is_ecommerce);
        
        $this->assertDatabaseHas('ocean_import_hbls', [
            'ocean_import_id' => $shipment->id,
            'hbl_no' => 'HBL-STORED-01',
            'lc_no' => 'LC111',
            'is_express_bl' => 1,
            'is_door_move' => 0,
        ]);
        
        $this->assertDatabaseHas('ocean_import_containers', [
            'ocean_import_id' => $shipment->id,
            'container_no' => 'CONT-1111',
            'seal_no' => 'SEAL-STORED-1',
        ]);

        // 2. Test Update (Modifying, adding, and deleting)
        $hbl = $shipment->hbls()->first();
        $container = $shipment->containers()->first();

        $updateResponse = $this
            ->actingAs($user)
            ->from("/ocean-import/{$shipment->id}/edit")
            ->put("/ocean-import/{$shipment->id}", [
                'file_no' => 'MOI-TEST-1234',
                'mbl_no' => 'MBL-TEST-1234',
                'office_id' => $office->id,
                'post_date' => now()->format('Y-m-d'),
                'is_direct_master' => '0', // switched off
                'is_ecommerce' => '1', // switched on
                'hbls' => [
                    // Update existing
                    [
                        'id' => $hbl->id,
                        'hbl_no' => 'HBL-STORED-01-UPDATED',
                        'lc_no' => 'LC222',
                        'is_express_bl' => '0',
                        'is_door_move' => '1',
                    ],
                    // Add new HBL
                    [
                        'hbl_no' => 'HBL-NEW-02',
                        'lc_no' => 'LC333',
                    ]
                ],
                'containers' => [
                    // Omit the old container (this should delete it!)
                    // Add new container
                    [
                        'container_no' => 'CONT-2222',
                        'seal_no' => 'SEAL-NEW-2',
                    ]
                ]
            ]);

        $updateResponse->assertRedirect();
        
        $shipment->refresh();
        $this->assertFalse($shipment->is_direct_master);
        $this->assertTrue($shipment->is_ecommerce);

        // Check HBLs are updated & new one is added
        $this->assertDatabaseHas('ocean_import_hbls', [
            'id' => $hbl->id,
            'hbl_no' => 'HBL-STORED-01-UPDATED',
            'lc_no' => 'LC222',
            'is_express_bl' => 0,
            'is_door_move' => 1,
        ]);
        $this->assertDatabaseHas('ocean_import_hbls', [
            'ocean_import_id' => $shipment->id,
            'hbl_no' => 'HBL-NEW-02',
            'lc_no' => 'LC333',
        ]);

        // Check omitted container is deleted from database
        $this->assertSoftDeleted('ocean_import_containers', [
            'id' => $container->id,
        ]);

        // Check new container is created
        $this->assertDatabaseHas('ocean_import_containers', [
            'ocean_import_id' => $shipment->id,
            'container_no' => 'CONT-2222',
            'seal_no' => 'SEAL-NEW-2',
        ]);
    }

    public function test_can_render_ocean_import_containers_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/ocean-import/list/containers');

        $response->assertOk();
    }

    public function test_can_render_ocean_import_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/ocean-import/list');

        $response->assertOk();
    }

    public function test_can_render_ocean_import_mbl_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/ocean-import/list/mbl');

        $response->assertOk();
    }

    public function test_can_render_ocean_import_hbl_list()
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/ocean-import/list/hbl');

        $response->assertOk();
    }

    public function test_can_create_port_via_api_without_country_id()
    {
        $user = User::factory()->create();
        if (!\App\Models\Country::exists()) {
            \App\Models\Country::create(['code' => 'TST', 'iso_alpha2' => 'TS', 'name' => 'Test Country']);
        }

        $response = $this
            ->actingAs($user)
            ->postJson('/api/ports', [
                'name' => 'London Port',
                'code' => 'GBLON',
            ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('ports', [
            'name' => 'London Port',
            'code' => 'GBLON',
        ]);
    }

    public function test_can_store_and_update_ocean_import_with_hbl_details()
    {
        $user = User::factory()->create();
        
        $office = Office::create([
            'code' => 'NYC',
            'name' => 'New York Office',
            'is_active' => true,
        ]);

        // 1. Test Store with HBL containers, commodities, and receipts
        $response = $this
            ->actingAs($user)
            ->post('/ocean-import', [
                'file_no' => 'MOI-TEST-HBL-DETAILS-1',
                'mbl_no' => 'MBL-TEST-HBL-DETAILS-1',
                'office_id' => $office->id,
                'post_date' => now()->format('Y-m-d'),
                'hbls' => [
                    [
                        'hbl_no' => 'HBL-DETAIL-01',
                        'po_no' => 'PO-100, PO-101',
                        'po_mapping_type' => 'container',
                        'hbl_mark' => 'MARK-STORED',
                        'hbl_description' => 'DESC-STORED',
                        'arrival_notice_remark' => 'AN-REMARK',
                        'delivery_order_remark' => 'DO-REMARK',
                        'containers' => [
                            [
                                'container_no' => 'CONT-HBL-01',
                                'pkg_qty' => '10',
                                'pkg_unit' => 'CARTON(S)',
                                'weight_kg' => '120.50',
                                'weight_unit' => 'KG',
                                'measure_cbm' => '1.50',
                                'measure_unit' => 'CBM',
                                'po_no' => 'PO-100',
                            ]
                        ],
                        'commodities' => [
                            [
                                'commodity_desc' => 'COMM-01',
                                'hts_code' => '1234.56.78',
                                'container_no' => 'CONT-HBL-01',
                                'po_no' => 'PO-100',
                            ]
                        ],
                        'receipts' => [
                            [
                                'receipt_no' => 'WR-001',
                                'vin_no' => 'VIN-001',
                                'total_pcs' => 5,
                                'available_pcs' => 5,
                                'allocated_pcs' => 0,
                                'unit' => 'PCS',
                                'actual_weight' => 50.0,
                                'measurement' => 0.5,
                                'remarks' => 'remarks stored',
                            ]
                        ]
                    ]
                ],
                'containers' => [
                    [
                        'container_no' => 'CONT-HBL-01',
                        'seal_no' => 'SEAL-HBL-1',
                    ]
                ]
            ]);

        $response->assertRedirect();
        
        $shipment = OceanImport::where('mbl_no', 'MBL-TEST-HBL-DETAILS-1')->first();
        $this->assertNotNull($shipment);
        
        $hbl = $shipment->hbls()->first();
        $this->assertNotNull($hbl);
        $this->assertEquals('HBL-DETAIL-01', $hbl->hbl_no);
        $this->assertEquals('MARK-STORED', $hbl->hbl_mark);
        $this->assertEquals('AN-REMARK', $hbl->arrival_notice_remark);
        
        // Assert container pivot table
        $this->assertDatabaseHas('ocean_import_container_hbl', [
            'hbl_id' => $hbl->id,
            'pkg_qty' => 10,
            'weight_kg' => 120.50,
            'po_no' => 'PO-100',
        ]);
        
        // Assert commodities table
        $this->assertDatabaseHas('ocean_import_hbl_commodities', [
            'hbl_id' => $hbl->id,
            'commodity_desc' => 'COMM-01',
            'hts_code' => '1234.56.78',
        ]);

        // Assert receipts table
        $this->assertDatabaseHas('ocean_import_hbl_receipts', [
            'hbl_id' => $hbl->id,
            'receipt_no' => 'WR-001',
            'vin_no' => 'VIN-001',
            'total_pcs' => 5,
        ]);

        // 2. Test Update (modifying the properties)
        $updateResponse = $this
            ->actingAs($user)
            ->put("/ocean-import/{$shipment->id}", [
                'file_no' => 'MOI-TEST-HBL-DETAILS-1',
                'mbl_no' => 'MBL-TEST-HBL-DETAILS-1',
                'office_id' => $office->id,
                'post_date' => now()->format('Y-m-d'),
                'hbls' => [
                    [
                        'id' => $hbl->id,
                        'hbl_no' => 'HBL-DETAIL-01-UPDATED',
                        'po_no' => 'PO-200',
                        'po_mapping_type' => 'item',
                        'hbl_mark' => 'MARK-UPDATED',
                        'hbl_description' => 'DESC-UPDATED',
                        'arrival_notice_remark' => 'AN-REMARK-UPDATED',
                        'delivery_order_remark' => 'DO-REMARK-UPDATED',
                        'containers' => [
                            [
                                'container_no' => 'CONT-HBL-01',
                                'pkg_qty' => '20',
                                'pkg_unit' => 'PALLET(S)',
                                'weight_kg' => '240.00',
                                'weight_unit' => 'KG',
                                'measure_cbm' => '3.00',
                                'measure_unit' => 'CBM',
                                'po_no' => 'PO-200',
                            ]
                        ],
                        'commodities' => [
                            [
                                'commodity_desc' => 'COMM-01-UPDATED',
                                'hts_code' => '8765.43.21',
                                'container_no' => 'CONT-HBL-01',
                                'po_no' => 'PO-200',
                            ]
                        ],
                        'receipts' => [
                            [
                                'receipt_no' => 'WR-002',
                                'vin_no' => 'VIN-002',
                                'total_pcs' => 15,
                                'available_pcs' => 15,
                                'allocated_pcs' => 0,
                                'unit' => 'PCS',
                                'actual_weight' => 150.0,
                                'measurement' => 1.5,
                                'remarks' => 'remarks updated',
                            ]
                        ]
                    ]
                ],
                'containers' => [
                    [
                        'container_no' => 'CONT-HBL-01',
                        'seal_no' => 'SEAL-HBL-1',
                    ]
                ]
            ]);

        $updateResponse->assertRedirect();
        
        $hbl->refresh();
        $this->assertEquals('HBL-DETAIL-01-UPDATED', $hbl->hbl_no);
        $this->assertEquals('MARK-UPDATED', $hbl->hbl_mark);
        
        // Assert container pivot table updated
        $this->assertDatabaseHas('ocean_import_container_hbl', [
            'hbl_id' => $hbl->id,
            'pkg_qty' => 20,
            'weight_kg' => 240.00,
        ]);
        
        // Assert commodities table updated
        $this->assertDatabaseHas('ocean_import_hbl_commodities', [
            'hbl_id' => $hbl->id,
            'commodity_desc' => 'COMM-01-UPDATED',
            'hts_code' => '8765.43.21',
        ]);
        // Assert old commodity deleted
        $this->assertDatabaseMissing('ocean_import_hbl_commodities', [
            'hbl_id' => $hbl->id,
            'commodity_desc' => 'COMM-01',
        ]);

        // Assert receipts table updated
        $this->assertDatabaseHas('ocean_import_hbl_receipts', [
            'hbl_id' => $hbl->id,
            'receipt_no' => 'WR-002',
            'vin_no' => 'VIN-002',
        ]);
        // Assert old receipt deleted
        $this->assertDatabaseMissing('ocean_import_hbl_receipts', [
            'hbl_id' => $hbl->id,
            'receipt_no' => 'WR-001',
        ]);
    }
}
