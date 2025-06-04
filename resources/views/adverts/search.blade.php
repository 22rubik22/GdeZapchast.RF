 <!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript" >
       (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
       m[i].l=1*new Date();
       for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
       k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
       (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

       ym(99461941, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataLayer"
       });
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/99461941" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все товары</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        /* Добавляем стили для отображения карты на весь экран */
       /* Стили для кнопок переключения режимов */
/* Карта в обычном режиме */
#map {
    width: 100%;
    height: 16rem; /* h-64 */
}
@media (min-width: 768px) {
    #map {
        height: 24rem; /* md:h-96 */
    }
}

/* Карта в полноэкранном режиме */
#map.full-screen {
    position: fixed;
    top: 8rem; /* Высота шапки + кнопки */
    left: 0;
    width: 100%;
    height: calc(100% - 8rem);
    z-index: 1000;
    display: block !important; /* Переопределяем hidden */
}

/* Кнопки в полноэкранном режиме карты */
#mapsButton.fixed {
    position: fixed;
    top: 4rem; /* Высота шапки */
    left: 0;
    width: 100%;
    padding: 1rem;
    background-color: white;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    z-index: 1001;
}

        .aspect-square {
            aspect-ratio: 1 / 1;
        }

        body {
            font-family: 'Nunito', sans-serif;
        }

        .blockadvert{
            border: 0.2px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        /* Стили для кнопок */
        .buttons-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            display: flex;
            justify-content: center;
            background-color: white;
            padding: 16px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1001;
        }

        /* Стили для отображения меню на весь экран */
        #fullScreenMenu, #filterMenu {
            position: fixed;
            top: 64px; /* Высота шапки */
            left: 0;
            width: 100%;
            height: calc(100% - 64px); /* Высота меню без учета шапки */
            background-color: white;
            z-index: 1000;
            overflow-y: auto;
            display: none; /* Скрываем меню по умолчанию */
        }

        #fullScreenMenu.active, #filterMenu.active {
            display: block; /* Показываем меню, когда оно активно */
        }
    </style>
</head>
<body class="flex flex-col items-center ">
<!-- Шапка -->
@include('components.header-seller')

<!-- Карта -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

<div id="map" class="w-full h-64 md:h-96 hidden sm:block"></div>

<!-- Поисковая форма -->
<div class="w-full mt-20 md:w-3/4 mt-10 mx-auto hidden sm:block">
    @include('components.search-form')
</div>

<div class=" blockadvert filters bg-white mt-4 w-full hidden md:w-3/4 p-4 rounded-lg shadow-md sm:block md:block 2xl:hidden">
    <form method="GET" action="{{ route('adverts.filterByEngine') }}">
        <h4 class="text-xl font-semibold mb-4">Фильтры по двигателю:</h4>
        @foreach($engines as $engine)
            <div>
                <input type="checkbox" name="engines[]" value="{{ $engine }}" id="engine-{{ $engine }}"
                       {{ in_array($engine, request('engines', [])) || !request()->has('engines') ? 'checked' : '' }}
                       class="mr-2">
                <label for="engine-{{ $engine }}" class="text-lg">{{ !empty($engine) ? ucfirst($engine) : 'Не указан' }}</label>
            </div>
        @endforeach

        <!-- Сохраняем другие параметры запроса -->
        <input type="hidden" name="search_query" value="{{ request('search_query') }}">
        <input type="hidden" name="brand" value="{{ request('brand') }}">
        <input type="hidden" name="model" value="{{ request('model') }}">
        <input type="hidden" name="year" value="{{ request('year') }}">

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg mt-4">Применить фильтры</button>
    </form>
</div>

<!-- Основной контент -->
<div class="flex flex-col w-full sm:flex-row sm:justify-start sm:w-full md:w-3/4">
    <!-- Результаты поиска -->
    <div class="w-full flex justify-center items-center space-x-4 mt-14 mb-4 sm:hidden px-4 hidden-on-map">
        <button id="sortButton" class="flex items-center justify-center px-4 py-2 bg-gray-700 text-white rounded-md w-1/2">
            <i class="fas fa-sort mr-2"></i>
            Сортировка
        </button>
        <button id="filterButton" class="flex items-center justify-center px-4 py-2 bg-gray-700 text-white rounded-md w-1/2">
            <i class="fas fa-filter mr-2"></i>
            Фильтры
        </button>
    </div>

    <div id="mapsButton" class="w-full flex justify-center items-center space-x-4 mb-4 sm:hidden px-4">
        <button id="listButton" class="flex items-center justify-center px-4 py-2 bg-blue-600 text-white rounded-lg w-1/2">
            <i class="fas fa-th-large mr-2"></i>
            Списком
        </button>
        <button id="mapButton" class="text-xs flex items-center justify-center pl-2 py-3 bg-white text-gray-600 border rounded-lg w-1/2">
            <i class="fas fa-map mr-2"></i>
            Показать на карте
        </button>
    </div>

    <div id="listView" class="w-full px-2">
    <!-- Вывод exactMatchAdvertsPaginated -->
   @if(isset($exactMatchAdvertsPaginated) && $exactMatchAdvertsPaginated->isNotEmpty())
        <h2 class="text-2xl font-semibold mt-8">То, что вы искали:</h2>
        <!-- Для телефонов -->
        <div id="exactMatchPhoneListView" class="grid grid-cols-2 gap-4 w-full sm:hidden">
            @foreach($exactMatchAdvertsPaginated as $advert)
                @include('components.advert-card-phone', ['advert' => $advert])
            @endforeach
        </div>
        <!-- Для больших экранов -->
        <div id="exactMatchDesktopListView" class="hidden sm:flex w-full flex-col items-start justify-center">
            @foreach($exactMatchAdvertsPaginated as $advert)
                @include('components.advert-card-desktop', ['advert' => $advert])
            @endforeach
        </div>
    @endif

    <!-- Вывод engineMatchAdvertsPaginated -->
   @if(isset($engineMatchAdvertsPaginated) && $engineMatchAdvertsPaginated->isNotEmpty())
        <h2 class="text-2xl font-semibold mt-8">Подойдет к вашему автомобилю:</h2>
        <!-- Для телефонов -->
        <div id="engineOrNumberMatchPhoneListView" class="grid grid-cols-2 gap-4 w-full sm:hidden">
            @foreach($engineMatchAdvertsPaginated as $advert)
                @include('components.advert-card-phone', ['advert' => $advert])
            @endforeach
        </div>
        <!-- Для больших экранов -->
        <div id="engineOrNumberMatchDesktopListView" class="hidden sm:flex w-full flex-col items-start justify-center">
            @foreach($engineMatchAdvertsPaginated as $advert)
                @include('components.advert-card-desktop', ['advert' => $advert])
            @endforeach
        </div>
    @endif

    <!-- Вывод similarAdvertsPaginated -->
   @if(isset($similarAdvertsPaginated) && $similarAdvertsPaginated->isNotEmpty())
        <h2 class="text-2xl font-semibold mt-8">Похоже на то, что вы ищете:</h2>
        <!-- Для телефонов -->
        <div id="allAdvertsPhoneListView" class="grid grid-cols-2 gap-4 w-full sm:hidden">
            @foreach($similarAdvertsPaginated as $advert)
                @include('components.advert-card-phone', ['advert' => $advert])
            @endforeach
        </div>
        <!-- Для больших экранов -->
        <div id="allAdvertsDesktopListView" class="hidden sm:flex w-full flex-col items-start justify-center">
            @foreach($similarAdvertsPaginated as $advert)
                @include('components.advert-card-desktop', ['advert' => $advert])
            @endforeach
        </div>
    @endif

    <!-- Общая пагинация -->
    @if(isset($paginatedAdverts) && $paginatedAdverts->hasPages())
    <div class="mb-20">
        @include('components.pagination', ['paginator' => $paginatedAdverts])
    </div>
@endif

@if(isset($paginatedAdverts) && $paginatedAdverts->isEmpty())
    <p class="text-center text-lg mt-8">Нет результатов для отображения.</p>
@endif
</div>

<!-- Фильтры по параметру engine для больших экранов -->
<div class="filters bg-white mt-4 ml-12 mt-24 hidden 2xl:block w-[35%]">
    <form method="GET" class="blockadvert p-2 rounded-lg shadow-md flex flex-col" action="{{ route('adverts.filterByEngine') }}" id="engineFilterForm">
        <h4 class="text-xl text-center font-semibold mb-4">Фильтры по двигателю:</h4>
        
        <!-- Кнопки "Отметить все" и "Убрать все" -->
        <div class="flex space-x-2 mb-3">
            <button type="button" onclick="checkAllEngines(true); return false;" class="text-blue-500 hover:text-blue-700 bg-blue-50 px-6 py-2 rounded-lg border-2 border-blue-500">
                Отметить все
            </button>
            <button type="button" onclick="checkAllEngines(false); return false;" class="text-blue-500 hover:text-blue-700 bg-blue-50 px-6 py-2 rounded-lg border-2 border-blue-500">
                Убрать все
            </button>
        </div>
        
        <!-- Контейнер для списка двигателей с прокруткой -->
        <div class="overflow-y-auto max-h-64 mb-4 pr-2">
            @foreach($engines as $engine)
                <div class="py-1">
                    <input type="checkbox" name="engines[]" value="{{ $engine }}" id="engine-{{ $engine }}"
                           {{ in_array($engine, request('engines', [])) || !request()->has('engines') ? 'checked' : '' }}
                           class="mr-2 engine-checkbox">
                    <label for="engine-{{ $engine }}" class="text-lg">{{ !empty($engine) ? ucfirst($engine) : 'Не указан' }}</label>
                </div>
            @endforeach
        </div>

        <!-- Сохраняем другие параметры запроса -->
        <input type="hidden" name="search_query" value="{{ request('search_query') }}">
        <input type="hidden" name="brand" value="{{ request('brand') }}">
        <input type="hidden" name="model" value="{{ request('model') }}">
        <input type="hidden" name="year" value="{{ request('year') }}">

        <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg mt-auto">Применить фильтры</button>
    </form>
</div>
</div>



<div id="fullScreenMenu" class="hidden w-full">
    <div class="menu-content w-full">
        <button id="closeMenuButton" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 close-button w-10 h-10 flex items-center justify-center">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="bg-white p-4 rounded-lg shadow-lg w-full">
            <h2 class="text-lg font-semibold mb-4">Фильтры</h2>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Состояние детали</h3>
                <div class="flex items-center mb-2">
                    <input type="radio" id="new" name="condition" class="mr-2">
                    <label for="new">Новая</label>
                </div>
                <div class="flex items-center mb-2">
                    <input type="radio" id="used" name="condition" class="mr-2">
                    <label for="used">Б/У деталь</label>
                </div>
                <div class="flex items-center mb-2">
                    <input type="radio" id="unspecified" name="condition" class="mr-2">
                    <label for="unspecified">Не указано</label>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Цена</h3>
                <div class="flex space-x-2">
                    <input type="text" placeholder="Цена от" class="border rounded p-2 w-full">
                    <input type="text" placeholder="до" class="border rounded p-2 w-full">
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Фото</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="photo" class="mr-2">
                    <label for="photo">Только с фото</label>
                </div>
            </div>Похоже на то, что вы ищете:

            <div class="mb-4">
                <h3 class="font-medium mb-2">Доставка</h3>
                <div class="flex items-center mb-2">
                    <input type="radio" id="pickup" name="delivery" class="mr-2">
                    <label for="pickup">С самовывозом</label>
                </div>
                <div class="flex items-center mb-2">
                    <input type="radio" id="delivery" name="delivery" class="mr-2">
                    <label for="delivery">С доставкой</label>
                </div>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Модель кузова</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="bodyModel1" class="mr-2">
                    <label for="bodyModel1">Тут список доступных кузовов</label>
                </div>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="bodyModel2" class="mr-2" checked>
                    <label for="bodyModel2">gx90</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Модель двигателя</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="engineModel1" class="mr-2" checked>
                    <label for="engineModel1">Тут список доступных двигателей</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">OEM номер</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="oemNumber1" class="mr-2">
                    <label for="oemNumber1">Тут список доступных номеров детали</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Перед/Зад</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="frontBack1" class="mr-2" checked>
                    <label for="frontBack1">Тут список доступных расположений</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Слева/Справа</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="leftRight1" class="mr-2" checked>
                    <label for="leftRight1">Тут список доступных расположений</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="mb-4">
                <h3 class="font-medium mb-2">Верх/Низ</h3>
                <div class="flex items-center mb-2">
                    <input type="checkbox" id="topBottom1" class="mr-2" checked>
                    <label for="topBottom1">Тут список доступных расположений</label>
                </div>
                <a href="#" class="text-blue-500">Показать все</a>
            </div>

            <div class="flex space-x-2">
                <button class="bg-blue-500 text-white py-2 px-4 rounded">Сохранить</button>
                <button class="border border-blue-500 text-blue-500 py-2 px-4 rounded">Сбросить</button>
            </div>
        </div>
    </div>
</div>

<!-- Меню фильтров на весь экран -->
<div id="filterMenu" class="hidden">
    <div class="menu-content">
        <button id="closeFilterMenuButton" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 close-button w-10 h-10 flex items-center justify-center">
            <i class="fas fa-times text-xl"></i>
        </button>
        <div class="bg-white p-4 rounded-lg shadow-lg w-full">
            <div class="text-center space-y-8">
                <p class="text-black text-lg">Сначала недавно добавленные</p>
                <p class="text-black text-lg">Сначала давно добавленные</p>
                <p class="text-black text-lg">Сначала дешёвые</p>
                <p class="text-black text-lg">Сначала дорогие</p>
            </div>
        </div>
    </div>
</div>
<footer class="bg-white text-white py-20 shadow-none text-center mt-20 w-full hidden md:block">
    <div class="logo2 flex justify-between items-center w-full max-w-screen-2xl mx-auto">
        <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.index', null, request()->get('city')) }}" class="text-black text-xl p-2">
            <span class="text-2xl">ГдеЗапчасть.рф</span>
        </a>
        <div class="w-full text-black">
            <p>&copy; {{ date('Y') }} Все права защищены.</p>
        </div>
    </div>
    <div class="flex justify-center space-x-4 mt-4 w-full max-w-screen-2xl mx-auto">
        <a href="{{ route('about') }}" class="text-black hover:text-blue-500">О проекте</a>
        <a href="{{ route('oferta') }}" class="text-black hover:text-blue-500">Оферта</a>
        <a href="{{ route('franchise.index') }}" class="text-black hover:text-blue-500">Франшиза</a>
        <a href="{{ route('help.index') }}" class="text-black hover:text-blue-500">Справка</a>
    </div>
</footer>

    <!-- Подключение Yandex Maps -->
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=9fbfa4df-7869-44a3-ae8e-0ebc49545ea9" type="text/javascript"></script>
    
    <script>
    function checkAllEngines(checked) {
        const checkboxes = document.querySelectorAll('.engine-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = checked;
        });
        
        // Не отправляем форму автоматически
        return false;
    }
    </script>
    
    <script>
    function generateProductNameSlug(productName) {
        // Функция для транслитерации кириллицы в латиницу (упрощенный вариант)
        function transliterate(text) {
            const transliterationMap = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
                'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
                'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
                'ч': 'ch', 'ш': 'sh', 'щ': 'sch', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
                'я': 'ya', ' ': '-',
            };

            let slug = '';
            for (let i = 0; i < text.length; i++) {
                const char = text[i].toLowerCase();
                slug += transliterationMap[char] || char; // Заменяем кириллицу на латиницу, иначе оставляем как есть
            }

            return slug;
        }

        // Транслитерируем, приводим к нижнему регистру и заменяем пробелы на дефисы
        const transliteratedText = transliterate(productName);
        const slug = transliteratedText.toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-+|-+$/g, ''); // Удаляем лишние дефисы в начале и конце

        return slug;
    }
    
function generateAdvertUrl(advert) {
    const baseUrl = '{{ route("adverts.show", ["id" => ":id", "product_name_slug" => ":product_name_slug"]) }}';

    let url = baseUrl
        .replace(':id', advert.id)
        .replace(':product_name_slug', generateProductNameSlug(advert.product_name));

    let params = [];

    if (advert.brand) {
        params.push('brand=' + advert.brand);
    }
    if (advert.model) {
        params.push('model=' + advert.model);
    }
    if (advert.year) {
        params.push('year=' + advert.year);
    }
    if (advert.engine) {
        params.push('engine=' + advert.engine);
    }
    if (advert.number) {
        params.push('number=' + advert.number);
    }

    if (params.length > 0) {
        url += '?' + params.join('&');
    }

    return url;
}
</script>

    <script>
        let mapInitialized = false;
        let myMap;

        ymaps.ready(function() {
            myMap = new ymaps.Map('map', {
                center: [52.753994, 104.622093],
                zoom: 9, 
                controls: ['zoomControl']
            });

            // Отключаем взаимодействие с картой
            myMap.behaviors.disable('drag');
            myMap.behaviors.disable('scrollZoom');

            // Массив адресов для геокодирования
            var addresses = @json($addresses);
            var prod_name = @json($prod_name);
            var image_prod = @json($image_prod);
            var advert_ids = @json($advert_ids);

            // URL изображения по умолчанию
            var defaultImageUrl = "{{ asset('images/dontfoto.jpg') }}";

            // Функция для геокодирования и добавления меток на карту
            function geocodeAndAddToMap(address, prod_name, image_url, advert_id) {
                if (address == "Не указан") {
                    return; // Пропускаем добавление метки, если адрес отсутствует
                }

                ymaps.geocode(address, {
                    results: 1
                }).then(function (res) {
                    var firstGeoObject = res.geoObjects.get(0),
                        coords = firstGeoObject.geometry.getCoordinates(),
                        bounds = firstGeoObject.properties.get('boundedBy');

                    // Проверяем, существует ли URL изображения
                    var imageUrl = image_url ? image_url : defaultImageUrl;

                    // Создаем метку с пользовательским контентом
                    var placemark = new ymaps.Placemark(coords, {
                        balloonContent: address + '<br><a href="{{ route('adverts.show', '') }}/' + advert_id + '">' + prod_name + '</a><br><img src="' + imageUrl + '" alt="Фото отсутствует" width="100">', // Пользовательский контент в баллуне с изображением и ссылкой
                        hintContent: prod_name // Пользовательский контент в подсказке
                    }, {
                        preset: 'islands#darkBlueDotIconWithCaption'
                    });

                    myMap.geoObjects.add(placemark);

                    // Центрируем карту на последней добавленной метке
                    myMap.setCenter(coords, 10, {
                        checkZoomRange: true
                    });
                });
            }

            // Выполняем геокодирование и добавление меток для каждого адреса
            addresses.forEach(function (address, index) {
                geocodeAndAddToMap(address, prod_name[index], image_prod[index], advert_ids[index]);
            });

            // Обработчик клика на карту
            document.getElementById('map').addEventListener('click', function() {
                if (!mapInitialized) {
                    mapInitialized = true;
                    // Включаем взаимодействие с картой
                    myMap.behaviors.enable('drag');
                    myMap.behaviors.enable('scrollZoom');
                }
            });

            // Обработчик ухода курсора с карты
            document.getElementById('map').addEventListener('mouseleave', function() {
                if (mapInitialized) {
                    // Отключаем взаимодействие с картой
                    myMap.behaviors.disable('drag');
                    myMap.behaviors.disable('scrollZoom');
                    mapInitialized = false;
                }
            });
        });

document.addEventListener('DOMContentLoaded', function() {
    // Получаем элементы
    const listButton = document.getElementById('listButton');
    const mapButton = document.getElementById('mapButton');
    const sortButton = document.getElementById('sortButton');
    const filterButton = document.getElementById('filterButton');
    const closeMenuButton = document.getElementById('closeMenuButton');
    const closeFilterMenuButton = document.getElementById('closeFilterMenuButton');
    const hiddenOnMapBlock = document.querySelector('.hidden-on-map');
    const listView = document.getElementById('listView');
    const mapElement = document.getElementById('map');
    const fullScreenMenu = document.getElementById('fullScreenMenu');
    const filterMenu = document.getElementById('filterMenu');
    const mapsButton = document.getElementById('mapsButton');

    // Функция для показа списка (скрывает карту и меню)
    function showListView() {
        listView.classList.remove('hidden');
        mapElement.classList.remove('full-screen');
        mapElement.classList.add('hidden', 'sm:block'); // Возвращаем оригинальные классы
        fullScreenMenu.classList.remove('active');
        filterMenu.classList.remove('active');
        listButton.classList.add('bg-blue-600', 'text-white');
        listButton.classList.remove('bg-white', 'text-gray-600', 'border');
        mapButton.classList.remove('bg-blue-600', 'text-white');
        mapButton.classList.add('bg-white', 'text-gray-600', 'border');
        hiddenOnMapBlock.classList.remove('hidden');
        mapsButton.classList.remove('fixed', 'top-0', 'z-50', 'bg-white', 'shadow-md');
        mapsButton.classList.add('mb-4');
    }

    // Функция для показа карты (скрывает список и меню)
    function showMapView() {
        listView.classList.add('hidden');
        mapElement.classList.add('full-screen');
        mapElement.classList.remove('hidden'); // Убираем hidden для отображения на мобильных
        fullScreenMenu.classList.remove('active');
        filterMenu.classList.remove('active');
        mapButton.classList.add('bg-blue-600', 'text-white');
        mapButton.classList.remove('bg-white', 'text-gray-600', 'border');
        listButton.classList.remove('bg-blue-600', 'text-white');
        listButton.classList.add('bg-white', 'text-gray-600', 'border');
        hiddenOnMapBlock.classList.add('hidden');
        // Фиксируем кнопки сверху при открытой карте
        mapsButton.classList.add('fixed', 'top-0', 'z-50', 'bg-white', 'shadow-md');
        mapsButton.classList.remove('mb-4');
    }

    // Остальной код обработчиков событий остается без изменений
    listButton.addEventListener('click', showListView);
    mapButton.addEventListener('click', showMapView);

    sortButton.addEventListener('click', function() {
        fullScreenMenu.classList.toggle('active');
        filterMenu.classList.remove('active');
        if (fullScreenMenu.classList.contains('active')) {
            listView.classList.add('hidden');
        } else {
            listView.classList.remove('hidden');
        }
    });

    filterButton.addEventListener('click', function() {
        filterMenu.classList.toggle('active');
        fullScreenMenu.classList.remove('active');
        if (filterMenu.classList.contains('active')) {
            listView.classList.add('hidden');
        } else {
            listView.classList.remove('hidden');
        }
    });

    closeMenuButton.addEventListener('click', function() {
        fullScreenMenu.classList.remove('active');
        listView.classList.remove('hidden');
    });

    closeFilterMenuButton.addEventListener('click', function() {
        filterMenu.classList.remove('active');
        listView.classList.remove('hidden');
    });

    // Инициализируем начальное состояние - показываем список
    showListView();
});
    </script>
</body>
</html>
