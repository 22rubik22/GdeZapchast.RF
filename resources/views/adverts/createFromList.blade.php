<!DOCTYPE html>
<html lang="en">
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
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Загрузить товары</title>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
   body {
        font-family: 'Nunito', sans-serif;
    }
    #modal {
        overflow-y: auto; /* Добавляем возможность прокрутки */
      
    }
    .page {
        display: none; /* Скрываем все страницы по умолчанию */
    }
    .page.active {
        display: block; /* Показываем активную страницу */
    }
    #next-to-columns:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
    .button-active {
    background-color: #3b82f6; /* Синий цвет для активной кнопки */
    color: white;
}

.button-inactive {
    background-color: #e5e7eb; /* Серый цвет для неактивной кнопки */
    color: #4b5563;
}

.inline-flex button[data-mode="name"] {
    background-color: #e5e7eb; /* Серый цвет для неактивной кнопки */
    color: #4b5563;
}
.inline-flex button[data-mode="name"].button-active {
    background-color: #3b82f6; /* Синий цвет для активной кнопки */
    color: white;
}


.inline-flex button[data-mode="number"] {
    background-color: #e5e7eb; /* Серый цвет для неактивной кнопки */
    color: #4b5563;
}
.inline-flex button[data-mode="number"].button-active {
     background-color: #3b82f6; /* Синий цвет для активной кнопки */
     color: white;
}
.inline-flex button.button-active{
    background-color: #3b82f6; /* Синий цвет для активной кнопки */
     color: white;
}
    
    .modal-content {
        max-height: 80vh; /* Ограничение высоты модального окна */
        overflow-y: auto; /* Добавляем прокрутку, если контент не помещается */
        display: flex; /* Используем Flexbox для позиционирования */
        flex-direction: column; /* Размещаем элементы вертикально */
    }
    
    #spinner-icon {
    color: black;
}

.file-upload-wrapper {
    display: inline-block; /* Ensures the wrapper takes the size of its content */
}


.file-upload-button {
  transition: background-color 0.2s ease-in-out;
  background-color: #e5e7eb;
  cursor: pointer; /* Устанавливаем курсор */
}

.file-upload-wrapper:hover .file-upload-button {
  background-color: rgb(201, 201, 201); /* Более темный фон при наведении */

}
</style>
<body>
    @include('components.header-seller')
    
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>
    
   <div class="container mx-auto p-4">
    <div class="flex flex-col items-center justify-center h-auto mt-[5rem] md:mt-[10rem] mb-[20rem] md:mb-[40rem]">
        <h1 class="text-2xl md:text-4xl font-semibold mb-8 md:mb-14 px-4 text-center">Как вы хотите добавить товары?</h1>
        <div class="flex flex-col md:flex-row justify-center space-y-4 md:space-y-0 md:space-x-4 w-full px-4">
            <a href="{{route('adverts.create')}}#create-form" class="bg-gray-200 text-lg md:text-2xl text-gray-800 py-6 md:py-12 px-4 md:px-16 rounded-xl font-semibold md:mr-16 border-gray-200 text-center">Создать товар с помощью формы</a>
            <a href="#sel" class="bg-gray-200 text-gray-800 text-lg md:text-2xl py-6 md:py-12 px-4 md:px-16 rounded-xl font-semibold text-center">Загрузить товары из прайс-листа</a>
        </div>
    </div>

    <div class="mt-8 md:mt-16">
        <h2 id="sel" class="text-lg md:text-xl font-semibold text-center mb-6">Выберите способ загрузки товаров из прайс-листа</h2>
        <div class="space-y-8">
            <div>
                <div class="flex items-center mb-4">
                    <div class="bg-gray-200 text-gray-800 rounded-full h-8 w-8 flex items-center justify-center mr-4">1</div>
                    <h3 class="text-base md:text-lg font-semibold">Прямая загрузка товаров из прайс-листа на сайт</h3>
                </div>
                <div class="bg-orange-100 p-4 rounded-xl mb-4">
                    <p class="text-sm md:text-base">Выберите этот способ загрузки товаров, если ваш прайс-лист соответствует <a href="#" class="text-blue-600">принятому формату</a>.</p>
                </div>
                <div class="flex flex-col md:flex-row items-start md:items-center mb-4 gap-4">
                    <label for="file-upload" class="mr-4">Выберите файл:</label>
                    <div class="file-upload-wrapper relative overflow-hidden rounded-xl border border-gray-300 w-full md:w-auto">
                        <input type="file" id="file-upload" class="absolute inset-0 w-full opacity-0 cursor-pointer"/>
                        <div class="file-upload-button px-4 py-1 rounded-xl flex items-center justify-center">
                            <span class="pointer-events-none">Выбрать файл</span>
                        </div>
                    </div>
                </div>
                
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4 gap-4">
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 w-full md:w-auto">Добавить товары на сайт</button>
                    <div class="text-left md:text-right w-full md:w-auto">
                        <p class="mb-1"><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">Требования к файлу для прямого импорта</a></p>
                        <p class="mb-1"><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">Инструкция по загрузке товаров из файла</a></p>
                        <p><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">Видеоинструкция</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex items-center justify-center my-5">
        <div class="flex-1 h-px bg-gray-300"></div>
        <div class="relative flex items-center justify-center w-12 h-12 md:w-20 md:h-20 rounded-full bg-white mx-2 bg-gray-200">
            <span class="text-base md:text-xl font-semibold">ИЛИ</span>
        </div>
        <div class="flex-1 h-px bg-gray-300"></div>
    </div>

    <div>
        <div class="flex items-center mb-4">
            <div class="bg-gray-200 text-gray-800 rounded-full h-8 w-8 flex items-center justify-center mr-4">2</div>
            <h3 class="text-base md:text-lg font-semibold">Конвертировать прайс-лист и загрузить товары на сайт</h3>
        </div>
        <div class="bg-orange-100 p-4 rounded-xl mb-4">
            <p class="text-sm md:text-base">Если Ваш прайс-лист отличается от <a href="#" class="text-blue-600">принятого формата для прямого импорта</a> выберите этот способ. Конвертация позволяет загрузить товары на сайт из прайс-листа любого вида.</p>
        </div>
        
        <form id="convert-form" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="flex flex-col md:flex-row items-start md:items-center mb-4 gap-4">
                <label for="branch" class="mr-4">Выберите филиал:</label>
                @if(auth()->user()->user_status == 1 && $user->branches->count() > 0)
                    <select name="branch" id="branch" class="border border-gray-300 rounded-xl px-4 py-2 w-full md:w-auto">
                        <option value="" disabled selected>Выберите филиал</option>
                        @foreach($user->branches as $branch)
                            <option value="{{ $branch->id_branch }}">{{ $branch->address }}</option>
                        @endforeach
                    </select>
                @else
                    <p class="text-sm md:text-base">Филиалы не указаны.</p>
                @endif
            </div>

            <div class="flex flex-col md:flex-row items-start md:items-center mb-4 gap-4">
                <label for="file" class="mr-4">Выберите файл:</label>
                <div class="file-upload-wrapper relative overflow-hidden rounded-xl border border-gray-300 w-full md:w-auto">
                    <input type="file" name="file" id="file" class="absolute inset-0 w-full opacity-0 cursor-pointer"/>
                    <div class="file-upload-button px-4 py-1 rounded-xl flex items-center justify-center">
                        <span class="pointer-events-none">Выбрать файл</span>
                    </div>
                </div>
            </div>

            <div class="flex items-center mb-4">
                <input type="checkbox" id="remove_duplicates" name="remove_duplicates" class="mr-2">
                <label for="remove_duplicates" class="text-sm md:text-base">Удалить дубликаты</label>
            </div>

            <div class="flex items-center mb-4">
                <input type="checkbox" id="archive_products" name="archive_products" class="mr-2">
                <label for="archive_products" class="text-sm md:text-base">Архивировать все товары из личного кабинета перед загрузкой прайс-листа</label>
            </div>

            <button type="button" id="convert-button" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-xl w-full md:w-auto">Открыть файл</button>
        </form>

        @if ($errors->any())
            <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li class="text-sm md:text-base">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(session('success'))
            <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded-md mb-4">
                <p class="text-sm md:text-base">{{ session('success') }}</p>
            </div>
        @endif

        <div class="flex justify-start md:justify-end items-center mb-4 mt-4 md:mt-[-3rem]">
            <div class="w-full md:w-auto">
                <p class="mb-1"><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">О конвертере прайс-листов</a></p>
                <p class="mb-1"><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">Первая конвертация файла</a></p>
                <p><a href="#" class="hover:text-blue-600 transition-colors duration-200 text-sm md:text-base">Видеоинструкция</a></p>
            </div>
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


   <div id="modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-xl w-4/5 max-w-screen-lg mx-auto modal-content">
        <!-- Кнопка закрытия -->
        <div class="flex justify-end mb-4">
            <button id="close-modal" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <!-- Страница настроек -->
        <div id="settings-page" class="page text-center">
          <form id="settings-form" action="{{ route('converter_set.update') }}" method="POST" class="space-y-6">
              @csrf
                @method('PUT')
                <h2 class="text-xl font-semibold mb-4">Пожалуйста, укажите марки, которые есть в вашем прайс-листе</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    @foreach ([
                        'acura', 'alfa_romeo', 'asia', 'aston_martin', 'audi', 'bentley', 'bmw', 'byd', 'cadillac', 'changan',
                        'chevrolet', 'citroen', 'daewoo', 'daihatsu', 'datsun', 'fiat', 'ford', 'gaz', 'geely', 'haval',
                        'honda', 'hyundai', 'infiniti', 'isuzu', 'jaguar', 'jeep', 'kia', 'lada', 'land_rover', 'mazda',
                        'mercedes_benz', 'mitsubishi', 'nissan', 'opel', 'peugeot', 'peugeot_lnonum', 'porsche', 'renault',
                        'skoda', 'ssangyong', 'subaru', 'suzuki', 'toyota', 'uaz', 'volkswagen', 'volvo', 'zaz'
                    ] as $brand)
                        <div class="flex items-center justify-start space-x-2">
                            <input type="hidden" name="{{ $brand }}" value="0">
                            <input class="form-check-input h-5 w-5 text-blue-600 rounded" type="checkbox" name="{{ $brand }}" id="{{ $brand }}" value="1">
                            <label class="form-check-label text-gray-700" for="{{ $brand }}">{{ ucfirst(str_replace('_', ' ', $brand)) }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="bg-orange-100 p-4 rounded-xl mt-6">
                    <p class="text-gray-700">Мы запомним ваш выбор для последующих конверсий. Сбросить настройки можно в разделе <a href="{{ route('converter_set.edit') }}" class="text-blue-600 hover:underline">настройки конвертера.</a></p>
                </div>

                               <p class="text-gray-500 mt-4">Настройки 1 из 2</p>
               <button id="next-to-columns" type="button" class="mt-6 bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600 disabled:bg-gray-400 disabled:cursor-not-allowed" disabled>Продолжить</button>
            </form>
        </div>
        <!-- Страница выбора столбцов -->
     <div id="columns-page" class="page hidden text-center">
    <form id="import-columns-form" action="{{ route('cars.import') }}" method="POST" class="space-y-6">
        @csrf
       

         <div class="mb-4">
             <p class="mb-2 font-bold">Указать вид данных по:</p>
            <div class="inline-flex space-x-2 bg-gray-200 rounded-xl h-[3rem]">
            <button type="button" class="column-name-button button-active bg-blue-500 text-white font-semibold py-2 px-4 rounded-xl focus:outline-none h-[2.6rem] mt-1 ml-1" data-mode="name">По названию столбца</button>
            <button type="button" class="column-number-button button-inactive mt-1 mr-1 bg-gray-200 text-gray-700 font-semibold py-2 px-4 rounded-xl focus:outline-none h-[2.6rem] " data-mode="number">По номеру столбца</button>
        </div>
        <div class="mt-4 mb-4 flex items-center justify-center">
    <div class="flex items-center space-x-2 mr-36">
        <label for="header-row" class="block text-gray-700 font-semibold">Искать заголовки в</label>
        <select id="header-row" name="header_row" class="bg-gray-200 form-control border p-2 rounded">
            @for ($i = 1; $i <= 20; $i++)
                <option value="{{ $i }}">{{ $i }}</option>
            @endfor
        </select>
        <label class="block text-gray-700 font-semibold">строке таблицы</label>
    </div>
    <div class="flex items-center space-x-2">
         <button type="button" id="refresh-columns-button" class="bg-gray-200 text-white py-2 px-3 rounded-xl focus:outline-none flex items-center">
            <img id="loading-icon" src="{{ asset('images/load-icon.png') }}" alt="Загрузка" class="h-5 w-5">
              <i id="spinner-icon" class="fas fa-spinner fa-spin mr-2 hidden bg-black-500"></i>
        </button>
        <span id="refresh-text" class="block text-gray-700 font-semibold">Обновить данные о столбцах</span>
      </div>
</div>

        <h2 class="text-xl font-semibold mb-4">Пожалуйста, укажите вид данных для каждого найденного столбца</h2>
        <div class="bg-orange-100 p-4 rounded-xl">
            <p class="text-gray-700">Если в Вашем прайс-листе отсутствуют отдельный столбец(ы) с данными об автомобиле: марка, модель, год выпуска, модель двигателя, номер кузова, то поиск этих данных будет осуществляться в столбце “Наименование”.</p>
        </div>
        <div class="grid grid-cols-2 gap-4 mt-6" id="columns-container">
           <h2 class="text-lg font-semibold">Найденные столбцы в вашем файле</h2>
           <h2 class="text-lg font-semibold">Данные, которые содержит столбец</h2>
        </div>
        <div class="bg-orange-100 p-4 rounded-2xl mt-6">
                    <p class="text-gray-700">Мы запомним ваш выбор для последующих конверсий. Сбросить настройки можно в разделе <a href="{{ route('converter_set.edit') }}" class="text-blue-600 hover:underline">настройки конвертера.</a></p>
                </div>
        <div class="mt-6">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-xl hover:bg-blue-600">Конвертировать файл</button>
        </div>
        <p class="text-gray-500 mt-4">Настройки 2 из 2</p>
    </form>
    
</div>

    </div>
    
</div>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/search-form.js') }}" defer></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
<script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
     <script>
    document.addEventListener('DOMContentLoaded', function () {
        const convertButton = document.getElementById('convert-button');
        const modal = document.getElementById('modal');
        const closeModalButton = document.getElementById('close-modal');
        const settingsPage = document.getElementById('settings-page');
        const columnsPage = document.getElementById('columns-page');
        const nextToColumnsButton = document.getElementById('next-to-columns');
        const settingsForm = document.getElementById('settings-form');
        const checkboxes = document.querySelectorAll('#settings-form input[type="checkbox"]');

        // Проверка, выбраны ли настройки
        function checkSettings() {
            const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            nextToColumnsButton.disabled = !isAnyChecked;
        }

        // Обработчик изменения состояния чекбоксов
        checkboxes.forEach(checkbox => {
            checkbox.addEventListener('change', checkSettings);
        });

        // Обработчик для кнопки "Продолжить"
        if (nextToColumnsButton) {
            nextToColumnsButton.addEventListener('click', function () {
                const formData = new FormData(settingsForm);

                // Отправляем данные формы настроек
                fetch(settingsForm.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    }
                })
                    .then(response => {
                        if (response.ok) {
                            // Успешно сохранено, переходим на страницу выбора столбцов
                            settingsPage.classList.remove('active');
                            columnsPage.classList.add('active');
                        } else {
                            Toastify({
                                text: 'Ошибка при сохранении настроек.',
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Toastify({
                            text: 'Произошла ошибка при сохранении настроек.',
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            stopOnFocus: true,
                        }).showToast();
                    });
            });
        }

      // Обработчик для кнопки "Открыть файл"
if (convertButton) {
    convertButton.addEventListener('click', function () {
        // Проверяем текст кнопки
        if (convertButton.textContent.trim() === "Конвертировать файл") {
            // Выполняем всю логику, но не открываем модальное окно
            fetch("{{ route('get.settings') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
                .then(response => response.json())
                .then(settings => {
                    // Если настройки не найдены, показываем страницу настроек
                    if (!settings.exists) {
                        settingsPage.classList.add('active');
                        columnsPage.classList.remove('active');
                        settingsPage.classList.add('hidden'); //Скрываем, т.к. не открываем модалку
                    } else {
                        // Если настройки найдены, показываем страницу выбора столбцов
                        settingsPage.classList.remove('active');
                        columnsPage.classList.add('active');
                        columnsPage.classList.add('hidden'); //Скрываем, т.к. не открываем модалку
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Toastify({
                        text: 'Произошла ошибка при загрузке настроек.',
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                });

            return; // Прерываем выполнение, чтобы не открывать модальное окно
        }

        // Показываем модальное окно (если текст кнопки не "Конвертировать файл")
        modal.classList.remove('hidden');

        // Проверяем наличие настроек
        fetch("{{ route('get.settings') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
            .then(response => response.json())
            .then(settings => {
                // Если настройки не найдены, показываем страницу настроек
                if (!settings.exists) {
                    settingsPage.classList.add('active');
                    columnsPage.classList.remove('active');
                } else {
                    // Если настройки найдены, показываем страницу выбора столбцов
                    settingsPage.classList.remove('active');
                    columnsPage.classList.add('active');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Toastify({
                    text: 'Произошла ошибка при загрузке настроек.',
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    stopOnFocus: true,
                }).showToast();
            });
    });
}


        // Закрытие модального окна
        if (closeModalButton) {
            closeModalButton.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
        }
    });
</script>
<script>

document.addEventListener('DOMContentLoaded', function () {
    const convertButton = document.getElementById('convert-button');
    const modal = document.getElementById('modal');
    const closeModalButton = document.getElementById('close-modal');
    const convertForm = document.getElementById('convert-form');
    const fileInput = document.getElementById('file');
    const refreshColumnsButton = document.getElementById('refresh-columns-button');
    const headerRowSelect = document.getElementById('header-row');
    const removeDuplicatesCheckbox = document.getElementById('remove_duplicates');
    const archiveProductsCheckbox = document.getElementById('archive_products');

    let savedColumnMappings = null;
    let isConvertButton = false;
     let id_filial = null; // Инициализируем переменную
     
     const branchSelect = document.getElementById('branch');

        if (branchSelect) { // Проверяем, существует ли элемент select
            branchSelect.addEventListener('change', function() {
                id_filial = this.value;  // Обновляем id_filial при изменении выбора
                console.log("Выбранный id_filial:", id_filial); // Для отладки
            });
        }

    function getSettingsFromForm() {
        const checkboxes = document.querySelectorAll('#settings-form input[type="checkbox"]');
        const selectedBrands = [];
        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
              selectedBrands.push(checkbox.name);
            }
        });
        return selectedBrands;
    }

    function checkSavedSettings() {
        fetch("{{ route('check.column.mappings') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
        })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`Ошибка HTTP: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.exists) {
                    // Заменяем кнопку "Открыть файл" на "Конвертировать файл"
                    convertButton.textContent = 'Конвертировать файл';
                    convertButton.classList.remove('bg-blue-500', 'hover:bg-blue-600');
                    convertButton.classList.add('bg-green-500', 'hover:bg-green-600');
                    isConvertButton = true;

                    // Получаем сохраненные mappings
                    fetch("{{ route('get.column.mappings') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                    })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error(`Ошибка HTTP: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(mappingData => {
                            if (mappingData.mappings) {
                                savedColumnMappings = mappingData.mappings;
                            }
                        })
                        .catch(error => {
                            console.error('Ошибка при получении сохраненных настроек:', error);
                             Toastify({
                                text: `Произошла ошибка при получении сохраненных настроек, повторите попытку позже`,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();
                        });
                } else {
                    isConvertButton = false;
                    convertButton.textContent = 'Открыть файл';
                    convertButton.classList.add('bg-blue-500', 'hover:bg-blue-600');
                    convertButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                }
            })
            .catch(error => {
                console.error('Ошибка при проверке сохраненных настроек:', error);
                 Toastify({
                                text: `Произошла ошибка при проверке сохраненных настроек, повторите попытку позже`,
                                duration: 3000,
                                close: true,
                                gravity: "top",
                                position: "right",
                                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                stopOnFocus: true,
                            }).showToast();
            });
    }

    checkSavedSettings();

   // Обработчик для кнопки "Обновить список столбцов"
    
    if (refreshColumnsButton) {
        const loadingIcon = document.getElementById('loading-icon');
        const spinnerIcon = document.getElementById('spinner-icon');
        refreshColumnsButton.addEventListener('click', function() {
           loadingIcon.classList.add('hidden');
           spinnerIcon.classList.remove('hidden');


           const form = document.getElementById('convert-form');
           const formData = new FormData(form);
           const headerRow = headerRowSelect.value; // Получаем выбранную строку заголовков

            // Get the values from the form
            let skip_rows = 0;  // Assuming you want to hardcode this.
            let csv_encoding = "auto";
            let csv_delimiter = ",";

            // Create URLSearchParams object for the query parameters
            const urlParams = new URLSearchParams({
                skip_rows: skip_rows,
                csv_encoding: csv_encoding,
                csv_delimiter: csv_delimiter
            });

           const url = `https://xn--80aahefm1drj1c4c.xn--p1ai/converter/get_columns?${urlParams.toString()}`;


           fetch(url, {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                    }
                    return response.json();
                })
                 .then(columns => {
                    loadingIcon.classList.remove('hidden');
                    spinnerIcon.classList.add('hidden');

                     // code columns
                       const columnsContainer = document.getElementById('columns-container');
                    columnsContainer.innerHTML = '';

                    const columnNamesDiv = document.createElement('div');
                    columnNamesDiv.className = 'col-span-1';

                    const h2Columns = document.createElement('h2');
                    h2Columns.textContent = 'Найденные столбцы в вашем файле';
                    h2Columns.className = 'text-xl font-bold mb-4';
                    columnsContainer.appendChild(h2Columns);

                    const h2Data = document.createElement('h2');
                    h2Data.textContent = 'Данные которые содержит столбец';
                    h2Data.className = 'text-xl font-bold mb-4';
                    columnsContainer.appendChild(h2Data);

                    const selectDiv = document.createElement('div');
                    selectDiv.className = 'col-span-1';

                    columns.forEach((column, index) => {
                        const labelDiv = document.createElement('div');
                        labelDiv.className = 'border border-gray-300 h-10 mb-4';

                        const label = document.createElement('label');
                        label.className = 'block text-gray-700 column-label';
                        label.textContent = column;
                        label.dataset.originalText = column;
                        labelDiv.appendChild(label);
                        columnNamesDiv.appendChild(labelDiv);

                        const select = document.createElement('select');
                        select.className = 'form-control border h-10 w-full';
                        select.name = column;

                        const options = {
                            'Выберите поле': 'none',
                            'Артикул': 'art_number',
                            'Название товара': 'product_name',
                            'Состояние': 'new_used',
                            'Марка': 'brand',
                            'Модель': 'model',
                            'Кузов': 'body',
                            'Номер запчасти': 'number',
                            'Номер двигателя': 'engine',
                            'Год': 'year',
                            'Расположение Л_П': 'L_R',
                            'Расположение Сп_Сз': 'F_R',
                            'Расположение Св_Сн': 'U_D',
                            'Цвет': 'color',
                            'Применимость': 'applicability',
                            'Количество': 'quantity',
                            'Цена': 'price',
                            'Доступность': 'availability',
                            'Время доставки': 'delivery_time',
                            'Главное фото': 'main_photo_url',
                            'Фото1': 'additional_photo_url_1',
                            'Фото2': 'additional_photo_url_2',
                            'Фото3': 'additional_photo_url_3'
                        };
                        for (let key in options) {
                            const optionElement = document.createElement('option');
                            optionElement.textContent = key;
                            optionElement.setAttribute("value", options[key]);
                            select.appendChild(optionElement);
                        }

                        const selectDivWrapper = document.createElement('div');
                        selectDivWrapper.className = 'mb-4';
                        selectDivWrapper.appendChild(select);

                         selectDiv.appendChild(selectDivWrapper);
                    });

                    columnsContainer.appendChild(columnNamesDiv);
                    columnsContainer.appendChild(selectDiv);

                       // Инициализируем переключение столбцов
                     initializeColumnSwitcher();
                })
                 .catch(error => {
                    loadingIcon.classList.remove('hidden');
                    spinnerIcon.classList.add('hidden');

                    console.error('Ошибка при загрузке файла:', error);
                     Toastify({
                        text: `Произошла ошибка при загрузке файла, повторите попытку позже`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    }).showToast();
                });
        });
    }


// Обработчик для кнопки "Открыть файл"
    convertButton.addEventListener('click', function () {
        if (!isConvertButton) {
           const form = document.getElementById('convert-form');
            const formData = new FormData(form);
           const headerRow = headerRowSelect.value; // Получаем выбранную строку заголовков

             // Get the values from the form
            let skip_rows = 0;  // Assuming you want to hardcode this.
            let csv_encoding = "auto";
            let csv_delimiter = ",";

            // Create URLSearchParams object for the query parameters
            const urlParams = new URLSearchParams({
                skip_rows: skip_rows,
                csv_encoding: csv_encoding,
                csv_delimiter: csv_delimiter
            });

           const url = `https://xn--80aahefm1drj1c4c.xn--p1ai/converter/get_columns?${urlParams.toString()}`;

           fetch(url, {
                method: 'POST',
                body: formData,
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                    }
                    return response.json();
                })
                .then(columns => {
                    const columnsContainer = document.getElementById('columns-container');
                    columnsContainer.innerHTML = '';

                    const columnNamesDiv = document.createElement('div');
                    columnNamesDiv.className = 'col-span-1';

                    const h2Columns = document.createElement('h2');
                    h2Columns.textContent = 'Найденные столбцы в вашем файле';
                    h2Columns.className = 'text-xl font-bold mb-4';
                    columnsContainer.appendChild(h2Columns);

                    const h2Data = document.createElement('h2');
                    h2Data.textContent = 'Данные которые содержит столбец';
                    h2Data.className = 'text-xl font-bold mb-4';
                    columnsContainer.appendChild(h2Data);

                    const selectDiv = document.createElement('div');
                    selectDiv.className = 'col-span-1';

                    columns.forEach((column, index) => {
                        const labelDiv = document.createElement('div');
                        labelDiv.className = 'border border-gray-300 h-10 mb-4';

                        const label = document.createElement('label');
                        label.className = 'block text-gray-700 column-label';
                        label.textContent = column;
                        label.dataset.originalText = column;
                        labelDiv.appendChild(label);
                        columnNamesDiv.appendChild(labelDiv);

                        const select = document.createElement('select');
                        select.className = 'form-control border h-10 w-full';
                        select.name = column;

                        const options = {
                            'Выберите поле': 'none',
                            'Артикул': 'art_number',
                            'Название товара': 'product_name',
                            'Состояние': 'new_used',
                            'Марка': 'brand',
                            'Модель': 'model',
                            'Кузов': 'body',
                            'Номер запчасти': 'number',
                            'Номер двигателя': 'engine',
                            'Год': 'year',
                            'Расположение Л_П': 'L_R',
                            'Расположение Сп_Сз': 'F_R',
                            'Расположение Св_Сн': 'U_D',
                            'Цвет': 'color',
                            'Применимость': 'applicability',
                            'Количество': 'quantity',
                            'Цена': 'price',
                            'Доступность': 'availability',
                            'Время доставки': 'delivery_time',
                            'Главное фото': 'main_photo_url',
                            'Фото1': 'additional_photo_url_1',
                            'Фото2': 'additional_photo_url_2',
                            'Фото3': 'additional_photo_url_3'
                        };
                        for (let key in options) {
                            const optionElement = document.createElement('option');
                            optionElement.textContent = key;
                            optionElement.setAttribute("value", options[key]);
                            select.appendChild(optionElement);
                        }

                        const selectDivWrapper = document.createElement('div');
                        selectDivWrapper.className = 'mb-4';
                        selectDivWrapper.appendChild(select);

                         selectDiv.appendChild(selectDivWrapper);
                    });

                    columnsContainer.appendChild(columnNamesDiv);
                    columnsContainer.appendChild(selectDiv);

                     // Инициализируем переключение столбцов
                     initializeColumnSwitcher();

                    // Показываем модальное окно
                    modal.classList.remove('hidden');

             
   // Обработчик для кнопки "Обновить список столбцов"
if (refreshColumnsButton) {
    console.log("Кнопка существует:", refreshColumnsButton);
    console.log("Привязываю обработчик к кнопке");

    refreshColumnsButton.addEventListener('click', function() {
        const form = document.getElementById('convert-form');
        const formData = new FormData(form);
        const headerRow = headerRowSelect.value; // Получаем выбранную строку заголовков
        
        // Вычисляем skip_rows как headerRow - 1 (так как нумерация строк обычно начинается с 0)
        let skip_rows = parseInt(headerRow) - 1;
        let csv_encoding = "auto";
        let csv_delimiter = ",";

        // Create URLSearchParams object for the query parameters
        const urlParams = new URLSearchParams({
            skip_rows: skip_rows,
            csv_encoding: csv_encoding,
            csv_delimiter: csv_delimiter
        });

          const url = `https://xn--80aahefm1drj1c4c.xn--p1ai/converter/get_columns?${urlParams.toString()}`;

        fetch(url, {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
            }
            return response.json();
        })
        .then(columns => {
            console.log("Ответ от сервера:", columns);
            const columnsContainer = document.getElementById('columns-container');
            columnsContainer.innerHTML = '';

            const columnNamesDiv = document.createElement('div');
            columnNamesDiv.className = 'col-span-1';

            const h2Columns = document.createElement('h2');
            h2Columns.textContent = 'Найденные столбцы в вашем файле';
            h2Columns.className = 'text-xl font-bold mb-4';
            columnsContainer.appendChild(h2Columns);

            const h2Data = document.createElement('h2');
            h2Data.textContent = 'Данные которые содержит столбец';
            h2Data.className = 'text-xl font-bold mb-4';
            columnsContainer.appendChild(h2Data);

            const selectDiv = document.createElement('div');
            selectDiv.className = 'col-span-1';

            columns.forEach((column, index) => {
                const labelDiv = document.createElement('div');
                labelDiv.className = 'border border-gray-300 h-10 mb-4';

                const label = document.createElement('label');
                label.className = 'block text-gray-700 column-label';
                label.textContent = column;
                label.dataset.originalText = column;
                labelDiv.appendChild(label);
                columnNamesDiv.appendChild(labelDiv);

                const select = document.createElement('select');
                select.className = 'form-control border h-10 w-full';
                select.name = column;

                const options = {
                    'Выберите поле': 'none',
                    'Артикул': 'art_number',
                    'Название товара': 'product_name',
                    'Состояние': 'new_used',
                    'Марка': 'brand',
                    'Модель': 'model',
                    'Кузов': 'body',
                    'Номер запчасти': 'number',
                    'Номер двигателя': 'engine',
                    'Год': 'year',
                    'Расположение Л_П': 'L_R',
                    'Расположение Сп_Сз': 'F_R',
                    'Расположение Св_Сн': 'U_D',
                    'Цвет': 'color',
                    'Применимость': 'applicability',
                    'Количество': 'quantity',
                    'Цена': 'price',
                    'Доступность': 'availability',
                    'Время доставки': 'delivery_time',
                    'Главное фото': 'main_photo_url',
                    'Фото1': 'additional_photo_url_1',
                    'Фото2': 'additional_photo_url_2',
                    'Фото3': 'additional_photo_url_3'
                };
                for (let key in options) {
                    const optionElement = document.createElement('option');
                    optionElement.textContent = key;
                    optionElement.setAttribute("value", options[key]);
                    select.appendChild(optionElement);
                }

                const selectDivWrapper = document.createElement('div');
                selectDivWrapper.className = 'mb-4';
                selectDivWrapper.appendChild(select);

                selectDiv.appendChild(selectDivWrapper);
            });

            columnsContainer.appendChild(columnNamesDiv);
            columnsContainer.appendChild(selectDiv);

            // Инициализируем переключение столбцов
            initializeColumnSwitcher();
        })
        .catch(error => {
            console.error('Ошибка при загрузке файла:', error);
            Toastify({
                text: `Произошла ошибка при загрузке файла, повторите попытку позже`,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                stopOnFocus: true,
            }).showToast();
        });
    });
}
})
                .catch(error => {
                    console.error('Ошибка при загрузке файла:', error);
                    Toastify({
                        text: `Произошла ошибка при загрузке файла, повторите попытку позже`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                });
        }
       else {
          // Если кнопка "Конвертировать файл", то отправляем форму импорта сразу
            if (fileInput.files.length > 0) {
                importColumnsForm.dispatchEvent(new Event('submit', { cancelable: true }));
            } else {
                  Toastify({
                    text: 'Выберите файл для конвертации.',
                    duration: 3000,
                    close: true,
                    gravity: "top",
                    position: "right",
                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                    stopOnFocus: true,
                }).showToast();
            }
        }
    });


    // Закрытие модального окна
    if (closeModalButton) {
        closeModalButton.addEventListener('click', function () {
            modal.classList.add('hidden');
        });
    }

    // Обработчик события submit для формы импорта
    const importColumnsForm = document.getElementById('import-columns-form');

    if (importColumnsForm) {
        importColumnsForm.addEventListener('submit', function (event) {
            event.preventDefault();

            const fileInput = document.getElementById('file');

            // Используем сохраненные настройки
            let columns_dict = {};
            if (savedColumnMappings) {
                columns_dict = savedColumnMappings;
            } else {
                const html_columns = importColumnsForm.querySelectorAll('.col-span-1');
                const column_names = html_columns[0];
                const column_name_options = html_columns[1];

                for (let i = 0; i < column_names.childElementCount; i++) {
                    const name_from_file = column_names.children[i].textContent;
                    const name_from_db = column_name_options.children[i].firstChild.value;
                    columns_dict[name_from_db] = name_from_file;
                }
            }

            // Сохраняем выбор столбцов
            if (!savedColumnMappings) {
                 fetch('/save-column-mappings', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        file_name: fileInput.files[0].name,
                        column_mappings: columns_dict,
                    }),
                })
                     .then(response => {
                        if (!response.ok) {
                            throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                         }
                         Toastify({
                             text: 'Соответствия успешно сохранены.',
                            duration: 3000,
                            close: true,
                            gravity: "top",
                             position: "right",
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                            stopOnFocus: true,
                          }).showToast();
                        return response.json();
                     })
                    .catch(error => {
                        console.error('Ошибка при сохранении соответствий:', error);
                           Toastify({
                            text: `Произошла ошибка при сохранении соответствий, повторите попытку позже`,
                            duration: 3000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                            stopOnFocus: true,
                        }).showToast();
                    });
            }

              fetch("{{ route('get.settings') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
              })
              .then(response => {
                if (!response.ok) {
                    throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                }
                return response.json();
              })
              .then(settings => {  // `settings` теперь содержит данные, возвращенные контроллером

                const userId = {{ auth()->id() }};
                const columns_str = JSON.stringify(columns_dict);
                   const skipRows = parseInt(headerRowSelect.value) - 1; // Используем выбранное значение из dropdown
                const csv_encoding = "auto";
                const csv_delimiter = ",";
                const addSheetNameToProductName = true;
                const extractDataFromProductName = true;
                //const remove_duplicates = true; // Removed fixed value
                const skip_empty_price_rows = true;
                //const deactivate_old_ad = true; // Removed fixed value
                const split_symbols = [",", "/", " ", ".", "(", ")", "\\", '"'];

                // **NEW: Get the values from the checkboxes**
                const remove_duplicates = removeDuplicatesCheckbox.checked;
                const deactivate_old_ad = archiveProductsCheckbox.checked;

                // Получаем бренды из ответа и преобразуем в JSON-строку
                const selected_brands = JSON.stringify(settings.settings); // settings.settings должно содержать массив брендов.
                console.log("Selected brands from API:", selected_brands);  // Проверяем, что получили

                const headerRow = headerRowSelect.value;

                const formData = new FormData();
                formData.append("file", document.getElementById('file').files[0]);
                formData.append("header_row", headerRow);

                const url = new URL('https://xn--80aahefm1drj1c4c.xn--p1ai/converter/upload');
                url.searchParams.append("user_id", userId);
                url.searchParams.append("id_filial", id_filial);
                url.searchParams.append("columns", columns_str);
                url.searchParams.append("skip_rows", skipRows);
                url.searchParams.append("csv_encoding", csv_encoding);
                url.searchParams.append("csv_delimiter", csv_delimiter);
                url.searchParams.append("add_sheet_name_to_product_name", addSheetNameToProductName);
                url.searchParams.append("extract_data_from_product_name", extractDataFromProductName);
                url.searchParams.append("skip_empty_price_rows", skip_empty_price_rows);
                url.searchParams.append("deactivate_old_ad", deactivate_old_ad);
                url.searchParams.append("selected_brands", selected_brands);  // Передаем JSON строку в URL
                url.searchParams.append("split_symbols", split_symbols);
                url.searchParams.append("remove_duplicates", remove_duplicates);

                console.log('FormData:', formData);
                console.log('URL:', url.toString());

                fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                    }
                   return response.json();
                })
                .then((result) => {
                    console.log("Response", result);
                       Toastify({
                        text: `Добавлено в очередь на обработку. Ваша позиция в очереди: ${result["queue_position"]}`,
                        duration: 5000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)",
                        stopOnFocus: true,
                    }).showToast();

                    const task_id = result["task_id"];
                    setTimeout(() => {
                        fetch("https://xn--80aahefm1drj1c4c.xn--p1ai/converter/get_status?task_id=" + task_id, {
                            method: 'GET'
                        })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error(`Произошла ошибка при конвертации файла, повторите попытку позже`);
                                }
                                return response.json();
                                                })
                                                                 .then(data => {
                        // Создаем объект для перевода статусов
                        const statusTranslations = {
                            'done': 'Готово',
                            'error': 'Ошибка',
                            // Можно добавить другие статусы при необходимости
                        };
                        
                        // Получаем переведенный статус или оставляем оригинальный, если перевода нет
                        const translatedStatus = statusTranslations[data.status] || data.status;
                        
                        // Формируем текст уведомления
                        let notificationText = `Статус вашего запроса: ${translatedStatus}`;
                        
                        // Если статус "done" и есть данные о количестве товаров, добавляем эту информацию
                        if (data.status === 'done' && data.lines_added !== undefined) {
                            notificationText += `\nЗагружено товаров: ${data.lines_added}`;
                        }
                        
                        Toastify({
                            text: notificationText,
                            duration: 5000,
                            close: true,
                            gravity: "top",
                            position: "right",
                            backgroundColor: data.status === 'done' 
                                ? "linear-gradient(to right, #00b09b, #96c93d)" 
                                : "linear-gradient(to right, #ff5f6d, #ffc371)",
                            stopOnFocus: true,
                        }).showToast();
                        
                        console.log("Данные ответа:", data);
                    })
                            .catch(error => {
                                console.error('Ошибка при получении статуса:', error);
                                 Toastify({
                                    text: `Произошла ошибка при получении статуса, повторите попытку позже`,
                                    duration: 3000,
                                    close: true,
                                    gravity: "top",
                                    position: "right",
                                    backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                                    stopOnFocus: true,
                                }).showToast();
                            });
                    }, 1000);
                })
                .catch((error) => {
                    console.error('Ошибка при конвертации файла:', error);
                     Toastify({
                        text: `Произошла ошибка при конвертации файла, повторите попытку позже`,
                        duration: 3000,
                        close: true,
                        gravity: "top",
                        position: "right",
                        backgroundColor: "linear-gradient(to right, #ff5f6d, #ffc371)",
                        stopOnFocus: true,
                    }).showToast();
                });
            });
        });
    }
});

function initializeColumnSwitcher() {
    const columnNameButton = document.querySelector('.column-name-button');
    const columnNumberButton = document.querySelector('.column-number-button');
    const columnsContainer = document.getElementById('columns-container');

        // Сохраняем оригинальные названия для каждого столбца
        const columnLabels = columnsContainer.querySelectorAll('.column-label');
        columnLabels.forEach(label => {
            label.dataset.originalText = label.textContent;
        });

    // Функция для обновления отображения столбцов
    function updateColumnsDisplay(useNumbers) {
        columnLabels.forEach((label, index) => {
            if (useNumbers) {
                label.textContent = `${index + 1} столбец (Столбец ${String.fromCharCode(65 + index)})`;
            } else {
                label.textContent = label.dataset.originalText;
            }
        });
    }

     // Устанавливаем начальное состояние кнопок
    columnNameButton.classList.add('button-active'); // По умолчанию "По названию столбца" активен
    columnNumberButton.classList.remove('button-active');

    // Обработчик для кнопки "По номеру столбца"
    columnNumberButton.addEventListener('click', function () {
        columnNumberButton.setAttribute('data-mode', 'number');
        columnNameButton.setAttribute('data-mode', 'name');
        updateColumnsDisplay(true);
         columnNumberButton.classList.add('button-active');
         columnNameButton.classList.remove('button-active');
    });

    // Обработчик для кнопки "По названию столбца"
    columnNameButton.addEventListener('click', function () {
        columnNameButton.setAttribute('data-mode', 'name');
        columnNumberButton.setAttribute('data-mode', 'number');
        updateColumnsDisplay(false);
         columnNameButton.classList.add('button-active');
          columnNumberButton.classList.remove('button-active');

    });
}

</script>

</body>
</html>