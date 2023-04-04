<?php

namespace App\Http\Controllers;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Deposit;
use Exception;

class DepositController extends Controller
{
    /**
     * @throws ValidationException
     */
    public function addMoney(Request $request, Deposit $deposit): JsonResponse
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
            'amount' => 'required|integer|notIn:0',
        ]);

        try {
            if ($request->input('amount') > 0) {
                $deposit->credit = $request->input('amount');
            } else {
                $deposit->debit = abs($request->input('amount'));
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

    public function balance(Deposit $deposit, $user_id): JsonResponse
    {
        $balance = $deposit->where('user_id', $user_id);
        $balance = $balance->sum('credit') - $balance->sum('debit');

        return response()->json([
            'balance' => $balance,
        ]);
    }
}
