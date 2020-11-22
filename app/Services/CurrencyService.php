<?php

namespace App\Services;

use App\Models\Currency;

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
}
