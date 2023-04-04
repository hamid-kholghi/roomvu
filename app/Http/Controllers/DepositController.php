<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Models\Deposit;

class DepositController extends Controller
{
    public function balance(Request $request)
    {
        $balance = Deposit::where('user_id', $request->input('user_id'));
        $balance = $balance->sum('credit') - $balance->sum('debit');
        return Response::json($balance);
    }

    public function credit()
    {
        //
    }
}
