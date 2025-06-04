<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Политика в отношении файлов cookie</title>
    <link rel="stylesheet" href="{{ asset('css/cookie-policy.css') }}"> <!-- Подключение основного CSS-файла -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<style>
    body {
    font-family: 'Nunito', sans-serif;
}
</style>
<body>
    @include('components.header-seller')  <!-- Подключение хэдера-->

<div class="content">
    <h1>Политика в отношении файлов cookie</h1>
    <p>С мая 2018 года действуют общие правила по защите персональных данных The EU General Data Protection Regulation (GDPR). Цель GDPR — обеспечить безопасность персональных данных граждан, вне зависимости от их физического месторасположения. Файлы cookie используются для оптимизации работы с сайтом. Данные файлы позволяют запомнить сделанный вами выбор (например, выбор города, в котором вы находитесь) для того, чтобы предоставить вам лучшее онлайн-предложение. Важно: файлы cookie не несут угрозы безопасности вашим данным.</p>

    <h2>Что такое файлы cookie?</h2>
    <p>Файлы cookie - это небольшие файлы с данными, которые сохраняются на вашем компьютере или мобильном устройстве, веб-сервисом/браузером при посещении веб-сайта. Файлы cookie широко используются владельцами веб-сайтов для того, чтобы их веб-сайты работали более эффективно.</p>

    <h2>Какие cookie используются?</h2>
    <p>Мы используем файлы cookie и аналогичные технологии, которые подразделяются на следующие категории:</p>
    <ul>
        <li>Постоянные</li>
        <li>Сессионные</li>
        <li>Сторонние</li>
    </ul>

    <!-- Добавьте остальную информацию о типах cookie и их использовании здесь -->

    <h2>Как управлять файлами cookie?</h2>
    <p>Вы можете управлять и контролировать ваши файлы cookie используя настройки браузера. При этом обращаем ваше внимание на то, что в случае отказа от cookie все персонализированные настройки будут сброшены.</p>

    <p>Условия настоящей политики могут меняться, мы рекомендуем регулярно следить за обновлением данного документа.</p>
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
</body>
</html>