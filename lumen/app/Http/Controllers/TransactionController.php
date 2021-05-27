<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Repositories\Transaction;
use Illuminate\Http\Request;

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
                $transactionResponse = $transaction->makeTransaction();
                
                if ($transactionResponse) {
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
        $rules = [
            'amount' => 'required|integer|min:1',
            'payer_id' => 'required|integer',
            'payee_id' => 'required|integer'
        ];
        $messages = [
            'amount.required' => 'O valor da transferencia deve ser informado',
            'amount.min' => 'O valor deve ser maior ou igual a 1',
            'payer_id.required' => 'O pagante deve ser informado',
            'payee_id.required' => 'O recebedor deve ser informado'
        ];
        return Validator::make($request->all(), $rules, $messages);
    }
}
