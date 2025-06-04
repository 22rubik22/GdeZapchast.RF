<script src="https://cdn.tailwindcss.com"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
<style>

    /* На ноутбуках и ПК (ширина экрана от 1024px и выше) */
@media (min-width: 1024px) {
    #main-form {
        flex-wrap: nowrap; 
    }
}
  @media (max-width: 767px) {
        /* Скругление углов для инпутов и кнопки */
       .wrap{
            border-radius: 8px; /* Скругление углов */
            margin-bottom: 10px; /* Отступ снизу */
        }

        /* Убираем отступ снизу у последнего элемента, чтобы не было лишнего отступа */
        #main-form button {
            margin-bottom: 0;
        }
    }
    
      @media (max-width: 767px) {
        /* Скругление углов для инпутов и кнопки */
        #main-form input,
        #main-form button {
            border-radius: 8px; /* Скругление углов */
            
        }

        /* Убираем отступ снизу у последнего элемента, чтобы не было лишнего отступа */
        #main-form button {
            margin-bottom: 0;
        }
    }

    #big-form {
        background-color: #5e94f7; 
    }

    #main-form {
        background-color: #5e94f7;
        padding: 20px;
        border-radius: 15px;
        width: 100%;
    }

    #brand-dropdown,
    #model-dropdown,
    #year-dropdown {
        display: none;
        position: absolute;
        top: 100%;
        left: 0;
        background-color: #ffffff;
        width: 100%;
        box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
        border: 1px solid #e2e8f0;
        overflow: hidden;
        max-height: 150px;
        overflow-y: auto;
        z-index: 10;
        margin-top: 5px;
    }

    #brand-dropdown div,
    #model-dropdown div,
    #year-dropdown div {
        padding: 10px 16px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        font-size: 14px;
        color: #4a5568;
    }
    
    #search_query{
        border-radius:0.5rem 0 0 0.5rem;
    }
    
    #show-button{
         border-radius:0 0.5rem 0.5rem 0;
    }
    
  
    
    #brand-input,
    #model-input,
    #year-input {
        text-align: center;
        color: black;
        font-weight: bold;
    }

    #brand-dropdown div:hover,
    #model-dropdown div:hover,
    #year-dropdown div:hover {
        background-color: #f3f4f6;
    }

    #brand-dropdown.show,
    #model-dropdown.show,
    #year-dropdown.show {
        display: block;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    #select-all-mods, #deselect-all-mods {
        padding: 5px 10px;
        border: 1px solid #3b82f6;
        border-radius: 5px;
        background-color: #eff6ff;
        cursor: pointer;
        transition: background-color 0.3s ease;
    }

    #select-all-mods:hover, #deselect-all-mods:hover {
        background-color: #dbeafe;
    }

    #modifications-buttons {
        transition: opacity 0.3s ease;
    }

    #modifications-buttons.hidden {
        opacity: 0;
        pointer-events: none;
    }

    #modifications-buttons:not(.hidden) {
        opacity: 1;
        pointer-events: auto;
    }
</style>
</head>
<body class="bg-blue-500 flex items-center justify-center min-h-screen">
<div class="ad-list w-full mt-4 max-w-6xl rounded-none md:rounded-[15px]" id="big-form">
    <h2 class="text-3xl font-bold text-left text-white p-4">
        Поиск запчастей
    </h2>
    <form action="#" class="flex flex-wrap items-center" id="main-form" method="GET">
        <input name="city" type="hidden" value=""/>
        <!-- Search Input -->
        <input class="wrap cursor-pointer md:w-[calc(100%-20px)] lg:w-[300px] flex-grow px-3 py-2 mr-0.5 border border-gray-300 rounded-lg focus:outline-none focus:ring focus:ring-blue-500 text-gray-500 md:text-sm" id="search_query" name="search_query" placeholder="Введите название или номер детали" type="text" value="">
        <!-- Brand Input with Autocomplete -->
        <div class="wrap relative flex items-center w-full md:w-[calc(50%-10px)] lg:w-[180px] mr-0.5">
            <div class="input-wrapper w-full">
                <img src="/images/Car.png" alt="Car Icon" class="absolute w-[20px] h-[20px] mt-2 ml-2">
                <input class="w-full cursor-pointer flex-grow px-3 py-2 border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 md:text-sm" id="brand-input" name="brand_input" type="text" value="Марка"/>
            </div>
            <input id="brand" name="brand" type="hidden" value=""/>
            <button class="bg-[#dfeaff] absolute right-0 h-[100%] w-[10%] border-[#dfeaff] text-gray-500 focus:outline-none" id="brand-dropdown-button" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="hidden" id="brand-dropdown"></div>
        </div>
        <!-- Model Input with Autocomplete -->
        <div class="wrap relative flex items-center w-full md:w-[calc(50%-10px)] lg:w-[180px] mr-0.5">
            <input class="w-full cursor-pointer flex-grow px-3 py-2 border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 md:text-sm" id="model-input" name="model_input" type="text" value="Модель">
            <input id="model" name="model" type="hidden" value=""/>
            <button class="bg-[#dfeaff] absolute right-0 text-gray-500 focus:outline-none h-[100%] w-[10%] border-[#dfeaff]" id="model-dropdown-button" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="hidden" id="model-dropdown"></div>
        </div>
        <!-- Year Select -->
        <div class="wrap relative flex items-center w-full md:w-[calc(50%-10px)] lg:w-[180px] mr-0.5">
            <input class="w-full cursor-pointer flex-grow px-3 py-2 border border-gray-300 focus:outline-none focus:ring focus:ring-blue-500 md:text-sm" id="year-input" name="year_input" readonly="" type="text" value="Год выпуска">
            <input id="year" name="year" type="hidden" value=""/>
            <button class="bg-[#dfeaff] absolute right-0 text-gray-500 focus:outline-none h-[100%] w-[10%] border-[#dfeaff]" id="year-dropdown-button" type="button">
                <i class="fas fa-chevron-down"></i>
            </button>
            <div class="hidden" id="year-dropdown"></div>
        </div>
        <!-- Show Button -->
        <button class="bg-[#e1ffdb] hover:bg-[#D5FFCD] font-bold py-2 px-4 rounded-lg border border-gray-300 focus:outline-none focus:shadow-outline md:text-sm w-full md:w-auto" id="show-button" type="button">
            Показать
        </button>
    </form>
</div>
    <!-- Import jQuery and Other JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
   <script>
    $(document).ready(function() {
        // Глобальные переменные для хранения данных
        let brandsData = [];
        let modelsData = [];

        // Настройка автодополнения для поля поиска запчастей
        $('#search_query').autocomplete({
            source: function(request, response) {
                var term = request.term.trim();
                $.ajax({
                    url: '{{ route('get.parts') }}',
                    type: 'GET',
                    data: { term: term },
                    success: function(data) {
                        if (term === "") {
                            response(data); // Показываем весь список, если поле пустое
                        } else {
                            response($.ui.autocomplete.filter(data, term)); // Фильтруем список по введенному значению
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            }
        });


$('#show-button').on('click', function(event) {
    // Проверяем, заполнено ли хотя бы одно поле
    if (isFormEmpty()) {
        alert('Пожалуйста, заполните хотя бы одно поле.');
        event.preventDefault();
        window.location.href = '{{ route('home') }}';
        return;
    }
    
    // Сохраняем выбранные модификации
    saveSelectedModifications();
    
    // Получаем данные формы
    const formData = $('#main-form').serialize();
    
    // Перенаправляем на страницу загрузки
    window.location.href = "{{ route('adverts.search.loading') }}?" + formData;
});

        // Функция для проверки, заполнено ли хотя бы одно поле
        function isFormEmpty() {
            var searchQuery = $('#search_query').val().trim();
            var brandInput = $('#brand-input').val().trim();
            var modelInput = $('#model-input').val().trim();
            var yearInput = $('#year-input').val().trim();

            // Проверяем, что значения не равны значениям по умолчанию
            var defaultBrandValue = 'Марка';
            var defaultModelValue = 'Модель';
            var defaultYearValue = 'Год выпуска';

            return !searchQuery && (brandInput === defaultBrandValue || !brandInput) && (modelInput === defaultModelValue || !modelInput) && (yearInput === defaultYearValue || !yearInput);
        }

        // Функция для обновления моделей
        function updateModels(brand) {
            $('#model-input').val(''); // Очищаем поле модели
            $('#model').val(''); // Очищаем скрытое поле модели
            $('#model-dropdown').empty(); // Очищаем выпадающий список моделей

            // Загружаем модели для новой марки
            loadModels(brand);
        }

        // Загрузка данных для моделей
        function loadModels(brand) {
            if (brand) {
                $.ajax({
                    url: '{{ route('get.models') }}',
                    type: 'GET',
                    data: { brand: brand },
                    success: function(data) {
                        modelsData = data; // Сохраняем данные
                        filterModels(''); // Инициализируем выпадающий список
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            }
        }

        // Обработчик для изменения марки
        $('#brand-input').on('change', function() {
            var brand = $(this).val();
            $('#brand').val(brand); // Устанавливаем значение в скрытое поле
            updateModels(brand); // Обновляем список моделей
        });
        // Обработчик для изменения модели
        $('#model-input').on('input', function() {
            const term = $(this).val().trim();
            $('#model').val(term); // Устанавливаем значение в скрытое поле
            filterModels(term); // Фильтруем модели
        });
        // Обработчик для фокуса на поле ввода марки
        $('#brand-input').on('focus', function() {
            $('#brand-dropdown').removeClass('hidden').addClass('show');
            if ($('#brand-dropdown').is(':empty')) {
                loadBrands();
            }
        });

        // Обработчик для фокуса на поле ввода модели
        $('#model-input').on('focus', function() {
            $('#model-dropdown').removeClass('hidden').addClass('show');
            if ($('#model-dropdown').is(':empty')) {
                loadModels();
            }
        });

        // Функция для загрузки списка марок
        function loadBrands() {
            $.ajax({
                url: '{{ route('get.brands') }}',
                type: 'GET',
                success: function(data) {
                    brandsData = data; // Сохраняем данные
                    filterBrands(''); // Инициализируем выпадающий список
                },
                error: function(xhr, status, error) {
                    console.error("AJAX Error: ", status, error);
                }
            });
        }

        // Фильтрация и отображение марок
        function filterBrands(term) {
            const filteredData = brandsData.filter(item =>
                item.brand.toLowerCase().includes(term.toLowerCase())
            ).sort((a, b) => {
                const aStartsWith = a.brand.toLowerCase().startsWith(term.toLowerCase());
                const bStartsWith = b.brand.toLowerCase().startsWith(term.toLowerCase());
                if (aStartsWith && !bStartsWith) return -1;
                if (!aStartsWith && bStartsWith) return 1;
                return 0;
            });

            $('#brand-dropdown').empty(); // Очищаем список

            // Фильтруем марки с объявлениями (advert_count > 0)
            const brandsWithAds = filteredData.filter(brand => brand.advert_count > 0);
            // Остальные марки (advert_count === 0)
            const brandsWithoutAds = filteredData.filter(brand => brand.advert_count === 0);

            // Сортируем марки с объявлениями по количеству объявлений (по убыванию)
            brandsWithAds.sort((a, b) => b.advert_count - a.advert_count);

            // Берем только топ-5 марок с объявлениями
            const topBrands = brandsWithAds.slice(0, 5);
            // Остальные марки с объявлениями (не попавшие в топ-5)
            const otherBrandsWithAds = brandsWithAds.slice(5);

            // Добавляем топ-5 марок (жирным шрифтом)
            topBrands.forEach(brand => {
                $('#brand-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100 font-bold" data-value="' + brand.brand + '">' + brand.brand + ' <span class="text-black-500">(' + brand.advert_count + ')</span></div>');
            });

            // Если есть остальные марки с объявлениями или без, добавляем разделитель
            if (otherBrandsWithAds.length > 0 || brandsWithoutAds.length > 0) {
                $('#brand-dropdown').append('<div class="border-t border-gray-200 my-1"></div>'); // Горизонтальная линия
            }

            // Добавляем остальные марки с объявлениями (не попавшие в топ-5), отсортированные по алфавиту
            if (otherBrandsWithAds.length > 0) {
                otherBrandsWithAds.sort((a, b) => a.brand.localeCompare(b.brand)); // Сортировка по алфавиту
                otherBrandsWithAds.forEach(brand => {
                    $('#brand-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + brand.brand + '">' + brand.brand + ' <span class="text-gray-500">(' + brand.advert_count + ')</span></div>');
                });
            }

            // Добавляем марки без объявлений, отсортированные по алфавиту
            if (brandsWithoutAds.length > 0) {
                brandsWithoutAds.sort((a, b) => a.brand.localeCompare(b.brand)); // Сортировка по алфавиту
                brandsWithoutAds.forEach(brand => {
                    $('#brand-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + brand.brand + '">' + brand.brand + ' <span class="text-gray-500">(' + brand.advert_count + ')</span></div>');
                });
            }
        }
        // Обработчик для кнопки выпадающего списка марок
        $('#brand-dropdown-button').on('click', function() {
            if ($('#brand-dropdown').hasClass('hidden')) {
                $('#brand-dropdown').removeClass('hidden').addClass('show');
                if ($('#brand-dropdown').is(':empty')) {
                    loadBrands();
                }
            } else {
                $('#brand-dropdown').removeClass('show').addClass('hidden');
            }
        });
        
         // Добавляем обработчик события click на поле "Марка"
  $('#brand-input').on('click', function() {
    $(this).val(''); // Очищаем значение поля
    $('#brand').val(''); // Очищаем скрытое поле
  });

        // Обработчик для кнопки выпадающего списка моделей
        $('#model-dropdown-button').on('click', function() {
            if ($('#model-dropdown').hasClass('hidden')) {
                $('#model-dropdown').removeClass('hidden').addClass('show');
                if ($('#model-dropdown').is(':empty')) {
                    loadModels();
                }
            } else {
                $('#model-dropdown').removeClass('show').addClass('hidden');
            }
        });

        // Обработчик для потери фокуса на поле ввода марки
        $('#brand-input').on('blur', function() {
            setTimeout(function() {
                if (!$('#brand-dropdown').is(':hover')) {
                    $('#brand-dropdown').removeClass('show').addClass('hidden');
                }
            }, 200);
        });

        // Обработчик для потери фокуса на поле ввода модели
        $('#model-input').on('blur', function() {
            setTimeout(function() {
                if (!$('#model-dropdown').is(':hover')) {
                    $('#model-dropdown').removeClass('show').addClass('hidden');
                }
            }, 200);
        });

        // Обработчик для наведения на выпадающий список марки
        $('#brand-dropdown').on('mouseenter', function() {
            $(this).data('hover', true);
        }).on('mouseleave', function() {
            $(this).data('hover', false);
        });

        // Обработчик для наведения на выпадающий список модели
        $('#model-dropdown').on('mouseenter', function() {
            $(this).data('hover', true);
        }).on('mouseleave', function() {
            $(this).data('hover', false);
        });

        // Обработчик ввода текста в поле марки
        $('#brand-input').on('input', function() {
            const term = $(this).val().trim();
            filterBrands(term);
        });

        // Загрузка данных для моделей
        function loadModels() {
            var brand = $('#brand').val();
            if (brand) {
                $.ajax({
                    url: '{{ route('get.models') }}',
                    type: 'GET',
                    data: { brand: brand },
                    success: function(data) {
                        modelsData = data; // Сохраняем данные
                        filterModels(''); // Инициализируем выпадающий список
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            }
        }

        // Фильтрация и отображение моделей
        function filterModels(term) {
            const filteredData = modelsData.filter(item => {
                // Проверяем, что item.model не равен null, прежде чем вызывать toLowerCase()
                const model = item.model ? item.model.toLowerCase() : ''; // Если model === null, используем пустую строку
                return model.includes(term.toLowerCase());
            }).sort((a, b) => {
                // Аналогично проверяем a.model и b.model на null
                const aStartsWith = a.model ? a.model.toLowerCase().startsWith(term.toLowerCase()) : false;
                const bStartsWith = b.model ? b.model.toLowerCase().startsWith(term.toLowerCase()) : false;
                if (aStartsWith && !bStartsWith) return -1;
                if (!aStartsWith && bStartsWith) return 1;
                return 0;
            });

            $('#model-dropdown').empty(); // Очищаем список

            // Фильтруем модели с объявлениями (advert_count > 0)
            const modelsWithAds = filteredData.filter(model => model.advert_count > 0);
            // Остальные модели (advert_count === 0)
            const modelsWithoutAds = filteredData.filter(model => model.advert_count === 0);

            // Сортируем модели с объявлениями по количеству объявлений (по убыванию)
            modelsWithAds.sort((a, b) => b.advert_count - a.advert_count);

            // Берем только топ-5 моделей с объявлениями
            const topModels = modelsWithAds.slice(0, 5);
            // Остальные модели с объявлениями (не попавшие в топ-5)
            const otherModelsWithAds = modelsWithAds.slice(5);

            // Добавляем топ-5 моделей (жирным шрифтом)
            topModels.forEach(model => {
                $('#model-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100 font-bold" data-value="' + model.model + '">' + model.model + ' <span class="text-gray-500">(' + model.advert_count + ')</span></div>');
            });

            // Если есть остальные модели с объявлениями или без, добавляем разделитель
            if (otherModelsWithAds.length > 0 || modelsWithoutAds.length > 0) {
                $('#model-dropdown').append('<div class="border-t border-gray-200 my-1"></div>'); // Горизонтальная линия
            }

            // Добавляем остальные модели с объявлениями (не попавшие в топ-5), отсортированные по алфавиту
            if (otherModelsWithAds.length > 0) {
                otherModelsWithAds.sort((a, b) => {
                    // Проверяем a.model и b.model на null
                    const aModel = a.model || ''; // Если null, используем пустую строку
                    const bModel = b.model || ''; // Если null, используем пустую строку
                    return aModel.localeCompare(bModel);
                });
                otherModelsWithAds.forEach(model => {
                    $('#model-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + model.model + '">' + model.model + ' <span class="text-gray-500">(' + model.advert_count + ')</span></div>');
                });
            }

            // Добавляем модели без объявлений, отсортированные по алфавиту
            if (modelsWithoutAds.length > 0) {
                modelsWithoutAds.sort((a, b) => {
                    // Проверяем a.model и b.model на null
                    const aModel = a.model || ''; // Если null, используем пустую строку
                    const bModel = b.model || ''; // Если null, используем пустую строку
                    return aModel.localeCompare(bModel);
                });
                modelsWithoutAds.forEach(model => {
                    $('#model-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + model.model + '">' + model.model + ' <span class="text-gray-500">(' + model.advert_count + ')</span></div>');
                });
            }
        }

        // Обработчик ввода текста в поле модели
        $('#model-input').on('input', function() {
            const term = $(this).val().trim();
            filterModels(term);
        });

        // Обработчик для выбора марки из выпадающего списка
        $('#brand-dropdown').on('click', 'div', function() {
            var brand = $(this).data('value');
            $('#brand-input').val(brand);
            $('#brand').val(brand);
            $('#brand-dropdown').removeClass('show').addClass('hidden');
            updateModels(brand);
        });

        // Обработчик для выбора модели из выпадающего списка
        $('#model-dropdown').on('click', 'div', function() {
            var model = $(this).data('value');
            $('#model-input').val(model);
            $('#model').val(model);
            $('#model-dropdown').removeClass('show').addClass('hidden');
        });

        // Обработчик для потери фокуса на поле ввода марки
        $('#brand-input').on('blur', function() {
            setTimeout(function() {
                if (!$('#brand-dropdown').is(':hover')) {
                    $('#brand-dropdown').removeClass('show').addClass('hidden');
                }
            }, 200);
        });

        // Обработчик для потери фокуса на поле ввода модели
        $('#model-input').on('blur', function() {setTimeout(function() {
                if (!$('#model-dropdown').is(':hover')) {
                    $('#model-dropdown').removeClass('show').addClass('hidden');
                }
            }, 200);
        });

        // Обработчик для наведения на выпадающий список марки
        $('#brand-dropdown').on('mouseenter', function() {
            $(this).data('hover', true);
        }).on('mouseleave', function() {
            $(this).data('hover', false);
        });

        // Обработчик для наведения на выпадающий список модели
        $('#model-dropdown').on('mouseenter', function() {
            $(this).data('hover', true);
        }).on('mouseleave', function() {
            $(this).data('hover', false);
        });

        // Обработчик для фокуса на поле ввода года
        $('#year-input').on('focus', function() {
            if ($('#year-dropdown').hasClass('hidden')) {
                $('#year-dropdown').removeClass('hidden').addClass('show');

                // Получаем выбранную марку и модель
                var brand = $('#brand').val();
                var model = $('#model').val();

                // Проверяем, что марка и модель выбраны
                if (brand && model) {
                    // Отправляем AJAX-запрос для получения списка годов
                    $.ajax({
                        url: '{{ route('get.years') }}', // Маршрут для получения годов
                        type: 'GET',
                        data: { brand: brand, model: model }, // Передаем марку и модель
                        success: function(data) {
                            // Очищаем список годов
                            $('#year-dropdown').empty();

                            // Добавляем годы в выпадающий список
                            $.each(data, function(index, year) {
                                $('#year-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + year + '">' + year + '</div>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                        }
                    });
                } else {
                    // Если марка или модель не выбраны, показываем сообщение
                    $('#year-dropdown').empty().append('<div class="px-3 py-2">Выберите марку и модель</div>');
                }
            }
        });

        // Обработчик для потери фокуса на поле ввода года
        $('#year-input').on('blur', function() {
            setTimeout(function() {
                if (!$('#year-dropdown').is(':hover')) {
                    $('#year-dropdown').removeClass('show').addClass('hidden');
                }
            }, 200);
        });

        // Обработчик для наведения на выпадающий список года
        $('#year-dropdown').on('mouseenter', function() {
            $(this).data('hover', true);
        }).on('mouseleave', function() {
            $(this).data('hover', false);
        });

        // Обработчик для кнопки выпадающего списка годов
        $('#year-dropdown-button').on('click', function() {
            if ($('#year-dropdown').hasClass('hidden')) {
                $('#year-dropdown').removeClass('hidden').addClass('show');

                // Получаем выбранную марку и модель
                var brand = $('#brand').val();
                var model = $('#model').val();

                // Проверяем, что марка и модель выбраны
                if (brand && model) {
                    // Отправляем AJAX-запрос для получения списка годов
                    $.ajax({
                        url: '{{ route('get.years') }}', // Маршрут для получения годов
                        type: 'GET',
                        data: { brand: brand, model: model }, // Передаем марку и модель
                        success: function(data) {
                            // Очищаем список годов
                            $('#year-dropdown').empty();

                            // Добавляем годы в выпадающий список
                            $.each(data, function(index, year) {
                                $('#year-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + year + '">' + year + '</div>');
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("AJAX Error: ", status, error);
                        }
                    });
                } else {
                    // Если марка или модель не выбраны, показываем сообщение
                    $('#year-dropdown').empty().append('<div class="px-3 py-2">Выберите марку и модель</div>');
                }
            } else {
                // Скрываем выпадающий список
                $('#year-dropdown').removeClass('show').addClass('hidden');
            }
        });

        // Обработчик для выбора года из выпадающего списка
        $('#year-dropdown').on('click', 'div', function() {
            var year = $(this).data('value');
            $('#year-input').val(year); // Устанавливаем значение в поле ввода
            $('#year').val(year); // Устанавливаем значение в скрытое поле
            $('#year-dropdown').removeClass('show').addClass('hidden'); // Скрываем список

            // После выбора года, запрашиваем модификации
            updateModifications();
        });



        // Функция для обновления модификаций
        function updateModifications() {
            var brand = $('#brand').val();
            var model = $('#model').val();
            var year = $('#year').val();

            // Скрываем placeholder и показываем блок модификаций
            $('#modifications-placeholder').hide();
            $('#modifications').show();

            if (brand && model && year) {
                $.ajax({
                    url: '/get-modifications', // Создайте маршрут для получения модификаций
                    type: 'GET',
                    data: { brand: brand, model: model, year: year },
                    success: function(data) {
                        // Очищаем блок модификаций
                        $('#modifications').empty();

                        // Добавляем модификации в блок
                        if (data.length > 0) {
                            $.each(data, function(index, modification) {
                                $('#modifications').append('<label class="flex items-center space-x-2 mb-2"><input type="checkbox" class="modification-checkbox" value="' + modification.id_modification + '" checked><span class="text-gray-700">' + modification.modification + '</span></label>');
                            });

                            // Показываем кнопки "Отметить все" и "Убрать все"
                            $('#modifications-buttons').removeClass('hidden');
                        } else {
                            // Если модификаций нет, показываем сообщение
                            $('#modifications').append('<div class="text-gray-500">Нет доступных модификаций</div>');

                            // Скрываем кнопки "Отметить все" и "Убрать все"
                            $('#modifications-buttons').addClass('hidden');
                        }

                        // Сохранение состояния чекбоксов в куки при изменении
                        $('.modification-checkbox').change(function() {
                            saveSelectedModifications();
                        });

                        // Сохранение состояния в куки по умолчанию
                        saveSelectedModifications();

                        // Обработчик для кнопки "Отметить все"
                        $('#select-all-mods').on('click', function() {
                            $('.modification-checkbox').prop('checked', true);
                            saveSelectedModifications();
                        });

                        // Обработчик для кнопки "Убрать все"
                        $('#deselect-all-mods').on('click', function() {
                            $('.modification-checkbox').prop('checked', false);
                            saveSelectedModifications();
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error);
                    }
                });
            } else {
                // Если параметры не выбраны, скрываем блок модификаций и показываем placeholder
                $('#modifications').hide();
                $('#modifications-placeholder').show();

                // Скрываем кнопки "Отметить все" и "Убрать все"
                $('#modifications-buttons').addClass('hidden');
            }
        }

    // Сохранение модификаций в куки
function saveSelectedModifications() {
    var selectedModifications = [];
    $('.modification-checkbox:checked').each(function() {
        var modificationId = $(this).val();
        var modificationText = $(this).parent().text().trim();
        selectedModifications.push({
            id_modification: modificationId,
            modification: modificationText
        });
    });
    console.log("Сохраненные модификации:", selectedModifications);
    Cookies.set('selectedModifications', JSON.stringify(selectedModifications), { expires: 7 });
}

// Обработчик изменения чекбоксов
$('.modification-checkbox').change(function() {
    saveSelectedModifications();
});

// Передача данных на сервер
$('#show-button').on('click', function(event) { // Добавляем event
    // Проверяем, заполнено ли хотя бы одно поле
    if (isFormEmpty()) {
        event.preventDefault(); // Предотвращаем отправку формы
        window.location.href = '{{ route('home') }}'; // Перенаправляем на главную
        return; // Прерываем выполнение функции
    }

    var selectedModifications = Cookies.get('selectedModifications');
    console.log("Передаваемые модификации:", selectedModifications);
    var formData = $('#main-form').serialize();
    if (selectedModifications) {
        formData += '&selected_modifications=' + encodeURIComponent(selectedModifications);
    }
    window.location.href = "{{ route('adverts.search.loading') }}?" + formData;
});

    });
</script>
