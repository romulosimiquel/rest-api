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
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $transaction = new Transaction($request);
            $validTransaction = $transaction->validateTransaction();

            if ($validTransaction) {
                $transaction->makeTransaction();
                $transaction->notifyPayee();

                return response()->json(
                    [
                        'message' => 'Transferência realizada com sucesso!',
                        'data' => $transaction->getTransactionModel()
                    ],
                    200
                );
            }
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], $th->getCode());
        }
    }

    private function validated($request)
    {
        return Validator::make($request->all(), Rules::transactionRules(), Messages::transactionMessages());
    }
}
