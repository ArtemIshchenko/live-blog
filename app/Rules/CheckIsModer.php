<?php

namespace App\Rules;

use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckIsModer implements Rule
{
    protected $password;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $user = User::firstWhere('email', $value);
        if (!is_null($user)) {
            $role = DB::table('roles')->where('name', 'moder')->first();
            if (!is_null($role)) {
                    if (!DB::table('model_has_roles')->where('model_id', $user->id)->where('role_id', $role->id)->exists()) {
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Пользователь не является модератором';
    }
}
