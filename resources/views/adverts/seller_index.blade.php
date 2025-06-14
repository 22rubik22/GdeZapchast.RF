@extends('layouts.seller')


@push('head')
    <title>{{$user['username']}} | Магазин</title>
@endpush

@section('pre-body')
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



        @media (max-width: 768px) {
            #search-filters-container {
                background-color: #5e94f7 !important; /* Синий фон */
                border-radius: 0;
                border: 1px solid #5e94f7;
                margin-top:0;
            }

            #modifications-container {
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
@endsection

@section('content')
    <script>
        const user = @json($user);
    </script>

    @include('components.header-seller')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

    @include('components.seller-info-block')

    <div class="w-full mt-20 md:w-[90%] mt-10 mx-auto sm:block">
        @include('components.search-form')
    </div>

    <div id="search-filters-wrapper"  class="w-full md:w-[90%] flex flex-col items-start">
        <div id="search-filters-container" class="hidden w-full md:w-[75%]"></div>
    </div>

    <div class="w-full md:w-[90%] max-w-6xl max-md:mb-20">
        <div class="
        flex
        flex-col-reverse
        xl:flex-row
        w-full
        sm:justify-start
        md:mt-8
        gap-20
    ">
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
                        @include('components.advert-card-phone', ['advert' => $advert])
                    @endforeach
                </div>

                <!-- Для больших и средних экранов -->
                <div class="hidden sm:flex w-[90%] flex-col items-start justify-center parent-container mx-auto">
                    @foreach($filteredAdverts as $advert)
                        @include('components.advert-card-desktop', ['advert' => $advert])
                    @endforeach
                </div>

                <div
                    id="filters-container"
                    class="
                        max-md:bg-[#5e94f7]
                        p-4
                        filters
                        bg-[#f3f3f3]
                        w-full
                        xl:w-1/3
                        h-auto
                        max-h-[32rem]
                        md:rounded-2xl
                        shadow-md
                    "
                >


                    <div id="modifications-container" class="modification p-4 h-auto xl:h-1/2 bg-[#f3f3f3] flex flex-col rounded-2xl">
                        <div class="flex-grow">
                            <label class="font-medium">Модификации:</label>
                            <div id="modifications-buttons" class="flex space-x-2 mb-4 hidden">
                                <button id="select-all-mods" class="text-blue-500 hover:text-blue-700">Отметить все</button>
                                <button id="deselect-all-mods" class="text-blue-500 hover:text-blue-700">Убрать все</button>
                            </div>
                            <div id="modifications" class="flex flex-col overflow-y-auto show_scroll" style="display: none;"></div>
                            <div id="modifications-placeholder" class="text-gray-500 mt-2 hidden">
                                Для отображения модификаций выберите параметры автомобиля
                            </div>
                        </div>

                        <div class="mt-4">
                            <button id="openModificationsModalBtn" class="w-100 l:w-[75%] xl:w-100 bg-[#E9E9E9] text-black px-4 py-2 rounded-lg hidden">
                                Показать все модификации
                            </button>
                        </div>
                    </div>


                    <div id="modificationsModal" class="fixed inset-0 bg-black/50 hidden z-50">
                        <div id="modificationsModalContent"
                             class="bg-white max-w-[80%] mx-auto mt-20 p-4 rounded-lg relative overflow-y-auto max-h-[80vh]">
                            <button id="closeModificationsModalBtn" class="absolute top-3 right-3">
                                ✕
                            </button>
                            <div id="modificationsModalContentContainer"></div>
                        </div>
                    </div>
                    {{--            @include('components.modifications_search-form')--}}
                </div>
        </div>

        <div class="h-24">
            @include('components.indexpagination', ['adverts' => $adverts])
        </div>
        @endif
    </div>
    {{--<div id="modificationsModal" class="fixed inset-0 bg-white z-50 overflow-y-auto hidden">--}}
    {{--    <div class="p-4">--}}
    {{--        <div class="flex justify-between items-center mb-4">--}}
    {{--            <h2 class="text-xl font-semibold">Все модификации</h2>--}}
    {{--            <button id="closeModificationsModalBtn" class="text-gray-500 hover:text-gray-700">--}}
    {{--                <i class="fas fa-times"></i>--}}
    {{--            </button>--}}
    {{--        </div>--}}
    {{--        <div id="modificationsModalContent" class="overflow-y-auto">--}}
    {{--            <!-- Сюда будут вставлены модификации -->--}}
    {{--        </div>--}}
    {{--    </div>--}}
    {{--</div>--}}
    <button
        id="scrollToTopBtn"
        title="Наверх"
        class="
            fixed bottom-4 right-4
            w-12 h-12
            bg-gray-500 hover:bg-gray-600
            text-white
            rounded-full
            shadow-lg
            flex items-center justify-center
            transition-colors duration-300
            z-50
            hidden
          "
    >
        <i class="fas fa-arrow-up text-xl leading-none"></i>
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
            // console.log('toggleModificationsVisibility');
            // const modificationsContainer = document.getElementById('modifications');
            //
            // // Переключаем состояние блока
            // if (modificationsContainer.style.display === 'none') {
            //     modificationsContainer.style.display = 'flex'; // Показываем блок
            // } else {
            //     modificationsContainer.style.display = 'none'; // Скрываем блок
            // }
            //
            // // Обновляем состояние текста
            // updateModificationsPlaceholder();
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
            //handleFiltersVisibility();

            // Вызываем функцию при изменении размера окна
            //window.addEventListener('resize', handleFiltersVisibility);
        });

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

    </script>
    @include('components.footer')
@endsection
