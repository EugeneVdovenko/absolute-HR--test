<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Currency
 *
 * @property string $code
 * @property string $title
 */
class Currency extends Model
{
    public $timestamps = false;

    public function sourceExchangeRate(): HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'source_currency_id');
    }

    public function targetExchangeRate(): HasMany
    {
        return $this->hasMany(ExchangeRate::class, 'target_currency_id');
    }
}
