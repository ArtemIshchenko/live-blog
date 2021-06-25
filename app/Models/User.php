<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    const STATUS = [
        'actived' => 0,
        'locked' => 1,
    ];

    const GENDER = [
        'male' => 0,
        'female' => 1,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'birthday',
        'gender',
        'country',
        'count_unread_messages',
        'status',
        'city',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status.required|integer|size:' . self::STATUS['actived'] => 'Пользователь с такими логином и паролем заблокирован',
        ];
    }

    public static function getStatusList() {
        return [
            self::STATUS['actived'] => 'Активный',
            self::STATUS['locked'] => 'Заблокирован',
        ];
    }

    public static function getGenderList() {
        return [
            self::GENDER['male'] => 'Мужчина',
            self::GENDER['female'] => 'Женщина',
        ];
    }
}
