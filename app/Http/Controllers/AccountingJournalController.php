<?php
namespace App\Http\Controllers;
use App\Models\AccountingJournal;
use Illuminate\Http\Request;
class AccountingJournalController extends Controller {
    public function index() { return response()->json(AccountingJournal::all()); }
}
