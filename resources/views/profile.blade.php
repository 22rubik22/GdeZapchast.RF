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
    <title>Профиль пользователя</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        .icon-text-hover:hover i,
        .icon-text-hover:hover p {
            color: #0077FF;
        }
    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="bg-white">
@include('components.header-seller')
<div class="container mx-auto p-4 mt-10 mb-20">
    <div class="flex flex-col md:flex-row justify-between items-center mb-4">
        <!-- Аватар пользователя -->
        <div class="w-full md:w-auto flex justify-center md:justify-start">
            <div class="w-44 h-44 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                @if($user->avatar_url)
                    <img src="{{ $user->avatar_url }}" alt="Аватар пользователя" class="w-full h-full object-contain ">
                @else
                    <i class="fas fa-user text-8xl text-gray-400"></i>
                @endif
            </div>
        </div>
        <!-- Информация о пользователе -->
        <div class="ml-0 md:ml-6 md:mt-0 w-full md:w-auto">
            <h1 class="text-3xl font-bold mb-4">{{ $user->username }}</h1>
            <p class="text-gray-600 mb-2">Email: <span class="text-black">{{ $user->email }}</span></p>

           


            @if(auth()->user()->user_status == 1)
            <p class="text-gray-600 mb-2">
                Филиалы:
                @if($user->branches->count() === 0)
                    <span class="text-black">Не указаны</span>
                @elseif($user->branches->count() === 1)
                    <span class="text-black">{{ $user->branches->first()->address }}</span>
                @else
                    <select class="text-black">
                        @foreach($user->branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->address }}</option>
                        @endforeach
                    </select>
                @endif
            </p>
            @else
                <p class="text-gray-600 mb-2">Город: <span class="text-black"> {{$user->city}}</span></p>
            @endif
            @if($user->UserPhoneNumber)
                <p class="text-gray-600 mb-2">Номер: <span class="text-black">{{ $user->UserPhoneNumber->number_1 }}</span></p>
            @else
                <p class="text-gray-600 mb-2">Номер не указан.</p>
            @endif

        </div>
        <!-- Пополнение кошелька -->
        <div class=" p-4 rounded-lg text-right flex items-center mt-4 md:mt-0 md:ml-auto">
            @if(auth()->user()->user_status == 1)
                <div class="bg-orange-100 p-4 rounded-lg text-right flex items-center mt-4 md:mt-0 md:ml-auto">
                    <div>
                       <p class="text-blue-600 text-xl font-bold flex items-center">
    <i class="fas fa-wallet text-2xl text-gray-600 mr-2"></i> {{ isset($balance) ? $balance : 0 }} ₽
</p>
                        <a href ="{{ route('pay.form') }}">
                            <p class="text-gray-600 flex items-center">
                                <i class="fas fa-plus text-gray-600 mr-1"></i> Пополнить кошелек
                            </p>
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <!-- Кнопки действий -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 mt-4">
       @if($user->user_status == 1 && $user->username != 'AvtoPay')
            <a href="{{ route('market.analysis') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-chart-bar text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Анализ рынка</p>
            </a>
        @endif
        <a href="{{ route('profile.edit', $user->id) }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
            <i class="fas fa-pencil-alt text-2xl text-gray-600 mr-4"></i>
            <p class="text-gray-600">Редактировать Профиль</p>
        </a>
        @if($user->user_status == 1)
            <a href="{{ route('tariff.settings') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-sliders-h text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Настроить тариф</p>
            </a>
            <a href="{{ route('adverts.create') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-plus text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Разместить товары</p>
            </a>
            <a href="{{ route('adverts.my_adverts') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-list text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Мои товары</p>
            </a>
            <a href="{{ route('pay.form') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-wallet text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Кошелек</p>
            </a>
        @endif
        <a href ="{{route('help.index')}}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
            <i class="fas fa-book text-2xl text-gray-600 mr-4"></i>
            <p class="text-gray-600">Справка</p>
        </a>
        <a href ="{{ route('open.support.chat') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
            <i class="fas fa-headset text-2xl text-gray-600 mr-4"></i>
            <p class="text-gray-600">Служба поддержки</p>
        </a>
        @if(auth()->user()->user_status == 1)
            <a href ="{{ route('converter_set.edit') }}" class="bg-gray-100 p-8 rounded-lg flex items-center icon-text-hover">
                <i class="fas fa-cog text-2xl text-gray-600 mr-4"></i>
                <p class="text-gray-600">Настройки конвертера</p>
            </a>
        @endif
      <div class="bg-gray-100 p-8 rounded-lg flex items-start icon-text-hover block lg:hidden">
    <form action="{{ route('logout') }}" method="POST" class="flex items-start mb-0 w-full">
        @csrf
        <button type="submit" class="text-sm flex items-center justify-center text-gray-600">
            <i class="fas fa-sign-out-alt text-2xl text-gray-600 mr-4"></i> <!-- Иконка выхода -->
            <span>Выйти</span>
        </button>
    </form>
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
</body>
</html>