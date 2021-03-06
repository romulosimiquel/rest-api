<?php

namespace App\Repositories;

use App\Models\User as UserModel;
use App\Services\HttpService;

class User
{
    public function sendEmailToUser(UserModel $user)
    {
        return HttpService::request('GET', 'http://o4d9z.mocklab.io/notify', ['userEmail' => $user->email]);
    }
}
