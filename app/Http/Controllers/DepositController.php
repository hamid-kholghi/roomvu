<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Deposit;
use Illuminate\Support\Str;

class DepositController extends Controller
{
    public function balance(Request $request)
    {
        $balance = Deposit::where('user_id', $request->input('user_id'));
        $balance = $balance->sum('credit') - $balance->sum('debit');
        return response()->json($balance);
    }

    public function addMoney(Request $request)
    {
        $amount = $request->input('amount');
        DB::beginTransaction();
        try {
            $credit = new Deposit();
            $credit->uuid = Str::uuid();
            $credit->user_id = $request->input('user_id');
            $credit->debit = ($amount < 0) ? $request->input('amount') : 0;
            $credit->credit = ($amount > 0) ? $request->input('amount') : 0;
            $credit->save();
            DB::commit();

            return response()->json([
                'reference_id' => $credit->uuid,
            ]);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::debug('DepositController@credit', [
                'message' => $exception->getMessage(),
                'errorCode' => $exception->getCode(),
                'data' => $request->all(),
            ]);
        }
    }

    public function report(Request $request)
    {
        try {
            $reports = Deposit::where('user_id', '=', $request->input('user_id'))
                ->firstOrFail();

            return response()->json($reports);
        } catch (Exception $exception) {
            Log::error('DepositController@report', [
                'message' => $exception->getMessage(),
                'errorCode' => $exception->getCode(),
                'data' => $request->all(),
            ]);

            return response()->json([
                'status' => 'failed',
                'statusCode' => 400,
                'message' => '$message',
                'data' => [],
            ], 400);
        }
    }
}
