<?php

namespace App\Repositories;

use App\Models\User as UserModel;

class User
{
    public function storeUser($userRequest)
    {
        $userModel = new UserModel();
        $userModel->fill($userRequest);
        $userModel->save();
        $userModel->refresh();
        dd('aqui');
        return $userModel;
    }

}