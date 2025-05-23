<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advert;
use App\Models\UserQuery;
use App\Models\BaseAvto;
use App\Models\Part;
use Illuminate\Support\Facades\Log;

class AdvertTableController extends Controller
{
   
public function getAllBrandsAndModels(Request $request, $advertId)
{
    try {
        $advert = Advert::findOrFail($advertId);

        // Получаем параметры фильтрации из запроса
        $filterBrand = $request->input('filterBrand');

        // Основные данные для поиска
        $productName = trim($advert->product_name);
        $brand = trim($advert->brand);
        $model = trim($advert->model);
        $year = $advert->year;
        $engineNumber = trim($advert->engine);
        $partNumber = trim($advert->number);

        Log::info('Starting getAllBrandsAndModels with: productName=' . $productName . ', brand=' . $brand . ', model=' . $model . ', year=' . $year . ', engineNumber=' . $engineNumber . ', partNumber=' . $partNumber);

        $carIds = [];

        // Поиск по номеру запчасти
        if ($partNumber) {
            $carIds = UserQuery::where('id_queri', $partNumber)->pluck('id_car')->toArray();

            if (empty($carIds)) {
                $idQueries = UserQuery::where('part_number', $partNumber)->pluck('id_queri')->toArray();
                if (!empty($idQueries)) {
                    $carIdsByPartNumber = UserQuery::whereIn('id_queri', $idQueries)->pluck('id_car')->toArray();
                    $carIds = array_merge($carIds, $carIdsByPartNumber);
                    $carIds = array_unique($carIds);
                }
            }
        }

        // Если номер запчасти не дал результатов, ищем по другим параметрам
        if (empty($carIds)) {
            $partNames = preg_split('/[\s()]+/', $productName, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($partNames as $partName) {
                $partName = trim($partName);
                $part = Part::where('part_name', 'like', '%' . $partName . '%')->first();

                if (!$part) continue;

                $partId = $part->part_id;
                $need = $part->need; // Получаем значение поля need

                $baseAvtoQuery = BaseAvto::query();

                if ($brand) {
                    $baseAvtoQuery->where('brand', $brand);
                }

                if ($model) {
                    $baseAvtoQuery->where('model', $model);
                }

                if ($year !== null && $year !== '') {
                    $baseAvtoQuery->where('year_from', '<=', $year)
                        ->where('year_before', '>=', $year);
                }

                if ($engineNumber) {
                    $baseAvtoQuery->where('engine', $engineNumber);
                }

                // Добавляем проверку на need
                if ($need === 'engine' && !$engineNumber) {
                    // Если требуется engine, но он не указан, пропускаем текущую итерацию
                    continue;
                }

                if ($need === 'year' && ($year === null || $year === '')) {
                    // Если требуется year, но он не указан, пропускаем текущую итерацию
                    continue;
                }
                 // Дополнительная проверка на необходимость указания года или двигателя
                // для более точного определения совместимости
                if ($need === 'engine' && $engineNumber) {
                  $baseAvtoQuery->where('engine', $engineNumber);
                }
                if ($need === 'year' && ($year !== null && $year !== '')) {
                  $baseAvtoQuery->where('year_from', '<=', $year)
                        ->where('year_before', '>=', $year);
                }

                $baseAvtos = $baseAvtoQuery->get();

                if ($baseAvtos->isEmpty()) continue;

                $idModifications = $baseAvtos->pluck('id_modification')->toArray();

                foreach ($idModifications as $idModification) {
                    $userQuery = UserQuery::where('id_car', $idModification)
                        ->where('id_part', $partId)
                        ->first();

                    if ($userQuery) {
                        $idQueri = $userQuery->id_queri;
                        $carIdsForQuery = UserQuery::where('id_queri', $idQueri)->pluck('id_car')->toArray();
                        $carIds = array_merge($carIds, $carIdsForQuery);
                    }
                }

                $carIds = array_unique($carIds);
            }
        }

        // Фильтрация по марке и модели
        $query = BaseAvto::whereIn('id_modification', $carIds);

        if ($filterBrand && $filterBrand !== 'all') {
            $query->where('brand', $filterBrand);
        }

        // Получаем все объявления для уникальных значений
        $allAdverts = $query->get();

        // Получаем уникальные значения brand и model
        $brands = $allAdverts->pluck('brand')->unique()->values()->toArray();
        $models = $allAdverts->pluck('model')->unique()->values()->toArray();

        // Группируем модели по маркам
        $modelsByBrand = $allAdverts->groupBy('brand')->map(function ($group) {
            return $group->pluck('model')->unique()->values()->toArray();
        });

        // Логируем уникальные значения
        Log::info('Unique brands: ' . implode(', ', $brands));
        Log::info('Models by brand: ' . json_encode($modelsByBrand));

        $data = [
            'brands' => $brands,  // Уникальные бренды
            'modelsByBrand' => $modelsByBrand,  // Модели, сгруппированные по маркам
        ];

        return response()->json($data);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
    }
}


    




public function getTableData(Request $request, $advertId)
{
    try {
        $advert = Advert::findOrFail($advertId);

        // Получаем параметры фильтрации из запроса
        $filterBrand = $request->input('filterBrand');
        $filterModel = $request->input('filterModel');

        // Основные данные для поиска
        $productName = trim($advert->product_name);
        $brand = trim($advert->brand);
        $model = trim($advert->model);
        $year = $advert->year;
        $engineNumber = trim($advert->engine);
        $partNumber = trim($advert->number);

        Log::info('Starting getTableData with: productName=' . $productName . ', brand=' . $brand . ', model=' . $model . ', year=' . $year . ', engineNumber=' . $engineNumber . ', partNumber=' . $partNumber);

        $carIds = [];

        // Поиск по номеру запчасти
        if ($partNumber) {
            $carIds = UserQuery::where('id_queri', $partNumber)->pluck('id_car')->toArray();

            if (empty($carIds)) {
                $idQueries = UserQuery::where('part_number', $partNumber)->pluck('id_queri')->toArray();
                if (!empty($idQueries)) {
                    $carIdsByPartNumber = UserQuery::whereIn('id_queri', $idQueries)->pluck('id_car')->toArray();
                    $carIds = array_merge($carIds, $carIdsByPartNumber);
                    $carIds = array_unique($carIds);
                }
            }
        }

        // Если номер запчасти не дал результатов, ищем по другим параметрам
        if (empty($carIds)) {
            $partNames = preg_split('/[\s()]+/', $productName, -1, PREG_SPLIT_NO_EMPTY);

            foreach ($partNames as $partName) {
                $partName = trim($partName);
                $part = Part::where('part_name', 'like', '%' . $partName . '%')->first();

                if (!$part) continue;

                $partId = $part->part_id;
                $need = $part->need; // Получаем значение поля need

                $baseAvtoQuery = BaseAvto::query();

                if ($brand) {
                    $baseAvtoQuery->where('brand', $brand);
                }

                if ($model) {
                    $baseAvtoQuery->where('model', $model);
                }

                if ($year !== null && $year !== '') {
                    $baseAvtoQuery->where('year_from', '<=', $year)
                        ->where('year_before', '>=', $year);
                }

                if ($engineNumber) {
                    $baseAvtoQuery->where('engine', $engineNumber);
                }

                // Добавляем проверку на need
                if ($need === 'engine' && !$engineNumber) {
                    // Если требуется engine, но он не указан, пропускаем текущую итерацию
                    continue;
                }

                if ($need === 'year' && ($year === null || $year === '')) {
                    // Если требуется year, но он не указан, пропускаем текущую итерацию
                    continue;
                }
                // Дополнительная проверка на необходимость указания года или двигателя
                // для более точного определения совместимости
                if ($need === 'engine' && $engineNumber) {
                  $baseAvtoQuery->where('engine', $engineNumber);
                }
                if ($need === 'year' && ($year !== null && $year !== '')) {
                  $baseAvtoQuery->where('year_from', '<=', $year)
                        ->where('year_before', '>=', $year);
                }

                $baseAvtos = $baseAvtoQuery->limit(3)->get();

                if ($baseAvtos->isEmpty()) continue;

                $idModifications = $baseAvtos->pluck('id_modification')->toArray();

                foreach ($idModifications as $idModification) {
                    $userQuery = UserQuery::where('id_car', $idModification)
                        ->where('id_part', $partId)
                        ->first();

                    if ($userQuery) {
                        $idQueri = $userQuery->id_queri;
                        $carIdsForQuery = UserQuery::where('id_queri', $idQueri)->pluck('id_car')->toArray();
                        $carIds = array_merge($carIds, $carIdsForQuery);
                    }
                }

                $carIds = array_unique($carIds);
            }
        }

        // Фильтрация по марке и модели
        $query = BaseAvto::whereIn('id_modification', $carIds);

        if ($filterBrand && $filterBrand !== 'all') {
            $query->where('brand', $filterBrand);
        }

        if ($filterModel && $filterModel !== 'all') {
            $query->where('model', $filterModel);
        }

        $adverts = $query->paginate(10);

        $filteredAdverts = collect($adverts->items())->reject(function ($advert) {
            return is_null($advert->model);
        })->values();

        $data = [
            'adverts' => $filteredAdverts,
            'pagination' => [
                'current_page' => $adverts->currentPage(),
                'last_page' => $adverts->lastPage(),
                'next_page_url' => $adverts->nextPageUrl(),
                'prev_page_url' => $adverts->previousPageUrl(),
            ]
        ];

        return response()->json($data);

    } catch (\Exception $e) {
        return response()->json(['error' => 'Internal Server Error', 'message' => $e->getMessage()], 500);
    }
}





private function getDefaultPagination(): array
{
    return [
        'current_page' => 1,
        'last_page' => 1,
        'next_page_url' => null,
        'prev_page_url' => null,
    ];
}




    public function getBrandsAndModels()
    {
        $brands = BaseAvto::distinct()->pluck('brand');
        $models = BaseAvto::distinct()->pluck('model');

        return response()->json([
            'brands' => $brands,
            'models' => $models,
        ]);
    }

    private function findPartsByProductName($productName)
    {
        return Part::where(Part::raw("'{$productName}'"), 'LIKE', Part::raw("CONCAT('%', part_name, '%')"))->get();
    }

    private function findModificationId($advert)
    {
        // 1. Находим все записи, соответствующие марке и модели
        $baseAvtos = BaseAvto::where('brand', $advert->brand)
            ->where('model', $advert->model)
            ->get();

        $foundModificationId = null; // Initialize a variable to store the modification ID

        // 2. Перебираем найденные записи и проверяем год и двигатель
        $matchingEngineCount = 0; // Counter to track matches based on engine
        foreach ($baseAvtos as $baseAvto) {


            if ($advert->year >= $baseAvto->year_from && $advert->year <= $baseAvto->year_before) {


                if (trim($advert->engine) === trim($baseAvto->engine)) {
                    $foundModificationId = $baseAvto->id_modification;
                    $matchingEngineCount++;
                }
            }
        }

        // 3.  Если нашли только одно соответствие по двигателю, возвращаем modification ID
        if ($matchingEngineCount === 1) {
            return $foundModificationId;
        }

        return null;  //Возвращаем null если не нашли или нашли больше одного совпадения.
    }
    
    
    private function getRelatedCars($relatedQueries)
    {
        if ($relatedQueries->isEmpty()) {
            return collect();
        }
    
        $carIds = $relatedQueries->pluck('id_car')->unique();
    
        return BaseAvto::whereIn('id_modification', $carIds);
    }
}