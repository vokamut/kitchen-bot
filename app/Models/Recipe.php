<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int id
 * @property string title
 * @property string|null link
 * @property string|null text
 * @property CategoryEnum category
 * @property Carbon created_at
 */
class Recipe extends Model
{
    public $timestamps = false;

    protected $casts = [
        'id' => 'integer',
        'created_at' => 'datetime',
        'category' => CategoryEnum::class,
    ];

    protected $fillable = [
        'category',
    ];

    protected static function boot(): void
    {
        parent::boot();

        self::creating(static function (Recipe $model) {
            $model->created_at = now();
        });
    }
}
