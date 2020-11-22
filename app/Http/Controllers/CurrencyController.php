<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    protected CurrencyService $currencyService;

    public function __construct(CurrencyService $currencyService)
    {
        $this->currencyService = $currencyService;
    }

    /**
     * Добавление валюты в список отслеживаемых
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo добавить валидацию
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $result = [];

        $currency = $this->currencyService->addCurrency($request->get('code'), $request->get('title'));
        if ($currency) {
            $result['meta']['success'] = [
                'status' => 'OK'
            ];
        } else {
            $result['meta']['failed'] = [
                'status' => 'Error'
            ];
        }

        return response()->json($result);
    }
}
