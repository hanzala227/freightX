<?php
namespace App\Http\Controllers;
use App\Models\Operation;
use Illuminate\Http\Request;
class OperationController extends Controller {
    public function index() { return response()->json(Operation::all()); }
}
