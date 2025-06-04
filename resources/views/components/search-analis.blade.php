
<style>
        #main-form {
            border: 0.5px solid #ccc;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 25px rgba(0, 0, 0, 0.3);
        }

        /* Стили для выпадающих списков */
        #brand-dropdown,
        #model-dropdown,
        #year-dropdown {
            display: none;
            position: absolute;
            top: 100%; /* Позиционируем под инпутом */
            left: 0;
            background-color: #ffffff;
            width: 100%;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            overflow: hidden;
            max-height: 150px;
            overflow-y: auto;
            z-index: 10;
            margin-top: 5px; /* Добавляем отступ сверху */
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
<body>
    <div class="ad-list">
        <form id="main-form" action="{{ route('market.analysis') }}" method="GET" class="flex flex-wrap gap-4 items-center" data-brands-url="{{ route('get.brands') }}">
            <input type="hidden" name="city" value="{{ request()->get('city') }}">

            <!-- Search Input -->
            <input
                type="text"
                name="search_query"
                id="search_query"
                placeholder="Введите название или номер детали"
                value="{{ request()->get('search_query') }}"
                class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500 text-gray-500 md:py-1 md:text-sm"
            />

            <!-- Brand Input with Autocomplete -->
            <div class="relative w-full md:w-auto flex items-center">
                <input
                    type="text"
                    id="brand-input"
                    name="brand_input"
                    placeholder="Введите марку"
                    class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500 text-gray-500 md:py-1 md:text-sm"
                />
                <input type="hidden" id="brand" name="brand" value="{{ request()->get('brand') }}">
                <button type="button" id="brand-dropdown-button" class="absolute right-0 px-2 py-2 text-gray-500 focus:outline-none">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="brand-dropdown" class="hidden"></div>
            </div>

            <!-- Model Input with Autocomplete -->
            <div class="relative w-full md:w-auto flex items-center">
                <input
                    type="text"
                    id="model-input"
                    name="model_input"
                    placeholder="Введите модель"
                    class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500 text-gray-500 md:py-1 md:text-sm"
                />
                <input type="hidden" id="model" name="model" value="{{ request()->get('model') }}">
                <button type="button" id="model-dropdown-button" class="absolute right-0 px-2 py-2 text-gray-500 focus:outline-none">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="model-dropdown" class="hidden"></div>
            </div>

            <!-- Year Select -->
            <div class="relative w-full md:w-auto flex items-center">
                <input
                    type="text"
                    id="year-input"
                    name="year_input"
                    placeholder="Выберите год выпуска"
                    class="w-full md:w-auto flex-grow px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-blue-500 text-gray-500 md:py-1 md:text-sm"
                    readonly
                />
                <input type="hidden" id="year" name="year" value="{{ request()->get('year') }}">
                <button type="button" id="year-dropdown-button" class="absolute right-0 px-2 py-2 text-gray-500 focus:outline-none">
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div id="year-dropdown" class="hidden"></div>
            </div>

            <!-- Show Button -->
            <button
                type="button"
                id="show-button"
                class="w-full md:w-auto px-4 py-2 bg-blue-500 text-white font-semibold rounded focus:outline-none focus:ring focus:ring-blue-500 md:py-1 md:text-sm"
            >
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

    // Обработчик для кнопки "Показать"
    $('#show-button').on('click', function() {
        // Проверяем, заполнено ли хотя бы одно поле
        if (isFormEmpty()) {
            alert('Пожалуйста, заполните хотя бы одно поле.');
            return;
        }

        // Если хотя бы одно поле заполнено, отправляем форму
        var formData = $('#main-form').serialize();
        window.location.href = '{{ route('market.analysis') }}?' + formData;
    });

    // Функция для проверки, заполнено ли хотя бы одно поле
    function isFormEmpty() {
        var searchQuery = $('#search_query').val().trim();
        var brandInput = $('#brand-input').val().trim();
        var modelInput = $('#model-input').val().trim();
        var yearInput = $('#year-input').val().trim();

        return !searchQuery && !brandInput && !modelInput && !yearInput;
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
        const filteredData = modelsData.filter(item => 
            item.model.toLowerCase().includes(term.toLowerCase())
        ).sort((a, b) => {
            const aStartsWith = a.model.toLowerCase().startsWith(term.toLowerCase());
            const bStartsWith = b.model.toLowerCase().startsWith(term.toLowerCase());
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
            otherModelsWithAds.sort((a, b) => a.model.localeCompare(b.model)); // Сортировка по алфавиту
            otherModelsWithAds.forEach(model => {
                $('#model-dropdown').append('<div class="px-3 py-2 cursor-pointer hover:bg-gray-100" data-value="' + model.model + '">' + model.model + ' <span class="text-gray-500">(' + model.advert_count + ')</span></div>');
            });
        }

        // Добавляем модели без объявлений, отсортированные по алфавиту
        if (modelsWithoutAds.length > 0) {
            modelsWithoutAds.sort((a, b) => a.model.localeCompare(b.model)); // Сортировка по алфавиту
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
    console.log("Сохраненные модификации:", selectedModifications); // Проверка данных
    Cookies.set('selectedModifications', JSON.stringify(selectedModifications), { expires: 7 });
}

// Обработчик изменения чекбоксов
$('.modification-checkbox').change(function() {
    saveSelectedModifications();
});

// Передача данных на сервер
$('#show-button').on('click', function() {
    var selectedModifications = Cookies.get('selectedModifications');
    console.log("Передаваемые модификации:", selectedModifications); // Проверка данных
    var formData = $('#main-form').serialize();
    if (selectedModifications) {
        formData += '&selected_modifications=' + encodeURIComponent(selectedModifications);
    }
    window.location.href = '{{ route('market.analysis') }}?' + formData;
});
});
</script>