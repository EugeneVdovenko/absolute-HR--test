<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ExchangeRate
 *
 * @property int source_currency_id
 * @property int target_currency_id
 * @property float exchange_rate
 */
class ExchangeRate extends Model
{
    public function sourceCurrency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function targetCurrency()
    {
        return $this->belongsTo(Currency::class);
    }
}
