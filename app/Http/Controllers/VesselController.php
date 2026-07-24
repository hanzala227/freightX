<?php

namespace App\Http\Controllers;

use App\Models\Vessel;
use Illuminate\Http\Request;

class VesselController extends Controller
{
    public function index(Request $request)
    {
        $query = Vessel::query();
        if ($request->has('q')) {
            $query->where('name', 'like', '%' . $request->q . '%');
        }
        return response()->json($query->where('is_active', true)->limit(10)->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required',
            'call_sign' => 'nullable',
            'mmsi' => 'nullable',
            'imo' => 'nullable',
        ]);
        return response()->json(Vessel::create($validated), 201);
    }
}
