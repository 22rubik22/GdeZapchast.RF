<?php

namespace App\Http\Controllers;


use App\Models\Advert;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use App\Models\BaseAvto;
use App\Models\Part;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MarketAnalysisController extends Controller
{
    public function index(Request $request)
    {
        // Получаем данные из запроса
        $searchQuery = $request->input('search_query');
        
        // Очищаем searchQuery от посторонних символов
    $searchQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', $searchQuery); // Удаляем все, кроме букв, цифр и пробелов
    $searchQuery = trim($searchQuery); // Убираем лишние пробелы по краям
    
        $brand = $request->input('brand');
        $model = $request->input('model');
        $year = $request->input('year');
        $selectedEngines = $request->input('engines', []);
        $selectedModifications = json_decode($request->input('selected_modifications', '[]'), true);

        // Проверяем, была ли отправлена форма
        if ($request->has('search_query')) {
        // Получаем авторизованного пользователя
        $user = Auth::user();
    
        // Получаем типы двигателей по выбранным модификациям
        $engineTypes = [];
    
        if (!empty($selectedModifications)) {
            $modificationIds = array_column($selectedModifications, 'id_modification');
            $engineTypes = BaseAvto::whereIn('id_modification', $modificationIds)
                ->pluck('engine')
                ->unique()
                ->toArray();
        }
    
        // Коллекция для всех объявлений
        $allAdverts = collect();
    
        // Разделяем searchQuery на слова
        $words = explode(' ', $searchQuery);
        $engineQuery = null;
    
        // Ищем номер двигателя среди слов
        foreach ($words as $word) {
            if (preg_match('/^[a-zA-Z0-9]+$/', $word)) {
                $engineQuery = $word; // Предполагаем, что это номер двигателя
                break;
            }
        }
    
        // Поиск по номеру двигателя (отдельный запрос)
        if ($engineQuery) {
            $engineAdverts = Advert::where('engine', $engineQuery)
                ->where('status_ad', '!=', 'not_activ')
                ->where('status_pay', '!=', 'not_pay')
                ->where('status_ad', '!=', 'arhiv')
                ->get();
    
            // Добавляем найденные объявления в общую коллекцию
            $allAdverts = $allAdverts->merge($engineAdverts);
        }
    
        // Поиск по номеру запчасти (отдельный запрос)
        if ($searchQuery) {
            // Поиск по номеру запчасти в таблице UserQuery
            $userQueries = UserQuery::where('id_queri', $searchQuery)->get();
    
            if ($userQueries->isNotEmpty()) {
                // Получаем все id_car из найденных записей
                $idCarList = $userQueries->pluck('id_car')->toArray();
    
                // Ищем модификации в таблице BaseAvto
                $modifications = BaseAvto::whereIn('id_modification', $idCarList)->get();
    
                if ($modifications->isNotEmpty()) {
                    // Ищем объявления в таблице Advert по модификациям
                    foreach ($modifications as $modification) {
                        $adverts = Advert::where('brand', $modification->brand)
                            ->where('model', $modification->model)
                            ->whereBetween('year', [$modification->year_from, $modification->year_before])
                            ->where('status_ad', '!=', 'not_activ')
                            ->where('status_pay', '!=', 'not_pay')
                            ->where('status_ad', '!=', 'arhiv')
                            ->get();
    
                        // Добавляем найденные объявления в общую коллекцию
                        $allAdverts = $allAdverts->merge($adverts);
                    }
                }
            }
    
            // Поиск по номеру запчасти в таблице Advert
            $partNumberAdverts = Advert::where('number', 'LIKE', '%' . $searchQuery . '%')
                ->where('status_ad', '!=', 'not_activ')
                ->where('status_pay', '!=', 'not_pay')
                ->where('status_ad', '!=', 'arhiv')
                ->get();
    
            // Добавляем найденные объявления в общую коллекцию
            $allAdverts = $allAdverts->merge($partNumberAdverts);
        }
    
        // Начинаем основной запрос к базе данных
        $query = Advert::query()
            ->where('status_ad', '!=', 'not_activ')
            ->where('status_pay', '!=', 'not_pay')
            ->where('status_ad', '!=', 'arhiv');
    
        // Фильтрация по типам двигателей, если они есть
        if (!empty($engineTypes)) {
            $query->whereIn('engine', $engineTypes);
        }
    
        // Фильтрация по модификациям
        if ($brand && $model) {
            if ($year) {
                // Если год указан, ищем поколение по марке, модели и году
                $generation = BaseAvto::where('brand', $brand)
                    ->where('model', $model)
                    ->where('year_from', '<=', $year)
                    ->where('year_before', '>=', $year)
                    ->first();
    
                if ($generation) {
                    $query->whereBetween('year', [$generation->year_from, $generation->year_before]);
                } else {
                    return back()->withErrors(['message' => 'Для указанного года не найдено подходящего поколения модели.']);
                }
            } else {
                // Если год не указан, ищем только по марке и модели
                $query->where('brand', $brand)->where('model', $model);
            }
        }
    
        // Фильтрация по параметру engine
        if (!empty($selectedEngines)) {
            // Приводим выбранные двигатели к нижнему регистру
            $selectedEngines = array_map('strtolower', $selectedEngines);
        
            // Используем whereRaw для сравнения в нижнем регистре
            $query->whereRaw('LOWER(engine) IN (?)', [implode(',', $selectedEngines)]);
        }
        
        

        // Добавляем функцию поиска по совместимости
        $compatibilityAdverts = collect(); // Коллекция для объявлений по совместимости
    
        if ($searchQuery && $brand && $model) {
            // Разбиваем searchQuery на слова
            $firstWord = $words[0]; // Первое слово для поиска
    
            // Ищем все part_id в таблице parts_list, где part_name начинается с первого слова (регистронезависимый поиск)
            $parts = Part::whereRaw('LOWER(part_name) LIKE ?', [strtolower($firstWord) . '%'])->get();
    
            if ($parts->isNotEmpty()) {
                foreach ($parts as $part) {
                    $partId = $part->part_id;
    
                    // Ищем id_modification по марке, модели и году (если год указан)
                    $modificationsQuery = BaseAvto::where('brand', $brand)
                        ->where('model', $model);
    
                    if ($year) {
                        $modificationsQuery->where('year_from', '<=', $year)
                            ->where('year_before', '>=', $year);
                    }
    
                    $modifications = $modificationsQuery->pluck('id_modification')->toArray();
    
                    if (!empty($modifications)) {
                        // Ищем id_queri в таблице users_queries по id_part и id_car
                        $userQueries = UserQuery::where('id_part', $partId)
                            ->whereIn('id_car', $modifications)
                            ->pluck('id_queri')
                            ->toArray();
    
                        if (!empty($userQueries)) {
                            // Ищем id_car в таблице users_queries по id_queri
                            $idCars = UserQuery::whereIn('id_queri', $userQueries)
                                ->pluck('id_car')
                                ->toArray();
    
                            if (!empty($idCars)) {
                                // Ищем данные в таблице base_avto по id_modification = id_car
                                $baseAvtos = BaseAvto::whereIn('id_modification', $idCars)
                                    ->get(['brand', 'model', 'year_from', 'year_before']);
    
                                if ($baseAvtos->isNotEmpty()) {
                                    // Ищем объявления в таблице adverts по brand, model и year
                                    foreach ($baseAvtos as $baseAvto) {
                                        $adverts = Advert::where('brand', $baseAvto->brand)
                                            ->where('model', $baseAvto->model)
                                            ->whereBetween('year', [$baseAvto->year_from, $baseAvto->year_before])
                                            ->where('status_ad', '!=', 'not_activ')
                                            ->where('status_pay', '!=', 'not_pay')
                                            ->where('status_ad', '!=', 'arhiv')
                                            ->get();
    
                                        // Добавляем найденные объявления в коллекцию
                                        $compatibilityAdverts = $compatibilityAdverts->merge($adverts);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    
        // Добавляем результаты поиска по совместимости в общую коллекцию
        $allAdverts = $allAdverts->merge($compatibilityAdverts);
    
       
    
        // Сортируем объявления по релевантности
        if ($searchQuery) {
            $allAdverts = $allAdverts->sortByDesc(function ($advert) use ($words, $partNumberAdverts, $engineQuery, $compatibilityAdverts) {
                // Если объявление найдено по номеру запчасти, по номеру двигателя или по совместимости, оно имеет максимальную релевантность
                if ($partNumberAdverts->contains('id', $advert->id) || $advert->engine === $engineQuery || $compatibilityAdverts->contains('id', $advert->id)) {
                    return PHP_INT_MAX; // Максимальное значение для сортировки
                }
                // Для остальных объявлений считаем релевантность по product_name
                $relevance = 0;
                foreach ($words as $word) {
                    if (stripos($advert->product_name, $word) !== false) {
                        $relevance++; // Увеличиваем релевантность за каждое найденное слово
                    }
                }
                return $relevance;
            });
        }
    
        // Сортируем объявления: сначала те, которые соответствуют выбранным модификациям, затем остальные
        if (!empty($engineTypes)) {
            $allAdverts = $allAdverts->sortByDesc(function ($advert) use ($engineTypes) {
                return in_array($advert->engine, $engineTypes) ? 1 : 0;
            });
        }
    
        // Получаем все уникальные значения для engine из найденных объявлений
        $engines = $allAdverts->pluck('engine')->unique()->filter()->values();
    


// Основной запрос для поиска объявлений
$query = Advert::query()
    ->where('status_ad', '!=', 'not_activ')
    ->where('status_pay', '!=', 'not_pay')
    ->where('status_ad', '!=', 'arhiv');

// Поиск по searchQuery
if ($searchQuery) {
    $words = array_filter(array_map('trim', explode(' ', $searchQuery))); // Удаляем пустые элементы
    $words = array_map('strtolower', $words); // Приводим слова к нижнему регистру

    $query->where(function ($q) use ($words) {
        foreach ($words as $word) {
            $q->whereRaw('LOWER(product_name) LIKE ?', ['%' . $word . '%']);
        }
    });
}

// Поиск по марке
if ($brand) {
    $query->where('brand', $brand);
}

// Поиск по модели
if ($model) {
    $query->where('model', $model);
}

// Поиск по году
if ($year) {
    // Ищем поколения по марке, модели и году
    $generations = BaseAvto::where('brand', $brand)
        ->where('model', $model)
        ->where('year_from', '<=', $year)
        ->where('year_before', '>=', $year)
        ->get();

    if ($generations->isNotEmpty()) {
        // Собираем диапазоны годов для фильтрации объявлений
        $yearRanges = $generations->map(function ($generation) {
            return [$generation->year_from, $generation->year_before];
        });

        // Добавляем условие для поиска объявлений в диапазонах годов
        $query->where(function ($q) use ($yearRanges) {
            foreach ($yearRanges as $range) {
                $q->orWhereBetween('year', $range);
            }
        });
    } else {
        // Если поколений не найдено, пропускаем добавление объявлений
        $allAdverts = collect();
    }
}

// Выполняем запрос и добавляем найденные объявления в общую коллекцию
$allAdverts = $allAdverts->merge($query->get());

// Убираем дубликаты объявлений
$allAdverts = $allAdverts->unique('id');

// Убираем дубликаты двигателей, учитывая регистр
$engines = $allAdverts->pluck('engine')
    ->filter() // Убираем пустые значения
    ->map(function ($engine) {
        return strtolower($engine); // Приводим к нижнему регистру
    })
    ->unique() // Убираем дубликаты
    ->values() // Сбрасываем ключи
    ->toArray(); // Преобразуем в массив

  

     // Разделяем объявления на товары авторизованного пользователя и конкурентов
     $userAdverts = $allAdverts->where('user_id', $user->id);
     $competitorAdverts = $allAdverts->where('user_id', '!=', $user->id);

     // Вычисляем минимальную, максимальную и медианную цену
$prices = $allAdverts->pluck('price')->sort()->values();

$minPrice = $prices->min();
$maxPrice = $prices->max();

// Расчет медианы
$count = $prices->count();
if ($count > 0) {
    $middle = intval($count / 2);
    if ($count % 2 === 0) {
        // Если количество элементов четное, берем среднее двух центральных значений
        $medianPrice = ($prices[$middle - 1] + $prices[$middle]) / 2;
    } else {
        // Если количество элементов нечетное, берем центральное значение
        $medianPrice = $prices[$middle];
    }
} else {
    $medianPrice = null; // Если нет цен, медиана не определена
}


// Сохраняем данные в сессии
session([
    'userAdverts' => $userAdverts,
    'competitorAdverts' => $competitorAdverts,
    'searchQuery' => $searchQuery,
    'brand' => $brand,
    'model' => $model,
    'year' => $year,
    'minPrice' => $minPrice,
    'maxPrice' => $maxPrice,
    'medianPrice' => $medianPrice,
]);

// Получаем все уникальные двигатели из всех объявлений
$allEngines = $allAdverts->pluck('engine')
    ->filter()
    ->unique()
    ->values();

// Сохраняем все двигатели в сессии
session(['allEngines' => $allEngines]);

 // Возвращаем представление с результатами
        return view('market', compact(
            'userAdverts',
            'competitorAdverts',
            'searchQuery',
            'brand',
            'model',
            'year',
            'engines',
            'minPrice',
            'maxPrice',
            'medianPrice'
        ));
    }

     // Если форма не была отправлена, возвращаем пустую страницу
     return view('market', [
        'userAdverts' => collect(),
        'competitorAdverts' => collect(),
        'searchQuery' => null,
        'brand' => null,
        'model' => null,
        'year' => null,
        'engines' => null,
        'minPrice' => null,
        'maxPrice' => null,
        'medianPrice' => null
    ]);
}

public function filterByEngineAnalis(Request $request)
{
    // Получаем результаты поиска из сессии
    $userAdverts = session('userAdverts', collect());
    $competitorAdverts = session('competitorAdverts', collect());

    // Если результаты поиска отсутствуют, возвращаем пустую коллекцию
    if ($userAdverts->isEmpty() && $competitorAdverts->isEmpty()) {
        return view('market', [
            'userAdverts' => collect(),
            'competitorAdverts' => collect(),
            'engines' => collect(),
            'searchQuery' => null,
            'brand' => null,
            'model' => null,
            'year' => null,
            'minPrice' => null,
            'maxPrice' => null,
            'medianPrice' => null,
        ]);
    }

    // Получаем выбранные двигатели из запроса
    $selectedEngines = $request->input('engines', []);

    // Приводим выбранные двигатели к нижнему регистру
    $selectedEngines = array_map('strtolower', $selectedEngines);

    // Фильтруем userAdverts и competitorAdverts по двигателю
    if (!empty($selectedEngines)) {
        $userAdverts = $userAdverts->filter(function ($advert) use ($selectedEngines) {
            $engine = strtolower($advert->engine ?? '');
            return in_array($engine, $selectedEngines);
        });

        $competitorAdverts = $competitorAdverts->filter(function ($advert) use ($selectedEngines) {
            $engine = strtolower($advert->engine ?? '');
            return in_array($engine, $selectedEngines);
        });
    }

    // Получаем уникальные двигатели для фильтров из сессии
    $engines = session('allEngines', collect());

    // Получаем остальные данные из сессии или запроса
    $searchQuery = session('searchQuery', null);
    $brand = session('brand', null);
    $model = session('model', null);
    $year = session('year', null);

    // Вычисляем минимальную, максимальную и медианную цену
    $prices = $userAdverts->merge($competitorAdverts)->pluck('price')->sort()->values();

    $minPrice = $prices->min();
    $maxPrice = $prices->max();

    // Расчет медианы
    $count = $prices->count();
    if ($count > 0) {
        $middle = intval($count / 2);
        if ($count % 2 === 0) {
            // Если количество элементов четное, берем среднее двух центральных значений
            $medianPrice = ($prices[$middle - 1] + $prices[$middle]) / 2;
        } else {
            // Если количество элементов нечетное, берем центральное значение
            $medianPrice = $prices[$middle];
        }
    } else {
        $medianPrice = null; // Если нет цен, медиана не определена
    }

    // Возвращаем представление с отфильтрованными объявлениями и всеми необходимыми данными
    return view('market', compact(
        'userAdverts',
        'competitorAdverts',
        'engines',
        'searchQuery',
        'brand',
        'model',
        'year',
        'minPrice',
        'maxPrice',
        'medianPrice'
    ));
}
}