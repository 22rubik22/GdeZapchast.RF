<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Справка</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<style>
    body {
        font-family: 'Nunito', sans-serif;

        /* Стили для затемненного фона */
    }
.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    z-index: 1000;
    justify-content: center;
    align-items: center;
}

/* Стили для увеличенного изображения */
.modal-image {
    max-width: 90%;
    max-height: 90%;
    border: 2px solid white;
    border-radius: 8px;
}

/* Стили для кнопки закрытия */
.close-button {
    position: absolute;
    top: 20px;
    right: 20px;
    color: white;
    font-size: 24px;
    cursor: pointer;
    z-index: 1001;
}

.active-title {
    color: #3b82f6;
}

.dropdown-content {
        padding-left: 20px; /* Отступ для подпунктов */
    }

/* Стили для иконки стрелки */
.arrow-icon {
    transition: transform 0.3s ease; /* Плавный переход для трансформации */
    transform: rotate(-90deg); /* Изначально стрелка повернута на 90 градусов вправо */
}

/* Класс для поворота стрелки влево */
.arrow-icon.rotated {
    transform: rotate(0deg); /* Поворот на 90 градусов влево */
}

/* Стили для плавного изменения цвета текста при наведении и убирании курсора */
.hover-text-blue {
    transition: color 0.3s ease; /* Плавный переход цвета текста */
}

.hover-text-blue:hover {
    color: #3b82f6; /* Цвет текста при наведении (blue-500 в Tailwind) */
}

/* Увеличиваем междустрочные интервалы внутри выпадающих меню */
.dropdown-content>p,
.dropdown-content>ul,
.dropdown-content>li {
    line-height: 1.5; /* Увеличиваем интервал между строками */
}

.bg-gray-100 p,
.bg-gray-100 i {
    transition: color 0.3s ease;
}

.bg-gray-100:hover p,
.bg-gray-100:hover i {
    color: #0077FF;
}

</style>

<style>
    /* Увеличиваем отступы между абзацами и списками только внутри dropdown-content */
    .dropdown-content > ul,
    .dropdown-content > ol {
        margin-top: 1.5rem; /* Увеличиваем отступ сверху */
        margin-bottom: 3rem; /* Увеличиваем отступ снизу */
    }

    .dropdown-content > p{
        margin-bottom:  1rem;;
    }

    /* Увеличиваем отступы слева для вложенных списков только внутри dropdown-content */
    .dropdown-content>ul,
    .dropdown-content>ol {
        padding-left: 2rem; /* Увеличиваем отступ слева */
    }

    /* Увеличиваем отступы между элементами списка только внутри dropdown-content */
    .dropdown-content>ul li,
    .dropdown-content>ol li {
        margin-top: 1rem; /* Увеличиваем отступ сверху для каждого элемента списка */
        margin-bottom: 1rem; /* Увеличиваем отступ снизу для каждого элемента списка */
    }

    /* Увеличиваем отступы для изображений только внутри dropdown-content */
    .dropdown-content>img {
        margin-top: 2rem; /* Увеличиваем отступ сверху для изображений */
        margin-bottom: 2rem; /* Увеличиваем отступ снизу для изображений */
    }

    /* Оставляем стили для hover-text-blue без изменений */
    .hover-text-blue {
        transition: color 0.3s ease;
    }

    .hover-text-blue:hover {
        color: #3b82f6;
    }
</style>

<body class="bg-white flex flex-col items-center p-4">
    @include('components.header-seller')   
    <h1 class="text-2xl font-bold mb-4 mt-10">Выбор раздела</h1>
    <div id="orange-block" class="bg-[#FFF1DE] text-center p-4 rounded-md mb-8 relative w-full md:w-[80%]">
        <p class="text-start text-lg md:text-xl text-[#535151] font-semibold">Для перехода к нужному разделу выберите соответствующий пункт или прокрутите страницу вниз до нужного раздела</p>
        <button onclick="hideOrangeBlock()" class="absolute -top-2 -right-2 text-gray-500">
            <img src="{{asset('images/close-circle.png')}}" alt="">
        </button>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mb-28 font-semibold w-full md:w-[80%] ">
        
        

        @if(auth()->user()->user_status == 1)

        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToTariffs()">
            <i class="fas fa-ruble-sign text-3xl mb-2 mr-4"></i>
            <p class="text-center">Тарифы и оплата</p>
        </div>
        
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToWalletInfo()">
            <i class="fas fa-wallet text-3xl mb-2 mr-4"></i>
            <p class="text-center">Кошелек</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToProductRequirements()">
            <i class="fas fa-cloud-upload-alt text-3xl mb-2 mr-2"></i>
            <p class="text-center">Размещение товаров</p>
        </div>
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToPriceListConverter()">
            <i class="fas fa-exchange-alt text-3xl mb-2 mr-4"></i>
            <p class="text-center">Конвертер прайс-листа</p>
        </div>
        @endif
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToProfileSettings2()">
            <i class="fas fa-pencil-alt text-3xl mb-2 mr-4"></i>
            <p class="text-center">Редактирование профиля</p>
        </div> 
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToProfileSettings()">
            <i class="fas fa-lock text-3xl mb-2 mr-4"></i>
            <p class="text-center">Смена пароля</p>
        </div>
        @if(auth()->user()->user_status == 1)
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToMarketAnalysis()">
            <i class="fas fa-chart-bar text-3xl mb-2 mr-4"></i>
            <p class="text-center">Анализ рынка</p>
        </div>
          
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToVideoInstructions()">
            <i class="fas fa-video text-3xl mb-2 mr-4"></i>
            <p class="text-center">Видеоинструкции</p>
        </div>
        @endif
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToProfile()">
            <i class="fas fa-user text-3xl mb-2 mr-4"></i>
            <p class="text-center">Профиль</p>
        </div>
        @if(auth()->user()->user_status == 1)
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToMyProducts()">
            <i class="fas fa-list text-3xl mb-2 mr-4"></i>
            <p class="text-center">Мои товары</p>
        </div>
         @endif
        <div class="bg-gray-100 p-6 rounded-lg flex items-center justify-center cursor-pointer" onclick="scrollToSearch()">
            <i class="fas fa-search text-3xl mb-2 mr-4"></i>
            <p class="text-center">Поиск товаров</p>
        </div>
    </div>

    <div class="w-full md:w-[80%] text-xl mb-10">

        <div id="profile-settings" class="mb-8"> <!-- Увеличиваем отступ снизу -->
            <h2 class="text-2xl font-bold mb-12 text-center">Профиль</h2> <!-- Увеличиваем отступ снизу -->
            <ul class="space-y-8 font-medium"> <!-- Увеличиваем отступ между пунктами -->
                <!-- Пункт 1 -->
                <li id="store-pages-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleStorePagesInfo()">
                        <span class="hover-text-blue cursor-pointer">Персональные страницы для магазинов</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-3 cursor-pointer arrow-icon" alt=""> <!-- Увеличиваем отступ слева -->
                    </div>
                    <div id="store-pages-text" class="hidden mt-4 text-xl dropdown-content"> <!-- Увеличиваем отступ сверху -->
                        <p>На платформе ГдеЗапчасть доступно два формата представления магазина:</p>
                        <ul class="list-none pl-8 space-y-6"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li class="space-y-4"> <!-- Увеличиваем отступ внутри подпункта -->
                                <strong>1. Стандартная страница магазина</strong>
                                <img src="{{asset("images/Рисунок1.png")}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                                <p>Профиль компании в едином стиле платформы, доступный при переходе с карточек товаров или через поиск по магазинам. Содержит полную информацию о компании, каталог товаров и контактные данные.</p>
                            </li>
                            <li class="space-y-4"> <!-- Увеличиваем отступ внутри подпункта -->
                                <strong>2. Индивидуальный сайт магазина</strong>
                                <img src="{{asset('images/Рисунок2.png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                                <p>Расширенная версия страницы магазина с возможностью:</p>
                                <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                                    <li>Размещения собственного логотипа в шапке сайта</li>
                                    <li>Установки индивидуального рекламного баннера</li>
                                    <li>Подключения собственного домена</li>
                                    <li>Использования всех функций основной платформы</li>
                                </ul>
                            </li>
                        </ul>
                        <p class="mt-6"><strong>Преимущества индивидуального сайта:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li>Автоматическая синхронизация товаров с основной платформой</li>
                            <li>Доступ к поисковой системе ГдеЗапчасть</li>
                            <li>Возможность самостоятельного продвижения сайта</li>
                            <li>Отсутствие необходимости в технической поддержке</li>
                        </ul>
                        <p class="mt-6">Индивидуальный сайт предоставляется бесплатно всем магазинам на платных тарифах. Покупка и обслуживание домена производятся отдельно через регистраторов доменных имен.</p>
                        <p class="mt-6"><strong>Правовая информация:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <p>Сервис предоставляется на условиях "как есть" (as is). Компания не несет ответственности за:</p>
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li>Любые убытки, включая упущенную выгоду, возникшие в результате использования сервиса</li>
                            <li>Работоспособность сторонних сервисов и доменных имен</li>
                            <li>Достоверность информации, размещаемой продавцами</li>
                            <li>Качество товаров и услуг, предлагаемых продавцами</li>
                            <li>Возможные перерывы в работе сервиса</li>
                        </ul>
                        <p class="mt-6">Используя сервис, вы принимаете на себя все риски, связанные с его использованием. Компания оставляет за собой право в любой момент изменять функционал сервиса, условия его предоставления или прекратить его работу без предварительного уведомления.</p>
                        <p class="mt-6">Размещая информацию на платформе, пользователь гарантирует, что имеет все необходимые права на её публикацию и несет полную ответственность за её содержание и достоверность.</p>
                        <p class="mt-6"><strong>Примечание:</strong> Наполнение сайта товарами происходит автоматически из каталога магазина на основной платформе ГдеЗапчасть.</p>
                    </div>
                </li>
                
                <!-- Пункт 2 -->
                <li id="edit-profile-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleEditProfileInfo()">
                        <span class="hover-text-blue cursor-pointer">Редактирование профиля компании</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-3 cursor-pointer arrow-icon" alt=""> <!-- Увеличиваем отступ слева -->
                    </div>
                    <div id="edit-profile-text" class="hidden mt-4 text-xl dropdown-content"> <!-- Увеличиваем отступ сверху -->
                        <p>Для того чтобы изменить информацию о вашей компании, перейдите на вкладку «профиль», затем в меню выберите пункт «Редактировать профиль».</p>
                        <img src="{{asset('images/Group 550 (2).png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                        <p class="mt-6">В данном разделе вы можете указать или изменить информацию о вашей компании. Корректное заполнение профиля помогает покупателям получить необходимую информацию о вашей организации и способствует повышению доверия к магазину.</p>
                        <img src="{{asset('images/127.0.0.1_8000_profile_37_edit 1.png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                        <p class="mt-6"><strong>Основная информация:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li><strong>Название магазина</strong> - торговое наименование, под которым вы работаете с клиентами.</li>
                            <li><strong>E-Mail</strong> - актуальный адрес электронной почты для связи.</li>
                            <li><strong>Телефон</strong> - основной номер телефона в формате 7 XXX XXX XX XX.</li>
                            <li><strong>Дополнительные телефоны</strong> - при необходимости можно добавить дополнительные контактные номера.</li>
                            <li><strong>Адрес</strong> - фактический адрес магазина или пункта выдачи.</li>
                            <li><strong>URL изображения профиля</strong> – прямая ссылка на фотографию магазина (рекомендуемый размер 150x100 px).</li>
                            <li><strong>URL логотипа</strong> - прямая ссылка на логотип компании.</li>
                        </ul>
                        <p class="mt-6"><strong>Как сделать прямую ссылку на изображение:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <p>Прямая ссылка на изображение (URL изображения профиля / URL логотипа) — это ссылка, которая ведет непосредственно на саму фотографию. Если фотографии есть только у вас на компьютере, то получить прямые гиперссылки можно, воспользовавшись любым бесплатным фотохостингом или соцсетью, например, vk.com. Перед размещением ваших фотографий убедитесь, что используемый вами фотохостинг предоставляет прямые ссылки на фотографии, в противном случае мы не сможем их выгрузить. Такие сервисы, как Яндекс.Диск и Google Диск, а также большинство облачных хранилищ не предоставляют прямые ссылки на свои файлы, в том числе и на фотографии.</p>
                        <p class="mt-6"><strong>Юридическая информация:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li><strong>Название организации</strong> - полное юридическое наименование (ООО, ИП и т.д.).</li>
                            <li><strong>Юридический адрес</strong> - адрес регистрации организации.</li>
                            <li><strong>ИНН</strong> - идентификационный номер налогоплательщика (10 или 12 цифр).</li>
                            <li><strong>КПП</strong> - код причины постановки на учет (9 цифр).</li>
                        </ul>
                        <p class="mt-6"><strong>Важно:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li>Все поля должны содержать актуальную и достоверную информацию.</li>
                            <li>Указанные контактные данные должны быть действующими.</li>
                            <li>При изменении данных необходимо нажать кнопку "Сохранить".</li>
                            <li>Отображение профиля может занять некоторое время после сохранения изменений.</li>
                        </ul>
                        <p class="mt-6">Техническая поддержка оставляет за собой право проверять достоверность указанной информации и запрашивать подтверждающие документы.</p>
                    </div>
                </li>
                
                <!-- Пункт 3 -->
                <li id="change-password-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleChangePasswordInfo()">
                        <span class="hover-text-blue cursor-pointer">Смена пароля</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-3 cursor-pointer arrow-icon" alt=""> <!-- Увеличиваем отступ слева -->
                    </div>
                    <div id="change-password-text" class="hidden mt-4 text-xl dropdown-content"> <!-- Увеличиваем отступ сверху -->
                        <p>Для изменения пароля от вашей учетной записи перейдите на вкладку «профиль», затем в меню выберите пункт «Редактировать профиль».</p>
                        <img src="{{asset('images/Group 550 (2).png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                        <ol class="list-decimal pl-8 space-y-6"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li class="space-y-4"> <!-- Увеличиваем отступ внутри подпункта -->
                                Нажмите кнопку "Сменить пароль" в правом верхнем углу страницы профиля.
                                <img src="{{asset('images/Group 550 (3).png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                            </li>
                            <li class="space-y-4"> <!-- Увеличиваем отступ внутри подпункта -->
                                В открывшемся окне введите:
                                <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                                    <li>Текущий пароль</li>
                                    <li>Новый пароль</li>
                                    <li>Подтверждение нового пароля</li>
                                    <img src="{{asset('images/127.0.0.1_8000_profile_37_edit (1).png')}}" class="border w-2/3 my-6 zoomable-image" alt=""> <!-- Увеличиваем отступ сверху и снизу -->
                                </ul>
                            </li>
                            <li class="space-y-4"> <!-- Увеличиваем отступ внутри подпункта -->
                                Нажмите "Сохранить" для применения изменений или "Отмена" для выхода без сохранения.
                            </li>
                        </ol>
                        <p class="mt-6"><strong>Важно:</strong></p> <!-- Увеличиваем отступ сверху -->
                        <ul class="list-disc pl-8 space-y-4"> <!-- Увеличиваем отступ слева и между подпунктами -->
                            <li>Новый пароль должен отличаться от текущего.</li>
                            <li>Значения в полях "Новый пароль" и "Повторите пароль" должны совпадать.</li>
                            <li>После смены пароля потребуется повторная авторизация в системе.</li>
                        </ul>
                        <p class="mt-6">При возникновении проблем со сменой пароля обратитесь в службу поддержки.</p>
                    </div>
                </li>
            </ul>
        </div>
@if(auth()->user()->user_status == 1)
        <div id="tariffs" class="mb-8">
            <h2 class="font-bold mb-10 text-center text-2xl">Тарифы и оплата</h2>
            <ul class="space-y-8 font-medium">
                <li id="trial-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleTrialInfo()">
                        <span class="hover-text-blue">Бесплатный пробный период</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="arrow-icon w-6 h-6 ml-4 cursor-pointer" alt="">
                    </div>
                    <div id="trial-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>Для новых пользователей мы предоставляем возможность оценить все преимущества нашей платформы с помощью бесплатного 14-дневного пробного периода. Этот период доступен только один раз для каждого нового аккаунта и включает в себя полный функционал платных тарифов без каких-либо ограничений.</p>
                        <div class="pl-8">
                            <p class="mt-6"><strong>Важно отметить:</strong></p>
                            <ul class="list-disc pl-8 space-y-4">
                                <li>Пробный период автоматически завершается через 14 дней.</li>
                                <li>Для продолжения использования сервиса после пробного периода необходимо выбрать и оплатить один из наших тарифных планов.</li>
                                <li>При активации платного тарифа во время действия пробного периода, бесплатный доступ прекращается и не может быть восстановлен.</li>
                                <li>Лимит активных товаров составляет 300,000 позиций (номенклатуры), а не количество товаров (остаток шт.), активные товары – товары доступные для поиска другим пользователям.</li>
                            </ul>
                        </div>
                    </div>
                </li>
                <li id="reduce-tariff-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleReduceTariffInfo()">
                        <span class="hover-text-blue">Как снизить стоимость тарифа</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="reduce-tariff-text" class="hidden mt-8 text-xl pl-8">
                        <ul class="space-y-6">
                            <li id="discount-system-item" class="flex flex-col items-start">
                                <div class="flex items-center cursor-pointer" onclick="toggleDiscountSystemInfo()">
                                    <span class="hover-text-blue">Система взаимных скидок</span>
                                    <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                                </div>
                                <div id="discount-system-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                                    <p>ГдеЗапчасть.рф предлагает уникальную программу сотрудничества для компаний, размещающих свои товары на площадке. Участвуя в программе "Взаимные скидки", вы получаете возможность снизить стоимость размещения товаров на нашей площадке и увеличить пробный период использования сервиса.</p>
                                    <div class="pl-8">
                                        <p class="mt-6"><strong>Как это работает</strong></p>
                                        <ol class="list-decimal pl-8 space-y-4">
                                            <li>Продавец настраивает систему скидок для покупателей, которые нашли товары через ГдеЗапчасть.рф</li>
                                            <li>На основе установленных компанией скидок, система автоматически рассчитывает встречную скидку на размещение товаров</li>
                                            <li>Максимальный размер встречной скидки может достигать 40% от стоимости тарифа</li>
                                            <li>Участникам программы предоставляется расширенный пробный период - 30 дней вместо стандартных 14</li>
                                        </ol>
                                    </div>
                                    <div class="pl-8">
                                        <p class="mt-6"><strong>Настройка скидок</strong></p>
                                        <ul class="list-disc pl-8 space-y-4">
                                            <li>Вы можете установить до 10 различных вариантов скидок</li>
                                            <li>Скидки настраиваются в зависимости от суммы покупки</li>
                                            <li>Для каждого диапазона сумм указывается процент скидки</li>
                                            <li>Возможно добавление примечаний об исключениях и особых условиях</li>
                                        </ul>
                                    </div>
                                    <div class="pl-8">
                                        <p class="mt-6"><strong>Преимущества участия</strong></p>
                                        <ul class="list-disc pl-8 space-y-4">
                                            <li>Повышение конкурентоспособности ваших предложений</li>
                                            <li>Увеличение количества клиентов</li>
                                            <li>Снижение затрат на размещение товаров</li>
                                            <li>Прозрачные условия сотрудничества</li>
                                        </ul>
                                    </div>
                                    <div class="pl-8">
                                        <p class="mt-6"><strong>Важно</strong></p>
                                        <ul class="list-disc pl-8 space-y-4">
                                            <li>Размер встречной скидки рассчитывается индивидуально для каждой компании</li>
                                            <li>Учитываются все настроенные диапазоны скидок</li>
                                            <li>Система анализирует реалистичность предоставляемых скидок</li>
                                            <li>Встречная скидка применяется автоматически при выставлении счета за размещение</li>
                                            <li>Продавец обязуется предоставлять скидки покупателям, сообщившим о том, что нашли товар через площадку ГдеЗапчасть.рф</li>
                                        </ul>
                                    </div>
                                    <div class="pl-8">
                                        <p class="mt-6"><strong>Правовая информация</strong></p>
                                        <ol class="list-decimal pl-8 space-y-4">
                                            <li>Сервис предоставляется на условиях "как есть"</li>
                                            <li>ГдеЗапчасть.рф не несет ответственности за:
                                                <ul class="list-disc pl-8 space-y-4">
                                                    <li>Убытки, возникшие в результате использования или невозможности использования сервиса</li>
                                                    <li>Действия компаний-участников программы</li>
                                                    <li>Претензии третьих лиц, связанные с участием в программе</li>
                                                </ul>
                                            </li>
                                            <li>Администрация сервиса оставляет за собой право:
                                                <ul class="list-disc pl-8 space-y-4">
                                                    <li>Вносить изменения в условия программы</li>
                                                    <li>Приостанавливать или прекращать действие программы</li>
                                                    <li>Отказать компании в участии без объяснения причин</li>
                                                </ul>
                                            </li>
                                        </ol>
                                    </div>
                                    <div class="pl-8">
                                        <p class="mt-6">Все изменения в условиях программы публикуются на сайте и вступают в силу с момента публикации.</p>
                                    </div>
                                </div>
                            </li>
                            <li id="how-to-connect-item dropdown-content" class="flex flex-col items-start">
                                <div class="flex items-center cursor-pointer" onclick="toggleHowToConnectInfo()">
                                    <span class="hover-text-blue">Как подключить</span>
                                    <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                                </div>
                                <div id="how-to-connect-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                                    <!-- Добавьте содержимое для "Как подключить" -->
                                </div>
                            </li>
                        </ul>
                    </div>
                </li>
                <li id="tariffs-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleTariffsInfo()">
                        <span class="hover-text-blue">Тарифы</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="tariffs-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p><strong>Индивидуальные тарифные решения</strong></p>
                        <p>На сайте ГдеЗапчасть.рф мы предлагаем гибкую систему настройки тарифов, адаптированную под уникальные потребности каждого продавца автозапчастей. Вы можете настроить тариф, указав желаемое количество активных товаров (номенклатуры) для размещения. Это легко сделать с помощью интерактивного ползунка или ввода числа в специальное поле, после чего стоимость тарифа будет рассчитана автоматически. Количество единиц товара в наличии (остаток шт.) не влияет на лимит активных позиций по тарифу.</p>
                        <div class="pl-8">
                            <p class="mt-6"><strong>Особенности тарификации:</strong></p>
                            <ul class="list-disc pl-8 space-y-4">
                                <li>Ежедневное списание средств за выбранный тариф, первое списание происходит сразу после установки тарифа.</li>
                                <li>Тариф определяет максимальное количество активных товарных позиций (номенклатуры), а не количество товаров (остаток шт.). Активные товары – это позиции, доступные для поиска другим пользователям.</li>
                                <li>Неограниченное количество обновлений и редактирований товаров в рамках выбранного тарифа.</li>
                                <li>Отсутствие дополнительных комиссий за просмотры контактов или товаров, кроме отдельно подключенных услуг продвижения.</li>
                                <li>Количество единиц товара в наличии (остаток шт.) не влияет на лимит активных позиций по тарифу.</li>
                            </ul>
                        </div>
                        <div class="pl-8">
                            <p class="mt-6"><strong>Превышение лимита товаров</strong></p>
                            <p>Если количество размещенных товаров превышает выбранный тариф, избыточные позиции автоматически становятся неактивными и скрываются от других пользователей. Неактивные товары выбираются случайным образом.</p>
                        </div>
                    </div>
                </li>
                <li id="change-tariff-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleChangeTariffInfo()">
                        <span class="hover-text-blue">Смена тарифа</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="change-tariff-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>Для того чтобы сменить тариф перейдите на вкладку «Профиль» затем в меню выберите пункт «Настроить тариф».</p>
                        <img src="{{asset('images/Group 549.png')}}" class="border w-2/3 my-6 zoomable-image" alt="">
                        <p>Введите требуемое количество товаров в поле или с помощью ползунка.
                            Стоимость тарифа будет рассчитана автоматически. Для сохранения тарифа нажмите кнопку «сохранить». После этого с вашего кошелька ежедневно будет списываться сумма равная «Стоимость размещения в день», первое списание произойдет сразу же после сохранения тарифа.
                        </p>
                        <img src="{{asset('images/Group 548 (2).png')}}" class="border w-2/3 my-6 zoomable-image" alt="">
                    </div>
                </li>
                <li id="tariff-payment-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleTariffPaymentInfo()">
                        <span class="hover-text-blue">Как происходят списания</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="arrow-icon w-6 h-6 ml-4 cursor-pointer" alt="">
                    </div>
                    <div id="tariff-payment-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>Списание средств за использование тарифа происходит автоматически на ежедневной основе с вашего внутреннего кошелька на платформе ГдеЗапчасть. Размер ежедневного списания соответствует размеру стоимости размещения товаров в день выбранного вами тарифа.</p>
                        
                        <p class="mt-6"><strong>Важная информация:</strong></p>
                        <ul class="list-disc pl-8 space-y-4">
                            <li>Первое списание производится сразу после активации или изменения тарифа.</li>
                            <li>Сумма списания фиксирована и соответствует дневной стоимости выбранного тарифа.</li>
                            <li>Для бесперебойной работы необходимо поддерживать достаточный баланс на внутреннем кошельке.</li>
                            <li>При недостаточном балансe размещенные товары автоматически становятся неактивными.</li>
                        </ul>
                
                        <p class="mt-6"><strong>Рекомендации:</strong></p>
                        <ol class="list-decimal pl-6 space-y-4">
                            <li>Регулярно проверяйте баланс вашего внутреннего кошелька.</li>
                            <li>Своевременно пополняйте счет для непрерывной работы сервиса.</li>
                        </ol>
                
                        <p class="mt-6">В случае возникновения вопросов по оплате или работе тарифа обратитесь в службу поддержки через чат связи или по указанным контактам.</p>
                    </div>
                </li>
                <li class="flex items-center"><span>Часто задаваемые вопросы</span> <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 arrow-icon" alt=""></li>
            </ul>
        </div>
        

        <div id="wallet-info" class="mb-8">
            <h2 class="text-2xl font-bold mb-10 text-center">Кошелек</h2>
            <ul class="space-y-8 font-medium">
                <li id="info-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleInfo()">
                        <span class="hover-text-blue">Общая информация</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="arrow-icon w-6 h-6 ml-2 cursor-pointer" alt="">
                    </div>
                    <div id="info-text" class="hidden mt-2 text-xl dropdown-content pl-6" style="line-height: 1.8; padding-left: 2rem;">
                        <p><strong>Что такое кошелек?</strong></p>
                        <div class="pl-6">
                            <p>Кошелёк на ГдеЗапчасть.рф – это современный финансовый инструмент для продавцов автозапчастей, обеспечивающий удобное управление средствами на платформе. Он позволяет легко оплачивать размещение и продвижение товаров, а также другие услуги, доступные на сайте. Все операции с кошельком автоматически отражаются в личном кабинете продавца, что обеспечивает полную прозрачность и контроль над расходами.</p>
                        </div>
                        <div class="pl-6">
                            <p class="mt-4"><strong>Зачем нужен кошелек?</strong></p>
                            <div class="pl-6">
                                <p>Кошелёк значительно упрощает процесс оплаты услуг на платформе, гарантируя непрерывное присутствие ваших товаров в нашей базе данных. Это позволяет избежать задержек в размещении и обновлении информации, обеспечивая постоянную доступность ваших предложений для потенциальных покупателей.</p>
                            </div>
                        </div>
                        <div class="pl-6">
                            <p class="mt-4"><strong>Безопасность и надежность</strong></p>
                            <div class="pl-6">
                                <p>Мы уделяем особое внимание безопасности финансовых операций на нашей платформе. Все транзакции проходят через защищенные каналы связи, а данные пользователей надежно зашифрованы. Наша служба поддержки готова помочь вам с любыми вопросами, связанными с использованием кошелька.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="wallet-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleWalletInfo()">
                        <span class="hover-text-blue">Как пополнить кошелек?</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="arrow-icon w-6 h-6 ml-2 cursor-pointer" alt="">
                    </div>
                    <div id="wallet-text" class="hidden mt-2 text-xl dropdown-content pl-6" style="line-height: 1.8; padding-left: 2rem;">
                        <p>В настоящее время пополнение кошелька доступно с помощью банковской карты. Мы постоянно работаем над расширением способов оплаты, чтобы сделать управление финансами еще удобнее для наших пользователей. В зависимости от выбранного метода, зачисление средств может занять от нескольких минут до пяти рабочих дней.</p>
                        <div class="pl-6">
                            <p>Для того чтобы пополнить кошелек перейдите на вкладку «профиль» затем в меню выберите пункт «Пополнить баланс»</p>
                            <img src="{{asset('images/Group 548.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                            <p>На открывшейся странице введите сумму пополнения и выберите желаемый способ пополнения</p>
                            <img src="{{asset('images/Group 548 (1).png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                            <p>После этого произойдет переход на страницу отплаты</p>
                            <img src="{{asset('images/securepayments.tinkoff.ru_ttRUxstJ.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                            <p>В зависимости от выбранного способа оплаты, зачисление средств может занять от нескольких минут до пяти рабочих дней.</p>
                        </div>
                    </div>
                </li>
                <li id="refund-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleRefundInfo()">
                        <span class="hover-text-blue">Возврат средств</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="refund-text" class="hidden mt-2 text-xl dropdown-content pl-6" style="line-height: 1.8; padding-left: 2rem;">
                        <p>ГдеЗапчасть.рф заботится о комфорте и финансовой безопасности своих пользователей. Если у вас возникла необходимость вернуть неиспользованные средства с баланса кошелька, мы предоставляем такую возможность в соответствии с нашей политикой возврата.</p>
                        <div class="pl-6">
                            <p class="mt-4"><strong>Процедура возврата:</strong></p>
                            <ol class="list-decimal pl-6">
                                <li>Для инициирования возврата средств необходимо обратиться в службу технической поддержки. Наши специалисты оперативно обработают ваш запрос и предоставят всю необходимую информацию.</li>
                                <li>Возврат осуществляется только на те реквизиты, с которых был произведен первоначальный платеж. Это обеспечивает дополнительную защиту ваших финансов и соответствует требованиям безопасности.</li>
                                <li>Сумма возврата не может превышать текущий баланс вашего кошелька на платформе. Возврату подлежат только неиспользованные средства.</li>
                                <li>После подтверждения запроса на возврат нашей службой поддержки, обработка платежа может занять до 10 рабочих дней, в зависимости от метода оплаты и банка-эмитента.</li>
                            </ol>
                        </div>
                        <div class="pl-6">
                            <p class="mt-4">Мы стремимся сделать процесс возврата средств максимально простым и прозрачным для наших пользователей. Если у вас возникнут дополнительные вопросы по этой процедуре, наша служба поддержки всегда готова предоставить подробные разъяснения и помощь.</p>
                        </div>
                        <div class="pl-6">
                            <p class="mt-4"><strong>Обратите внимание:</strong> Комиссии за уже оказанные услуги и использованные функции платформы возврату не подлежат.</p>
                        </div>
                    </div>
                </li>
                <li class="flex items-center"><span>Часто задаваемые вопросы</span> <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 arrow-icon" alt=""></li>
            </ul>
        </div>
        
        <div id="product-requirements" class="mb-8 ">
            <h2 class="text-2xl font-bold mb-10 text-center">Размещение товаров</h2>
            <ul class="space-y-8 font-medium ">
                <li id="file-requirements-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleFileRequirementsInfo()">
                        <span class="hover-text-blue">Требования к размещаемым товарам</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="file-requirements-text" class="hidden mt-2 text-xl dropdown-content">
                        <!-- Текст "Предложение должно быть конкретным..." -->
                        <div id="product-requirements-text" class="mt-2 text-xl dropdown-content">
                            <p class="mt-4"><strong>Предложение должно быть конкретным, существующим и актуальным:</strong></p>
                            <ul class="list-disc pl-6">
                                <li>Товар должен быть у вас в наличии.</li>
                                <li>Размещайте предложение о конкретном существующем имеющем потребительскую ценность товаре.</li>
                                <li>Запрещается размещать несколько товаров в одной карточке товара, за исключением случаев, если они составляют комплект.</li>
                                <li>Не размещайте предложения о продаже товаров через аукцион или с торгов.</li>
                                <li>Если вы продали товар или ваше предложение перестало быть актуальным по другим причинам, снимите его с публикации как можно скорее.</li>
                                <li>Не размещайте в карточке товара информацию общего рекламного характера без конкретного предложения. Размещение товаров на ГдеЗапчасть.рф производится в целях информирования пользователей о наличии товаров, возможности и условиях совершения сделок.</li>
                                <li>Не размещайте предложение о товаре без цели заключить сделку в отношении именно того товара, который указан в карточке товара (фиктивные предложения). Запрещено размещать предложения о товарах для целей лидогенерации — получения контактных данных потенциальных покупателей. В тестовом режиме проводятся усиленные проверки актуальности предложений для выявления фиктивных.</li>
                            </ul>
                            <p class="mt-4"><strong>Информация в карточке товара должна быть достоверной и полной:</strong></p>
                            <ul class="list-disc pl-6">
                                <li>Мы рассматриваем как грубое нарушение правил ГдеЗапчасть.рф размещение в карточке товара любой информации, которая может ввести в заблуждение относительно вашего товара или условий вашего предложения.</li>
                                <li>Если ваше предложение должно в силу закона содержать какие-либо сведения, вы обязаны их указать.</li>
                                <li>Вы самостоятельно несете всю ответственность за распространение предложения товара, в котором отсутствуют обязательные сведения или указана недостоверная информация.</li>
                            </ul>
                            <p class="mt-4"><strong>Предложение должно быть законным:</strong></p>
                            <ul class="list-disc pl-6">
                                <li>Не размещайте в карточке товара предложения, условия или информацию, нарушающие законодательство Российской Федерации или права третьих лиц, либо способствующие таким нарушениям.</li>
                                <li>Вы вправе размещать предложения только о тех товарах, в отношении которых у вас есть все необходимые права, лицензии, регистрации, разрешения, сертификаты и т.п.</li>
                                <li>До размещения предложения о товаре вы обязаны убедиться в законности всех его элементов (включая фотографии, видео, ссылки на сторонние сайты, когда они допустимы, и т.п.). За любые допущенные вами нарушения законодательства или прав третьих лиц вы будете нести ответственность самостоятельно.</li>
                            </ul>
                            <p class="mt-4"><strong>Ответственность сторон:</strong></p>
                            <ul class="list-disc pl-6">
                                <li>Продавец несет полную ответственность за всю информацию, размещаемую в карточке товара, включая описание, характеристики, фотографии, цену и условия продажи.</li>
                                <li>Платформа ГдеЗапчасть.рф предоставляет только информационные услуги и не несет ответственности за содержание объявлений, размещаемых продавцами.</li>
                                <li>Размещая информацию о товаре, продавец подтверждает ее достоверность и полноту, а также наличие у него всех необходимых прав, разрешений и сертификатов на продажу данного товара.</li>
                                <li>В случае нарушения законодательства РФ или прав третьих лиц при размещении информации о товаре, всю ответственность за такие нарушения несет продавец.</li>
                                <li>ГдеЗапчасть.рф оставляет за собой право удалять объявления, нарушающие правила платформы или законодательство РФ, без предварительного уведомления продавца.</li>
                                <li>Пользователи платформы самостоятельно несут ответственность за свои действия, связанные с размещением и использованием информации на ГдеЗапчасть.рф, в соответствии с действующим законодательством РФ.</li>
                            </ul>
                        </div>
                
                        <!-- Вложенный блок с "Термины и определения" -->
                        <div id="terms-item" class="flex flex-col items-start mt-4">
                            <div class="flex items-center cursor-pointer" onclick="toggleTermsInfo()">
                                <span class="hover-text-blue">Термины и определения</span>
                                <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                            </div>
                            <div id="terms-text" class="hidden mt-2 text-xl dropdown-content">
                                <p>ГдеЗапчасть.рф - это онлайн-платформа, предназначенная для размещения информации о товарах и предложениях продавцов, с целью информирования пользователей о возможности совершения сделок купли-продажи. Платформа предоставляет информационные услуги и не несет ответственности за содержание размещаемых объявлений.</p>
                                <ul class="list-disc pl-6 mt-4">
                                    <li><strong>Продавец</strong> - это лицо, которое имеет в наличии конкретный товар и размещает информацию о нем на платформе ГдеЗапчасть.рф с целью продажи. Продавец несет полную ответственность за достоверность и полноту размещаемой информации, а также за соответствие предлагаемого товара законодательству РФ.</li>
                                    <li><strong>Пользователь (покупатель, посетитель)</strong> - это лицо, которое использует платформу ГдеЗапчасть.рф для поиска и ознакомления с информацией о товарах, предлагаемых продавцами. Пользователь может выступать в роли потенциального покупателя, имеющего возможность совершить сделку на основе размещенной информации. Пользователь самостоятельно несет ответственность за свои действия, связанные с использованием информации на платформе.</li>
                                    <li><strong>Карточка товара</strong> - это структурированное описание конкретного товара на платформе ГдеЗапчасть.рф, содержащее полную и достоверную информацию о предлагаемом товаре, включая его наличие, потребительскую ценность, цену и условия продажи. Карточка товара предназначена для информирования пользователей о возможности совершения сделки в отношении данного товара и должна соответствовать требованиям актуальности, законности и полноты предоставляемой информации. Она не должна содержать общую рекламную информацию, фиктивные предложения или вводить пользователей в заблуждение относительно товара или условий его приобретения.</li>
                                    <li><strong>Товар</strong> - это конкретный, существующий и актуальный продукт или изделие, имеющее потребительскую ценность, которое продавец имеет в наличии и предлагает к продаже через размещение информации о нем на платформе ГдеЗапчасть.рф с целью заключения сделки купли-продажи. Товар должен соответствовать законодательству РФ, иметь все необходимые разрешения и сертификаты, а информация о нем должна быть достоверной, полной и не вводить покупателей в заблуждение.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </li>
        
                <li id="price-list-upload-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="togglePriceListUploadInfo()">
                        <span class="hover-text-blue">Загрузка товаров из прайс-листа</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="price-list-upload-text" class="hidden mt-2 text-xl dropdown-content">
                        <!-- Текст "Наша платформа предоставляет удобные инструменты..." -->
                        <div id="upload-intro-text" class="mt-2 text-xl">
                            <p>Наша платформа предоставляет удобные инструменты для загрузки товаров компаниями, специализирующимися на продаже автозапчастей и разборке автомобилей. Доступны два способа импорта товаров:</p>
                            <ol class="list-decimal pl-6">
                                <li><strong>Прямой импорт</strong>
                                    <p>Предназначен для загрузки прайс-листов, соответствующих установленному формату. При соблюдении требований к структуре файла, товары автоматически добавляются в общую базу данных.</p>
                                </li>
                                <li><strong>Импорт с конвертацией</strong>
                                    <p>Если формат вашего прайс-листа отличается от стандартного (иное количество столбцов или способ организации данных), воспользуйтесь встроенным конвертером. Сервис автоматически распределит информацию по соответствующим параметрам и загрузит товары в систему.</p>
                                </li>
                            </ol>
                            <p class="mt-4">Рекомендуем заполнять всю возможную информацию о запчасти – вероятность, что такие товары найдут посетители больше.</p>
                            <p class="mt-4">Технические требования к файлам и подробные инструкции по работе с сервисами импорта доступны в соответствующих разделах справки.</p>
                            <p class="mt-4"><strong>Примечание:</strong> Конвертер прайс-листов предоставляется бесплатно всем компаниям, размещающим товары на платформе.</p>
                        </div>
                
                        <!-- Вложенный блок "Прямой импорт товаров из прайс-листа" -->
                        <div id="direct-price-list-import-item" class="flex flex-col items-start mt-4">
                            <div class="flex items-center cursor-pointer" onclick="toggleDirectPriceListImportInfo()">
                                <span class="hover-text-blue">Прямой импорт товаров из прайс-листа</span>
                                <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                            </div>
                            <div id="direct-price-list-import-text" class="hidden mt-2 text-xl dropdown-content">
                                <!-- Вложенный блок "Прямая загрузка товаров на сайт" -->
                                <div id="direct-upload-item" class="flex flex-col items-start mt-4">
                                    <div class="flex items-center cursor-pointer" onclick="toggleDirectUploadInfo()">
                                        <span class="hover-text-blue">Требование к файлу</span>
                                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                                    </div>
                                    <div id="direct-upload-text" class="hidden mt-2 text-xl dropdown-content">
                                        <ul class="list-disc pl-6">
                                            <li><strong>Общие требования:</strong>
                                                <ol class="list-decimal pl-6">
                                                    <li>Названия и порядок столбцов должен быть такой же как в файле-примере.</li>
                                                    <li>Должно быть заполнено обязательное поле «цена».</li>
                                                    <li>Написание названия марок и моделей автомобилей в вашем файле должно соответствовать принятому написанию названий марок и моделей автомобилей на ГдеЗапчасть.рф. <a href="#" class="text-blue-500">Скачать файл</a>.</li>
                                                    <li>Наименование товарных позиций должно быть в каждой строчке.</li>
                                                    <li>В одной строке — один товар. Если вы продаете товары в комплекте, это необходимо указать в наименовании товара.</li>
                                                    <li>Все товары должны быть на одном листе с общим форматированием.</li>
                                                    <li>Названия товаров должны быть максимально простые и понятные, без сокращений и аббревиатур. Это нужно для того, чтобы ваши товары с большей вероятностью попали в правильный раздел на сайте и корректно распознались.</li>
                                                    <li>Поле "срок доставки" в прайс-листе может использоваться по-разному:
                                                        <ul class="list-disc pl-6">
                                                            <li>Для указания времени перемещения товара между филиалами вашего магазина в пределах одного города (если применимо).</li>
                                                            <li>Оставьте это поле пустым, если товар есть в наличии.</li>
                                                            <li>Обратите внимание, что наш прайс-лист унифицирован с форматами других популярных площадок для удобства продавцов. Однако на нашем сайте размещаются только товары, имеющиеся в наличии.</li>
                                                        </ul>
                                                    </li>
                                                    <li>Если не будет заполнена поле «марка» - мы пометим товар как универсальный.</li>
                                                    <li>Столбец «применимость» можно использовать как для перечисления автомобилей к которым подходит запчасть, так и для подробного описания товара.</li>
                                                </ol>
                                            </li>
                                            <li><strong>Поддерживаемые форматы файлов:</strong>
                                                <ul class="list-disc pl-6">
                                                    <li>CSV. Кодировка Windows-1251, разделитель "точка с запятой".</li>
                                                    <li>XLSX. Кодировка Windows-1251 или UTF8.</li>
                                                </ul>
                                            </li>
                                            <li><strong>Пример прайс-листа:</strong>
                                                <img src="{{asset('images/Group 543 (2).png')}}" class="border w-3/3 my-4 zoomable-image" alt="">
                                            </li>
                                            <li><strong>Изображение товара:</strong>
                                                <p>Чтобы у ваших товаров были изображения, необходимо вставить в столбец «Фотографии» напротив каждого товара прямую ссылку на картинку с вашего сайта, либо с любого другого интернет-ресурса. Можно добавить несколько ссылок через запятую. К одному товару можно добавить не более 5 ссылок на изображения.</p>
                                            </li>
                                            <li><strong>Поле «Цена»:</strong>
                                                <p>Поле «Цена» должно содержать только числовое значение (без знаков препинания и/или текста).</p>
                                                <table class="w-full border-collapse border border-gray-300 mt-4">
                                                    <thead>
                                                        <tr>
                                                            <th class="border border-gray-300 p-2">Названия поля</th>
                                                            <th class="border border-gray-300 p-2">Обязательное поле</th>
                                                            <th class="border border-gray-300 p-2">Формат поля</th>
                                                            <th class="border border-gray-300 p-2">Пример заполнения</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Артикул</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">AR-417</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Наименование товара</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Бампер</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Новый или б.у.</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Новый</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Марка</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Toyota</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Модель</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Corolla</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Кузов</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">AE110</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Номер</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">ST-TY27-000-A0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Производитель</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">SAT</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Двигатель</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">5A-FE</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Год</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Числовое поле</td>
                                                            <td class="border border-gray-300 p-2">1999</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">L-R</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">F-R</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">F</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">U-D</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Цвет</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Черный</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Номера замен</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">52119-1E330</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Применимость</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Примечание</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">Бампер toyota corolla 95-97 нижняя часть (пр-во тайвань)</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Количество</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2">1</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Цена</td>
                                                            <td class="border border-gray-300 p-2">Да</td>
                                                            <td class="border border-gray-300 p-2">Числовое поле</td>
                                                            <td class="border border-gray-300 p-2">7900</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Наличие</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Текстовое поле</td>
                                                            <td class="border border-gray-300 p-2"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class="border border-gray-300 p-2">Фотография</td>
                                                            <td class="border border-gray-300 p-2">Нет</td>
                                                            <td class="border border-gray-300 p-2">Ссылка</td>
                                                            <td class="border border-gray-300 p-2">https://i.ibb.co/T0pdLbX/416761.jpg</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                
                                <!-- Вложенный блок "Как импортировать товары из прайс-листа" -->
                                <div id="import-item" class="flex flex-col items-start mt-4">
                                    <div class="flex items-center cursor-pointer" onclick="toggleImportInfo()">
                                        <span class="hover-text-blue">Как импортировать товары из прайс-листа</span>
                                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                                    </div>
                                    <div id="import-text" class="hidden mt-2 text-xl dropdown-content">
                                        <p>Прейдите на вкладку «Разместить товары» и выберите пункт «Загрузить товары из прайс-листа».</p>
                                        <img src="{{asset('images/Group 549 (1).png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>Затем нажмите «Выберите файл» в способе загрузки «Прямая загрузка товаров из прайс-листа»</p>
                                        <img src="{{asset('images/Group 550.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>Затем выберите файл из которого хотите импортировать товары и нажмите «открыть»</p>
                                        <img src="{{asset('images/inst2.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>После того как вы выбрали файл нажмите «Добавить товары на сайт» - товары будут загружены на сайт. Процесс импорта может занять от нескольких секунд до нескольких минут – в зависимости от размера вашего файла и загруженности сервера.</p>
                                        <img src="{{asset('images/Group 550 (1).png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                
                        <!-- Вложенный блок "Конвертация прайс-листа и импорт товаров" -->
                        <div id="price-list-conversion-item" class="flex flex-col items-start mt-4">
                            <div class="flex items-center cursor-pointer" onclick="togglePriceListConversionInfo()">
                                <span class="hover-text-blue">Конвертация прайс-листа и импорт товаров</span>
                                <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                            </div>
                            <div id="price-list-conversion-text" class="hidden mt-2 text-xl dropdown-content">
                                <!-- Вложенный блок "Конвертирование прайс-листа и загрузка товаров на сайт" -->
                                <div id="converter-item" class="flex flex-col items-start mt-4">
                                    <div class="flex items-center cursor-pointer" onclick="toggleConverterInfo()">
                                        <span class="hover-text-blue">Что такое конвертация прайс-листа</span>
                                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                                    </div>
                                    <div id="converter-text" class="hidden mt-2 text-xl dropdown-content">

                                        <p><strong>Конвертер прайс-листов:</strong> удобный инструмент для загрузки товаров</p>
                                        <p><strong>Что такое конвертер прайс-листов?</strong> Это встроенный инструмент, который позволяет быстро и легко загрузить ваш прайс-лист в нашу базу данных, даже если он не соответствует стандартному формату. Конвертер автоматически обрабатывает данные из вашего файла и распределяет их по соответствующим полям.</p>
                                        <ul class="list-disc pl-6">
                                            <li><strong>Основные возможности конвертера:</strong>
                                                <ol class="list-decimal pl-6">
                                                    <li>Распознавание информации об автомобиле.</li>
                                                    <li>Обработка данных.</li>
                                                    <li>Оптимизация контента.</li>
                                                    <li>Работа с файлами.</li>
                                                </ol>
                                            </li>
                                            <li><strong>Как это работает?</strong>
                                                <p>Просто загрузите ваш прайс-лист в систему, и конвертер автоматически обработает данные, распределив их по соответствующим полям.</p>
                                            </li>
                                            <li><strong>Какие файлы можно обработать:</strong>
                                                <ol class="list-decimal pl-6">
                                                    <li>Многостраничные файлы Excel (книги Excel), XSLX, CSV, XLS.</li>
                                                    <li>Файлы с различной структурой данных.</li>
                                                    <li>Файлы с нестандартным форматированием цен.</li>
                                                </ol>
                                            </li>
                                        </ul>
                                        <p>Независимо от исходного формата вашего прайс-листа, конвертер быстро обработает данные и подготовит их к загрузке на сайт в стандартизированном виде.</p>
                                        <img src="{{asset('images/Group 332.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p><strong>Важное предупреждение об использовании конвертера прайс-листов</strong></p>
                                        <p>Уважаемые пользователи, обратите внимание на следующую информацию:</p>
                                        <ul class="list-disc pl-6">
                                            <li><strong>Ограничение ответственности:</strong>
                                                <p>Платформа ГдеЗапчасть.рф не несет ответственности за возможные ошибки или неточности в обработанных файлах.</p>
                                            </li>
                                            <li><strong>Обязанность проверки:</strong>
                                                <p>Пользователи обязаны самостоятельно проверять и подтверждать корректность всей информации о своих товарах после обработки файла конвертером.</p>
                                            </li>
                                            <li><strong>Ограничения функционала:</strong>
                                                <p>Конвертер может не распознавать некоторые марки, модели автомобилей и другие параметры.</p>
                                            </li>
                                            <li><strong>Предоставление услуг:</strong>
                                                <p>Компания предоставляет услуги в режиме "как есть".</p>
                                            </li>
                                            <li><strong>Отказ от претензий:</strong>
                                                <p>Используя конвертер прайс-листов, вы соглашаетесь с тем, что платформа ГдеЗапчасть.рф не несет ответственности за любые прямые или косвенные убытки, связанные с использованием данного инструмента.</p>
                                            </li>
                                            <li><strong>Изменения в работе сервиса:</strong>
                                                <p>Платформа оставляет за собой право вносить изменения в работу конвертера без предварительного уведомления пользователей.</p>
                                            </li>
                                        </ul>
                                        <p>Используя конвертер прайс-листов, вы подтверждаете, что ознакомились с данным предупреждением и принимаете все указанные условия.</p>
                                    </div>
                                </div>
                
                                <!-- Вложенный блок "Загрузка товаров с помощью конвертации" -->
                                <div id="conversion-item" class="flex flex-col items-start mt-4">
                                    <div class="flex items-center cursor-pointer" onclick="toggleConversionInfo()">
                                        <span class="hover-text-blue">Загрузка товаров с помощью конвертации</span>
                                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                                    </div>
                                    <div id="conversion-text" class="hidden mt-2 text-xl dropdown-content">
                                        <p>Прейдите на вкладку «Разместить товары» и выберите пункт «Загрузить товары из прайс-листа».</p>
                                        <img src="{{asset('images/Group 549 (1).png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>Затем нажмите «Выберите файл» в способе загрузки «Конвертировать прайс-лист и загрузить товары на сайт».</p>
                                        <img src="{{asset('images/Group 550.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>Затем выберите файл, из которого хотите импортировать товары, и нажмите «Открыть».</p>
                                        <img src="{{asset('images/inst4.png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p>После того как вы выбрали файл, нажмите «Открыть файл» – начнется конвертация прайс-листа, после чего товары будут загружены на сайт. Процесс конвертации и импорта может занять от нескольких секунд до нескольких минут – в зависимости от размера вашего файла и загруженности сервера.</p>
                                        <img src="{{asset('images/Group 550 (1).png')}}" class="border w-2/3 my-4 zoomable-image" alt="">
                                        <p><strong>Примечание:</strong> Если Вы впервые конвертируете файл или настройки конвертера были сброшены, появится окно, в котором потребуется установить параметры для Вашего прайс-листа. После первой конвертации выбранные параметры будут сохранены и автоматически применятся для последующих конвертаций.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
        

                <li id="form-creation-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleFormCreationInfo()">
                        <span class="hover-text-blue">Создание товара с помощью формы</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="form-creation-text" class="hidden mt-2 text-xl dropdown-content">
                        <p>Для размещения товара на платформе воспользуйтесь формой создания карточки товара. Внимательно заполните все поля, отмеченные звездочкой (*) – они являются обязательными для заполнения.</p>
                        <ul class="list-disc pl-6">
                            <li><strong>Основные параметры товара:</strong>
                                <ul class="list-disc pl-6">
                                    <li><strong>Артикул</strong> – уникальный идентификатор товара в вашей системе учета</li>
                                    <li><strong>Название товара*</strong> – полное наименование детали</li>
                                    <li><strong>Номер детали</strong> – оригинальный номер или артикул производителя</li>
                                    <li><strong>Состояние</strong> – выберите состояние запчастей</li>
                                </ul>
                            </li>
                            <li><strong>Характеристики автомобиля:</strong>
                                <ul class="list-disc pl-6">
                                    <li><strong>Марка*</strong> – выберите производителя автомобиля из выпадающего списка</li>
                                    <li><strong>Модель</strong> – укажите модель автомобиля</li>
                                    <li><strong>Год выпуска</strong> – выберите год производства автомобиля</li>
                                    <li><strong>Модель кузова</strong> – укажите тип кузова (если применимо)</li>
                                    <li><strong>Модель двигателя</strong> – укажите модификацию двигателя</li>
                                </ul>
                            </li>
                            <li><strong>Расположение детали:</strong>
                                <ul class="list-disc pl-6">
                                    <li>Слева/Справа</li>
                                    <li>Спереди/Сзади</li>
                                    <li>Сверху/Снизу</li>
                                </ul>
                            </li>
                            <li><strong>Дополнительная информация:</strong>
                                <ul class="list-disc pl-6">
                                    <li><strong>Цвет</strong> – укажите цвет детали (если применимо)</li>
                                    <li><strong>Применимость</strong> – опишите совместимость с другими моделями</li>
                                    <li><strong>Количество</strong> – укажите доступное количество</li>
                                    <li><strong>Цена</strong> – стоимость в рублях</li>
                                    <li><strong>Наличие</strong> – статус товара</li>
                                </ul>
                            </li>
                            <li><strong>Фотографии:</strong>
                                <p>Вы можете добавить до 4 фотографий товара, указав URL-адреса изображений:</p>
                                <ul class="list-disc pl-6">
                                    <li>Основное фото (обязательно)</li>
                                    <li>Три дополнительных фото</li>
                                </ul>
                            </li>
                        </ul>
                        <p class="mt-4">После заполнения всех необходимых полей нажмите кнопку "Добавить товар". Карточка товара появится в общей базе после модерации.</p>
                        <p class="mt-4"><strong>Примечание:</strong> Размещение товаров является платной услугой. Тарифы рассчитываются индивидуально в зависимости от объема размещаемых позиций.</p>
                    </div>
                </li>
                <!-- Пункт 9 -->
                <li class="flex items-center">
                    <span>Часто задаваемые вопросы</span>
                    <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-2 arrow-icon" alt="">
                </li>
            </ul>
        </div>

        <div id="video-instructions" class="mb-8 ">
            <h2 class="text-2xl font-bold mb-10 text-center">Страница «мои товары»</h2>
            <ul class="space-y-8 font-medium">
                <!-- Пункт 1 -->
                <li id="status-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleStatusInfo()">
                        <span class="hover-text-blue">Статусы товаров в личном кабинете</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="status-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>В разделе "Мои товары" каждая позиция имеет один из четырех статусов:</p>
                        <ul class="list-disc pl-8">
                            <li><strong>Активный</strong>
                                <p>Товар доступен для поиска и просмотра всем пользователям сайта. Присваивается при соблюдении двух условий:</p>
                                <ul class="list-disc pl-8">
                                    <li>Оплачена абонентская плата за размещение</li>
                                    <li>Срок размещения не превышает 30 дней</li>
                                </ul>
                            </li>
                            <li><strong>Неактивный</strong>
                                <p>Товар скрыт от поиска и недоступен другим пользователям. Присваивается при отсутствии активной абонентской платы за размещение.</p>
                            </li>
                            <li><strong>Архивный</strong>
                                <p>Товар автоматически перемещается в архив по истечении 30 дней с момента размещения. Архивные товары скрыты от поиска и недоступны другим пользователям.</p>
                            </li>
                            <li><strong>Проданный</strong>
                                <p>Статус присваивается товару после отметки о продаже продавцом. Проданные товары скрыты от поиска и недоступны другим пользователям.</p>
                            </li>
                        </ul>
                    </div>
                </li>
        
                <!-- Пункт 2 -->
                <li id="displayed-items-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleDisplayedItemsInfo()">
                        <span class="hover-text-blue">Отображаемые товары</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="displayed-items-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <video src="{{asset('videos/отображаемые товары.mov')}}" controls class="my-8">
                            Ваш браузер не поддерживает воспроизведение видео.
                        </video>
                        <p>В списке товаров вы можете выбрать, какие позиции хотите видеть, используя фильтр "Отображаемые товары":</p>
                        <ul class="list-disc pl-8">
                            <li><strong>Активные (по умолчанию)</strong> — показываются только товары с активным статусом размещения</li>
                            <li><strong>Все</strong> — отображаются товары со всеми статусами</li>
                            <li><strong>Неактивные</strong> — отображаются только товары с неактивным статусом</li>
                            <li><strong>Архивные</strong> — отображаются только товары, перемещенные в архив</li>
                            <li><strong>Проданные</strong> — отображаются только товары, отмеченные как проданные</li>
                        </ul>
                        <p class="mt-4">Выбранный фильтр применяется только к вашему списку товаров и не влияет на их видимость для других пользователей.</p>
                    </div>
                </li>
        
                <!-- Пункт 3 -->
                <li id="search-products-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleSearchProductsInfo()">
                        <span class="hover-text-blue">Поиск по товарам</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="search-products-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <video src="{{asset('videos/поиск по товарам.mov')}}" controls class="my-8">
                            Ваш браузер не поддерживает воспроизведение видео.
                        </video>
                        <p>Для быстрого поиска нужных позиций в вашем списке товаров предусмотрены два фильтра:</p>
                        <ul class="list-disc pl-8">
                            <li><strong>Поисковая строка</strong> — позволяет искать товары по наименованию или номеру запчасти. Достаточно ввести полное название или его часть, либо артикул/номер детали.</li>
                            <li><strong>Марка автомобиля</strong> — выпадающий список с марками автомобилей, присутствующими в ваших товарах.</li>
                        </ul>
                        <p class="mt-4">Фильтры можно использовать как по отдельности, так и совместно для более точного поиска. Например, вы можете отфильтровать все товары определенной марки, а затем найти конкретную деталь через поисковую строку.</p>
                        <p class="mt-4">Данный функционал помогает эффективно управлять вашим списком товаров и быстро находить нужные позиции при работе с большим количеством наименований.</p>
                    </div>
                </li>
        
                <!-- Пункт 4 -->
                <li id="edit-product-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleEditProductInfo()">
                        <span class="hover-text-blue">Редактирование товара</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="edit-product-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <video src="{{asset('videos/редактирвование товара.mov')}}" controls class="my-8">
                            Ваш браузер не поддерживает воспроизведение видео.
                        </video>
                        <p>Для внесения изменений в существующий товар используйте иконку редактирования (синий карандаш) в соответствующей строке таблицы. При нажатии на иконку откроется форма редактирования, содержащая все поля выбранного товара.</p>
                        <p class="mt-4">В форме редактирования вы можете изменить любые характеристики товара. Доступные поля соответствуют столбцам основной таблицы.</p>
                        <p class="mt-4">После внесения необходимых изменений нажмите кнопку "Сохранить" в нижней части формы. Изменения будут применены, а форма редактирования автоматически закроется.</p>
                        <p class="mt-4">Чтобы отменить редактирование, закройте форму с помощью крестика в правом верхнем углу или кликнув за пределами формы. При закрытии формы внесенные изменения не сохранятся.</p>
                        <p class="mt-4">Внесенные изменения сразу отобразятся в основной таблице товаров.</p>
                    </div>
                </li>
        
                <!-- Пункт 5 -->
                <li id="deletion-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleDeletionInfo()">
                        <span class="hover-text-blue">Удаление товаров</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="deletion-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <!-- Удаление одного товара -->
                        <div id="single-deletion-item" class="flex flex-col items-start mt-4 ">
                            <div class="flex items-center cursor-pointer" onclick="toggleSingleDeletionInfo()">
                                <span class="hover-text-blue">Удаление одного товара</span>
                                <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                            </div>
                            <div id="single-deletion-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                                <video src="{{asset('videos/удаление одного.mov')}}" controls class="my-8">
                                    Ваш браузер не поддерживает воспроизведение видео.
                                </video>
                                <p>Для удаления товара используйте иконку удаления (красная урна) в соответствующей строке таблицы. При нажатии на иконку появится диалоговое окно с запросом подтверждения удаления.</p>
                                <p class="mt-4">В окне подтверждения вам будет предложено подтвердить или отменить удаление выбранного товара. Это сделано для предотвращения случайного удаления данных.</p>
                                <p class="mt-4">Для подтверждения удаления нажмите кнопку "Удалить". После этого товар будет безвозвратно удален из системы.</p>
                                <p class="mt-4">Чтобы отменить удаление, нажмите кнопку "Отмена" или закройте диалоговое окно с помощью крестика в правом верхнем углу.</p>
                                <p class="mt-4">После подтверждения удаления товар исчезнет из таблицы, и восстановить его будет невозможно.</p>
                                <p class="mt-4"><strong>Примечание:</strong> Отменить массовое удаление после подтверждения будет невозможно. Пожалуйста, внимательно проверяйте список выбранных товаров перед удалением.</p>
                            </div>
                        </div>
        
                        <!-- Удаление нескольких товаров -->
                        <div id="multiple-deletion-item" class="flex flex-col items-start mt-4">
                            <div class="flex items-center cursor-pointer" onclick="toggleMultipleDeletionInfo()">
                                <span class="hover-text-blue">Удаление нескольких товаров</span>
                                <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                            </div>
                            <div id="multiple-deletion-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                                <video src="{{asset('videos/удаление нескольких.mov')}}" controls class="my-8">
                                    Ваш браузер не поддерживает воспроизведение видео.
                                </video>
                                <ol class="list-decimal pl-8">
                                    <li>Нажмите кнопку "Выбрать" в центральной части верхней панели страницы.</li>
                                    <li>Слева от каждого товара появятся чекбоксы для выбора.</li>
                                    <li>Отметьте товары, которые необходимо удалить.</li>
                                    <li>Нажмите кнопку "Удалить выбранные" на верхней панели.</li>
                                    <li>В появившемся диалоговом окне подтвердите удаление.</li>
                                </ol>
                                <p class="mt-4">После подтверждения выбранные товары будут безвозвратно удалены из системы.</p>
                                <p class="mt-4"><strong>Примечание:</strong> Отменить массовое удаление после подтверждения будет невозможно. Пожалуйста, внимательно проверяйте список выбранных товаров перед удалением.</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>

        <div id="market-analysis" class="mb-8">
            <h2 class="text-2xl font-bold mb-12 text-center">Анализ рынка</h2>
            <ul class="space-y-8 font-medium">
                <!-- Подраздел "Анализ рынка автозапчастей" -->
                <li id="market-analysis-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleMarketAnalysisInfo()">
                        <span class="hover-text-blue">Анализ рынка автозапчастей - профессиональный инструмент для бизнеса</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="market-analysis-text" class="hidden mt-4 text-xl dropdown-content">
                        <p><strong>Анализ рынка</strong> - это специализированный инструмент для компаний, работающих на рынке автозапчастей. Сервис предоставляет детальную аналитику по товарным позициям и конкурентной среде, помогая принимать взвешенные бизнес-решения.</p>
                        
                        <p class="mt-6"><strong>Основные возможности сервиса:</strong></p>
                        <ul class="list-disc pl-8 space-y-4">
                            <li><strong>Анализ конкурентной среды</strong>
                                <ul class="list-disc pl-8 space-y-2">
                                    <li>Выявление компаний, предлагающих аналогичные товары</li>
                                    <li>Определение количества активных продавцов по каждой позиции</li>
                                    <li>Мониторинг ассортимента конкурентов</li>
                                </ul>
                            </li>
                            <li><strong>Ценовой анализ</strong>
                                <ul class="list-disc pl-8 space-y-2">
                                    <li>Расчет средней рыночной стоимости товаров</li>
                                    <li>Определение минимальных и максимальных цен на рынке</li>
                                    <li>Сравнительный анализ собственного ценообразования</li>
                                </ul>
                            </li>
                        </ul>
    
                        <p class="mt-6"><strong>Особенности работы сервиса:</strong></p>
                        <ul class="list-disc pl-8 space-y-4">
                            <li>Использование передовых алгоритмов интеллектуального поиска</li>
                            <li>Обработка данных в режиме реального времени</li>
                            <li>Доступ к актуальной информации по всей базе товаров платформы</li>
                        </ul>
    
                        <p class="mt-6"><strong>Преимущества использования:</strong></p>
                        <ul class="list-disc pl-8 space-y-4">
                            <li>Оптимизация ценовой политики</li>
                            <li>Выявление новых рыночных возможностей</li>
                            <li>Повышение конкурентоспособности бизнеса</li>
                        </ul>
    
                        <p class="mt-6">Сервис "Анализ рынка" предоставляется бесплатно всем компаниям, размещающим товары на платформе ГдеЗапчасть. Данные обновляются автоматически при каждом запросе, что гарантирует актуальность получаемой информации.</p>
    
                        <p class="mt-6"><strong>Для доступа к сервису необходимо:</strong></p>
                        <ol class="list-decimal pl-8 space-y-4">
                            <li>Быть зарегистрированным как компания-продавец</li>
                            <li>Иметь активное размещение товаров на платформе</li>
                            <li>Авторизоваться в личном кабинете</li>
                        </ol>
    
                        <p class="mt-6">По всем вопросам работы сервиса обращайтесь в службу поддержки ГдеЗапчасть.</p>
                    </div>
                </li>
            </ul>
        </div>
    @endif
        <div id="recomen" class="mb-8">
            <h2 class="font-bold mb-10 text-center text-2xl">Поиск товаров и рекомендательные технологии, политика обработки информации и cookie</h2>
            <ul class="space-y-8 font-medium">
                <li id="search-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleSearchInfo()">
                        <span class="hover-text-blue">Поиск товаров</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="search-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>ГдеЗапчасть предлагает инновационную систему поиска автозапчастей, разработанную для максимального удобства пользователей. Наш сервис позволяет эффективно находить необходимые детали как в магазинах, так и на авторазборах.</p>
                        
                        <div class="pl-8">
                            <p class="mt-4"><strong>Ключевые особенности системы поиска:</strong></p>
                            <div class="pl-8">
                                <p><strong>Интеллектуальный поиск</strong></p>
                                <ul class="list-disc pl-8">
                                    <li><strong>Мультивариантные запросы:</strong> При заполнении всех полей формы поиска система автоматически определяет совместимость запчасти с другими моделями автомобилей. Поиск осуществляется по всем возможным комбинациям, что существенно расширяет диапазон результатов.</li>
                                    <li><strong>Интегрированный автомобильный каталог:</strong> На основе введенных данных об автомобиле система идентифицирует оригинальный каталожный номер запчасти и находит все номера аналогов других производителей. Поиск выполняется по всем найденным номерам.</li>
                                    <li><strong>Реверсивный поиск по номеру запчасти:</strong> При вводе номера запчасти система определяет оригинальный номер, находит все совместимые аналоги и модели автомобилей, для которых подходит данная деталь. Поиск производится по всем выявленным вариантам.</li>
                                </ul>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>Стандартный поиск</strong></p>
                            <div class="pl-8">
                                <p>Если система не может применить алгоритмы интеллектуального поиска, выполняется стандартный поиск по введенным данным. При указании года выпуска автомобиля система автоматически определяет его поколение и осуществляет поиск по всем годам выпуска в рамках этого поколения.</p>
                                <p><strong>Особенности стандартного поиска:</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Точное соответствие введенным параметрам</li>
                                    <li>Отсутствие поиска по аналогам и связанным запросам</li>
                                    <li>Менее обширные, но релевантные результаты</li>
                                </ul>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>Рекомендации для эффективного поиска:</strong></p>
                            <div class="pl-8">
                                <ul class="list-disc pl-8">
                                    <li>Заполняйте все поля формы поиска</li>
                                    <li>При поиске по номеру запчасти вводите номер без пробелов и знаков препинания</li>
                                    <li>Все функции работают автоматически и не требуют специальных знаний</li>
                                </ul>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4">ГдеЗапчасть непрерывно совершенствует систему поиска автозапчастей для обеспечения максимального удобства пользователей. По всем вопросам и предложениям обращайтесь в службу поддержки.</p>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>Отказ от ответственности</strong></p>
                            <div class="pl-8">
                                <p>Уважаемые пользователи, обратите внимание на следующую информацию:</p>
                                <p><strong>1. Общие положения</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Используя систему поиска на сайте ГдеЗапчасть (далее - "Сервис"), пользователь принимает все условия, изложенные в настоящей оговорке.</li>
                                    <li>Сервис предоставляется на условиях "как есть" без каких-либо гарантий.</li>
                                </ul>
                            </div>
        
                            <div class="pl-8">
                                <p><strong>2. Ограничение ответственности</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Сервис прилагает все разумные усилия для обеспечения точности и актуальности результатов поиска, однако не гарантирует:
                                        <ul class="list-disc pl-8">
                                            <li>полноту и достоверность результатов поиска;</li>
                                            <li>точное соответствие найденных запчастей указанной модели автомобиля;</li>
                                            <li>наличие товара у продавцов на момент обращения;</li>
                                            <li>соответствие цен, указанных в результатах поиска, действительным ценам продавцов.</li>
                                        </ul>
                                    </li>
                                    <li>Сервис не несет ответственности за:
                                        <ul class="list-disc pl-8">
                                            <li>любой прямой или косвенный ущерб, возникший в результате использования системы поиска;</li>
                                            <li>решения, принятые пользователем на основе полученных результатов поиска;</li>
                                            <li>технические сбои и временную недоступность функций поиска.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
        
                            <div class="pl-8">
                                <p><strong>3. Интеллектуальная собственность</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Все права на систему поиска, включая алгоритмы и базы данных, принадлежат Сервису.</li>
                                    <li>Любое несанкционированное использование системы поиска запрещено.</li>
                                </ul>
                            </div>
        
                            <div class="pl-8">
                                <p><strong>4. Изменения и дополнения</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Сервис оставляет за собой право в любое время без предварительного уведомления:
                                        <ul class="list-disc pl-8">
                                            <li>изменять функциональность системы поиска;</li>
                                            <li>модифицировать алгоритмы поиска;</li>
                                            <li>вносить изменения в настоящую оговорку.</li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
        
                            <div class="pl-8">
                                <p><strong>5. Заключительные положения</strong></p>
                                <ul class="list-disc pl-8">
                                    <li>Использование системы поиска означает полное согласие с настоящей оговоркой.</li>
                                    <li>В случае несогласия с какими-либо положениями пользователь должен прекратить использование системы поиска.</li>
                                    <li>Все споры, возникающие при использовании системы поиска, решаются путем переговоров, а при недостижении согласия - в судебном порядке по месту нахождения Сервиса в соответствии с законодательством РФ.</li>
                                </ul>
                            </div>
        
                            <div class="pl-8">
                                <p class="mt-4">Используя платформу ГдеЗапчасть, вы подтверждаете, что ознакомились с данным предупреждением и принимаете все указанные условия.</p>
                            </div>
                        </div>
                    </div>
                </li>
                <li id="recommendations-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleRecommendationsInfo()">
                        <span class="hover-text-blue">Рекомендательные технологии</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="recommendations-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>Рекомендательные технологии - информационные технологии предоставления информации на основе сбора, систематизации и анализа сведений, относящихся к предпочтениям пользователей сети «Интернет», находящихся на территории Российской Федерации.</p>
                        
                        <div class="pl-8">
                            <p class="mt-4"><strong>Система рекомендаций на платформе ГдеЗапчасть работает следующим образом:</strong></p>
                            <div class="pl-8">
                                <ol class="list-decimal pl-8">
                                    <li><strong>Сбор и анализ пользовательских данных:</strong>
                                        <ul class="list-disc pl-8">
                                            <li>Сервис собирает информацию о просмотренных автозапчастях</li>
                                            <li>Сохраняет историю поисковых запросов</li>
                                            <li>Отслеживает взаимодействие с карточками товаров</li>
                                            <li>Анализирует предпочтения по категориям и наименованиям запчастей</li>
                                        </ul>
                                    </li>
                                    <li><strong>Обработка собранной информации:</strong>
                                        <ul class="list-disc pl-8">
                                            <li>Формирование профиля пользовательских интересов</li>
                                            <li>Определение наиболее релевантных категорий запчастей</li>
                                            <li>Анализ частоты поиска определенных деталей</li>
                                            <li>Учет просмотренных товаров</li>
                                        </ul>
                                    </li>
                                    <li><strong>Формирование рекомендаций:</strong>
                                        <ul class="list-disc pl-8">
                                            <li>Подбор наиболее подходящих автозапчастей на основе собранных данных</li>
                                            <li>Отображение просмотренных ранее товаров</li>
                                            <li>Предложение сопутствующих деталей и комплектующих</li>
                                            <li>Показ персонализированной рекламы товаров и услуг</li>
                                        </ul>
                                    </li>
                                    <li><strong>Улучшение результатов поиска:</strong>
                                        <ul class="list-disc pl-8">
                                            <li>Приоритетное отображение наиболее подходящих запчастей</li>
                                            <li>Учет популярности товаров среди пользователей</li>
                                            <li>Анализ совместимости с указанной моделью автомобиля</li>
                                            <li>Формирование релевантных предложений</li>
                                        </ul>
                                    </li>
                                </ol>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4">Вся собранная информация используется исключительно для улучшения качества обслуживания и подбора наиболее подходящих автозапчастей для каждого клиента. Данные пользователей надежно защищены и обрабатываются в соответствии с законодательством о защите персональных данных.</p>
                            <p class="mt-4">Используя платформу ГдеЗапчасть, вы соглашаетесь с тем, что мы собираем и анализируем данные о ваших действиях для улучшения качества сервиса и предоставления более точных рекомендаций.</p>
                        </div>
                    </div>
                </li>
                <li id="cookies-item" class="flex flex-col items-start">
                    <div class="flex items-center cursor-pointer" onclick="toggleCookiesInfo()">
                        <span class="hover-text-blue">Политика в отношении файлов cookie</span>
                        <img src="{{asset('images/DownCircle.png')}}" class="w-6 h-6 ml-4 cursor-pointer arrow-icon" alt="">
                    </div>
                    <div id="cookies-text" class="hidden mt-4 text-xl dropdown-content pl-8">
                        <p>С мая 2018 года действуют общие правила по защите персональных данных The EU General Data Protection Regulation (GDPR).</p>
                        <p>Цель GDPR — обеспечить безопасность персональных данных граждан, вне зависимости от их физического месторасположения.</p>
                        <p>Файлы cookie используются для оптимизации работы с сайтом. Данные файлы позволяют запомнить сделанный вами выбор (например, выбор города, в котором вы находитесь) для того, чтобы предоставить вам лучшее онлайн-предложение.</p>
                        <p><strong>Важно:</strong> файлы cookie не несут угрозы безопасности вашим данным.</p>
                        
                        <div class="pl-8">
                            <p class="mt-4"><strong>ЧТО ТАКОЕ COOKIE?</strong></p>
                            <div class="pl-8">
                                <p>Файлы cookie - это небольшие файлы с данными, которые сохраняются на вашем компьютере или мобильном устройстве, веб-сервисом/браузером при посещении веб-сайта. Файлы cookie широко используются владельцами веб-сайтов для того, чтобы их веб-сайты работали более эффективно.</p>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>КАКИЕ COOKIE ИСПОЛЬЗУЮТСЯ?</strong></p>
                            <div class="pl-8">
                                <p>Мы используем файлы cookie и аналогичные технологии, которые подразделяются на следующие категории:</p>
                                <ul class="list-disc pl-8">
                                    <li><strong>Постоянные:</strong> Такие файлы хранятся до окончания их срока действия или пока Вы их не удалите.</li>
                                    <li><strong>Сессионные:</strong> Такой вид файлов хранится только на протяжении вашего сеанса в браузере и при закрытии удаляются.</li>
                                    <li><strong>Сторонние:</strong> Такой вид файлов, которые принадлежат домену, отличающемуся от домена, указанного в адресной строке. Наиболее часто, такие cookie используются, когда веб-страница содержит контент с внешних веб-сайтов.</li>
                                </ul>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>КАКИЕ ТИПЫ COOKIE ИСПОЛЬЗУЮТСЯ И КАКОВА ЦЕЛЬ ИХ ИСПОЛЬЗОВАНИЯ?</strong></p>
                            <div class="pl-8">
                                <ul class="list-disc pl-8">
                                    <li><strong>Тип cookies: Обязательные</strong> - для предоставления услуг, доступных на наших веб-сайтах, а также для использования некоторых из его функций, таких как: вход в систему, оплата товаров или услуг, доступ к защищенным областям. Без таких cookie невозможна корректная работа сайта, они обязательны для использования всеми пользователями.</li>
                                    <li><strong>Тип cookies: Функциональные</strong> - для повышения производительности и функциональности наших веб-сайтов, не являются необходимыми для их использования. Но без этих cookie некоторые функции могут стать недоступными.</li>
                                    <li><strong>Тип cookies: Рекламные</strong> - для сопоставления информации на веб-сайте и рекламе вашим интересам. В частности, как предотвращение повторного появления одного и того же объявления, обеспечение правильного отображения рекламы для рекламодателей, а также выбор рекламы, основанной на ваших интересах.</li>
                                    <li><strong>Тип cookies: Аналитические</strong> - для сбора информации, которая используется в обобщенной форме (иными словами, для понимания, как используются наши веб-сайты, насколько эффективны маркетинговые кампании, или чтобы помочь нам настроить наши веб-сайты под вас).</li>
                                </ul>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>КАКОЙ СРОК ХРАНЕНИЯ ФАЙЛОВ COOKIE?</strong></p>
                            <div class="pl-8">
                                <p>Для различных видов файлов cookie срок хранения на вашем устройстве может отличаться. Как правило, файлы cookie будут храниться на вашем устройстве в течение периода, необходимого для достижения их цели, после чего они будут автоматически удалены с вашего устройства.</p>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4"><strong>КАК УПРАВЛЯТЬ ФАЙЛАМИ COOKIE?</strong></p>
                            <div class="pl-8">
                                <p>Вы можете управлять и контролировать ваши файлы cookie, используя настройки браузера. При этом обращаем ваше внимание на то, что в случае отказа от cookie все персонализированные настройки будут сброшены.</p>
                            </div>
                        </div>
        
                        <div class="pl-8">
                            <p class="mt-4">Условия настоящей политики могут меняться, мы рекомендуем регулярно следить за обновлением данного документа.</p>
                        </div>
                    </div>
                </li>
            </ul>
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

    <div id="modal-overlay" class="modal-overlay">
        <span id="close-modal" class="close-button">&times;</span>
        <img id="modal-image" class="modal-image" src="" alt="">
    </div>


    <script>

function toggleInfo() {
    const infoText = document.getElementById('info-text');
    const arrowIcon = document.querySelector('#info-item .arrow-icon');
    const title = document.querySelector('#info-item .hover-text-blue');
    
    infoText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Как пополнить кошелек?"
function toggleWalletInfo() {
    const walletText = document.getElementById('wallet-text');
    const arrowIcon = document.querySelector('#wallet-item .arrow-icon');
    const title = document.querySelector('#wallet-item .hover-text-blue');
    
    walletText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function toggleMarketAnalysisInfo() {
    const marketAnalysisText = document.getElementById('market-analysis-text');
    const arrowIcon = document.querySelector('#market-analysis-item .arrow-icon');
    const title = document.querySelector('#market-analysis-item .hover-text-blue');
    
    marketAnalysisText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Возврат средств"
function toggleRefundInfo() {
    const refundText = document.getElementById('refund-text');
    const arrowIcon = document.querySelector('#refund-item .arrow-icon');
    const title = document.querySelector('#refund-item .hover-text-blue');
    
    refundText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Создание товара с помощью формы"
function toggleFormCreationInfo() {
    const formCreationText = document.getElementById('form-creation-text');
    const arrowIcon = document.querySelector('#form-creation-item .arrow-icon');
    const title = document.querySelector('#form-creation-item .hover-text-blue');
    
    formCreationText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Пробный период"
function toggleTrialInfo() {
    const trialText = document.getElementById('trial-text');
    const arrowIcon = document.querySelector('#trial-item .arrow-icon');
    const title = document.querySelector('#trial-item .hover-text-blue');
    
    trialText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Тарифы"
function toggleTariffsInfo() {
    const tariffsText = document.getElementById('tariffs-text');
    const arrowIcon = document.querySelector('#tariffs-item .arrow-icon');
    const title = document.querySelector('#tariffs-item .hover-text-blue');
    
    tariffsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Смена тарифа"
function toggleChangeTariffInfo() {
    const changeTariffText = document.getElementById('change-tariff-text');
    const arrowIcon = document.querySelector('#change-tariff-item .arrow-icon');
    const title = document.querySelector('#change-tariff-item .hover-text-blue');
    
    changeTariffText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Требования к файлу для прямой загрузки товаров"
function toggleFileRequirementsInfo() {
    const fileRequirementsText = document.getElementById('file-requirements-text');
    const arrowIcon = document.querySelector('#file-requirements-item .arrow-icon');
    const title = document.querySelector('#file-requirements-item .hover-text-blue');
    
    fileRequirementsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Термины и определения"
function toggleTermsInfo() {
    const termsText = document.getElementById('terms-text');
    const arrowIcon = document.querySelector('#terms-item .arrow-icon');
    const title = document.querySelector('#terms-item .hover-text-blue');
    
    termsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Загрузка товаров из прайс-листа"
function togglePriceListUploadInfo() {
    const priceListUploadText = document.getElementById('price-list-upload-text');
    const arrowIcon = document.querySelector('#price-list-upload-item .arrow-icon');
    const title = document.querySelector('#price-list-upload-item .hover-text-blue');
    
    priceListUploadText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Прямой импорт товаров из прайс-листа"
function toggleDirectPriceListImportInfo() {
    const directPriceListImportText = document.getElementById('direct-price-list-import-text');
    const arrowIcon = document.querySelector('#direct-price-list-import-item .arrow-icon');
    const title = document.querySelector('#direct-price-list-import-item .hover-text-blue');
    
    directPriceListImportText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Прямая загрузка товаров на сайт"
function toggleDirectUploadInfo() {
    const directUploadText = document.getElementById('direct-upload-text');
    const arrowIcon = document.querySelector('#direct-upload-item .arrow-icon');
    const title = document.querySelector('#direct-upload-item .hover-text-blue');
    
    directUploadText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Импорт товаров через прайс-лист"
function toggleImportInfo() {
    const importText = document.getElementById('import-text');
    const arrowIcon = document.querySelector('#import-item .arrow-icon');
    const title = document.querySelector('#import-item .hover-text-blue');
    
    importText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Конвертация прайс-листа и импорт товаров"
function togglePriceListConversionInfo() {
    const priceListConversionText = document.getElementById('price-list-conversion-text');
    const arrowIcon = document.querySelector('#price-list-conversion-item .arrow-icon');
    const title = document.querySelector('#price-list-conversion-item .hover-text-blue');
    
    priceListConversionText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Конвертирование прайс-листа и загрузка товаров на сайт"
function toggleConverterInfo() {
    const converterText = document.getElementById('converter-text');
    const arrowIcon = document.querySelector('#converter-item .arrow-icon');
    const title = document.querySelector('#converter-item .hover-text-blue');
    
    converterText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Загрузка товаров с помощью конвертации"
function toggleConversionInfo() {
    const conversionText = document.getElementById('conversion-text');
    const arrowIcon = document.querySelector('#conversion-item .arrow-icon');
    const title = document.querySelector('#conversion-item .hover-text-blue');
    
    conversionText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Статусы товаров в личном кабинете"
function toggleStatusInfo() {
    const statusText = document.getElementById('status-text');
    const arrowIcon = document.querySelector('#status-item .arrow-icon');
    const title = document.querySelector('#status-item .hover-text-blue');
    
    statusText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function toggleFileTypesInfo() {
    const fileTypesText = document.getElementById('file-types-text');
    const arrowIcon = document.querySelector('#file-types-item .arrow-icon');
    const title = document.querySelector('#file-types-item .hover-text-blue');
    
    fileTypesText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для "Система взаимных скидок"
function toggleDiscountSystemInfo() {
    const discountSystemText = document.getElementById('discount-system-text');
    const arrowIcon = document.querySelector('#discount-system-item .arrow-icon');
    const title = document.querySelector('#discount-system-item .hover-text-blue');
    
    discountSystemText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для "Как подключить"
function toggleHowToConnectInfo() {
    const howToConnectText = document.getElementById('how-to-connect-text');
    const arrowIcon = document.querySelector('#how-to-connect-item .arrow-icon');
    const title = document.querySelector('#how-to-connect-item .hover-text-blue');
    
    howToConnectText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Отображаемые товары"
function toggleDisplayedItemsInfo() {
    const displayedItemsText = document.getElementById('displayed-items-text');
    const arrowIcon = document.querySelector('#displayed-items-item .arrow-icon');
    const title = document.querySelector('#displayed-items-item .hover-text-blue');
    
    displayedItemsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Поиск по товарам"
function toggleSearchProductsInfo() {
    const searchProductsText = document.getElementById('search-products-text');
    const arrowIcon = document.querySelector('#search-products-item .arrow-icon');
    const title = document.querySelector('#search-products-item .hover-text-blue');
    
    searchProductsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Редактирование товара"
function toggleEditProductInfo() {
    const editProductText = document.getElementById('edit-product-text');
    const arrowIcon = document.querySelector('#edit-product-item .arrow-icon');
    const title = document.querySelector('#edit-product-item .hover-text-blue');
    
    editProductText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Удаление товаров"
function toggleDeletionInfo() {
    const deletionText = document.getElementById('deletion-text');
    const arrowIcon = document.querySelector('#deletion-item .arrow-icon');
    const title = document.querySelector('#deletion-item .hover-text-blue');
    
    deletionText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Удаление одного товара"
function toggleSingleDeletionInfo() {
    const singleDeletionText = document.getElementById('single-deletion-text');
    const arrowIcon = document.querySelector('#single-deletion-item .arrow-icon');
    const title = document.querySelector('#single-deletion-item .hover-text-blue');
    
    singleDeletionText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Удаление нескольких товаров"
function toggleMultipleDeletionInfo() {
    const multipleDeletionText = document.getElementById('multiple-deletion-text');
    const arrowIcon = document.querySelector('#multiple-deletion-item .arrow-icon');
    const title = document.querySelector('#multiple-deletion-item .hover-text-blue');
    
    multipleDeletionText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function scrollToProfile() {
    const profileSection = document.getElementById('profile-settings');
    if (profileSection) {
        profileSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function scrollToMyProducts() {
    const myProductsSection = document.getElementById('video-instructions');
    if (myProductsSection) {
        myProductsSection.scrollIntoView({ behavior: 'smooth' });
    }
}

function scrollToSearch() {
    const searchSection = document.getElementById('recomen'); // Выбираем первый заголовок в разделе "Поиск товаров"
    if (searchSection) {
        searchSection.scrollIntoView({ behavior: 'smooth' });
    }
}

// Функция для открытия/закрытия блока "Персональные страницы для магазинов"
function toggleStorePagesInfo() {
    const storePagesText = document.getElementById('store-pages-text');
    const arrowIcon = document.querySelector('#store-pages-item .arrow-icon');
    const title = document.querySelector('#store-pages-item .hover-text-blue');
    
    storePagesText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Редактирование профиля компании"
function toggleEditProfileInfo() {
    const editProfileText = document.getElementById('edit-profile-text');
    const arrowIcon = document.querySelector('#edit-profile-item .arrow-icon');
    const title = document.querySelector('#edit-profile-item .hover-text-blue');
    
    editProfileText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Смена пароля"
function toggleChangePasswordInfo() {
    const changePasswordText = document.getElementById('change-password-text');
    const arrowIcon = document.querySelector('#change-password-item .arrow-icon');
    const title = document.querySelector('#change-password-item .hover-text-blue');
    
    changePasswordText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Поиск товаров"
function toggleSearchInfo() {
    const searchText = document.getElementById('search-text');
    const arrowIcon = document.querySelector('#search-item .arrow-icon');
    const title = document.querySelector('#search-item .hover-text-blue');
    
    searchText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Рекомендательные технологии"
function toggleRecommendationsInfo() {
    const recommendationsText = document.getElementById('recommendations-text');
    const arrowIcon = document.querySelector('#recommendations-item .arrow-icon');
    const title = document.querySelector('#recommendations-item .hover-text-blue');
    
    recommendationsText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Политика в отношении файлов cookie"
function toggleCookiesInfo() {
    const cookiesText = document.getElementById('cookies-text');
    const arrowIcon = document.querySelector('#cookies-item .arrow-icon');
    const title = document.querySelector('#cookies-item .hover-text-blue');
    
    cookiesText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function toggleReduceTariffInfo() {
    const reduceTariffText = document.getElementById('reduce-tariff-text');
    const arrowIcon = document.querySelector('#reduce-tariff-item .arrow-icon');
    const title = document.querySelector('#reduce-tariff-item .hover-text-blue');
    
    reduceTariffText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

// Функция для открытия/закрытия блока "Анализ рынка автозапчастей"
function toggleMarketAnalysisInfo() {
    const marketAnalysisText = document.getElementById('market-analysis-text');
    const arrowIcon = document.querySelector('#market-analysis-item .arrow-icon');
    const title = document.querySelector('#market-analysis-item .hover-text-blue');
    
    marketAnalysisText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function toggleConverterCapabilitiesInfo() {
    const converterCapabilitiesText = document.getElementById('converter-capabilities-text');
    const arrowIcon = document.querySelector('#converter-capabilities-item .arrow-icon');
    const title = document.querySelector('#converter-capabilities-item .hover-text-blue');
    
    converterCapabilitiesText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}

function toggleTariffPaymentInfo() {
    const tariffPaymentText = document.getElementById('tariff-payment-text');
    const arrowIcon = document.querySelector('#tariff-payment-item .arrow-icon');
    const title = document.querySelector('#tariff-payment-item .hover-text-blue');
    
    tariffPaymentText.classList.toggle('hidden');
    arrowIcon.classList.toggle('rotated');
    title.classList.toggle('active-title');
}
    </script>

<script>
    // Функция для открытия модального окна с изображением
    function openModal(imageSrc) {
        const modalOverlay = document.getElementById('modal-overlay');
        const modalImage = document.getElementById('modal-image');
        modalImage.src = imageSrc;
        modalOverlay.style.display = 'flex';
    }

    // Функция для закрытия модального окна
    function closeModal() {
        const modalOverlay = document.getElementById('modal-overlay');
        modalOverlay.style.display = 'none';
    }

    // Добавляем обработчики событий только на изображения с классом "border w-2/3 my-4"
    document.querySelectorAll('img.zoomable-image').forEach(img => {
        img.addEventListener('click', () => {
            openModal(img.src);
        });
    });

    // Закрытие модального окна при клике на крестик
    document.getElementById('close-modal').addEventListener('click', closeModal);

    // Закрытие модального окна при клике на затемненный фон
    document.getElementById('modal-overlay').addEventListener('click', (event) => {
        if (event.target === document.getElementById('modal-overlay')) {
            closeModal();
        }
    });

    function hideOrangeBlock() {
    const orangeBlock = document.getElementById('orange-block');
    orangeBlock.style.display = 'none'; // Скрываем блок
}


    function scrollToTariffs() {
        const tariffsBlock = document.getElementById('tariffs');
        if (tariffsBlock) {
            tariffsBlock.scrollIntoView({ behavior: 'smooth' });
        }
    }

</script>

<script>
    function scrollToWalletInfo() {
        const block = document.getElementById('wallet-info');
        if (block) block.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToProductRequirements() {
        const block = document.getElementById('product-requirements');
        if (block) block.scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToPriceListConverter() {
    const priceListUploadText = document.getElementById('price-list-upload-text');
    const priceListConversionText = document.getElementById('price-list-conversion-text');
    
    // Открываем пункт "Загрузка товаров из прайс-листа", если он закрыт
    if (priceListUploadText.classList.contains('hidden')) {
        togglePriceListUploadInfo();
    }
    
    // Открываем пункт "Конвертация прайс-листа и импорт товаров", если он закрыт
    if (priceListConversionText.classList.contains('hidden')) {
        togglePriceListConversionInfo();
    }
    
    // Производим скролл до элемента с id 'price-list-conversion-item'
    const targetElement = document.getElementById('price-list-conversion-item');
    if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth' });
    }
}

function scrollToProfileSettings() {
    const changePasswordText = document.getElementById('change-password-text');
    
    // Открываем пункт "Смена пароля", если он закрыт
    if (changePasswordText.classList.contains('hidden')) {
        toggleChangePasswordInfo();
    }
    
    // Производим скролл до элемента с id 'change-password-item'
    const targetElement = document.getElementById('change-password-item');
    if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth' });
    }
}
function scrollToProfileSettings2() {
    const editProfileText = document.getElementById('edit-profile-text');
    
    // Открываем пункт "Редактирование профиля компании", если он закрыт
    if (editProfileText.classList.contains('hidden')) {
        toggleEditProfileInfo();
    }
    
    // Производим скролл до элемента с id 'edit-profile-item'
    const targetElement = document.getElementById('edit-profile-item');
    if (targetElement) {
        targetElement.scrollIntoView({ behavior: 'smooth' });
    }
}
function scrollToMarketAnalysis() {
    const marketAnalysisBlock = document.getElementById('market-analysis');
    const marketAnalysisText = document.getElementById('market-analysis-text');

    // Прокручиваем страницу до раздела "Анализ рынка"
    if (marketAnalysisBlock) {
        marketAnalysisBlock.scrollIntoView({ behavior: 'smooth' });
    }

    // Открываем пункт "Анализ рынка автозапчастей", если он закрыт
    if (marketAnalysisText.classList.contains('hidden')) {
        toggleMarketAnalysisInfo(); // Используем существующую функцию
    }
}

    function scrollToVideoInstructions() {
        const block = document.getElementById('video-instructions');
        if (block) block.scrollIntoView({ behavior: 'smooth' });
    }
</script>
</body>
</html>