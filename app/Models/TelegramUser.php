<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int telegram_id
 * @property string|null state
 * @property Carbon created_at
 */
class TelegramUser extends Model
{
    protected $primaryKey = 'telegram_id';

    public $incrementing = false;

    public $timestamps = false;

    protected $casts = [
        'telegram_id' => 'integer',
        'created_at' => 'datetime',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (TelegramUser $model) {
            $model->created_at = now();
        });
    }
}
