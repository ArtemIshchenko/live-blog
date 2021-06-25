<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    const STATUS = [
        'new' => 0,
        'sendToModerate' => 1,
        'approved' => 2,
        'refused' => 3,
        'locked' => 4,
    ];

    const VISIBILITY = [
        'isPrivate' => 0,
        'isPublic' => 1,
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'status' => self::STATUS['new'],
        'is_public' => self::VISIBILITY['isPrivate'],
    ];

    public static function getStatusList() {
        return [
            self::STATUS['new'] => 'Новый',
            self::STATUS['sendToModerate'] => 'Отправлено на модерацию',
            self::STATUS['approved'] => 'Подтверждено',
            self::STATUS['refused'] => 'Отклонено',
            self::STATUS['locked'] => 'Заблокировано',
        ];
    }
}
