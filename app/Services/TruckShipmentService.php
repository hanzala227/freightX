<?php

namespace App\Services;

use App\Models\TruckShipment;
use App\Models\TruckShipmentMemo;
use App\Models\TruckShipmentContainer;
use Illuminate\Support\Facades\DB;

class TruckShipmentService
{
    protected function sanitizeData(array $data): array
    {
        // Convert empty date strings to null to prevent SQL Invalid datetime format errors
        $dateFields = ['post_date', 'etd', 'eta', 'feta', 'est_delivery_date', 'delivered_date'];
        foreach ($dateFields as $key) {
            if (array_key_exists($key, $data) && ($data[$key] === '' || $data[$key] === null)) {
                $data[$key] = null;
            }
        }

        // Handle checkboxes — unchecked checkboxes don't send values, set to false when missing
        foreach (['is_delivered', 'is_ecommerce'] as $boolField) {
            if (array_key_exists($boolField, $data)) {
                $data[$boolField] = filter_var($data[$boolField], FILTER_VALIDATE_BOOLEAN);
            } else {
                $data[$boolField] = false;
            }
        }

        // Ensure numeric fields with DB defaults don't pass null
        $defaultZeroFields = ['pkg_qty', 'weight_kg', 'volume_cbm', 'measure_cft'];
        foreach ($defaultZeroFields as $field) {
            if (array_key_exists($field, $data) && ($data[$field] === null || $data[$field] === '')) {
                $data[$field] = 0;
            } elseif (!array_key_exists($field, $data)) {
                $data[$field] = 0;
            }
        }

        return $data;
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            // Auto-assign current user as operator so shipment appears in My Shipment List
            if (empty($data['op_id'])) {
                $data['op_id'] = auth()->id();
            }

            $containers = json_decode($data['containers'] ?? '[]', true);
            $memos = json_decode($data['memos'] ?? '[]', true);
            unset($data['containers'], $data['memos']);

            $data = $this->sanitizeData($data);

            $shipment = TruckShipment::create($data);

            foreach ($containers as $container) {
                $shipment->containers()->create($container);
            }

            foreach ($memos as $memo) {
                $shipment->memos()->create([
                    'subject' => $memo['subject'] ?? '',
                    'content' => $memo['content'] ?? '',
                    'user_id' => auth()->id(),
                ]);
            }

            return $shipment;
        });
    }

    public function update(TruckShipment $truckShipment, array $data)
    {
        return DB::transaction(function () use ($truckShipment, $data) {
            $containers = json_decode($data['containers'] ?? '[]', true);
            $memos = json_decode($data['memos'] ?? '[]', true);
            unset($data['containers'], $data['memos']);

            $data = $this->sanitizeData($data);

            $truckShipment->update($data);

            // Sync containers: delete old, create new
            $truckShipment->containers()->delete();
            foreach ($containers as $container) {
                $truckShipment->containers()->create($container);
            }

            // Sync memos: delete old, create new
            $truckShipment->memos()->delete();
            foreach ($memos as $memo) {
                $truckShipment->memos()->create([
                    'subject' => $memo['subject'] ?? '',
                    'content' => $memo['content'] ?? '',
                    'user_id' => auth()->id(),
                ]);
            }

            return $truckShipment;
        });
    }
}
