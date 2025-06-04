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
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $advert->product_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=9fbfa4df-7869-44a3-ae8e-0ebc49545ea9" type="text/javascript"></script>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">

    <style>
    
#brand-filters, #model-filters {
    display: flex;
    flex-wrap: wrap; /* Перенос элементов на следующую строку */
    gap: 8px; /* Отступ между элементами */
    overflow-x: visible; /* Убираем горизонтальную прокрутку */
    white-space: normal; /* Разрешаем перенос текста */
}

#brand-filters::-webkit-scrollbar, #model-filters::-webkit-scrollbar {
    display: none; /* Скрываем полосу прокрутки */
}


body {
    font-family: 'Nunito', sans-serif;
}





   /* Выделение строки таблицы при наведении */
        tbody tr:hover {
            background-color: #f0f8ff; /* Светло-серый цвет для выделения */
            transition: background-color 0.3s ease; /* Плавное изменение цвета */
        }

        .favorite-btn i.fas {
    color: red; /* Красный цвет для избранного */
}

        @media (max-width: 767px) {
            .fixed-buttons {
                position: fixed;
                left: 0;
                width: 100%;
                background-color: white;
                box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
                padding: 1rem;
                display: flex;
                justify-content: space-between;
            }
        }



        /* Стили для пагинации */
#pagination-container {
    display: flex; /* Используем flexbox для выравнивания */
    justify-content: center; /* Центрируем кнопки */
    margin: 20px 0; /* Отступы сверху и снизу */
}

#pagination-container button {
    padding: 5px 10px; /* Уменьшаем внутренние отступы */
    margin: 0 5px; /* Отступы между кнопками */
    border: 1px solid #007bff; /* Граница кнопок */
    border-radius: 10px; /* Скругляем углы */
    text-decoration: none; /* Убираем подчеркивание */
 
    font-size: 14px; /* Уменьшаем размер шрифта */
    cursor: pointer; /* Курсор в виде указателя */
}

#pagination-container button:hover {
    background-color: #007bff; /* Цвет фона при наведении */
    color: white; /* Цвет текста при наведении */
}

/* Стили для текущей страницы */
#pagination-container button.active {
    background-color: #007bff; /* Цвет фона для активной страницы */
    color: white; /* Цвет текста для активной страницы */
    border: 1px solid #007bff; /* Граница активной страницы (можно убрать, если не нужна) */
}

/* Стили для модального окна */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0, 0, 0, 0.5); /* Полупрозрачный чёрный фон */
    backdrop-filter: blur(20px); /* Размытие фона */
}

/* Стили для увеличенного изображения */
.modal-content {
    display: block;
    max-width: 80%;
    max-height: 80%;
    margin: auto;
    margin-top: 5%;
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
}
/* Кнопка закрытия */
.close {
    position: absolute;
    top: 20px;
    right: 35px;
    color: #f1f1f1;
    font-size: 40px;
    font-weight: bold;
    transition: 0.3s;
    cursor: pointer;
}

.close:hover,
.close:focus {
    color: #bbb;
    text-decoration: none;
    cursor: pointer;
}

.photo-container {
    position: relative;
    width: 100%;
    max-height: 500px;
    overflow: hidden;
}
.blurred-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover; /* Чтобы фото покрывало весь фон */
    background-position: center; /* Центрируем фото */
    filter: blur(10px); /* Размытие фона */
    z-index: 1; /* Фон находится под основным фото */
}

#main-photo {
    position: relative;
    z-index: 2; /* Основное фото поверх размытого фона */
    display: block;
    margin: 0 auto; /* Центрируем фото */
}


.modal-blurred-background {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-size: cover;
    background-position: center;
    filter: blur(10px); /* Сильнее размытие для модального окна */
    z-index: 1;
}

.modal-main-photo {
    position: relative;
    z-index: 2;
    display: block;
    max-width: 100%;
    max-height: 80vh;
    margin: 0 auto;
}
    </style>
</head>
<body class="text-gray-800">
    @include('components.header-seller')

  <!-- путь -->
<div class="container_path px-4 py-2 text-gray-600 font-medium">
    <a href="{{ route('adverts.index') }}" class=" hover:text-blue-500">Главная</a> /
    <a href="javascript:history.back()" class=" hover:text-blue-500">Поиск</a> /
    <a href="{{ route('adverts.show', $advert->id) }}" class=" hover:text-blue-500">{{ $advert->product_name }}</a>
</div>



    <div class="container mx-auto">
        <h1 class="text-3xl font-semibold mb-4">{{ $advert->product_name }}</h1>
     <div class="flex flex-col lg:flex-row">
    <div class="lg:w-2/3"> 
        <div class="rounded-lg mb-4 ">
            @if ($advert->main_photo_url)
             <div class="photo-container rounded-lg shadow-lg">
    <div class="blurred-background rounded-lg shadow-lg" style="background-image: url('{{ $advert->main_photo_url }}');"></div>
    <img id="main-photo" alt="Изображение товара" class="w-full h-auto object-cover"
         src="{{ $advert->main_photo_url }}" style="max-height: 500px; max-width: 100%; object-fit: contain;"/>
</div>
            @else
                <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует"
                     class="w-full h-auto object-cover" style="max-height: 500px; max-width: 100%; object-fit: contain;">
            @endif
        </div>
        <div class="flex space-x-2 overflow-x-auto">
            @if ($advert->additional_photo_url_1)
                <img alt="Миниатюра 1"
                     class="w-32 h-32 bg-gray-100 p-2 rounded-lg object-cover cursor-pointer"
                     src="{{ $advert->additional_photo_url_1 }}" onclick="swapImage(this)"
                     style="max-height: 128px; max-width: 128px; object-fit: cover;"/>
            @endif
            @if ($advert->additional_photo_url_2)
                <img alt="Миниатюра 2"
                     class="w-32 h-32 bg-gray-100 p-2 rounded-lg object-cover cursor-pointer"
                     src="{{ $advert->additional_photo_url_2 }}" onclick="swapImage(this)"
                     style="max-height: 128px; max-width: 128px; object-fit: cover;"/>
            @endif
            @if ($advert->additional_photo_url_3)
                <img alt="Миниатюра 3"
                     class="w-32 h-32 bg-gray-100 p-2 rounded-lg object-cover cursor-pointer"
                     src="{{ $advert->additional_photo_url_3 }}" onclick="swapImage(this)"
                     style="max-height: 128px; max-width: 128px; object-fit: cover;"/>
            @endif
        </div>
    </div>

            <div class="p-4 lg:w-1/3 lg:pl-8 mt-4 lg:mt-0 ">
                <div class="bg-white p-6 rounded-lg shadow-lg">
                    <div class="flex justify-between items-center">
                        <div>
                        <p class="text-3xl font-bold">{{ $advert->price }} ₽</p>
                        <p class="text-gray-500 text-base">
                            @if($advert->created_at)
                                @if($advert->created_at->isToday())
                                    сегодня в {{ $advert->created_at->format('H:i') }}
                                @elseif($advert->created_at->isYesterday())
                                    вчера в {{ $advert->created_at->format('H:i') }}
                                @else
                                    {{ $advert->created_at->format('d.m.Y в H:i') }}
                                @endif
                            @else
                                дата не указана
                            @endif
                        </p>
                    </div>
                        <button class="text-gray-500 hover:text-gray-700 favorite-btn" data-advert-id="{{ $advert->id }}">
                            <i class="{{ $isFavorite ? 'fas' : 'far' }} fa-heart text-2xl"></i>
                        </button>
                    </div>
                    <div class="mt-2">
                        <p class="text-red-500 font-semibold text-lg">{{ $advert->user->userAddress->city ?? 'Не указан' }}</p>
                        <div class="flex items-center mt-1">
                            <i class="fas fa-truck text-black-500 text-lg"></i>
                            <p class="text-base ml-1">Есть доставка</p>
                        </div>
                        <a class="text-blue-500 text-base mt-1 block" href="#">Показать условия доставки</a>
                    </div>
                    <div class="mt-4 flex justify-between items-start">
                        <div>
                            <p class="font-semibold text-lg">{{ $advert->user->username }}</p>
                            <p class="text-gray-500 text-base">{{ $advert->user->userAddress->address_line ?? 'Не указан' }}</p>
                            <a class="text-blue-500 text-base mt-1 block" href="#yamap">показать на карте</a>
                        </div>
                        <div class="flex justify-center">
                           <img alt="Логотип {{ $advert->user->username }}" class="w-24 h-24 rounded-full mr-4 object-cover" src="{{ $advert->user->avatar_url ?? asset('images/noava.jpg') }}"/>
                        </div>
                    </div>
                    <div class="mt-6 hidden md:block">
                        <button id="show-phone-btn-desktop" class="w-full bg-blue-500 text-white py-2 rounded-lg text-lg">Показать телефон</button>
                       <button class="w-full bg-green-500 text-white py-2 rounded-lg text-lg mt-2">
    @guest
        <a href="{{ route('login') }}" style="color: inherit; text-decoration: none;" onclick="event.preventDefault(); window.location='{{ route('login') }}';">Написать продавцу</a>
    @else
        <a href="{{ route('open.chat.with.seller', ['advert' => $advert->id]) }}" style="color: inherit; text-decoration: none;">Написать продавцу</a>
    @endguest
</button>


                    </div>
                </div>
            </div>
        </div>
        <div id="myModal" class="modal">
    <span class="close">&times;</span>
    <img class="modal-content" id="img01" src="{{ $advert->main_photo_url }}" alt="Увеличенное изображение"/>
</div>
        <div class="p-4 max-w-md mt-8">
            <h1 class="text-xl font-semibold mb-4">
                Продавец
            </h1>
            <div class="flex items-center">
                <img alt="Логотип {{ $advert->user->username }}" class="w-24 h-24 rounded-full mr-4 object-cover" src="{{ $advert->user->avatar_url ?? asset('images/noava.jpg') }}"/>
                <div>
                    <h2 class="text-lg font-semibold">
                        {{ $advert->user->username }}
                    </h2>
                    <p class="text-base text-gray-600">
                        {{ $advert->user->userAddress->address_line ?? 'Не указан' }}
                    </p>
                    <div class="flex flex-col items-start mt-1">
                        <span id="phone-number" class="text-base font-semibold bg-orange-100 px-2 py-1 rounded">
                            {{ $advert->user->UserPhoneNumber->number_1 }}
                        </span>
                        <a class="text-base text-blue-500 " href="#yamap">
                            показать на карте
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class=" p-4 mt-8">
            <h2 class="text-2xl font-semibold mb-4">Характеристики</h2>
            <div class="grid grid-cols-2 gap-2 text-base w-2/3">
                <div>Артикул</div>
                <div>{{ $advert->article_number ?? 'Не указан' }}</div>
                <div>Номер запчасти</div>
                <div>{{ $advert->number ?? 'Не указан' }}</div>
                <div>Марка</div>
                <div>{{ $advert->brand }}</div>
                <div>Модель</div>
                <div>{{ $advert->model }}</div>
                <div>Кузов</div>
                <div>{{ $advert->body ?? 'Не указан' }}</div>
                <div>Двигатель</div>
                <div>{{ $advert->engine ?? 'Не указан' }}</div>
                <div>Год выпуска</div>
                <div>{{ $advert->year }}</div>
                <div>Состояние</div>
                <div>{{ $advert->condition ?? 'Не указан' }}</div>
            </div>
        </div>
        <div class="p-4 mt-8">
            <h2 class="text-2xl font-semibold mb-4">Описание</h2>
            <p class="text-base">{{ $advert->body }}</p>
        </div>
     <div class="p-4 mt-8">
    <h2 class="text-2xl font-semibold mb-4">Может подойти</h2>
    <div class="bg-yellow-100 p-4 rounded-lg mb-4 text-base">
        Совместимость не гарантирована. Данные сформированы автоматически и могут содержать ошибки. Уточните применимость к вашему авто у продавца.
    </div>
    <div id="compatibility-container">
        <div class="mb-4">
            <div class="flex items-start space-x-2">
                <span class="font-bold text-lg">Марка</span>
                <div id="brand-filters" class="flex items-center space-x-2"></div>
            </div>
            <div class="flex items-start space-x-2 mt-2">
                <span class="font-bold text-lg">Модель</span>
                <div id="model-filters" class="flex items-center space-x-2"></div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-base text-left" id="adverts-table">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="p-2 whitespace-nowrap">Марка</th>
                        <th class="p-2 whitespace-nowrap">Модель</th>
                        <th class="p-2 whitespace-nowrap">Поколение</th>
                        <th class="p-2 whitespace-nowrap">Период выпуска</th>
                        <th class="p-2 whitespace-nowrap">Модификация</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Данные будут загружены динамически -->
                </tbody>
            </table>
        </div>
        <div id="pagination-container" class="mt-4 flex justify-center space-x-2">
            <!-- Пагинация будет загружена динамически -->
        </div>
    </div>
    <div id="no-data-message" class="hidden text-center py-8 text-gray-500 text-lg">
        Нет данных о применимости
    </div>
</div>
        

        <div class="p-4 mt-8">
            <h2 class="text-2xl font-semibold mb-4">Доставка и оплата</h2>
            <p class="text-base mb-4">Гарантия и условия возврата</p>
            <p class="text-base mb-4">Гарантия есть!</p>
            <p class="text-base mb-4">**** ВНИМАНИЕ! ****</p>
            <p class="text-base mb-4">Цена указана за наличный расчет или переводом на карту!</p>
            <p class="text-base mb-4">Возможна оплата по терминалу и по счету. (+10% к стоимости).</p>
            <p class="text-base mb-4">Цена и наличие может быть неактуальными!</p>
            <p class="text-base mb-4">Перед тем как ехать, всю информацию уточняйте пожалуйста в переписке или по телефону!</p>
            <p class="text-base mb-4">ПРОСЬБА !!! до приезда в магазин !!! оповестить нас по телефону о Вашем намерении забрать в магазине товар!</p>
            <p class="text-base mb-4">Звоните нам или пишите на WhatsApp, подберем запчасти под Ваш бюджет!</p>
            <div class="text-base mb-4">
                <p class="font-semibold">Доставка и оплата</p>
                <p>Самовывоз — <a class="text-blue-500 hover:underline" href="#">Иркутск, ул.Лермонтова 321\2</a></p>
                <p>Доставка по городу Иркутск — 300 р.</p>
                <p>До транспортной компании в Иркутске — 300 р.</p>
                <p>При заказе от 50 000 р. доставка курьером в Иркутске и до транспортной компании бесплатна</p>
                <p>Самовывоз ул. Баррикад 82 ТЦ "Gold Car", пав. 5</p>
            </div>
            <div class="flex items-center p-4">
                <img alt="Логотип {{ $advert->user->username }}" class="w-24 h-24 rounded-full mr-4 object-cover" src="{{ $advert->user->avatar_url ?? asset('images/noava.jpg') }}"/>
                <div class="ml-4">
                    <div class="text-lg font-semibold">
                        {{ $advert->user->username }}
                    </div>
                    <div class="text-gray-500 text-base">
                        {{ $advert->user->userAddress->address_line ?? 'Не указан' }}
                    </div>
                </div>
                <div class="ml-auto flex space-x-4 hidden md:flex">
                    <button id="show-phone-btn-desktop2" class="bg-blue-500 text-white px-4 py-2 rounded text-lg">
                        Показать телефон
                    </button>
                  <button class="bg-green-500 text-white px-4 py-2 rounded text-lg">
    <a href="{{ route('open.chat.with.seller', ['advert' => $advert->id]) }}" style="color: inherit; text-decoration: none;">Написать продавцу</a>
</button>
                </div>
            </div>
        </div>
        <div class="p-4 mt-8" id='yamap'>
            <div id="map" class="w-full h-96 mt-4 mb-12"></div>
        </div>
    </div>
 @include('components.footer')   

    <!-- Фиксированные кнопки на мобильных устройствах -->
    <div class="fixed-buttons bottom-14 md:hidden flex space-x-2 z-10">
  <button id="show-phone-btn-mobile" class="w-1/2 bg-blue-500 text-white py-2 rounded-lg text-sm">Позвонить</button>
  <button class="w-1/2 bg-green-500 text-white py-2 rounded-lg text-sm">
   <a href="{{ route('open.chat.with.seller', ['advert' => $advert->id]) }}" style="color: inherit; text-decoration: none;">Написать продавцу</a>
  </button>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById("myModal");
    const modalImg = document.getElementById("img01");
    const span = document.getElementsByClassName("close")[0];
    const mainPhoto = document.getElementById("main-photo");

    function openModal(img) {
        modal.style.display = "block";
        modalImg.src = img.src;
    }

    span.onclick = function() {
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = "none";
        }
    }

    mainPhoto.addEventListener('click', function() {
        openModal(this);
    });
});
</script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const showPhoneBtnDesktop = document.getElementById('show-phone-btn-desktop');
            const showPhoneBtnDesktop2 = document.getElementById('show-phone-btn-desktop2');
            const showPhoneBtnMobile = document.getElementById('show-phone-btn-mobile');

            if (showPhoneBtnDesktop) {
                showPhoneBtnDesktop.addEventListener('click', function() {
                    showPhoneBtnDesktop.textContent = "{{ $advert->user->UserPhoneNumber->number_1 }}";
                });
            }

            if (showPhoneBtnDesktop2) {
                showPhoneBtnDesktop2.addEventListener('click', function() {
                    showPhoneBtnDesktop2.textContent = "{{ $advert->user->UserPhoneNumber->number_1 }}";
                });
            }

            if (showPhoneBtnMobile) {
                showPhoneBtnMobile.addEventListener('click', function() {
                    showPhoneBtnMobile.textContent = "{{ $advert->user->UserPhoneNumber->number_1 }}";
                });
            }
        });

        ymaps.ready(init);

        function init() {
            var myMap = new ymaps.Map('map', {
                center: [52.753994, 104.622093],
                zoom: 9, 
                controls: ['zoomControl']
            });

            // Данные для геокодирования
            var address = "{{ $advert->branch->address ?? 'Не указан' }}"; // Используем адрес из таблицы branches
            var prod_name = "{{ $advert->product_name }}";
            var image_url = "{{ $advert->main_photo_url }}";
            var advert_id = "{{ $advert->id }}";

            // URL изображения по умолчанию
            var defaultImageUrl = "{{ asset('images/dontfoto.jpg') }}";

            // Функция для геокодирования и добавления метки на карту
            function geocodeAndAddToMap(address, prod_name, image_url, advert_id) {
                if (!address || address === "Не указан") {
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
                    myMap.setCenter(coords, 15, {
                        checkZoomRange: true
                    });
                });
            }

            // Выполняем геокодирование и добавление метки для адреса
            geocodeAndAddToMap(address, prod_name, image_url, advert_id);
        }

        function swapImage(thumbnail) {
            const mainPhoto = document.getElementById('main-photo');
            const mainPhotoSrc = mainPhoto.src;
            const thumbnailSrc = thumbnail.src;

            mainPhoto.src = thumbnailSrc;
            thumbnail.src = mainPhotoSrc;
        }

        document.querySelectorAll('td.truncate').forEach(function(cell) {
            cell.addEventListener('click', function() {
                const fullText = cell.getAttribute('data-fulltext');
                if (cell.textContent !== fullText) {
                    cell.textContent = fullText;
                } else {
                    cell.textContent = cell.textContent.slice(0, 20) + '...';
                }
            });
        });


document.querySelectorAll('.favorite-btn').forEach(button => {
    button.addEventListener('click', function () {
        const advertId = this.getAttribute('data-advert-id');
        const icon = this.querySelector('i');

        // Определяем текущее состояние (добавлено или нет)
        const isFavorite = icon.classList.contains('fas');

        if (isFavorite) {
            // Удаляем из избранного
            fetch(`/favorites/remove/${advertId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                   
                }
            });
        } else {
            // Добавляем в избранное
            fetch(`/favorites/add/${advertId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                   
                }
            });
        }
    });
});
    </script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const advertId = {{ $advert->id }};
    const brandFiltersContainer = document.getElementById('brand-filters');
    const modelFiltersContainer = document.getElementById('model-filters');
    const tableBody = document.querySelector('#adverts-table tbody');
    const paginationContainer = document.getElementById('pagination-container');
    const compatibilityContainer = document.getElementById('compatibility-container');
    const noDataMessage = document.getElementById('no-data-message');

    let currentPage = 1;
    let currentFilterBrand = null;
    let currentFilterModel = null;
    let allBrands = [];
    let modelsByBrand = {}; // Объект для хранения моделей по маркам

    // Функция для загрузки всех марок и моделей
    function loadAllBrandsAndModels() {
        fetch(`/advert/${advertId}/all-brands-and-models`)
            .then(response => response.json())
            .then(data => {
                allBrands = data.brands;
                modelsByBrand = data.modelsByBrand; // Загружаем модели, сгруппированные по маркам
                updateFilters();
            });
    }

    // Функция для загрузки данных таблицы
    function loadTableData(page = 1, filterBrand = null, filterModel = null) {
        let url = `/advert/${advertId}/table-data?page=${page}`;
        if (filterBrand) url += `&filterBrand=${filterBrand}`;
        if (filterModel) url += `&filterModel=${filterModel}`;

        fetch(url)
            .then(response => response.json())
            .then(data => {
                tableBody.innerHTML = '';
                
                if (data.adverts && data.adverts.length > 0) {
                    // Показываем контейнер с данными
                    compatibilityContainer.style.display = 'block';
                    noDataMessage.style.display = 'none';
                    
                    data.adverts.forEach(car => {
                        const row = document.createElement('tr');
                        row.className = 'border-b';
                        row.innerHTML = `
                            <td class="p-2 truncate">${car.brand}</td>
                            <td class="p-2 truncate">${car.model}</td>
                            <td class="p-2 truncate">${car.generation}</td>
                            <td class="p-2 truncate">${car.year_from} - ${car.year_before}</td>
                            <td class="p-2 truncate">${car.modification}</td>
                        `;
                        tableBody.appendChild(row);
                    });

                    updatePagination(data.pagination);
                } else {
                    // Скрываем контейнер с данными и показываем сообщение
                    compatibilityContainer.style.display = 'none';
                    noDataMessage.style.display = 'block';
                    paginationContainer.innerHTML = '';
                }
            });
    }

    // Функция для обновления фильтров
    function updateFilters() {
        brandFiltersContainer.innerHTML = '';
        modelFiltersContainer.innerHTML = '';

        // Фильтруем и сортируем марки по алфавиту
        const sortedBrands = [...allBrands]
            .filter(brand => brand != null) // Убираем null и undefined
            .sort((a, b) => a.localeCompare(b)); // Сортируем по алфавиту

        // Добавляем "Показать все" для марок
        const showAllBrands = document.createElement('a');
        showAllBrands.href = '#';
        showAllBrands.className = `text-blue-600 text-lg filter-link ${
            currentFilterBrand === null ? 'bg-gray-200 rounded px-2' : ''
        }`;
        showAllBrands.setAttribute('data-filter', 'brand');
        showAllBrands.setAttribute('data-value', 'all');
        showAllBrands.textContent = 'Показать все';
        brandFiltersContainer.appendChild(showAllBrands);

        // Добавляем отсортированные марки
        sortedBrands.forEach(brand => {
            const link = document.createElement('a');
            link.href = '#';
            link.className = `text-blue-600 text-lg filter-link ${
                currentFilterBrand === brand ? 'bg-gray-200 rounded px-2' : ''
            }`;
            link.setAttribute('data-filter', 'brand');
            link.setAttribute('data-value', brand);
            link.textContent = brand;
            brandFiltersContainer.appendChild(link);
        });

        // Добавляем "Показать все" для моделей
        const showAllModels = document.createElement('a');
        showAllModels.href = '#';
        showAllModels.className = `text-blue-600 text-lg filter-link ${
            currentFilterModel === null ? 'bg-gray-200 rounded px-2' : ''
        }`;
        showAllModels.setAttribute('data-filter', 'model');
        showAllModels.setAttribute('data-value', 'all');
        showAllModels.textContent = 'Показать все';
        modelFiltersContainer.appendChild(showAllModels);

        // Фильтруем и сортируем модели по алфавиту (если есть выбранная марка)
        const modelsToShow = currentFilterBrand ? modelsByBrand[currentFilterBrand] : [];
        if (modelsToShow) {
            const sortedModels = [...modelsToShow]
                .filter(model => model != null) // Убираем null и undefined
                .sort((a, b) => a.localeCompare(b)); // Сортируем по алфавиту

            sortedModels.forEach(model => {
                const link = document.createElement('a');
                link.href = '#';
                link.className = `text-blue-600 text-lg filter-link ${
                    currentFilterModel === model ? 'bg-gray-200 rounded px-2' : ''
                }`;
                link.setAttribute('data-filter', 'model');
                link.setAttribute('data-value', model);
                link.textContent = model;
                modelFiltersContainer.appendChild(link);
            });
        }

        // Обработчики событий для фильтров
        document.querySelectorAll('.filter-link').forEach(link => {
            link.addEventListener('click', function (e) {
                e.preventDefault();
                const filterType = this.getAttribute('data-filter');
                const filterValue = this.getAttribute('data-value');

                // Убираем выделение у всех элементов
                document.querySelectorAll('.filter-link').forEach(el => {
                    el.classList.remove('bg-gray-200', 'rounded', 'px-2');
                });

                // Выделяем выбранный элемент
                this.classList.add('bg-gray-200', 'rounded', 'px-2');

                if (filterType === 'brand') {
                    currentFilterBrand = filterValue === 'all' ? null : filterValue;
                    currentFilterModel = null; // Сбрасываем фильтр модели при выборе марки
                    updateFilters(); // Обновляем фильтры, чтобы показать модели для выбранной марки
                } else if (filterType === 'model') {
                    currentFilterModel = filterValue === 'all' ? null : filterValue;
                }

                loadTableData(1, currentFilterBrand, currentFilterModel);
            });
        });
    }

    // Функция для обновления пагинации
    function updatePagination(pagination) {
        paginationContainer.innerHTML = '';
        currentPage = pagination.current_page; // Обновляем текущую страницу

        if (pagination.last_page > 1) {
            // Кнопка "Назад"
            if (pagination.current_page > 1) {
                const prevButton = document.createElement('button');
                prevButton.innerHTML = '&laquo;';
                prevButton.className = 'px-4 py-2 mx-1 rounded bg-white text-blue-600 border border-gray-300 hover:bg-gray-100';
                prevButton.addEventListener('click', () => {
                    loadTableData(pagination.current_page - 1, currentFilterBrand, currentFilterModel);
                });
                paginationContainer.appendChild(prevButton);
            }

            // Первая страница
            if (pagination.current_page > 3) {
                const firstPageButton = document.createElement('button');
                firstPageButton.textContent = '1';
                firstPageButton.className = `px-4 py-2 mx-1 rounded border ${
                    1 === currentPage 
                        ? 'bg-blue-600 text-white border-blue-600' 
                        : 'bg-white text-blue-600 border-gray-300 hover:bg-gray-100'
                }`;
                firstPageButton.addEventListener('click', () => {
                    loadTableData(1, currentFilterBrand, currentFilterModel);
                });
                paginationContainer.appendChild(firstPageButton);

                // Многоточие
                if (pagination.current_page > 4) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-4 py-2 mx-1 text-gray-500';
                    paginationContainer.appendChild(ellipsis);
                }
            }

            // Страницы вокруг текущей
            const startPage = Math.max(1, pagination.current_page - 2);
            const endPage = Math.min(pagination.last_page, pagination.current_page + 2);

            for (let i = startPage; i <= endPage; i++) {
                const pageButton = document.createElement('button');
                pageButton.textContent = i;
                pageButton.className = `px-4 py-2 mx-1 rounded border ${
                    i === currentPage 
                        ? 'bg-blue-600 text-white border-blue-600' 
                        : 'bg-white text-blue-600 border-gray-300 hover:bg-gray-100'
                }`;
                pageButton.addEventListener('click', () => {
                    loadTableData(i, currentFilterBrand, currentFilterModel);
                });
                paginationContainer.appendChild(pageButton);
            }

            // Последняя страница
            if (pagination.current_page < pagination.last_page - 2) {
                // Многоточие
                if (pagination.current_page < pagination.last_page - 3) {
                    const ellipsis = document.createElement('span');
                    ellipsis.textContent = '...';
                    ellipsis.className = 'px-4 py-2 mx-1 text-gray-500';
                    paginationContainer.appendChild(ellipsis);
                }

                const lastPageButton = document.createElement('button');
                lastPageButton.textContent = pagination.last_page;
                lastPageButton.className = `px-4 py-2 mx-1 rounded border ${
                    pagination.last_page === currentPage 
                        ? 'bg-blue-600 text-white border-blue-600' 
                        : 'bg-white text-blue-600 border-gray-300 hover:bg-gray-100'
                }`;
                lastPageButton.addEventListener('click', () => {
                    loadTableData(pagination.last_page, currentFilterBrand, currentFilterModel);
                });
                paginationContainer.appendChild(lastPageButton);
            }

            // Кнопка "Вперед"
            if (pagination.current_page < pagination.last_page) {
                const nextButton = document.createElement('button');
                nextButton.innerHTML = '&raquo;';
                nextButton.className = 'px-4 py-2 mx-1 rounded bg-white text-blue-600 border border-gray-300 hover:bg-gray-100';
                nextButton.addEventListener('click', () => {
                    loadTableData(pagination.current_page + 1, currentFilterBrand, currentFilterModel);
                });
                paginationContainer.appendChild(nextButton);
            }
        }
    }

    // Загружаем все марки и модели при загрузке страницы
    loadAllBrandsAndModels();

    // Загружаем данные таблицы при загрузке страницы
    loadTableData();
});
</script>


<script>
    const showPhoneBtnMobile = document.getElementById('show-phone-btn-mobile');
    const phoneNumber = "{{ $advert->user->UserPhoneNumber->number_1 }}"; // Замените на ваш номер телефона

    if (showPhoneBtnMobile) {
        showPhoneBtnMobile.addEventListener('click', function() {
            // Изменяем текст кнопки на номер телефона
            showPhoneBtnMobile.textContent = phoneNumber;

            // Открываем приложение для набора номера
            window.location.href = `tel:${phoneNumber}`;
        });
    }
</script>
</body>
</html>