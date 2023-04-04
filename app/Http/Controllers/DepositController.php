<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddMoneyRequest;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    public function balance(Request $request, Deposit $deposit)
    {
        $balance = $deposit->where('user_id', $request->input('user_id'));
        $balance = $balance->sum('credit') - $balance->sum('debit');

        return response()->json($balance);
    }

    public function addMoney(AddMoneyRequest $request, Deposit $deposit)
    {
        $amount = $request->input('amount');
        try {
            $deposit->uuid = Str::uuid();
            $deposit->user_id = $request->input('user_id');
            switch ($amount) {
                case $amount > 0:
                    $deposit->credit = $amount;
                    break;
                case $amount < 0:
                    $deposit->debit = $amount;
                    break;
                default:
                    throw new Exception("");
                    break;
            }
            $deposit->save();
        } catch (Exception $exception) {
            Log::debug('DepositController@addMoney', [
                'message' => $exception->getMessage(),
                'errorCode' => $exception->getCode(),
                'data' => $request->all(),
            ]);
        }

        return response()->json([
            'reference_id' => $deposit->uuid,
        ]);
    }
}
