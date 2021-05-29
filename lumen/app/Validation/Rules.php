<?php

namespace App\Validation;

class Rules
{
    public static function transactionRules()
    {
        return [
            'amount' => 'required|integer|min:1',
            'payer_id' => 'required|integer',
            'payee_id' => 'required|integer'
        ];
    }
}
