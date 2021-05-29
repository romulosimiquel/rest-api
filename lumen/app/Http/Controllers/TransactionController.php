<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use App\Repositories\Transaction;
use App\Validation\Messages;
use Illuminate\Http\Request;
use App\Validation\Rules;

class TransactionController extends Controller
{
    public function performTransaction(Request $request)
    {
        try {
            $validator = $this->validated($request);
            if ($validator->fails()) {
                return response()->json([$validator->errors()], 422);
            }

            $transaction = new Transaction($request);
            $transaction->validateTransaction();

            if ($transaction->validateTransaction()) {
                $transaction->makeTransaction();

                if ($transaction->notifyReciever()) {
                    return response()->json('Transferência realizada com sucesso!', 200);
                }
            }
            return response()->json($transaction->getErrorMessage(), 500);
        } catch (\Throwable $th) {
            return response()->json('Não foi possível realizar a transferência', 500);
        }
    }

    private function validated($request)
    {
        return Validator::make($request->all(), Rules::transactionRules(), Messages::transactionMessages());
    }
}
