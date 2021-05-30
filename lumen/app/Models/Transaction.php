<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'amount', 'payer_id', 'payee_id'
    ];

    protected $hidden = [
        'created_at', 'updated_at'
    ];

    protected $table = 'transaction';
}
