<?php

namespace App\Repositories;

use App\Models\Transaction as TransactionModel;
use App\Repositories\TransactionValidation;
use App\Models\User as UserModel;
use App\Repositories\User;

class Transaction
{
    private $transactionModel;
    private $payerModel;
    private $payeeModel;
    private $amount;

    public function __construct($transactionRequest)
    {
        $this->payerModel = $this->getPayerModel($transactionRequest->payer_id);
        $this->payeeModel = $this->getPayeeModel($transactionRequest->payee_id);
        $this->amount     = $transactionRequest->amount;
    }

    public function validateTransaction()
    {
        $validation = new TransactionValidation(
            $this->payerModel,
            $this->payeeModel,
            $this->amount
        );
        return $validation->validateTransaction();
    }

    public function makeTransaction()
    {
        $this->subtractAmountFromPayer();

        $this->sumAmountToPayee();

        $this->saveTransaction();

        return true;
    }

    private function subtractAmountFromPayer()
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
        $this->setTransactionModel($transactionModel);
    }

    private function setTransactionModel($transactionModel)
    {
        $this->transactionModel = $transactionModel;
    }

    public function getTransactionModel()
    {
        return $this->transactionModel;
    }

    public function notifyPayee()
    {
        $user = new User();
        $emailResponse = $user->sendEmailToUser($this->payeeModel);
        if ($emailResponse == false || $emailResponse->message != 'Success') {
            throw new \Exception('Não foi possível notificar o recebedor!', 500);
        }
        return true;
    }

    private function getPayerModel($payer_id)
    {
        $userModel = UserModel::find($payer_id);
        if ($userModel) {
            return $userModel;
        }
        throw new \Exception('Pagante não existe!', 404);
    }

    private function getPayeeModel($payee_id)
    {
        $userModel = UserModel::find($payee_id);
        if ($userModel) {
            return $userModel;
        }
        throw new \Exception('Recebedor não existe!', 404);
    }
}
