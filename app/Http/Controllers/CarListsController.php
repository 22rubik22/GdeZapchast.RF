<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BaseAvto;
use App\Models\Advert;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class CarListsController extends Controller
{
    /**
     * Возвращает список моделей по бренду с количеством активных объявлений,
     * опционально отфильтрованных по user_id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int|null                  $id      — необязательный user_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModels(Request $request, ?int $id = null)
    {
        // 1) Валидация входных данных
        $data = $request->validate([
            'brand' => 'required|string',
        ]);
        $brand = $data['brand'];

        // 2) Получаем все уникальные модели из base_avto
        $models = BaseAvto::where('brand', $brand)
            ->whereNotNull('model')
            ->distinct()
            ->pluck('model');

        // 3) Готовим запрос для подсчёта объявлений
        $advertsQuery = Advert::query()
            ->where('brand', $brand)
            ->where('status_ad', 'activ');

        if ($id !== null) {
            $advertsQuery->where('user_id', $id);
        }

        // 4) Группировка и подсчёт
        // Получим коллекцию вида ['Focus' => 3, 'Mondeo' => 5, …]
        $counts = $advertsQuery
            ->groupBy('model')
            ->select('model', DB::raw('COUNT(*) as total'))
            ->pluck('total', 'model');

        // 5) Формируем итоговый массив моделей с count (0, если нет объявлений)
        $result = $models->map(function (string $model) use ($counts) {
            return [
                'model'        => $model,
                'advert_count' => (int) ($counts[$model] ?? 0),
            ];
        });

        return response()->json($result);
    }

 public function getModelsCreate(Request $request)
    {
        $brand = $request->input('brand');
        $models = BaseAvto::where('brand', $brand)->distinct()->pluck('model');

        return response()->json($models);
    }


// Получаем уникальные годы для данной модели
public function getYears(Request $request)
{
    $brand = $request->input('brand');
    $model = $request->input('model');

    // Проверяем, что марка и модель переданы
    if (!$brand || !$model) {
        return response()->json([], 400); // Возвращаем пустой массив с ошибкой 400, если данные не переданы
    }

    // Получаем уникальные годы для данной модели
    $years = BaseAvto::where('brand', $brand)
                     ->where('model', $model)
                     ->select('year_from', 'year_before')
                     ->distinct()
                     ->get();

    $yearList = [];

    foreach ($years as $year) {
        for ($y = $year->year_from; $y <= $year->year_before; $y++) {
            $yearList[] = $y;
        }
    }

    $yearList = array_unique($yearList); // Удаляем дубликаты

    sort($yearList); // Сортируем массив по возрастанию

    return response()->json($yearList);
}


    // Получаем уникальные модификации для данной модели
    public function getModifications(Request $request)
    {
        $brand = $request->input('brand');
        $model = $request->input('model');
        $year = $request->input('year');

        // Проверяем, что марка, модель и год переданы
        if (!$brand || !$model || !$year) {
            return response()->json([], 400); // Возвращаем пустой массив с ошибкой 400, если данные не переданы
        }

        // Получаем модификации для данной марки, модели и года
        $modifications = BaseAvto::where('brand', $brand)
                                  ->where('model', $model)
                                  ->where('year_from', '<=', $year)
                                  ->where('year_before', '>=', $year)
                                  ->distinct()
                                  ->get(['id_modification', 'modification']); // Получаем id_modification и modification

        return response()->json($modifications);
    }

    // Получаем id_modifications
    public function getIdModifications(Request $request)
    {
        $brand = $request->input('brand');
        $model = $request->input('model');
        $year = $request->input('year');
        $modifications = $request->input('modifications'); // Предполагается, что это массив

        // Проверяем, что марка, модель, год и модификации переданы
        if (!$brand || !$model || !$year || empty($modifications)) {
            return response()->json([], 400); // Возвращаем пустой массив с ошибкой 400, если данные не переданы
        }

        // Получаем id_modification для данной марки, модели, года и модификаций
        $idModifications = BaseAvto::where('brand', $brand)
                                    ->where('model', $model)
                                    ->where('year_from', '<=', $year)
                                    ->where('year_before', '>=', $year)
                                    ->whereIn('modification', $modifications) // Используем whereIn для фильтрации по массиву модификаций
                                    ->pluck('id_modification');

        return response()->json(['id_modifications' => $idModifications]);
    }

    // Получаем уникальные марки
    public function getBrands(?int $id = null)
    {
        $cacheKey = 'brands_with_count';
        $cacheKey .= $id ? '_'.$id : '';
        return Cache::remember($cacheKey, 10*60, function () use ($id) {
            return BaseAvto::select([
                'base_avto.brand',
                // только уникальные adverts.id
                DB::raw('COUNT(DISTINCT adverts.id) AS advert_count'),
            ])
                ->leftJoin('adverts', function ($join) use ($id) {
                    $join->on('base_avto.brand', '=', 'adverts.brand')
                        ->where('adverts.status_ad', 'activ');

                    if ($id !== null) {
                        $join->where('adverts.user_id', $id);
                    }
                })
                ->groupBy('base_avto.brand')
                ->orderByDesc('advert_count')
                ->get();
        });
    }
}
