<?php

namespace App\Repositories;

use App\Models\Transaction as TransactionModel;
use App\Models\User as UserModel;
use App\Services\HttpService;
use App\Repositories\User;

class TransactionValidation
{
    private $transactionModel;
    private $payerModel;
    private $payeeModel;
    private $amount;

    public function __construct($payerModel, $payeeModel, $amount)
    {
        $this->payerModel = $payerModel;
        $this->payeeModel = $payeeModel;
        $this->amount     = $amount;
    }

    public function validateTransaction()
    {
        if ($this->isPayerSellerUser()) {
            throw new \Exception('Vendedores não podem realizar transferência!', 401);
        }

        if ($this->payerModel->id === $this->payeeModel->id) {
            throw new \Exception('Você não pode realizar uma transferência para si mesmo!', 401);
        }

        if ($this->hasNotEnoughBalance()) {
            throw new \Exception('Você não possui saldo suficiente para realizar a transferência!', 401);
        }

        if ($this->transactionAuthorization()) {
            throw new \Exception('Transferência não autorizada!', 401);
        }

        return true;
    }

    private function isPayerSellerUser()
    {
        if ($this->payerModel->user_type_id == 2) {
            return true;
        }
        return false;
    }

    private function hasNotEnoughBalance()
    {
        if ($this->payerModel->balance < $this->amount) {
            return true;
        }
        return false;
    }

    private function transactionAuthorization()
    {
        $authResponse = HttpService::request(
            'GET',
            'https://run.mocky.io/v3/8fafdd68-a090-496f-8c9a-3442cf30dae6'
        );
        if ($authResponse == false || $authResponse->message != 'Autorizado') {
            return false;
        }
    }
}
