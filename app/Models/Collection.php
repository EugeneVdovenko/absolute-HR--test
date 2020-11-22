<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Collection
 *
 * @property string $title
 * @property string $comment
 * @property $currencies
 */
class Collection extends Model
{
    protected $casts = [
        'currencies' => 'array'
    ];
}
