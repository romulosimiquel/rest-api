<?php

namespace App\Validation;

class Messages
{
    public static function transactionMessages()
    {
        return [
            'amount.required' => 'O valor da transferencia deve ser informado',
            'amount.integer' => 'O valor deverá ser um número',
            'amount.min' => 'O valor deve ser maior ou igual a 1',
            'payer_id.required' => 'O pagante deve ser informado',
            'payee_id.required' => 'O recebedor deve ser informado'
        ];
    }
}
