<?php

namespace App\Http\Controllers\API;

use App\Helpers\ResultGenerate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthAPIController
{
    /**
     * @var string
     */
    private $login;
    /**
     * @var string
     */
    private $password;

    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->login = $request->login;
        $this->password = $request->password;
    }

    /**
     * @return string
     */
    public function Login(): string
    {

        if (empty($this->login) || empty($this->password)) {
            return ResultGenerate::Error('Ошибка доступа');
        }

        $foundUser = User::query()->where('email', $this->login)->first();

        if (empty($foundUser)) {
            return ResultGenerate::Error('Ошибка доступа');
        }

        if (Hash::check($foundUser->password, $this->password)) {
            return ResultGenerate::Error('Ошибка доступа');
        }

        $foundUser->apiToken = Str::random(32);
        $foundUser->apiTokenTime = date("Y-m-d H:i:s", time());
        $foundUser->save();

        return ResultGenerate::JSON((object)['token' => $foundUser->apiToken]);
    }
}