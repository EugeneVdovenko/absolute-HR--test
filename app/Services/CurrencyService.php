<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Database\Eloquent\Collection;

class CurrencyService
{
    public function addCurrency(string $code, string $title): ?Currency
    {
        $model = new Currency();
        $model->code = $code;
        $model->title = $title;
        if ($model->save()) {
            return $model;
        }

        return null;
    }

    public function getCurrencyIdsByCodes($codes)
    {
        return Currency::query()
            ->whereIn('code', $codes)
            ->pluck('id', 'code')
            ->toArray();
    }
}
