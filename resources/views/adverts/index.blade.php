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
    <title>Единая база данных автозапчастей магазинов и авторазборов Вашего города</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<style>


.parent-container:hover .absolute {
    display: none; /* Скрываем градиентный блок при наведении */
}
/* Стили для контейнера модификаций */
#modifications-container {
    display: flex;
    flex-direction: column;
    height: 100%; /* Занимает всю доступную высоту */
}

/* Блок с модификациями (будет растягиваться) */
#modifications-container .flex-grow {
    flex-grow: 1; /* Занимает всё доступное пространство */
    overflow-y: auto; /* Добавляем прокрутку, если контент не помещается */
}

/* Стили для кнопки */
#openModificationsModalBtn {
    display: none; /* Скрыта по умолчанию */
}

@media (max-width: 768px) {
    #openModificationsModalBtn {
        display: block; /* Показываем кнопку только на мобильных устройствах */
    }
}

#modifications {
    display: flex;
    flex-direction: column; /* Текст будет выводиться в один элемент в строку */
    overflow-y: auto; 
    height: 10rem;/* Вертикальная прокрутка, если текст не помещается */
}

.blockadvert {
    box-shadow: 0px 4px 25px rgba(0, 0, 0, 0.15); /* Adjust values as needed */
    width: 10%;
}

/* Стили для мобильной версии блока фильтров */
#search-filters-container {
    display: flex;
    flex-direction: column;
    padding-right: 1rem;    
    padding-left: 1rem;
    border-radius: 0.5rem;
    margin-top: 1rem;
}

#search-filters-container .modification {
    margin-bottom: 1rem;
}

#search-filters-container .modification label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

/* Стили для модального окна */
#modificationsModal {
    display: none; /* Скрыто по умолчанию */
}

#modificationsModalContent {
    max-height: 80vh; /* Ограничиваем высоту контента */
    overflow-y: auto; /* Добавляем прокрутку, если контент не помещается */
}

/* Стили для кнопки закрытия модального окна */
#closeModificationsModalBtn {
    font-size: 1.5rem;
    cursor: pointer;
}

@media (max-width: 768px) {
    #search-filters-container {
        background-color: #5e94f7 !important; /* Синий фон */
        border-radius: 0;
        border: 1px solid #5e94f7;
        margin-top:0;
    }
    
      #modifications-container {
        height: 250px !important; /* Высота 200px для мобильных устройств */
        overflow-y: auto; /* Добавляем вертикальную прокрутку, если контент не помещается */
         border-radius: 15px;

    }
}

#search-filters-container .modification #modifications {
    display: flex;
    flex-direction: column;
    overflow-y: auto;
    max-height: 10rem;
    margin-top: 0.5rem;
}

#search-filters-container .modification #additional-options {
    display: flex;
    flex-direction: column;
    margin-top: 0.5rem;
}

#search-filters-container .modification #additional-options label {
    margin-bottom: 0.5rem;
}

body {
    font-family: 'Nunito', sans-serif;
}

#scrollToTopBtn {
    display: none; /* Скрываем кнопку по умолчанию */
    position: fixed;
    bottom: 70px;
    right: 20px;
    z-index: 99;
    font-size: 18px;
    border: none;
    outline: none;
    background-color: #6b7280; /* Серый цвет */
    color: white;
    cursor: pointer;
    padding: 15px;
    border-radius: 50%;
    transition: background-color 0.3s;
}

#scrollToTopBtn:hover {
    background-color: #4b5563; /* Темно-серый цвет при наведении */
}


</style>
<body class=" flex flex-col items-center">

@include('components.header-seller')   
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script> 

<!-- Рекламный баннер -->
<div class="w-full md:w-[90%] mx-auto mt-10 hidden md:block">
    <img src="{{ asset('images/banner.png') }}" alt="Реклама" class="banner w-full rounded-2xl hidden md:block">
</div>

<div class="w-full md:w-[90%] flex flex-col items-start">
    <div class="w-full md:w-[80%]">
        
        @include('components.search-form')  
    </div>
</div>

<div id="search-filters-wrapper"  class="w-full md:w-[90%] flex flex-col items-start">
    <div id="search-filters-container" class="hidden w-full md:w-[75%]"></div>
</div>

<div class="w-full md:w-[90%]">
    <div class="flex flex-col w-full sm:flex-row sm:justify-start sm:w-full mt-8">
    @if($adverts->isEmpty())
        <p class="text-center text-lg mt-8">Нет доступных товаров.</p>
    @else
        @php
            // Фильтруем коллекцию, исключая товар с id 1111
            $filteredAdverts = $adverts->reject(function($advert) {
                return $advert->id == 1111;
            });
        @endphp

        <!-- Для телефонов -->
        <div class="grid grid-cols-2 sm:grid-cols-2 gap-4 px-2 sm:hidden">
            @foreach($filteredAdverts as $advert)
<div class="bg-white rounded-lg"
     onclick="location.href=generateAdvertUrl({
         id: '{{ $advert->id }}',
         product_name: '{{ $advert->product_name }}',
         brand: '{{ $advert->brand }}',
         model: '{{ $advert->model }}',
         year: '{{ $advert->year }}',
         engine: '{{$advert->engine}}',
         number: '{{$advert->number}}'
     })">
    <div class="relative">
        @if ($advert->main_photo_url)
            <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="w-full h-48 object-cover rounded-lg">
        @else
            <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="w-full h-48 object-cover rounded-lg">
        @endif
        <span class="absolute top-2 right-2 bg-[#FFE6C1] text-black text-xs font-normal px-2 py-1 rounded">
             {{ $advert->user->userAddress->city ?? 'Не указан' }}
        </span>
    </div>
    <div class="px-2 py-1 flex flex-col" style="min-height: 100px;"> <!-- Фиксированная минимальная высота -->
        <div class="text-lg font-bold overflow-hidden whitespace-nowrap relative">
            {{ $advert->product_name }}
            <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-white to-transparent"></div>
        </div>
        <div class="text-xl text-black font-semibold">
            {{ $advert->price }} ₽
        </div>
        <div class="flex flex-wrap items-center text-gray-500 text-sm mt-2" style='min-height: 20px;'>
            @if($advert->brand)
                <i class="fas fa-car mr-2"></i>
                <span>{{ $advert->brand }}</span>
                <span class="mx-1">|</span>
            @endif
            
            @if($advert->model)
                <span>{{ $advert->model }}</span>
                <span class="mx-1">|</span>
            @endif
            
            @if($advert->year)
                <span>{{ $advert->year }}</span>
            @endif
        </div>
        
        <div style='min-height: 20px;'>
            @if($advert->number)
            <p class="text-sm text-gray-600">
                <i class="fas fa-barcode"></i>
                <span>{{ $advert->number }}</span>
            </p>
        @endif     
        </div>
          <!-- Блок со временем, который всегда будет внизу -->
            <div class="text-gray-500 text-sm mt-auto" style="display: block !important;">
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
            </div>
    </div>
</div>
            @endforeach
        </div>

      <!-- Для больших и средних экранов -->
<div class="hidden sm:flex w-[90%] flex-col items-start justify-center mr-20 parent-container">
    @foreach($filteredAdverts as $advert)
    <div class="blockadvert bg-white rounded-2xl flex max-w-5xl w-full mt-8 cursor-pointer transition-colors duration-300 hover:bg-[#f0f8ff] relative border border-[#b8b8b8]"
         onclick="location.href=generateAdvertUrl({
             id: '{{ $advert->id }}',
             product_name: '{{ $advert->product_name }}',
             brand: '{{ $advert->brand }}',
             model: '{{ $advert->model }}',
             year: '{{ $advert->year }}',
             engine: '{{$advert->engine}}',
              number: '{{$advert->number}}'
             
         })" tabindex="0" role="button">
        <!-- Кружок с надписью -->
        <!--<div class="absolute -right-9 top-2/3 transform -translate-y-1/2 w-20 h-20 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold shadow-lg">
            <span class="flex items-center justify-center text-center">Скидка до -20%</span>
        </div>-->
    
        <!-- Вывод главного фото -->
        <div class="w-1/4 flex-shrink-0">
            <div class="w-[220px] h-[175px] bg-gray-200 rounded-l-2xl border border-r-[#b8b8b8] overflow-hidden">
                @if ($advert->main_photo_url)
                    <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="w-full h-full object-cover">
                @else
                    <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="w-full h-full object-cover">
                @endif
            </div>
        </div>
    
        <div class="flex flex-col justify-between w-3/4 lg:ml-10 sm:ml-20">
            <div class="flex justify-between items-start">
                <div class="pt-4">
                    <div class="relative max-w-[300px]"> <!-- Укажите нужную ширину -->
                        <h2 class="text-xl font-semibold product-name overflow-hidden whitespace-nowrap pr-8" data-original-name="{{ $advert->product_name }}">
                            {{ $advert->product_name }}
                        </h2>
                        <div class="absolute inset-y-0 right-0 w-8 bg-gradient-to-l from-white to-transparent pointer-events-none"></div>
                    </div>
                    @if($advert->number)
                    <p class="beg bg-gray-200 mt-4 px-3 py-1 w-24 text-sm rounded-xl text-center">{{ $advert->number }}</p>
                    @endif
                </div>
                <div class="text-right pr-4 pt-4">
                    <p class="text-xl font-semibold">{{ $advert->price }} ₽</p>
                    <p class="text-red-500">{{ $advert->user->userAddress->city ?? 'Не указан' }}</p>
                </div>
            </div>
            <div class="flex space-x-3 pb-4 w-full justify-start">
                @if($advert->brand)
                <span class="bg-[#FFE6C1] text-black text-sm font-medium px-2.5 py-0.5 rounded-xl">{{ $advert->brand }}</span>
                @endif
                
                @if($advert->model)
                <span class="bg-[#FFE6C1] text-black text-sm font-medium px-2.5 py-0.5 rounded-xl">{{ $advert->model }}</span>
                @endif
                
                @if($advert->body)
                <span class="bg-[#FFE6C1] text-black text-sm font-medium px-2.5 py-0.5 rounded-xl">{{ $advert->body }}</span>
                @endif
                
                @if($advert->engine)
                <span class="bg-[#FFE6C1] text-black text-sm font-medium px-2.5 py-0.5 rounded-xl">{{ $advert->engine }}</span>
                @endif
            </div>
            
            <!-- Добавленное время в правом нижнем углу -->
            <div class="absolute bottom-2 right-4 text-gray-500 text-sm" style="display: block !important;">
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
            </div>
        </div>
    </div>
    @endforeach
</div>
        <div id="filters-container" class="blockadvert p-4 filters bg-[#f3f3f3] w-1/3 h-[32rem] rounded-2xl shadow-md hidden xl:block">
            <!-- Блок "Модификации" -->
            
<div id="modifications-container" class="modification p-4 h-1/2 bg-[#f3f3f3] flex flex-col">
    <!-- Блок с модификациями -->
    <div class="flex-grow">
        <label class="font-medium">Модификации:</label>
        <div id="modifications-buttons" class="flex space-x-2 mb-2 hidden">
            <button id="select-all-mods" class="text-blue-500 hover:text-blue-700">Отметить все</button>
            <button id="deselect-all-mods" class="text-blue-500 hover:text-blue-700">Убрать все</button>
        </div>
        <div id="modifications" class="flex flex-col overflow-y-auto" style="display: none;">
            <!-- Здесь будут отображаться модификации -->
        </div>
        <div id="modifications-placeholder" class="text-gray-500 mt-2 hidden">
            Для отображения модификаций выберите параметры автомобиля
        </div>
    </div>

    <!-- Отдельный блок для кнопки -->
    <div class="mt-4">
        <button id="openModificationsModalBtn" class="w-[75%] bg-[#E9E9E9] text-black px-4 py-2 rounded-lg md:hidden">
            Показать все модификации
        </button>
    </div>
</div>

<!-- Модальное окно для отображения всех модификаций -->
<div id="modificationsModal" class="fixed inset-0 bg-white z-50 overflow-y-auto hidden">
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Все модификации</h2>
            <button id="closeModificationsModalBtn" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modificationsModalContent" class="overflow-y-auto">
            <!-- Сюда будут вставлены модификации -->
        </div>
    </div>
</div>
        
            <!-- Блок "Дополнительно" -->
            <!--<div id="additional-container" class="modification mt-12 h-1/2">
                <label class="font-medium">Дополнительно:</label>
                <div id="additional-options" class="flex flex-col mt-2">
                
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" class="form-checkbox text-blue-500">
                        <span class="text-gray-700">Есть установка</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" class="form-checkbox text-blue-500">
                        <span class="text-gray-700">Есть эвакуатор</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" class="form-checkbox text-blue-500">
                        <span class="text-gray-700">Есть рассрочка/кредит</span>
                    </label>
                    <label class="flex items-center space-x-2 mb-2">
                        <input type="checkbox" class="form-checkbox text-blue-500">
                        <span class="text-gray-700">Есть доставка</span>
                    </label>
                </div>
            </div>-->
        </div>
    </div>

        <div class="h-24">
            @include('components.indexpagination', ['adverts' => $adverts])
        </div>
    @endif
</div>
<div id="modificationsModal" class="fixed inset-0 bg-white z-50 overflow-y-auto hidden">
    <div class="p-4">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold">Все модификации</h2>
            <button id="closeModificationsModalBtn" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div id="modificationsModalContent" class="overflow-y-auto">
            <!-- Сюда будут вставлены модификации -->
        </div>
    </div>
</div>
 <button id="scrollToTopBtn" title="Наверх" class="fixed bottom-4 right-4 p-3 bg-gray-500 text-white rounded-full shadow-lg hover:bg-gray-600 transition duration-300 hidden">
    <i class="fas fa-arrow-up"></i>
</button>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const productNameElements = document.querySelectorAll('.product-name');

    productNameElements.forEach(element => {
      const originalName = element.dataset.originalName;
      if (originalName.length > 45) {
        element.textContent = originalName.substring(0, 45) + '...';
      }
    });
  });
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
            'я': 'ya', ' ': '-', "'": '', // Удаляем символ '
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
// Функция для проверки, выбраны ли все значения
function checkAllValuesSelected() {
    const searchQuery = document.querySelector('input[name="search_query"]').value;
    const brand = document.getElementById('brand').value;
    const model = document.getElementById('model').value;
    const year = document.getElementById('year').value;

    // Проверяем, выбраны ли все значения
    if (searchQuery && brand && model && year) {
        // Если все значения выбраны, вызываем функцию
        toggleModificationsVisibility();
    } else {
        // Если не все значения выбраны, скрываем блок с модификациями
        const modificationsContainer = document.getElementById('modifications');
        modificationsContainer.style.display = 'none';
        updateModificationsPlaceholder();
    }
}

// Функция для обновления текста в зависимости от состояния блока с модификациями
function updateModificationsPlaceholder() {
    const modificationsContainer = document.getElementById('modifications');
    const placeholder = document.getElementById('modifications-placeholder');

    // Проверяем, скрыт ли блок с модификациями
    if (modificationsContainer.style.display === 'none') {
        // Если блок скрыт, показываем текст
        placeholder.classList.remove('hidden');
    } else {
        // Если блок видимый, скрываем текст
        placeholder.classList.add('hidden');
    }
}

// Функция для переключения видимости блока с модификациями
function toggleModificationsVisibility() {
    const modificationsContainer = document.getElementById('modifications');

    // Переключаем состояние блока
    if (modificationsContainer.style.display === 'none') {
        modificationsContainer.style.display = 'flex'; // Показываем блок
    } else {
        modificationsContainer.style.display = 'none'; // Скрываем блок
    }

    // Обновляем состояние текста
    updateModificationsPlaceholder();
}

// Добавляем обработчики событий на изменение значений полей
document.querySelector('input[name="search_query"]').addEventListener('input', checkAllValuesSelected);
document.getElementById('brand-input').addEventListener('input', checkAllValuesSelected);
document.getElementById('model-input').addEventListener('input', checkAllValuesSelected);
document.getElementById('year').addEventListener('change', checkAllValuesSelected);

// Вызываем функцию при загрузке страницы
document.addEventListener('DOMContentLoaded', () => {
    checkAllValuesSelected();
});
</script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const filtersContainer = document.getElementById('filters-container');
    const searchFiltersContainer = document.getElementById('search-filters-container');
    const searchFiltersWrapper = document.getElementById('search-filters-wrapper');

function handleFiltersVisibility() {
    const filtersContainer = document.getElementById('filters-container');
    const searchFiltersContainer = document.getElementById('search-filters-container');
    const searchFiltersWrapper = document.getElementById('search-filters-wrapper');

    if (window.innerWidth < 1280) {
        // Скрываем основной блок фильтров
        filtersContainer.classList.add('hidden');
        
        // Показываем блок под поиском
        searchFiltersContainer.classList.remove('hidden');
        
        // Проверяем, есть ли уже загруженные модификации в мобильном контейнере
        const hasExistingModifications = document.querySelector('#search-filters-container .modification-checkbox');
        
        // Копируем содержимое только если модификаций еще нет
        if (!hasExistingModifications) {
            searchFiltersContainer.innerHTML = filtersContainer.innerHTML;
        }
        
        // Применяем стили для мобильной версии
        searchFiltersContainer.classList.add('mobile-filters');
        
        // Показываем внешний блок
        searchFiltersWrapper.classList.remove('hidden');
    } else {
        // Показываем основной блок фильтров
        filtersContainer.classList.remove('hidden');
        
        // Скрываем блок под поиском
        searchFiltersContainer.classList.add('hidden');
        
        // Убираем стили для мобильной версии
        searchFiltersContainer.classList.remove('mobile-filters');
        
        // Скрываем внешний блок если он пуст
        if (searchFiltersContainer.innerHTML.trim() === '') {
            searchFiltersWrapper.classList.add('hidden');
        }
    }
}

    // Вызываем функцию при загрузке страницы
    handleFiltersVisibility();

    // Вызываем функцию при изменении размера окна
    window.addEventListener('resize', handleFiltersVisibility);
});
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
    const scrollToTopBtn = document.getElementById('scrollToTopBtn');

    // Показываем кнопку, когда пользователь прокрутил страницу вниз на 100px
    window.onscroll = function() {
        if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
            scrollToTopBtn.style.display = 'block';
        } else {
            scrollToTopBtn.style.display = 'none';
        }
    };

    // Прокручиваем страницу вверх при нажатии на кнопку
    scrollToTopBtn.addEventListener('click', () => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Плавная прокрутка
        });
    });
});

document.addEventListener('DOMContentLoaded', () => {
    const openModalBtn = document.getElementById('openModificationsModalBtn');
    const closeModalBtn = document.getElementById('closeModificationsModalBtn');
    const modificationsModal = document.getElementById('modificationsModal');
    const modificationsModalContent = document.getElementById('modificationsModalContent');
    const modificationsContainer = document.getElementById('modifications');

    // Открываем модальное окно
    openModalBtn.addEventListener('click', () => {
        // Копируем содержимое модификаций в модальное окно
        modificationsModalContent.innerHTML = modificationsContainer.innerHTML;
        modificationsModal.style.display = 'block';
    });

    // Закрываем модальное окно
    closeModalBtn.addEventListener('click', () => {
        modificationsModal.style.display = 'none';
    });

    // Закрываем модальное окно при клике вне его области
    modificationsModal.addEventListener('click', (event) => {
        if (event.target === modificationsModal) {
            modificationsModal.style.display = 'none';
        }
    });
});
    </script>
  @include('components.footer')   
</body>
</html>