<?php

namespace App\Http\Controllers;

use App\Services\CollectionService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CollectionController extends Controller
{
    private CollectionService $collectionService;

    public function __construct(CollectionService $collectionService)
    {
        $this->collectionService = $collectionService;
    }

    /**
     * Получаем курсы валют на заданный день
     *
     * @param Request $request ?date=31-12-2000 }
     * @param int $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo добавить валидацию
     */
    public function getExchangeRates(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $date = $request->get('date') ? Carbon::createFromFormat('d-m-Y', $request->get('date')) : now();
        $col = $this->collectionService->getExchangeRatesCollection($id, $date);

        if ($col) {
            $response = [
                'data' => $col,
                'meta' => [
                    'success' => [
                        'status' => 'OK',
                    ],
                ]
            ];
        } else {
            $response = [
                'meta' => [
                    'failed' => [
                        'status' => 'Error',
                    ],
                ]
            ];
        }

        return response()->json($response);
    }

    /**
     * Создание коллекции
     *
     * @param Request $request {title: string, currencies: { sources_code: target_code, ... }}
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo добавить валидацию
     */
    public function create(Request $request): \Illuminate\Http\JsonResponse
    {
        $currencies = $request->get('currencies', []);
        $col = $this->collectionService->createCollection($request->get('title'), $currencies);

        if ($col) {
            $response = [
                'meta' => [
                    'success' => [
                        'status' => 'OK',
                        'id' => $col->id,
                    ],
                ]
            ];
        } else {
            $response = [
                'meta' => [
                    'failed' => [
                        'status' => 'Error',
                    ],
                ]
            ];
        }

        return response()->json($response);
    }

    /**
     * Добавляем комментарий
     *
     * @param Request $request
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo добавить валидацию
     */
    public function addComment(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        if ($this->collectionService->addComment($id, $request->get('comment'))) {
            $response = [
                'meta' => [
                    'success' => [
                        'status' => 'OK',
                        'message' => 'Комментарий сохранен',
                    ],
                ]
            ];
        } else {
            $response = [
                'meta' => [
                    'failed' => [
                        'status' => 'Error',
                    ],
                ]
            ];
        }

        return response()->json($response);
    }
}
