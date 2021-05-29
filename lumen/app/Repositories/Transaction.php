<?php

namespace App\Repositories;

use App\Models\Transaction as TransactionModel;
use App\Models\User as UserModel;
use App\Repositories\User;
use App\Services\HttpService;

class Transaction
{
    private $payerModel;
    private $payeeModel;
    private $amount;
    private $errorMessage;

    public function __construct($transactionRequest)
    {
        $this->payerModel = $this->getPayerModel($transactionRequest->payer_id);
        $this->payeeModel = $this->getPayeeModel($transactionRequest->payee_id);
        $this->amount     = $transactionRequest->amount;
    }

    public function makeTransaction()
    {
        $this->subctractAmountFromPayer();

        $this->sumAmountToPayee();

        $this->saveTransaction();

        return true;
    }

    private function subctractAmountFromPayer()
    {
        $this->payerModel->balance = $this->payerModel->balance - $this->amount;
        $this->payerModel->saveOrFail();
        $this->payerModel->refresh();
    }

    private function sumAmountToPayee()
    {
        $this->payeeModel->balance = $this->payeeModel->balance + $this->amount;
        $this->payeeModel->saveOrFail();
        $this->payeeModel->refresh();
    }

    private function saveTransaction()
    {
        $transactionModel = new TransactionModel();
        $transactionModel->payer_id = $this->payerModel->id;
        $transactionModel->payee_id = $this->payeeModel->id;
        $transactionModel->amount   = $this->amount;
        $transactionModel->saveOrFail();
        $transactionModel->refresh();
    }

    public function notifyReciever()
    {
        $user = new User();
        $emailResponse = $user->sendEmailToUser($this->payeeModel);
        if ($emailResponse == false || $emailResponse->message != 'Success') {
            $this->setErrorMessage('Não foi possível notificar o recebedor!');
            return false;
        }
    }

    public function validateTransaction()
    {
        if ($this->isSellerUser()) {
            $this->setErrorMessage('Vendedores não podem realizar transferência!');
            return false;
        }

        if ($this->payerModel->id === $this->payeeModel->id) {
            $this->setErrorMessage('Você não pode realizar uma transferência para si mesmo!');
            return false;
        }

        if ($this->hasNotEnoughBalance()) {
            $this->setErrorMessage('Você não possui saldo suficiente para realizar a transferência!');
            return false;
        }

        if ($this->transactionAuthorization()) {
            $this->setErrorMessage('Transferência não autorizada!');
            return false;
        }

        return true;
    }

    private function getPayerModel($payer_id)
    {
        return UserModel::findOrFail($payer_id);
    }

    private function getPayeeModel($payee_id)
    {
        return UserModel::findOrFail($payee_id);
    }

    private function isSellerUser()
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

    private function setErrorMessage($message)
    {
        $this->errorMessage = $message;
    }

    public function getErrorMessage()
    {
        return $this->errorMessage;
    }
}
