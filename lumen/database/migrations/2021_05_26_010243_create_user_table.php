<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User as UserModel;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('cpf')->unique();
            $table->string('password');
            $table->integer('balance');
            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_type');
            $table->timestamps();
        });
        UserModel::insert([
            [
                'name' => 'Usuario Comum 1',
                'email' => 'email@comum1.com',
                'cpf' => '90346831091',
                'password' => 'a665a45920422f9d417e4867efdc4fb8a04a1f3fff1fa07e998e86f7f7a27ae3',
                'balance' => 100,
                'user_type_id' => 1,
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'Usuario Comum 2',
                'email' => 'email@comum2.com',
                'cpf' => '28938083012',
                'password' => '8d23cf6c86e834a7aa6eded54c26ce2bb2e74903538c61bdd5d2197997ab2f72',
                'balance' => 2,
                'user_type_id' => 1,
                'created_at' => \Carbon\Carbon::now()
            ],
            [
                'name' => 'Usuario Lojista 1',
                'email' => 'email@lojista.com',
                'cpf' => '66744762001',
                'password' => '8d969eef6ecad3c29a3a629280e686cf0c3f5d5a86aff3ca12020c923adc6c92',
                'balance' => 50,
                'user_type_id' => 2,
                'created_at' => \Carbon\Carbon::now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
