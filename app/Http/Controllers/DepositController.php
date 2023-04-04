<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    public function balance(Request $request)
    {
        $balance = Deposit::where('user_id', $request->input('user_id'));
        $balance = $balance->sum('credit') - $balance->sum('debit');

        return response()->json([
            'balance' => $balance,
        ]);
    }

    public function addMoney(Request $request, Deposit $deposit): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'amount' => 'required|integer|notIn:0',
        ]);

        try {
            if ($request->input('amount') > 0) {
                $deposit->credit = $request->input('amount');
            } else {
                $deposit->debit = $request->input('amount');
            }

            $deposit->uuid = Str::uuid();
            $deposit->user_id = $request->input('user_id');
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
