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
use App\Models\Favorite;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AdvertsController extends Controller
{
    // Показать все объявления со статусом "activ"
    public function index(Request $request)
    {
        // Получаем объявления со статусом "activ"
        $query = Advert::where('status_ad', 'activ')
        ->where('status_pay', '!=', 'not_pay')
         ->where('status_ad', '!=', 'not_activ')
          ->where('status_ad', '!=', 'arhiv');

        // Фильтрация по городу, если параметр передан
        if ($request->has('city') && $request->input('city') !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', $request->input('city'));
            });
        }
    
        // Пагинация объявлений
        $adverts = $query->paginate(20);
    
        // Получаем список городов для выпадающего списка
        $cities = User::distinct()->pluck('city')->toArray(); // Получаем уникальные города из модели User
        
        
    
        return view('adverts.index', compact('adverts', 'cities'));
    }

    // Показать форму для создания нового объявления
    public function create()
    {
        return view('adverts.create');
    }
    


    public function store(Request $request)
{
    // Валидация данных
    $validatedData = $request->validate([
        'art_number' => 'required',
        'product_name' => 'required',
        'brand' => 'required',
        'price' => 'required|numeric|min:0',
        'branch_id' => 'required|exists:branches,id_branch', // Проверяем, что branch_id существует в таблице branches
    ]);

    // Создание объявления
    $advert = new Advert();
    $advert->user_id = auth()->id(); // Предполагается, что пользователь авторизован
    $advert->art_number = $validatedData['art_number'];
    $advert->product_name = $validatedData['product_name'];
    $advert->brand = $validatedData['brand'];
    $advert->price = $validatedData['price'];
    $advert->id_branch = $validatedData['branch_id']; // Сохраняем ID филиала

    // Присвоение необязательных полей, если они присутствуют в запросе
    $optionalFields = [
        'number', 'model', 'new_used', 'year', 'body', 'engine', 'L_R', 'F_R', 'U_D',
        'color', 'applicability', 'quantity', 'availability', 'main_photo_url',
        'additional_photo_url_1', 'additional_photo_url_2', 'additional_photo_url_3'
    ];

    foreach ($optionalFields as $field) {
        if ($request->has($field)) {
            $advert->$field = $request->input($field);
        }
    }
    $advert->save();

    return redirect()->route('adverts.index')->with('success', 'Объявление успешно создано.');
}


    // страница объявления
public function show(Request $request, $id, $product_name_slug, $brand = null, $model = null, $year = null, $engine = null, $number = null)
{
    $advert = Advert::findOrFail($id);

    // Получаем параметры, приоритет отдается параметрам запроса (query parameters)
    $brand = $request->input('brand', $brand); // Если есть в запросе, берем оттуда, иначе из URL
    $model = $request->input('model', $model);
    $year = $request->input('year', $year);
    $engine = $request->input('engine', $engine);
    $number = $request->input('number', $number);

    // Получаем текущее значение массива из куки
    $currentArray = json_decode(request()->cookie('viewed', '[]'), true);

    // Добавляем новый элемент в массив
    $currentArray[$id] = 1;

    // Сохраняем обновленный массив в куки
    Cookie::queue('viewed', json_encode($currentArray), 9999);

    // Проверка, находится ли товар в избранном у текущего пользователя
    $isFavorite = false;
    if (auth()->check()) {
        $isFavorite = Favorite::where('user_id', auth()->id())
            ->where('advert_id', $id)
            ->exists();
    }

    // Найти детали, которые соответствуют product_name
    $parts = $this->findPartsByProductName($advert->product_name);

    // Инициализируем переменные для хранения найденной детали
    $foundPartId = null;
    $foundPartName = null;

    // Если детали найдены, берем первую
    if ($parts->isNotEmpty()) {
        $foundPartId = $parts->first()->part_id;
        $foundPartName = $parts->first()->part_name;
    }

    // Поиск id_modification в модели BaseAvto
    $modificationId = $this->findModificationId($advert);

    // Теперь $adverts - это пустая коллекция, так как нет запросов к UserQuery.  Нужно либо убрать ее из compact(),
    // либо заполнить ее данными каким-то другим способом, исходя из логики приложения
    $adverts = collect();

    // Получаем адрес из таблицы branches
    $branchAddress = null;
    if ($advert->branch) {
        $branchAddress = $advert->branch->address;
    }

    $product_name = $advert->product_name;
    $main_photo_url = $advert->main_photo_url;
    $address_line = $branchAddress;

    // Передать товар, найденную деталь, модификацию и запросы в представление
    return view('adverts.show', compact(
        'advert', 'foundPartId', 'foundPartName', 'modificationId', 'adverts',
        'branchAddress', 'product_name', 'main_photo_url', 'address_line', 'isFavorite', 'brand', 'model', 'year', 'product_name_slug', 'engine', 'number'
    ));
}
    
 private function findPartsByProductName($productName)
{
    return Part::where('part_name', 'LIKE', '%' . $productName . '%')->get();
}

 private function findModificationId($advert)
{
    $query = BaseAvto::where('brand', $advert->brand)
        ->where('model', $advert->model);

    if ($advert->year !== null) {
        $query->where('year_from', '<=', $advert->year)
              ->where('year_before', '>=', $advert->year);
    }

    $baseAvto = $query->first();

    return $baseAvto ? $baseAvto->id_modification : null;
}
private function getRelatedCars($relatedQueries)
{
    // Проверяем, что $relatedQueries не пустая
    if ($relatedQueries->isEmpty()) {
        return collect(); // Возвращаем пустую коллекцию, если нет связанных запросов
    }

    // Получаем уникальные id_car из связанных запросов
    $carIds = $relatedQueries->pluck('id_car')->unique();

    // Возвращаем запрос Eloquent для получения данных из BaseAvto
    return BaseAvto::whereIn('id_modification', $carIds);
}
    
    // Обновить данные объявления в базе данных
    public function update(Request $request)
    {
        $advert = Advert::find($request->id);
    
        // Обновление текстовых полей
        if ($request->art_number !== $request->old_art_number) {
            $advert->art_number = $request->art_number;
        }
        if ($request->product_name !== $request->old_product_name) {
            $advert->product_name = $request->product_name;
        }
        if ($request->number !== $request->old_number) {
            $advert->number = $request->number;
        }
        if ($request->new_used !== $request->old_new_used) {
            $advert->new_used = $request->new_used;
        }
        if ($request->brand !== $request->old_brand) {
            $advert->brand = $request->brand;
        }
        if ($request->model !== $request->old_model) {
            $advert->model = $request->model;
        }
        if ($request->year !== $request->old_year) {
            $advert->year = $request->year;
        }
        if ($request->body !== $request->old_body) {
            $advert->body = $request->body;
        }
        if ($request->engine !== $request->old_engine) {
            $advert->engine = $request->engine;
        }
        if ($request->L_R !== $request->old_L_R) {
            $advert->L_R = $request->L_R;
        }
        if ($request->F_R !== $request->old_F_R) {
            $advert->F_R = $request->F_R;
        }
        if ($request->U_D !== $request->old_U_D) {
            $advert->U_D = $request->U_D;
        }
        if ($request->color !== $request->old_color) {
            $advert->color = $request->color;
        }
        if ($request->applicability !== $request->old_applicability) {
            $advert->applicability = $request->applicability;
        }
        if ($request->quantity !== $request->old_quantity) {
            $advert->quantity = $request->quantity;
        }
        if ($request->price !== $request->old_price) {
            $advert->price = $request->price;
        }
        if ($request->availability !== $request->old_availability) {
            $advert->availability = $request->availability;
        }
    
        // Обновление URL фотографий
        if ($request->main_photo_url !== $request->old_main_photo_url) {
            $advert->main_photo_url = $request->main_photo_url;
        }
        if ($request->additional_photo_url_1 !== $request->old_additional_photo_url_1) {
            $advert->additional_photo_url_1 = $request->additional_photo_url_1;
        }
        if ($request->additional_photo_url_2 !== $request->old_additional_photo_url_2) {
            $advert->additional_photo_url_2 = $request->additional_photo_url_2;
        }
        if ($request->additional_photo_url_3 !== $request->old_additional_photo_url_3) {
            $advert->additional_photo_url_3 = $request->additional_photo_url_3;
        }
    
        $advert->save();
    
        return redirect()->route('adverts.my_adverts')->with('success', 'Объявление успешно обновлено');
    }

   
public function myAdverts(Request $request)
{
    $userId = auth()->id();
    $search = $request->input('search');
    $brandFilter = $request->input('brand');
    $statusFilter = $request->input('status');

    $brands = Advert::where('user_id', $userId)
                    ->select('brand')
                    ->distinct()
                    ->pluck('brand');

    $query = Advert::where('user_id', $userId);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('product_name', 'like', "%{$search}%")
              ->orWhere('number', '=', "{$search}");
        });
    }

    if ($brandFilter) {
        $query->whereRaw('LOWER(brand) = ?', [strtolower($brandFilter)]);
    }

    // Добавляем фильтрацию по статусу
    if ($statusFilter) {
        // Фильтруем по статусу
        if ($statusFilter == 'not_activ') {
            $query->where('status_ad', 'not_activ');
        } elseif ($statusFilter == 'arhiv') {
            $query->where('status_ad', 'arhiv');
        } elseif ($statusFilter == 'sale') {
            $query->where('status_ad', 'sale');
        } elseif ($statusFilter == 'all') {
            // Не применяем фильтрацию по статусу, показываем все
        } else {
            $query->where('status_ad', $statusFilter);
        }
    } else {
        // По умолчанию фильтруем по активным товарам
        $query->where('status_ad', 'activ');
    }

    $adverts = $query->paginate(100);

    // Если нет объявлений, передаем параметры фильтрации в представление
    return view('adverts.my_adverts', compact('adverts', 'brands', 'search', 'brandFilter', 'statusFilter'));
}



    // Удалить объявление из базы данных
    public function destroy($id)
    {
        $advert = Advert::findOrFail($id);
        $advert->delete();
        return redirect()->route('adverts.my_adverts')->with('success', 'Объявление удалено успешно.');
    }

    public function destroyMultiple(Request $request)
{
    $ids = $request->input('ids');
    Advert::whereIn('id', $ids)->delete();

    return response()->json(['success' => true]);
}
    
   public function viewed(Request $request)
{
    // Получаем данные из куки и преобразуем в массив
    $testData = json_decode($request->cookie('viewed', '[]'), true);

    $adverts = []; // Инициализируем массив для хранения объявлений

    foreach ($testData as $id => $value) {
        $advert = Advert::find($id);
        if ($advert) {
            $adverts[] = $advert; // Добавляем объявление в массив
        }
    }

    // Преобразуем массив в коллекцию Laravel
    $adverts = collect($adverts);

    // Передаем данные в представление
    return view('adverts.viewed', compact('adverts'));
}

    public function viewAdvert(Request $request, $advertId)
    {
        // Получаем данные из куки
        $viewedAdverts = json_decode($request->cookie('viewed_adverts', '[]'), true);

        // Добавляем новый товар в список просмотренных
        if (!in_array($advertId, $viewedAdverts)) {
            $viewedAdverts[] = $advertId;
        }

        // Логируем данные перед сохранением в куки
        Log::info('Сохраняем в куки: ' . json_encode($viewedAdverts));

        // Сохраняем обновленный список в куки
        $cookie = Cookie::make('viewed_adverts', json_encode($viewedAdverts), 60 * 24 * 7); // 1 неделя

        // Проверяем, что куки создается корректно
        if ($cookie) {
            Log::info('Куки создана: ' . $cookie->getValue());
        } else {
            Log::error('Ошибка при создании куки');
        }

        $value = Cookie::get('test-cookie-2');

        echo $value;

        //return redirect()->back()->withCookie($cookie);
        return $value;
    }

     public function favorites(Request $request)
{
    $user = auth()->user();

if ($user) {
    // Получаем избранные товары пользователя через связь
    $favorites = $user->favorites()->with('advert')->get();

    // Для каждого товара проверяем, находится ли он в избранном у текущего пользователя
    $favorites->each(function ($favorite) use ($user) {
        $favorite->advert->isFavorite = Favorite::where('user_id', $user->id)
            ->where('advert_id', $favorite->advert->id)
            ->exists();
    });

    // Передаем данные в представление
    return view('adverts.favorites', compact('favorites'));
} else {
    return back()->with('error', 'Для доступа к избранному необходимо авторизоваться.');
}
    
    
    // Получаем текущего авторизованного пользователя
    $user = auth()->user();

  
}


public function showLoading(Request $request)
{
    return view('adverts.loading', [
        'search_query' => $request->input('search_query'),
        'brand' => $request->input('brand'),
        'model' => $request->input('model'),
        'year' => $request->input('year'),
        'selected_modifications' => $request->input('selected_modifications')
    ]);
}

public function search(Request $request)
{
    // Получаем данные из запроса
    $searchQuery = $request->input('search_query');
    $searchQuery = preg_replace('/[^\p{L}\p{N}\s]/u', '', $searchQuery);
    $searchQuery = trim($searchQuery);
    $searchQueryLower = mb_strtolower($searchQuery, 'UTF-8');

    // Получаем марку, модель и год из запроса
    $brand = $request->input('brand');
    $model = $request->input('model');
    $year = $request->input('year');
    $year = $year ? (int)$year : null;

    // Получаем выбранные модификации из запроса
    $selectedModifications = json_decode($request->input('selected_modifications', '[]'), true);

    // Разбиваем поисковый запрос на слова
    $words = explode(' ', $searchQuery);

    // Определяем главное слово
    $mainWord = null;
    foreach ($words as $word) {
        $part = Part::where('part_name', $word)->first();
        if ($part) {
            $mainWord = $word;
            break; // Нашли первое совпадение, выходим из цикла
        }
    }

        // Если главное слово не найдено, можно использовать первый элемент массива $words
        if (empty($mainWord) && !empty($words)) {
           $mainWord = $words[0];
        }
        
        $isPartNumberSearch = false;
       // Проверяем, содержит ли строка цифры (возможный номер запчасти)
if (preg_match('/\d/', $mainWord)) {
    
    $foundIdQueries = [];
    
    // 1. Пытаемся найти по id_queri (точное совпадение) с кэшированием
    $cacheKey = 'user_query_id_'.$mainWord;
    $userQueriesById = Cache::remember($cacheKey, 300, function() use ($mainWord) {
        return UserQuery::where('id_queri', $mainWord)
                      ->select('id_queri', 'id_part')
                      ->limit(100)
                      ->get();
    });
    
    if ($userQueriesById->isNotEmpty()) {
        $foundIdQueries = $userQueriesById->pluck('id_queri')->unique()->toArray();
        $isPartNumberSearch = true;
    } 
    // 2. Если не найдено по id_queri, ищем по part_number с оптимизациями
    else {
        $cacheKey = 'user_query_pn_'.$mainWord;
        $userQueriesByPartNumber = Cache::remember($cacheKey, 300, function() use ($mainWord) {
            return UserQuery::where('part_number', $mainWord)
                          ->select('id_queri', 'id_part')
                          ->limit(100)
                          ->get();
        });
        
        if ($userQueriesByPartNumber->isNotEmpty()) {
            $foundIdQueries = $userQueriesByPartNumber->pluck('id_queri')->unique()->toArray();
            $isPartNumberSearch = true;
        }
    }
    
    // Если нашли какие-то id_queri
    if (!empty($foundIdQueries)) {
        // Оптимизированный поиск названий запчастей
        $cacheKey = 'part_names_'.md5(implode(',', $foundIdQueries));
        $partNames = Cache::remember($cacheKey, 300, function() use ($foundIdQueries) {
            return Part::whereIn('part_id', function($query) use ($foundIdQueries) {
                         $query->select('id_part')
                               ->from('users_queries')
                               ->whereIn('id_queri', $foundIdQueries)
                               ->groupBy('id_part');
                     })
                     ->pluck('part_name')
                     ->unique()
                     ->values()
                     ->toArray();
        });
        
        if (!empty($partNames)) {
            $mainWord = $partNames[0]; // Используем первое найденное название
        }
    }
}
    // Получаем part_id и need на основе need решаем, стоит ли отключать фильтрацию по модификациям.
    $partNameFromQuery = $mainWord; // Используем главное слово
    $part = Part::where('part_name', $partNameFromQuery)->first();

    if ($part) {
        $partIdFromPartsList = $part->part_id;
        $needValue = $part->need; // Получаем значение столбца need

        if ($needValue === 'year') {
            $selectedModifications = null; // Присваиваем null, если need равен year
        }

    } else {
        $partIdFromPartsList = null;
    }

    $allAdverts = collect();
    // Разделение по массивам
    $exactMatchAdverts = collect();
    $engineOrNumberMatchAdverts = collect();
    $remainingAdverts = collect(); // Массив для объявлений, которые не попали в первые два

    // Получаем синонимы (с кэшированием)
    $synonyms = Cache::remember('synonyms:' . $mainWord, 60, function () use ($mainWord) {
        return $this->getSynonyms($mainWord);
    });

    // Добавляем исходный запрос в массив синонимов, чтобы он тоже участвовал в поиске
    $synonyms[] = $mainWord;


    $engineOrPartNumbers = [];
    $productNameParts = [];

    // Разделяем запрос на номера и название
    $lastWord = end($words);
    if (!empty($lastWord) && preg_match('/^[a-zA-Z0-9-]+$/', $lastWord)) {
        $engineOrPartNumbers[] = $lastWord;
        array_pop($words); // Remove the last word from the array
        $productNameParts = $words;
    } else {
        $productNameParts = $words;
    }

    $productName = trim(implode(' ', $productNameParts));
    Log::info("Product Name: " . $productName);

    // Если есть номера двигателей/запчастей
    if (!empty($engineOrPartNumbers)) {
        $engineOrPartNumber = $engineOrPartNumbers[0];

        // 1. Поиск объявлений по номеру двигателя
        $engineAdvertsQuery = $this->getBaseAdvertQuery()
            ->where('engine', '=', $engineOrPartNumber);

        $engineAdvertsQuery = $this->applyBrandModelYearFilter($engineAdvertsQuery, $brand, $model, $year);
        $engineAdverts = $engineAdvertsQuery->limit(5000)->get();

        // 2. Поиск совместимостей в таблице base_avto
        $modifications = BaseAvto::where('engine', $engineOrPartNumber)->get();

        $compatibleAdverts = collect();

        // Объединяем результаты поиска по номеру двигателя и совместимостям
        $allAdverts = $engineAdverts->concat($compatibleAdverts)->unique('id');

        // Если найдены объявления по номеру двигателя, фильтруем по названию продукта
        if ($allAdverts->isNotEmpty()) {
            $allAdverts = $this->filterByProductName($allAdverts,$mainWord, $synonyms);
        }

        // 1. Поиск по номеру запчасти напрямую
        $numberQuery = $this->getBaseAdvertQuery();

        if ($engineOrPartNumber) {
            $numberQuery->whereRaw('LOWER(number) = ?', [strtolower($engineOrPartNumber)]);
        }

        $numberQuery = $this->applyBrandModelYearFilter($numberQuery, $brand, $model, $year);
        $numberAdverts = $numberQuery->limit(5000)->get();

        // 2. Поиск по соответствиям и названию
        $query = $this->getBaseAdvertQuery();

        // Кэшируем UserQuery
        $userQueries = Cache::remember('user_queries:' . $engineOrPartNumber, 60, function () use ($engineOrPartNumber) {
            return UserQuery::where('id_queri', '=', $engineOrPartNumber)->get();
        });

        $idQueriFound = null;

       // Обработка случая, когда $userQueries пуст
if ($userQueries->isEmpty()) {
    $userQueries = Cache::remember('user_queries_part_number:' . $engineOrPartNumber, 60, function () use ($engineOrPartNumber) {
        return UserQuery::where('part_number', '=', $engineOrPartNumber)->get();
    });

    if ($userQueries->isNotEmpty()) {
        // Получаем ВСЕ найденные id_queri для этого part_number
        $idQueriList = $userQueries->pluck('id_queri')->unique()->toArray();
        $idQueriFound = $idQueriList; // Теперь это массив всех подходящих id_queri

        // 1. Поиск объявлений, где number равен исходному part_number
        $partNumberMatchAdvertsQuery = $this->getBaseAdvertQuery();
        $partNumberMatchAdvertsQuery->where('number', '=', $engineOrPartNumber);
        $partNumberMatchAdvertsQuery = $this->applyBrandModelYearFilter($partNumberMatchAdvertsQuery, $brand, $model, $year);
        $partNumberMatchAdverts = $partNumberMatchAdvertsQuery->limit(5000)->get();

        // 2. Поиск объявлений, где number равен любому из найденных id_queri
        $idQueriMatchAdvertsQuery = $this->getBaseAdvertQuery();
        $idQueriMatchAdvertsQuery->whereIn('number', $idQueriList);
        $idQueriMatchAdvertsQuery = $this->applyBrandModelYearFilter($idQueriMatchAdvertsQuery, $brand, $model, $year);
        $idQueriMatchAdverts = $idQueriMatchAdvertsQuery->limit(5000)->get();

        // Объединяем оба результата
        $combinedAdverts = $partNumberMatchAdverts;

        // Добавляем найденные объявления в $exactMatchAdverts, избегая дубликатов
        $exactMatchAdvertsIds = $exactMatchAdverts->pluck('id')->toArray();
        foreach ($combinedAdverts as $advert) {
            if (!in_array($advert->id, $exactMatchAdvertsIds)) {
                $exactMatchAdverts->push($advert);
            }
        }

        // Удаляем дубликаты
        $exactMatchAdverts = $exactMatchAdverts->unique('id');
        
        $engineOrNumberMatchAdverts = $idQueriMatchAdverts;
    }
} else {
    $idQueriFound = [$engineOrPartNumber]; // Для единообразия делаем массивом
}

        // Проверяем, найден ли id_queri. Если нет - все равно ищем объявления по number и engine
        if (!$idQueriFound) {

            // Ищем объявления, у которых number соответствует поисковому запросу
            $numberMatchAdvertsQuery = $this->getBaseAdvertQuery();
            $numberMatchAdvertsQuery->where('number', '=', $engineOrPartNumber);
            $numberMatchAdvertsQuery = $this->applyBrandModelYearFilter($numberMatchAdvertsQuery, $brand, $model, $year);
            $numberMatchAdverts = $numberMatchAdvertsQuery->limit(5000)->get();

            // Ищем объявления, у которых engine соответствует поисковому запросу
            $engineMatchAdvertsQuery = $this->getBaseAdvertQuery();
            $engineMatchAdvertsQuery->where('engine', '=', $engineOrPartNumber);
            $engineMatchAdvertsQuery = $this->applyBrandModelYearFilter($engineMatchAdvertsQuery, $brand, $model, $year);
            $engineMatchAdverts = $engineMatchAdvertsQuery->limit(5000)->get();

            // Объединяем результаты
            $allAdverts = $allAdverts->concat($numberMatchAdverts)->concat($engineMatchAdverts)->unique('id');

            // 2.2 Если поиск по соответствиям не дал результатов, ищем по названию
            if ($engineOrPartNumber) {
                $query = $this->getBaseAdvertQuery();

                // Объединяем слова и синонимы в одну строку для полнотекстового поиска
                $searchText = implode(' ', array_merge($words, $synonyms));
                $query->whereRaw('MATCH(product_name) AGAINST (?)', [$searchText]);

            }

            $query = $this->applyBrandModelYearFilter($query, $brand, $model, $year);
            $matchAdverts = $query->limit(500)->get();

            // Подготовка объявлений для сортировки
            $matchAdverts = $matchAdverts->map(function ($advert) {
                $advert->sort_order = 3;
                return $advert;
            });

            $allAdverts = $allAdverts->concat($matchAdverts);


        } else {

            $partName = null;
            $partId = null;
            $partSynonyms = [];  //  Добавляем массив для синонимов part_name

            // Добавлена проверка на null для $userQueries перед использованием
            if ($userQueries->isNotEmpty()) {
                $firstUserQuery = $userQueries->first(); // Получаем первую модель UserQuery

                if ($firstUserQuery) { // Добавлена проверка, что $firstUserQuery не null
                    $partId = $firstUserQuery->id_part;

                     // Кэшируем Part
                    $part = Cache::remember('part:' . $partId, 60, function () use ($partId) {
                       return Part::find($partId);
                    });


                    if ($part) {
                        $partName = $part->part_name;
                        $partSynonyms = $this->getSynonyms($partName); // Получаем синонимы для part_name
                    }
                }
            }

           // Проверяем, является ли введенный номер уже id_queri
Log::info('Проверяем, является ли введенный номер уже id_queri. engineOrPartNumber: ' . $engineOrPartNumber);
$isEngineOrPartNumberAnIdQueri = UserQuery::where('id_queri', $engineOrPartNumber)->exists();

Log::info('Результат проверки, является ли engineOrPartNumber id_queri: ' . ($isEngineOrPartNumberAnIdQueri ? 'Да' : 'Нет'));

if (!$isEngineOrPartNumberAnIdQueri) {
    Log::info('engineOrPartNumber НЕ является id_queri.  Выполняем стандартный поиск.');

    //  --- НОВАЯ ЛОГИКА ПОИСКА ---
    $idQueriList = $userQueries->pluck('id_queri')->toArray();

    //  Формируем строку для поиска в queri_number
    $searchString = implode(',', $idQueriList);

    //  Выполняем поиск в таблице adverts
    $advertsQuery = $this->getBaseAdvertQuery()
        ->where('status_queri', 'done')
        ->where(function ($query) use ($idQueriList) {
            foreach ($idQueriList as $idQueri) {
                $query->orWhere('queri_number', 'like', '%' . $idQueri . '%');
            }
        });

    $advertsQuery = $this->applyBrandModelYearFilter($advertsQuery, $brand, $model, $year);
    $matchedAdverts = $advertsQuery->limit(5000)->get();

    $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->concat($matchedAdverts)->unique('id');

    //  Добавляем фильтрацию по названию продукта
    if ($engineOrNumberMatchAdverts->isNotEmpty() && $productName) {
        $engineOrNumberMatchAdverts = $this->filterByProductName($engineOrNumberMatchAdverts, $mainWord, $synonyms);
        Log::info('engineOrNumberMatchAdverts after filterByProductName: ' . count($engineOrNumberMatchAdverts));
    }

    // Поиск объявлений, у которых в number указан найденный id_queri
    $directMatchAdvertsQuery = $this->getBaseAdvertQuery();

    if ($idQueriFound) {
        $directMatchAdvertsQuery->where('number', '=', $idQueriFound);
    }

    $directMatchAdvertsQuery = $this->applyBrandModelYearFilter($directMatchAdvertsQuery, $brand, $model, $year);
    $directMatchAdverts = $directMatchAdvertsQuery->limit(5000)->get();

    // Добавляем найденные объявления в $exactMatchAdverts
    $exactMatchAdvertsIds = $exactMatchAdverts->pluck('id')->toArray(); // Получаем ID уже добавленных объявлений
    foreach ($directMatchAdverts as $advert) {
        if (!in_array($advert->id, $exactMatchAdvertsIds)) { // Проверяем, что ID нет в массиве
            $exactMatchAdverts->push($advert);
        }
    }

    // Удаляем дубликаты, если они есть
    $exactMatchAdverts = $exactMatchAdverts->unique('id');

    // 2.2 Если поиск по соответствиям не дал результатов, ищем по названию
    if ($engineOrPartNumber && $userQueries->isEmpty()) {
        $query = $this->getBaseAdvertQuery();

        // Объединяем слова и синонимы в одну строку для полнотекстового поиска
        $searchText = implode(' ', array_merge($words, $synonyms));
        $query->whereRaw('MATCH(product_name) AGAINST (?)', [$searchText]);
    }

    $query = $this->applyBrandModelYearFilter($query, $brand, $model, $year);
    $matchAdverts = $query->limit(5000)->get();

        } else {
            //  --- НОВАЯ ЛОГИКА ПОИСКА ---
        $idQueriList = $userQueries->pluck('id_queri')->toArray();
        $partNumbers = [];
        
        // 1. Получаем part_number по id_queri (Предполагается, что у вас есть таблица, связывающая id_queri и part_number)
        if (!empty($idQueriList)) {
            // Предположим, что у вас есть модель QueryPartNumber, связывающая id_queri и part_number
            $partNumbers = UserQuery::whereIn('id_queri', $idQueriList)->pluck('part_number')->toArray();
            $partNumbers = array_filter(array_unique(array_map('trim', $partNumbers))); // Удаляем пустые и дубликаты
        }
        
        //  Формируем строку для поиска в queri_number
        $searchString = implode(',', $idQueriList);
        
        //  Выполняем поиск в таблице adverts
        $advertsQuery = $this->getBaseAdvertQuery()
            ->where('status_queri', 'done')
            ->where(function ($query) use ($idQueriList) {
                foreach ($idQueriList as $idQueri) {
                    $query->orWhere('queri_number', 'like', '%' . $idQueri . '%');
                }
            });
        
        $advertsQuery = $this->applyBrandModelYearFilter($advertsQuery, $brand, $model, $year);
        $matchedAdverts = $advertsQuery->limit(5000)->get();
        
        $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->concat($matchedAdverts)->unique('id');
        
        //  Добавляем фильтрацию по названию продукта
        if ($engineOrNumberMatchAdverts->isNotEmpty() && $productName) {
            $engineOrNumberMatchAdverts = $this->filterByProductName($engineOrNumberMatchAdverts, $mainWord, $synonyms);
            Log::info('engineOrNumberMatchAdverts after filterByProductName: ' . count($engineOrNumberMatchAdverts));
        }
        
        //  2.  Поиск по part_number  -----------------------------------------------------
        $partNumberMatchAdvertsQuery = $this->getBaseAdvertQuery();
        
        if (!empty($partNumbers)) {
            $partNumberMatchAdvertsQuery->whereIn('number', $partNumbers); // Предполагается, что 'number' содержит part_number
        }
        
        $partNumberMatchAdvertsQuery = $this->applyBrandModelYearFilter($partNumberMatchAdvertsQuery, $brand, $model, $year);
        $partNumberMatchAdverts = $partNumberMatchAdvertsQuery->limit(5000)->get();
        
        // Объединяем результаты, избегая дубликатов
        $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->concat($partNumberMatchAdverts)->unique('id');
        
        // Поиск объявлений, у которых в number указан найденный id_queri
        $directMatchAdvertsQuery = $this->getBaseAdvertQuery();
        
        if ($idQueriFound) {
            $directMatchAdvertsQuery->where('number', '=', $idQueriFound);
        }
        
        $directMatchAdvertsQuery = $this->applyBrandModelYearFilter($directMatchAdvertsQuery, $brand, $model, $year);
        $directMatchAdverts = $directMatchAdvertsQuery->limit(5000)->get();
        
        // Добавляем найденные объявления в $exactMatchAdverts
        $exactMatchAdvertsIds = $exactMatchAdverts->pluck('id')->toArray(); // Получаем ID уже добавленных объявлений
        foreach ($directMatchAdverts as $advert) {
            if (!in_array($advert->id, $exactMatchAdvertsIds)) { // Проверяем, что ID нет в массиве
                $exactMatchAdverts->push($advert);
            }
        }
        
        // Удаляем дубликаты, если они есть
        $exactMatchAdverts = $exactMatchAdverts->unique('id');
        
        // 2.2 Если поиск по соответствиям не дал результатов, ищем по названию
        if ($engineOrPartNumber && $userQueries->isEmpty()) {
            $query = $this->getBaseAdvertQuery();
        
            // Объединяем слова и синонимы в одну строку для полнотекстового поиска
            $searchText = implode(' ', array_merge($words, $synonyms));
            $query->whereRaw('MATCH(product_name) AGAINST (?)', [$searchText]);
        }
        
        $query = $this->applyBrandModelYearFilter($query, $brand, $model, $year);
        $matchAdverts = $query->limit(5000)->get();
        
        }
            // Подготовка объявлений для сортировки
            $directMatchAdverts = $directMatchAdverts->map(function ($advert) {
                $advert->sort_order = 0;
                return $advert;
            });

            $numberAdverts = $numberAdverts->map(function ($advert) {
                $advert->sort_order = 1;
                return $advert;
            });

            $matchAdverts = $matchAdverts->map(function ($advert) use ($partName) {
                if ($partName && strpos($advert->product_name, $partName) !== false) {
                    $advert->sort_order = 2;
                } else {
                    $advert->sort_order = 3;
                }
                return $advert;
            });
            
                  if ($isPartNumberSearch) {
            // 1. Поиск в user_queries сначала по id_queri
            $userQueries = UserQuery::where('id_queri', $engineOrPartNumber)->get();
        
            // Если не найдено по id_queri, ищем по part_number и получаем связанные id_queri
            if ($userQueries->isEmpty()) {
                
                $partNumberQueries = UserQuery::where('part_number', $engineOrPartNumber)->get();
                
                if ($partNumberQueries->isNotEmpty()) {
                    $relatedIds = $partNumberQueries->pluck('id_queri')->filter()->unique();
                    $userQueries = UserQuery::whereIn('id_queri', $relatedIds)->get();
                }
            }
        
            // 2. Получаем марки и модели автомобилей
            $idCarList = $userQueries->pluck('id_car')->unique()->toArray();
            $baseAvtoRecords = BaseAvto::whereIn('id_modification', $idCarList)->get();
            
            $brandsModels = $baseAvtoRecords->map(function($item) {
                return ['brand' => $item->brand, 'model' => $item->model];
            })->unique()->toArray();
        
            // 3. Определяем значение need для фильтрации
            $idParts = $userQueries->pluck('id_part')->unique()->filter()->values()->toArray();
            $needValue = null;
            
            if (!empty($idParts)) {
                $parts = Part::whereIn('part_id', $idParts)->get();
                $needValues = $parts->pluck('need')->unique()->filter()->values()->toArray();
                
                if (!empty($needValues)) {
                    $needValue = in_array('engine', $needValues) ? 'engine' : $needValues[0];
                }
            }
        
            // 4. Поиск объявлений по параметрам
            $partNumberAdverts = collect();
        
            foreach ($brandsModels as $brandModel) {
                $query = Advert::where('brand', $brandModel['brand'])
                              ->where('model', $brandModel['model']);
                    
                if ($needValue === 'engine') {
                    $query->where(function($q) {
                        $q->whereNull('engine')->orWhere('engine', '');
                    })->where(function($q) {
                        $q->whereNull('number')->orWhere('number', '');
                    });
                } else {
                    $query->where(function($q) {
                        $q->whereNull('year')->orWhere('year', '');
                    })->where(function($q) {
                        $q->whereNull('number')->orWhere('number', '');
                    });
                }
                
                $partNumberAdverts = $partNumberAdverts->concat($query->limit(100)->get());
            }
        
            // 5. Фильтрация по названию
            $partNumberAdverts = $this->filterByProductName($partNumberAdverts, $mainWord, $synonyms);
            
            // 6. Удаление дубликатов и объединение результатов
            $partNumberAdvertIds = $partNumberAdverts->pluck('id')->toArray();
            
            $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->reject(function($advert) use ($partNumberAdvertIds) {
                return in_array($advert->id, $partNumberAdvertIds);
            });
            
            $allAdverts = $allAdverts->concat($partNumberAdverts)->unique('id');
        }

            // Если найдены объявления по номеру запчасти, фильтруем по названию продукта
            if ($allAdverts->isNotEmpty()) {
                $allAdverts = $this->filterByProductName($allAdverts, $mainWord, $synonyms);
            }
        }
    } else {
        // Если нет номера двигателя/запчасти, ищем просто по названию
         Log::info('Поиск без номера начало:');
       $query = $this->getBaseAdvertQuery();
        if (!empty($productName)) {

            // Объединяем слова и синонимы в одну строку для полнотекстового поиска
            $searchText = implode(' ', array_merge($words, $synonyms));
            $query->whereRaw('MATCH(product_name) AGAINST (?)', [$searchText]);
        }

        $query = $this->applyBrandModelYearFilter($query, $brand, $model, $year);
        $allAdverts = $query->limit(5000)->get();
         Log::info('Поиск 1 закончен:');
         
$partNeed = null; // Инициализируем переменную

if ($partIdFromPartsList) {
    $part = Part::find($partIdFromPartsList); // Предполагаем, что Part модель соответствует таблице parts_list

    if ($part) {
        $partNeed = $part->need;
    } else {
        Log::warning('Запись в Part (parts_list) не найдена для part_id: ' . $partIdFromPartsList);
    }
} else {
    Log::warning('Невозможно получить need, так как partIdFromPartsList равен null.');
}

// Проверяем, равно ли значение 'need' году
if ($partNeed === 'year') { // Используем строгое сравнение
    Log::info('Значение need равно year, начинаем поиск id_modification.');

    $year = (int) $year; // Преобразуем $year в целое число (если это строка)

    $baseAvtoMatches = BaseAvto::where('brand', $brand)
        ->where('model', $model)
        ->where('year_from', '<=', $year)
        ->where('year_before', '>=', $year)
        ->limit(10000) //  Добавлен лимит в 100 записей
        ->get();

    $idModifications = $baseAvtoMatches->pluck('id_modification')->toArray();

    Log::info('Найденные id_modification:', ['id_modification' => $idModifications]); // Выводим в лог найденные id_modification
} else {
    Log::info('Значение need не равно year, пропуск поиска id_modification.');
    // Можно добавить логику обработки, если need не равно 'year', например, другую стратегию поиска.
    $idModifications = []; // или другое значение по умолчанию
}

if (!empty($idModifications)) {
    Log::info('Начинаем поиск товаров по id_modification: ' . implode(', ', $idModifications));

   // Получаем данные из BaseAvto по id_modification
$baseAvtoMatches = BaseAvto::whereIn('id_modification', $idModifications)->get();

foreach ($baseAvtoMatches as $baseAvtoMatch) {
    // Ищем точные совпадения с учетом главного слова
    $exactMatchedAdvertsQuery = Advert::where('brand', $baseAvtoMatch->brand)
        ->where('model', $baseAvtoMatch->model)
        ->where('year', $year) // Ищем строгое соответствие по году
        ->where('product_name', 'like', '%' . $mainWord . '%') // Фильтруем по главному слову
        ->limit(10000);

    //Ищем точные совпадения по синонимам если $mainWord не найдено
     if ($exactMatchedAdvertsQuery->count() === 0) {
        $exactMatchedAdvertsQuery = Advert::where('brand', $baseAvtoMatch->brand)
            ->where('model', $baseAvtoMatch->model)
            ->where('year', $year) // Ищем строгое соответствие по году
            ->where(function ($query) use ($synonyms) {
                foreach ($synonyms as $synonym) {
                    $query->orWhere('product_name', 'like', '%' . $synonym . '%');
                }
            })
            ->limit(10000);
    }
    
    $exactMatchedAdverts = $exactMatchedAdvertsQuery->get();

    // Добавляем точные совпадения в коллекцию $exactMatchAdverts
    $exactMatchAdverts = $exactMatchAdverts->concat($exactMatchedAdverts);

    // Ищем остальные совпадения (по диапазону year_from и year_before, исключая точные) с учетом главного слова
    $matchedAdvertsQuery = Advert::where('brand', $baseAvtoMatch->brand)
        ->where('model', $baseAvtoMatch->model)
        ->where('year', '>=', $baseAvtoMatch->year_from)
        ->where('year', '<=', $baseAvtoMatch->year_before)
        ->where('year', '!=', $year) // Исключаем точные совпадения по году
        ->where('product_name', 'like', '%' . $mainWord . '%') // Фильтруем по главному слову
        ->limit(1000); // Добавлен лимит в 50 записей
        
      // Ищем остальные совпадения по синонимам
       if ($matchedAdvertsQuery->count() === 0) {
            $matchedAdvertsQuery = Advert::where('brand', $baseAvtoMatch->brand)
                ->where('model', $baseAvtoMatch->model)
                ->where('year', '>=', $baseAvtoMatch->year_from)
                ->where('year', '<=', $baseAvtoMatch->year_before)
                ->where('year', '!=', $year) // Исключаем точные совпадения по году
                ->where(function ($query) use ($synonyms) {
                    foreach ($synonyms as $synonym) {
                        $query->orWhere('product_name', 'like', '%' . $synonym . '%');
                    }
                })
                ->limit(1000);
        }
        
    $matchedAdverts = $matchedAdvertsQuery->get();

    // Добавляем остальные совпадения в коллекцию $engineOrNumberMatchAdverts
    $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->concat($matchedAdverts);
}

    // Удаляем дубликаты
    $exactMatchAdverts = $exactMatchAdverts->unique('id');
    $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->unique('id');

    Log::info('Найдено точных совпадений: ' . $exactMatchAdverts->count());
    Log::info('Найдено остальных совпадений: ' . $engineOrNumberMatchAdverts->count());
} else {
    Log::info('Нет id_modification для поиска товаров.');
}

$engineOrNumberMatchAdverts = $this->filterByProductName($engineOrNumberMatchAdverts,$mainWord, $synonyms);
$exactMatchAdverts = $this->filterByProductName($exactMatchAdverts,$mainWord, $synonyms);
if ($brand && $model && $year){
 $partNameFromQuery = $mainWord;
        $part = Part::where('part_name', $partNameFromQuery)->first();

        if ($part) {
            $partIdFromPartsList = $part->part_id;
           
        } else {
            $partIdFromPartsList = null;
        }
        
         $year = (int) $year;
        $baseAvtoMatches = BaseAvto::where('brand', $brand)
            ->where('model', $model)
            ->where('year_from', '<=', $year)
            ->where('year_before', '>=', $year)
            ->get();

        $idModifications = $baseAvtoMatches->pluck('id_modification')->toArray();


        if ($partIdFromPartsList !== null && !empty($idModifications)) {
            $userQueries = UserQuery::whereIn('id_car', $idModifications)
                ->where('id_part', $partIdFromPartsList)
                ->get();
            $idQueriList = $userQueries->pluck('id_queri')->toArray();
        } else {
            $idQueriList = [];
        }

$allAdverts = $this->getAllAdvertsFromIdQueriList($idQueriList, $mainWord, $synonyms, $brand, $model, $partIdFromPartsList);
$allAdverts = $allAdverts->unique('id');
$allAdverts = $this->filterByProductName($allAdverts, $mainWord, $synonyms);
}
}

    $engineOrNumberMatchAdverts = $this->filterByProductName($engineOrNumberMatchAdverts,$mainWord, $synonyms);
    $exactMatchAdverts = $this->filterByProductName($exactMatchAdverts,$mainWord, $synonyms);
    $allAdverts = $this->filterByProductName($allAdverts, $mainWord, $synonyms);
    
    
if ($brand && $model && $year && !empty($selectedModifications)) {
     Log::info('Начало поиска по всем значениям:');
    $cacheKey = md5(serialize([$brand, $model, $year, $selectedModifications, $searchQuery])); // Ключ кэша, включающий все параметры поиска
    // Попытка получить данные из кэша
    $cachedData = Cache::get($cacheKey);

    if ($cachedData) {
        // Данные найдены в кэше
        Log::info('Данные получены из кэша для ключа: ' . $cacheKey);

        $partIdFromPartsList = $cachedData['partIdFromPartsList'];
        $idModifications = $cachedData['idModifications'];
        $idQueriList = $cachedData['idQueriList'];
    } else {
    // Данных в кэше нет, выполняем запросы в базу данных
    Log::info('Данные отсутствуют в кэше, выполняем запросы к БД для ключа: ' . $cacheKey);
    Log::debug('Параметры запроса:', [
        'brand' => $brand,
        'model' => $model,
        'year' => $year,
        'mainWord' => $mainWord
    ]);

    // Поиск part_id по названию детали
    $partNameFromQuery = $mainWord;
    Log::info('Поиск part_id для детали: ' . $partNameFromQuery);
    $part = Part::where('part_name', $partNameFromQuery)->first();

    if ($part) {
        $partIdFromPartsList = $part->part_id;
        Log::info('Найден part_id: ' . $partIdFromPartsList);
    } else {
        $partIdFromPartsList = null;
        Log::warning('Part не найден для: ' . $partNameFromQuery);
    }

    // Поиск модификаций авто
    Log::info('Поиск модификаций авто для brand=' . $brand . ', model=' . $model . ', year=' . $year);
    $baseAvtoMatches = BaseAvto::where('brand', $brand)
        ->where('model', $model)
        ->where('year_from', '<=', $year)
        ->where('year_before', '>=', $year)
        ->get();

    Log::info('Найдено модификаций: ' . $baseAvtoMatches->count());
    Log::debug('ID модификаций:', $baseAvtoMatches->pluck('id_modification')->toArray());

    $idModifications = $baseAvtoMatches->pluck('id_modification')->toArray();

    // Поиск запросов пользователей
    if ($partIdFromPartsList !== null && !empty($idModifications)) {
        Log::info('Поиск user_queries для part_id=' . $partIdFromPartsList . ' и ' . count($idModifications) . ' модификаций');
        $userQueries = UserQuery::whereIn('id_car', $idModifications)
            ->where('id_part', $partIdFromPartsList)
            ->get();

        Log::info('Найдено user_queries: ' . $userQueries->count());
        $idQueriList = $userQueries->pluck('id_queri')->toArray();
        Log::debug('ID user_queries:', $idQueriList);
    } else {
        $idQueriList = [];
        Log::warning('Не удалось найти user_queries - отсутствует part_id или модификации');
    }

    // Сохраняем данные в кэш
    $cacheData = [
        'partIdFromPartsList' => $partIdFromPartsList,
        'idModifications' => $idModifications,
        'idQueriList' => $idQueriList,
    ];
    
    Cache::put($cacheKey, $cacheData, 60 * 10); // Кэшируем на 10 минут
    Log::info('Данные сохранены в кэш для ключа: ' . $cacheKey, [
        'cache_ttl' => '10 минут',
        'cached_data_summary' => [
            'partId' => $partIdFromPartsList,
            'modifications_count' => count($idModifications),
            'queri_count' => count($idQueriList)
        ]
    ]);
}
  

    $engines = BaseAvto::whereIn('id_modification', $idModifications)->pluck('engine')->toArray();

    $allEngineNumbers = [];

    $baseAvtoMatches = BaseAvto::where('brand', $brand)
        ->where('model', $model)
        ->where('year_from', '<=', $year)
        ->where('year_before', '>=', $year)
        ->get();

    foreach ($baseAvtoMatches as $baseAvtoMatch) {
        if (!in_array($baseAvtoMatch->engine, $allEngineNumbers)) {
            $allEngineNumbers[] = $baseAvtoMatch->engine;
        }
    }
    
   

  $partNumbers = UserQuery::whereIn('id_queri', $idQueriList)
    ->pluck('part_number')
    ->toArray();

// Фильтруем пустые строки, null и строки только из пробелов
$partNumbers = array_filter($partNumbers, function ($value) {
    return $value !== null && trim($value) !== '';
});

// Логирование для проверки
\Log::info('Количество partNumbers после фильтрации(3): ' . count($partNumbers));
\Log::debug('Отфильтрованные partNumbers:', $partNumbers);

     $allAdverts = collect();

    if (!empty($partNumbers)) {
        foreach ($partNumbers as $partNumber) {
            $results = Advert::query()
                ->where(function ($query) use ($partNumber) {
                    $query->where('engine', '=', $partNumber)
                        ->orWhere('number', '=', $partNumber)
                        ->orWhere('queri_number', '=', $partNumber);
                })
                ->where(function ($query) use ($synonyms) { // Ищем соответствие любому из синонимов
                    foreach ($synonyms as $synonym) {
                        $query->orWhere('product_name', 'like', '%' . $synonym . '%');
                    }
                })
                ->get();

            $allAdverts = $allAdverts->concat($results);
        }

        $allAdverts = $allAdverts->unique('id');
    }

    $engineMatchAdverts = $this->getBaseAdvertQuery()
        ->whereIn('engine', $engines)
        ->get();

    $numberMatchAdverts = $this->getBaseAdvertQuery()
        ->whereIn('number', $idQueriList)
        ->get();

    $engineMatchAdverts = $this->filterByProductName($engineMatchAdverts,$mainWord, $synonyms);
    $numberMatchAdverts = $this->filterByProductName($numberMatchAdverts, $mainWord, $synonyms);

    $allAdverts = $allAdverts->concat($engineMatchAdverts)->concat($numberMatchAdverts)->unique('id');


    if ($partIdFromPartsList && !empty($idModifications)) {
        $additionalUserQueries = UserQuery::where('id_part', $partIdFromPartsList)
            ->whereIn('id_car', $idModifications)
            ->get();

        if ($additionalUserQueries->isNotEmpty()) {
            $additionalIdQueriList = $additionalUserQueries->pluck('id_queri')->toArray();

            $userQueriesForAdditionalIdCar = UserQuery::whereIn('id_queri', $additionalIdQueriList)->get();

            if ($userQueriesForAdditionalIdCar->isNotEmpty()) {
                $additionalIdCars = $userQueriesForAdditionalIdCar->pluck('id_car')->toArray();

                $additionalBaseAvtoMatches = BaseAvto::whereIn('id_modification', $additionalIdCars)->get();

                if ($additionalBaseAvtoMatches->isNotEmpty()) {
                    $additionalEngines = $additionalBaseAvtoMatches->pluck('engine')->toArray();

                    $additionalEngineMatchAdverts = $this->getBaseAdvertQuery()
                        ->whereIn('engine', $additionalEngines)
                        ->get();

                    $additionalEngineMatchAdverts = $this->filterByProductName($additionalEngineMatchAdverts,$mainWord, $synonyms);

                    $allAdverts = $allAdverts->concat($additionalEngineMatchAdverts)->unique('id');
                }
            }

            $allIdQueriList = array_merge($idQueriList, $additionalIdQueriList);

            $additionalNumberMatchAdverts = $this->getBaseAdvertQuery()
                ->whereIn('number', $allIdQueriList)
                ->get();

            $allAdverts = $allAdverts->concat($additionalNumberMatchAdverts)->unique('id');
        }
    }

    $exactMatchAdverts = collect();
    $engineOrNumberMatchAdverts = collect();
    $remainingAdverts = collect();

    $exactMatchAdvertsIds = collect($exactMatchAdverts)->pluck('id')->toArray();
    $engineOrNumberMatchAdvertsIds = collect($engineOrNumberMatchAdverts)->pluck('id')->toArray();


    foreach ($allAdverts as $advert) {
        $isExactMatch = false;
        $isEngineOrNumberMatch = false;

        foreach ($selectedModifications as $modification) {
            $selectedIdModification = $modification['id_modification'];
            $baseAvtoMatch = BaseAvto::find($selectedIdModification);

            if ($baseAvtoMatch) {
                // Проверка точного соответствия
                if (
                    mb_strtolower($advert->brand) == mb_strtolower($baseAvtoMatch->brand) &&
                    mb_strtolower($advert->model) == mb_strtolower($baseAvtoMatch->model) &&
                    $advert->year >= $baseAvtoMatch->year_from &&
                    $advert->year <= $baseAvtoMatch->year_before &&
                    (!empty($advert->engine) && mb_strtolower($advert->engine) == mb_strtolower($baseAvtoMatch->engine))
                ) {
                    if (!in_array($advert->id, $exactMatchAdvertsIds)) {
                        $exactMatchAdverts->push($advert);
                        $exactMatchAdvertsIds[] = $advert->id;
                    }
                    $isExactMatch = true;
                    break; // Прерываем цикл по modifications
                }

                // Проверка соответствия только по двигателю
                if (!$isExactMatch) {
                    if (!empty($advert->engine) && mb_strtolower($advert->engine) == mb_strtolower($baseAvtoMatch->engine)) {
                        if (!in_array($advert->id, $engineOrNumberMatchAdvertsIds)) {
                            $engineOrNumberMatchAdverts->push($advert);
                            $engineOrNumberMatchAdvertsIds[] = $advert->id;
                        }
                        $isEngineOrNumberMatch = true;
                        break; // Прерываем цикл по modifications
                    }

                    // Проверка соответствия по номеру или product_name
                    if (!empty($advert->number)) {
                        $userQueryMatch = UserQuery::where('id_queri', $advert->number)
                            ->where('id_car', $selectedIdModification)
                            ->first();

                        if ($userQueryMatch) {
                            if (!in_array($advert->id, $engineOrNumberMatchAdvertsIds)) {
                                $engineOrNumberMatchAdverts->push($advert);
                                $engineOrNumberMatchAdvertsIds[] = $advert->id;
                            }
                            $isEngineOrNumberMatch = true;
                            break; // Прерываем цикл по modifications
                        } else {
                            $userQueryByPartNumber = UserQuery::where('part_number', $advert->number)->first();

                            if ($userQueryByPartNumber) {
                                $idQueriMatches = UserQuery::where('part_number', $advert->number)->pluck('id_queri')->toArray();

                                foreach ($idQueriMatches as $idQueri) {
                                    $userQueryMatchByIdQueriAndIdCar = UserQuery::where('id_queri', $idQueri)
                                        ->where('id_car', $selectedIdModification)
                                        ->first();

                                    if ($userQueryMatchByIdQueriAndIdCar) {
                                        if (!in_array($advert->id, $engineOrNumberMatchAdvertsIds)) {
                                            $engineOrNumberMatchAdverts->push($advert);
                                            $engineOrNumberMatchAdvertsIds[] = $advert->id;
                                        }
                                        $isEngineOrNumberMatch = true;
                                        break 2; // Прерываем цикл по modifications и цикл по idQueriMatches
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        // Если не было найдено точного соответствия или соответствия по двигателю/номеру
        if (!$isExactMatch && !$isEngineOrNumberMatch) {
            // Блок кода для поиска по названию запчасти и модификации
            $searchAuto = $mainWord;
            Log::info('$searchAuto:', ['Название запчасти' => $searchAuto]);
            $partName = $searchAuto;

            $parts = Part::where('part_name', 'LIKE', '%' . $partName . '%')->get();

            $partIds = []; // Инициализируем массив для хранения part_id

            if ($parts->isNotEmpty()) { // Проверяем, что коллекция не пуста
                foreach ($parts as $part) {
                    $partIds[] = $part->part_id; // Добавляем part_id в массив
                }
                $partIds = array_unique($partIds); // Удаляем дубликаты из массива
                $partIds = array_values($partIds); // Сбрасываем ключи массива после удаления дубликатов
                Log::info('Найдены part_id:', ['part_name' => $partName, 'part_ids' => $partIds]);
            } else {
                Log::warning('Не найдены part_id для part_name:', ['part_name' => $partName]);
            }

            $modificationAuto = [];

            if (is_array($selectedModifications)) { // Убрали проверку на существование ключа 'Модификации'
                foreach ($selectedModifications as $modification) { // Перебираем $selectedModifications напрямую
                    if (is_array($modification) && isset($modification['id_modification'])) {
                        $modificationAuto[] = $modification['id_modification'];
                    } else {
                        Log::warning('Пропущена модификация без id_modification:', ['modification' => $modification]);
                    }
                }
            }

            Log::info('$modificationAuto:', ['id_modification' => $modificationAuto]);

            // Получаем все id_queri из таблицы user_queries, где id_modification есть в $modificationAuto и part_id есть в $partIds
            $IdQueriAutoSearch = UserQuery::whereIn('id_car', $modificationAuto)
                ->whereIn('id_part', $partIds)
                ->pluck('id_queri')
                ->toArray();
            Log::info('$IdQueriAutoSearch:', ['id_queri' => $IdQueriAutoSearch]);

            $statusQueri = $advert->status_queri;
            $queriNumber = $advert->queri_number;

            Log::info('$statusQueri:', ['статус' => $statusQueri]);
            Log::info('$queriNumber:', ['номер объявления' => $queriNumber]);

            // Разделяем строку queriNumber на массив номеров
            $queriNumbers = explode(',', $queriNumber);
            $queriNumbers = array_map('trim', $queriNumbers); // Удаляем лишние пробелы

            // Флаг, показывающий, было ли найдено соответствие хотя бы для одного номера
            $foundMatch = false;

            // Проверяем статус и наличие хотя бы одного номера объявления из массива в массиве $IdQueriAutoSearch
            if ($statusQueri === 'done') {
                foreach ($queriNumbers as $singleQueriNumber) {
                    if (in_array($singleQueriNumber, $IdQueriAutoSearch)) {
                        Log::info('Найдено соответствие по status_queri = done и queri_number для объявления ID: ' . $advert->id . ', queri_number: ' . $singleQueriNumber);
                        if (!in_array($advert->id, $engineOrNumberMatchAdvertsIds)) {
                            $engineOrNumberMatchAdverts->push($advert);
                            $engineOrNumberMatchAdvertsIds[] = $advert->id;
                        }
                        $isEngineOrNumberMatch = true;
                        $foundMatch = true; // Устанавливаем флаг, что найдено соответствие
                        break; // Выходим из цикла, так как нашли первое соответствие
                    }
                }
            }

        }
    }
    


$allAdverts = collect();
$allAdverts = $this->getAllAdvertsFromIdQueriList($idQueriList, $mainWord, $synonyms, $brand, $model, $partIdFromPartsList);
// Удаление дубликатов по id
$allAdverts = $allAdverts->unique('id');
$allAdverts = $this->filterByProductName($allAdverts, $mainWord, $synonyms);

   Log::info('part_id:',['id запчасти' => $partIdFromPartsList]);
}


if (!empty($partNumbers)) {
        foreach ($partNumbers as $partNumber) {
            $results = Advert::query()
                ->where(function ($query) use ($partNumber) {
                    $query->where('queri_number', '=', $partNumber);
                })
                ->where(function ($query) use ($synonyms) { // Ищем соответствие любому из синонимов
                    foreach ($synonyms as $synonym) {
                        $query->orWhere('product_name', 'like', '%' . $synonym . '%');
                    }
                })
                ->get();

            $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->concat($results);
        }

        $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->unique('id');
    }

$exactMatchAdvertIds = $exactMatchAdverts->pluck('id')->toArray();
$engineOrNumberMatchAdvertIds = $engineOrNumberMatchAdverts->pluck('id')->toArray();

$allAdverts = $allAdverts->reject(function ($advert) use ($exactMatchAdvertIds, $engineOrNumberMatchAdvertIds) {
    return in_array($advert->id, $exactMatchAdvertIds) || in_array($advert->id, $engineOrNumberMatchAdvertIds);
})->values();

$allAdverts = $allAdverts->map(function ($advert) use ($selectedModifications) {
    $advert->is_compatible = false;
    if (!empty($selectedModifications)) {
        foreach ($selectedModifications as $modification) {
            $baseAvtoMatch = BaseAvto::find($modification['id_modification']);

            if ($baseAvtoMatch &&
                mb_strtolower($advert->brand) == mb_strtolower($baseAvtoMatch->brand) &&
                mb_strtolower($advert->model) == mb_strtolower($baseAvtoMatch->model) &&
                $advert->year >= $baseAvtoMatch->year_from &&
                $advert->year <= $baseAvtoMatch->year_before &&
                mb_strtolower($advert->engine) == mb_strtolower($baseAvtoMatch->engine)
            ) {
                $advert->is_compatible = true;
                break;
            }
        }
    }
    return $advert;
});

$allAdverts = $allAdverts->map(function ($advert) use ($mainWord, $searchQueryLower, $synonyms) {
    $advertNameLower = mb_strtolower($advert->product_name, 'UTF-8');
    $mainWordLower = mb_strtolower($mainWord, 'UTF-8');
    $exactMatchBonus = 50;
    $synonymMatchBonus = 900; // Бонус за соответствие синониму (должен быть меньше 1000, но больше, чем обычные бонусы)

    $isExactMatch = $advertNameLower === $mainWordLower;

    // Проверяем на соответствие синонимам
    $isSynonymMatch = false;
    foreach ($synonyms as $synonym) {
        $synonymLower = mb_strtolower($synonym, 'UTF-8');
        if ($advertNameLower === $synonymLower) {
            $isSynonymMatch = true;
            break; // Нашли соответствие, можно выходить из цикла
        }
    }

    if ($isExactMatch) {
        $advert->relevance = 1000;
    } elseif ($isSynonymMatch) {
        $advert->relevance = $synonymMatchBonus;
    } else {
        $advert->relevance = 0;

        if (strpos($advertNameLower, $searchQueryLower) !== false) {
            $advert->relevance = $exactMatchBonus;
        }

        $words = explode(' ', $searchQueryLower);
        foreach ($words as $word) {
            if (strpos($advertNameLower, $word) !== false) {
                $advert->relevance++;
            }
        }
    }

    return $advert;
});


$allAdverts = $allAdverts->sortByDesc('is_compatible')
        ->sortBy(function ($advert) {
            return $advert->is_compatible ? 0 : 1;
        })
        ->sortByDesc('relevance')
        ->sortBy('sort_order')
        ->values();
        
        $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->map(function ($advert) use ($mainWord, $searchQueryLower, $synonyms) {
    $advertNameLower = mb_strtolower($advert->product_name, 'UTF-8');
    $mainWordLower = mb_strtolower($mainWord, 'UTF-8');
    $exactMatchBonus = 50;
    $synonymMatchBonus = 900;

    $isExactMatch = $advertNameLower === $mainWordLower;

    $isSynonymMatch = false;
    foreach ($synonyms as $synonym) {
        $synonymLower = mb_strtolower($synonym, 'UTF-8');
        if ($advertNameLower === $synonymLower) {
            $isSynonymMatch = true;
            break;
        }
    }

    if ($isExactMatch) {
        $advert->relevance = 1000;
    } elseif ($isSynonymMatch) {
        $advert->relevance = $synonymMatchBonus;
    } else {
        $advert->relevance = 0;

        if (strpos($advertNameLower, $searchQueryLower) !== false) {
            $advert->relevance = $exactMatchBonus;
        }

        $words = explode(' ', $searchQueryLower);
        foreach ($words as $word) {
            if (strpos($advertNameLower, $word) !== false) {
                $advert->relevance++;
            }
        }
    }

    return $advert;
});


$engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->sortByDesc('is_compatible')
        ->sortBy(function ($advert) {
            return $advert->is_compatible ? 0 : 1;
        })
        ->sortByDesc('relevance')
        ->sortBy('sort_order')
        ->values();


    // Параметры пагинации
    $perPage = 10;
    $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();

    // 1. Определяем начальные размеры коллекций
    $exactCount = $exactMatchAdverts->count();
    $engineCount = $engineOrNumberMatchAdverts->count();

    // 2. Создаем пустую коллекцию для пагинированных элементов
    $paginatedItems = collect();

    // 3. Заполняем $paginatedItems данными, учитывая приоритет и пагинацию

    // a) Обрабатываем $exactMatchAdverts
    $exactOffset = ($currentPage - 1) * $perPage;
    if ($exactOffset < $exactCount) {
        $exactLimit = min($perPage, $exactCount - $exactOffset); // Сколько элементов exact нужно взять
        $paginatedItems = $paginatedItems->concat($exactMatchAdverts->slice($exactOffset, $exactLimit));
    } else {
        $exactLimit = 0; // Ничего не берем из exact
    }

    // b) Обрабатываем $engineOrNumberMatchAdverts
    $engineOffset = max(0, ($currentPage - 1) * $perPage - $exactCount); // Смещение для engine, учитывая сколько взяли из exact
    if ($paginatedItems->count() < $perPage && $engineOffset < $engineCount) {
        $engineLimit = min($perPage - $paginatedItems->count(), $engineCount - $engineOffset); // Сколько элементов engine нужно взять
        $paginatedItems = $paginatedItems->concat($engineOrNumberMatchAdverts->slice($engineOffset, $engineLimit));
    } else {
        $engineLimit = 0; // Ничего не берем из engine
    }


    // c) Обрабатываем $allAdverts
    $allOffset = max(0, ($currentPage - 1) * $perPage - $exactCount - $engineCount); // Смещение для all, учитывая сколько взяли из exact и engine
    if ($paginatedItems->count() < $perPage) {
        // Сколько элементов all нужно взять, чтобы добить до $perPage
        $allLimit = min($perPage - $paginatedItems->count(), $allAdverts->count() - $allOffset);
        $paginatedItems = $paginatedItems->concat($allAdverts->slice($allOffset, $allLimit));
    } else {  $allLimit = 0; // Ничего не берем из all
    }

    // 4. Считаем общее количество элементов для пагинатора
    $total = $exactCount + $engineCount + $allAdverts->count();

    // 5. Создаем LengthAwarePaginator
    $paginatedAdverts = new LengthAwarePaginator(
        $paginatedItems,
        $total,
        $perPage,
        $currentPage,
        ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );

    // 6. Разделяем результаты для отображения (необязательно, но может быть удобно)
    $exactMatchAdvertsPaginated = $paginatedItems->filter(function ($item) use ($exactMatchAdverts) {
        return $exactMatchAdverts->contains($item);
    })->values();

    $engineMatchAdvertsPaginated = $paginatedItems->filter(function ($item) use ($engineOrNumberMatchAdverts) {
        return $engineOrNumberMatchAdverts->contains($item);
    })->values();

    $similarAdvertsPaginated = $paginatedItems->filter(function ($item) use ($allAdverts) {
        return $allAdverts->contains($item);
    })->values();


   // Получаем engine из всех трех источников
    $engines = $allAdverts->pluck('engine')
        ->concat(collect($exactMatchAdverts)->pluck('engine'))
        ->concat(collect($engineOrNumberMatchAdverts)->pluck('engine'))
        ->filter()
        ->map(function ($engine) {
            return strtolower($engine);
        })
        ->unique()
        ->values()
        ->toArray();

    $addresses = $allAdverts->map(function ($advert) {
        $address = $advert->branch->address ?? 'Не указан';
        return $address;
    })->unique()->values()->toArray();

    $prod_name = $allAdverts->pluck('product_name')->toArray();
    $image_prod = $allAdverts->pluck('main_photo_url')->toArray();
    $advert_ids = $allAdverts->pluck('id')->toArray();

// В конце метода search перед return добавьте:
session([
    'exactMatchAdvertsPaginated' => $exactMatchAdvertsPaginated,
    'engineMatchAdvertsPaginated' => $engineMatchAdvertsPaginated,
    'similarAdvertsPaginated' => $similarAdvertsPaginated,
    'paginatedAdverts' => $paginatedAdverts,
    'engines' => $engines,
    'addresses' => $addresses,
    'prod_name' => $prod_name,
    'image_prod' => $image_prod,
    'advert_ids' => $advert_ids
]);
    return response()->view('adverts.search', compact(
        'allAdverts', //  Остается для фильтров, не для основного отображения
        'searchQuery',
        'brand',
        'model',
        'year',
        'engines',
        'addresses',
        'prod_name',
        'image_prod',
        'advert_ids',
        'exactMatchAdvertsPaginated',
        'engineMatchAdvertsPaginated',
        'similarAdvertsPaginated',
        'paginatedAdverts' // Используйте этот пагинатор во view
    ))->header('Cache-Control', 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
}


protected function getAllAdvertsFromIdQueriList(array $idQueriList, string $mainWord, array $synonyms, string $brand = null, string $model = null, $partIdFromPartsList = null): Collection
{
    $allAdverts = collect();
    
     // Поиск по part_id
    if ($partIdFromPartsList !== null) {
        $partsListRecord = Part::where('part_id', $partIdFromPartsList)->first();

        if ($partsListRecord) {
            $need = $partsListRecord->need;

            if ($need === 'year') {
                $yearAdverts = Advert::where('brand', $brand)
                    ->where('model', $model)
                    ->where(function ($query) {
                        $query->whereNull('year')
                            ->orWhere('year', '')
                            ->orWhere('year', ' ');
                    })
                    ->get();
   // Filter the adverts by product_name using the existing filterByProductName method
                $filteredYearAdverts = $this->filterByProductName($yearAdverts, $mainWord, $synonyms);
                Log::info('Количество объявлений после фильтрации по product_name (year, part_id):', ['count' => $filteredYearAdverts->count()]);
                $allAdverts = $allAdverts->concat($filteredYearAdverts);
            } elseif ($need === 'engine') {
                $engineAdverts = Advert::where('brand', $brand)
                    ->where('model', $model)
                    ->where(function ($query) {
                        $query->whereNull('engine')
                            ->orWhere('engine', '')
                            ->orWhere('engine', ' ');
                    })
                    ->get();

                 // Filter the adverts by product_name using the existing filterByProductName method
                $filteredEngineAdverts = $this->filterByProductName($engineAdverts,$mainWord, $synonyms);
                $allAdverts = $allAdverts->concat($filteredEngineAdverts);
            } else {
                Log::warning('Неизвестное значение "need" в PartsList:', ['need' => $need, 'part_id' => $partIdFromPartsList]);
            }
        } else {
            Log::warning('Не найдена запись в PartsList с part_id:', ['part_id' => $partIdFromPartsList]);
        }
    } else {
        Log::info('partIdFromPartsList is null, пропуск поиска по PartsList.');
    }
    
    // Добавляем поиск по brand и model в начале функции, если они переданы
    if ($brand && $model) {
        $brandModelAdverts = Advert::where('brand', $brand)
            ->where('model', $model)
            ->where(function ($query) {
                $query->whereNull('engine')
                    ->orWhere('engine', '')
                    ->orWhere('engine', ' '); // Добавлена проверка на пустую строку
            })
            ->where(function ($query) {
                $query->whereNull('number')
                    ->orWhere('number', '')
                    ->orWhere('number', ' '); // Добавлена проверка на пустую строку
            })
            ->where(function ($query) {
                $query->whereNull('year')
                    ->orWhere('year', '')
                    ->orWhere('year', ' '); // Добавлена проверка на пустую строку
            })
            ->get();

        Log::info('Найдены Adverts по brand и model без engine, number, year (или пустые значения) (внутри getAllAdvertsFromIdQueriList):', ['count' => $brandModelAdverts->count(), 'brand' => $brand, 'model' => $model]);
        $allAdverts = $allAdverts->concat($brandModelAdverts);
    } else {
        Log::info('Brand или Model не указаны, пропуск поиска Adverts без engine, number, year (или пустые значения) (внутри getAllAdvertsFromIdQueriList).');
    }

    Log::info('$idQueriList:', ['Номер' => $idQueriList]);

    // 1. Fetch UserQueries using eager loading
    $userQueries = UserQuery::whereIn('id_queri', $idQueriList)->get();

    if ($userQueries->isEmpty()) {
        Log::warning('Не найдены UserQuery с id_queri из списка.', ['idQueriList' => $idQueriList]);
        return $allAdverts; // Return empty collection if no UserQueries found
    }

    // 2. Group UserQueries by id_car for efficiency
    $userQueriesByCar = $userQueries->groupBy('id_car');

    // 3. Fetch BaseAvto data for all unique id_car values using eager loading
    $idCars = $userQueriesByCar->keys()->toArray();
    $baseAvtos = BaseAvto::whereIn('id_modification', $idCars)->get();
    $baseAvtosById = $baseAvtos->keyBy('id_modification');


    foreach ($userQueriesByCar as $idCar => $queriesForCar) {
        Log::info('Обрабатываем id_car:', ['id_car' => $idCar]);

        // 4. Retrieve BaseAvto data from the fetched data
        $baseAvto = $baseAvtosById->get($idCar);

        if (!$baseAvto) {
            Log::warning('Не найден BaseAvto с id_modification:', ['id_modification' => $idCar]);
            continue; // Skip to the next id_car if BaseAvto not found
        }

        $brand = $baseAvto->brand;
        $model = $baseAvto->model;
        Log::info('Найдены brand и model:', ['brand' => $brand, 'model' => $model]);

        // 5. Build the query for Adverts
        $advertQuery = Advert::where('brand', $brand)
            ->where('model', $model)
            ->where(function ($query) {
                $query->whereNull('year')
                    ->orWhere('year', '');
            })
            ->where(function ($query) {
                $query->whereNull('engine')
                    ->orWhere('engine', '');
            })
            ->where(function ($query) {
                $query->whereNull('number')
                    ->orWhere('number', '');
            });

        // 6. Execute the query and retrieve the adverts
        $adverts = $advertQuery->get();

        if ($adverts->isEmpty()) {
            Log::info('Не найдены Adverts для brand и model без year, engine, number.', ['brand' => $brand, 'model' => $model]);
            continue; // Skip to the next id_car if no adverts found
        }

        Log::info('Найдены Adverts:', ['count' => $adverts->count(), 'brand' => $brand, 'model' => $model]);

        // 7. Filter adverts by product_name
        foreach ($queriesForCar as $userQuery) {
          $productName = $userQuery->product_name;
          Log::info('Фильтрация по product_name:', ['product_name' => $productName]);
          $filteredAdverts = $this->filterByProductName($adverts, $mainWord, $synonyms);
          Log::info('Количество объявлений после фильтрации по product_name:', ['count' => $filteredAdverts->count()]);
          $allAdverts = $allAdverts->concat($filteredAdverts);
        }
    }

   Log::info('Общее количество объявлений перед удалением дубликатов:', ['count' => $allAdverts->count()]);

// Удаление дубликатов по id
$allAdverts = $allAdverts->unique('id');

Log::info('Общее количество объявлений после удаления дубликатов:', ['count' => $allAdverts->count()]);
    return $allAdverts;
}


private function getSynonyms(string $mainWord): array
    {
        $synonyms = [];

        // 1. Ищем part_id для поискового запроса в таблице parts_list
        $part = Part::where('part_name', $mainWord)->first();

        if ($part) {
            $partId = $part->part_id;

            // 2. Если нашли part_id, ищем все part_name с этим же part_id (синонимы)
            $synonyms = Part::where('part_id', $partId)
                ->pluck('part_name')
                ->toArray();

            // 3.  Удаляем из синонимов сам поисковый запрос (чтобы не было дубликатов)
            $synonyms = array_diff($synonyms, [$mainWord]);

            // 4. Сбрасываем ключи массива (делаем его последовательным)
            $synonyms = array_values($synonyms);
        }

        return $synonyms;
    }


private function getBaseAdvertQuery()
    {
        return Advert::with('branch')->active(); // Вместо ['user.userAddress'] используем 'branch'
    }

private function applyBrandModelYearFilter($query, $brand, $model, $year)
{
    if ($brand) {
        $query->where('brand', $brand);
    }

    if ($model) {
        $query->where('model', $model);
    }

    if ($year) {
        $query->where('year', $year); // Больше не нужно приводить к int
    }

    return $query;
}

  protected function filterByProductName($adverts, $mainWord, $synonyms = [])
{
    if (empty($mainWord) && empty($synonyms)) {
        return $adverts;
    }

    $filteredAdverts = $adverts->filter(function ($advert) use ($mainWord, $synonyms) {
        $productNameLower = mb_strtolower($advert->product_name, 'UTF-8');
        $productNameFound = false;

        // Проверяем соответствие ТОЛЬКО главному слову
        if (!empty($mainWord) && strpos($productNameLower, mb_strtolower($mainWord, 'UTF-8')) !== false) {
            $productNameFound = true;
        }

        // Проверяем соответствие синонимам главного слова
        if (!$productNameFound && !empty($synonyms)) {
            foreach ($synonyms as $synonym) {
                if (strpos($productNameLower, mb_strtolower($synonym, 'UTF-8')) !== false) {
                    $productNameFound = true;
                    break;
                }
            }
        }

        return $productNameFound;
    });

    return $filteredAdverts;
}



    
public function filterByEngine(Request $request)
{
    // Получаем все исходные коллекции из сессии
    $exactMatchAdverts = session('exactMatchAdverts', collect());
    $engineOrNumberMatchAdverts = session('engineOrNumberMatchAdverts', collect());
    $allAdverts = session('allAdverts', collect());
    $engines = session('engines', []);

    // Получаем выбранные двигатели
    $selectedEngines = $request->input('engines', []);
    $selectedEngines = array_map('strtolower', $selectedEngines);

    // Фильтруем все коллекции полностью (не только текущую страницу)
    if (!empty($selectedEngines)) {
        $exactMatchAdverts = $exactMatchAdverts->filter(function ($advert) use ($selectedEngines) {
            $engine = strtolower($advert->engine ?? '');
            return in_array($engine, $selectedEngines);
        });

        $engineOrNumberMatchAdverts = $engineOrNumberMatchAdverts->filter(function ($advert) use ($selectedEngines) {
            $engine = strtolower($advert->engine ?? '');
            return in_array($engine, $selectedEngines);
        });

        $allAdverts = $allAdverts->filter(function ($advert) use ($selectedEngines) {
            $engine = strtolower($advert->engine ?? '');
            return in_array($engine, $selectedEngines);
        });
    }

    // Применяем оригинальную сортировку
    $allAdverts = $allAdverts->sortByDesc('is_compatible')
        ->sortBy(function ($advert) {
            return $advert->is_compatible ? 0 : 1;
        })
        ->sortByDesc('relevance')
        ->sortBy('sort_order')
        ->values();

    // Параметры пагинации
    $perPage = 10;
    $currentPage = \Illuminate\Pagination\Paginator::resolveCurrentPage();

    // Определяем размеры отфильтрованных коллекций
    $exactCount = $exactMatchAdverts->count();
    $engineCount = $engineOrNumberMatchAdverts->count();

    // Создаем пагинированную коллекцию (как в оригинальном search)
    $paginatedItems = collect();

    // Заполняем пагинированную коллекцию с учетом приоритетов
    $exactOffset = ($currentPage - 1) * $perPage;
    if ($exactOffset < $exactCount) {
        $exactLimit = min($perPage, $exactCount - $exactOffset);
        $paginatedItems = $paginatedItems->concat($exactMatchAdverts->slice($exactOffset, $exactLimit));
    }

    $engineOffset = max(0, ($currentPage - 1) * $perPage - $exactCount);
    if ($paginatedItems->count() < $perPage && $engineOffset < $engineCount) {
        $engineLimit = min($perPage - $paginatedItems->count(), $engineCount - $engineOffset);
        $paginatedItems = $paginatedItems->concat($engineOrNumberMatchAdverts->slice($engineOffset, $engineLimit));
    }

    $allOffset = max(0, ($currentPage - 1) * $perPage - $exactCount - $engineCount);
    if ($paginatedItems->count() < $perPage) {
        $allLimit = min($perPage - $paginatedItems->count(), $allAdverts->count() - $allOffset);
        $paginatedItems = $paginatedItems->concat($allAdverts->slice($allOffset, $allLimit));
    }

    // Создаем пагинатор
    $total = $exactCount + $engineCount + $allAdverts->count();
    $paginatedAdverts = new \Illuminate\Pagination\LengthAwarePaginator(
        $paginatedItems,
        $total,
        $perPage,
        $currentPage,
        ['path' => \Illuminate\Pagination\Paginator::resolveCurrentPath()]
    );

    // Разделяем результаты для отображения
    $exactMatchAdvertsPaginated = $paginatedItems->filter(function ($item) use ($exactMatchAdverts) {
        return $exactMatchAdverts->contains($item);
    })->values();

    $engineMatchAdvertsPaginated = $paginatedItems->filter(function ($item) use ($engineOrNumberMatchAdverts) {
        return $engineOrNumberMatchAdverts->contains($item);
    })->values();

    $similarAdvertsPaginated = $paginatedItems->filter(function ($item) use ($allAdverts) {
        return $allAdverts->contains($item);
    })->values();

    // Получаем обновленные двигатели для фильтров
    $engines = $allAdverts->pluck('engine')
        ->concat($exactMatchAdverts->pluck('engine'))
        ->concat($engineOrNumberMatchAdverts->pluck('engine'))
        ->filter()
        ->map(function ($engine) {
            return strtolower($engine);
        })
        ->unique()
        ->values()
        ->toArray();

    $addresses = $allAdverts->map(function ($advert) {
        return $advert->branch->address ?? 'Не указан';
    })->unique()->values()->toArray();

    $prod_name = $allAdverts->pluck('product_name')->toArray();
    $image_prod = $allAdverts->pluck('main_photo_url')->toArray();
    $advert_ids = $allAdverts->pluck('id')->toArray();

    return response()->view('adverts.search', compact(
        'allAdverts',
        'engines',
        'addresses',
        'prod_name',
        'image_prod',
        'advert_ids',
        'exactMatchAdvertsPaginated',
        'engineMatchAdvertsPaginated',
        'similarAdvertsPaginated',
        'paginatedAdverts'
    ));
}
}