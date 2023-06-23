<?php

namespace App\Traits;

use App\Models\User;
use App\Http\Requests\ValidateUserIdRequest;

trait CheckUserExist
{
    public function check_user_exist(ValidateUserIdRequest $request)
    {
        $validatedData = $request->validated();
        $userId = $validatedData['user_id'];
        $user = User::findOrFail($userId);
        return $user;
    }
}
