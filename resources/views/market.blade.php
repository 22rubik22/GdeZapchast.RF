<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализ рынка</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<style>
    body {
    font-family: 'Nunito', sans-serif;
}
</style>
<body>
@include('components.header-seller')

<div class="container mx-auto p-4 mt-40 mb-20">
    <h2 class="text-2xl font-bold mb-4 pl-6">Анализ рынка</h2>
    <div class="ad-list relative p-4">
        @include('components.search-analis')  
    </div>
    @if(!empty($engines) && (is_array($engines) || is_object($engines)))
    <div class=" blockadvert filters bg-white mt-4 w-full hidden md:w-3/4 p-4 rounded-lg shadow-md sm:block md:block 2xl:hidden">
    <form method="GET" action="{{ route('adverts.filterByEngineAnalis') }}">
    <h4 class="text-xl font-semibold mb-4">Фильтры по двигателю:</h4>
    @foreach($engines as $engine)
        <div>
            <input type="checkbox" name="engines[]" value="{{ strtolower($engine) }}" id="engine-{{ $engine }}"
                {{ in_array(strtolower($engine), array_map('strtolower', request('engines', []))) || !request()->has('engines') ? 'checked' : '' }}
                class="mr-2">
            <label for="engine-{{ $engine }}" class="text-lg">{{ !empty($engine) ? ucfirst($engine) : 'Не указан' }}</label>
        </div>
    @endforeach
    

    <!-- Сохраняем другие параметры запроса -->
    <input type="hidden" name="search_query" value="{{ request('search_query') }}">
    <input type="hidden" name="brand" value="{{ request('brand') }}">
    <input type="hidden" name="model" value="{{ request('model') }}">
    <input type="hidden" name="year" value="{{ request('year') }}">

    <button type="submit"                 class="w-full cursor-pointer md:w-auto px-4 py-2 bg-blue-500 text-white font-semibold rounded-md focus:outline-none focus:ring focus:ring-blue-500 md:py-1 md:text-sm">Применить фильтры</button>
</form>
@else
   
@endif
    </div>

   <!-- Фильтры по параметру engine для больших экранов -->
@if(!empty($engines) && (is_array($engines) || is_object($engines)))
    <div class="filters bg-white mt-4 ml-auto hidden 2xl:block">
        <form method="GET" class="blockadvert p-2 rounded-lg shadow-md" action="{{ route('adverts.filterByEngineAnalis') }}">
            <h4 class="text-xl text-center font-semibold mb-4">Фильтры по двигателю:</h4>
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
@else
 
@endif

    @if(isset($searchQuery))
        <!-- Ссылка с якорем для перемещения на блок статистики -->
        <a href="#statistic" class="btn btn-secondary bg-gray-500 text-white p-2 rounded-md mt-4 block w-full md:w-auto">Перейти к статистике цен</a>

        @if(isset($userAdverts) && $userAdverts->count() > 0)
            <h4 class="text-xl font-bold mt-4 pl-6">Мои товары</h4>
            @foreach($userAdverts as $advert)
                <div class="advert-block bg-white border border-gray-300 rounded-lg p-4 shadow-md cursor-pointer transition-colors duration-300 hover:bg-blue-100" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
                    <div class="advert-details flex gap-4">
                        <!-- Вывод главного фото -->
                        @if ($advert->main_photo_url)
                        <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото"  class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                    @else
                        <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                    @endif
                        <div class="flex-1">
                         
                            <strong>Название продукта:</strong> {{ $advert->product_name }}<br>
                            <strong>Цена:</strong> {{ $advert->price }} ₽<br>
                            <strong>Статус:</strong> {{ $advert->status_ad }}<br>
                            <strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}<br>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="mt-4">Нет результатов для "{{ $searchQuery }}" и "{{ $brand }}" среди ваших товаров.</p>
        @endif

        @if(isset($competitorAdverts) && $competitorAdverts->count() > 0)
            <h4 class="text-xl font-bold mt-4 pl-6">Товары конкурентов</h4>
            @foreach($competitorAdverts as $advert)
                <div class="advert-block bg-white border border-gray-300 rounded-lg p-4 shadow-md cursor-pointer transition-colors duration-300 hover:bg-blue-100" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
                    <div class="advert-details flex gap-4">
                        <!-- Вывод главного фото -->
                        @if ($advert->main_photo_url)
                        <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                    @else
                        <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                    @endif
                        <div class="flex-1">
                       
                            <strong>Название продукта:</strong> {{ $advert->product_name }}<br>
                            <strong>Цена:</strong> {{ $advert->price }} ₽<br>
                            <strong>Статус:</strong> {{ $advert->status_ad }}<br>
                            <strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}<br>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="mt-4">Нет результатов для "{{ $searchQuery }}" и "{{ $brand }}" среди товаров конкурентов.</p>
        @endif

        @if(isset($minPrice) && isset($maxPrice) && isset($medianPrice))
            <div class="statistic bg-gray-100 border border-gray-300 rounded-lg p-4 shadow-md mt-4" id="statistic">
                <h3 class="text-xl font-bold mb-4">Статистика цен</h3>
                <p>Минимальная цена: <span class="min-price text-green-600">{{ $minPrice }} ₽</span></p>
                <p>Средняя цена: <span class="avg-price text-orange-600">{{ $medianPrice }} ₽</span></p>
                <p>Максимальная цена: <span class="max-price text-red-600">{{ $maxPrice }} ₽</span></p>
            </div>
        @endif
    @endif
</div>

@extends('layouts.app')

@section('content')
    <!-- Здесь можно добавить дополнительный контент для страницы анализа рынка -->
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем, есть ли результаты поиска на странице
        const filteredAdverts = document.querySelector('.container h3:first-of-type');

        if (filteredAdverts) {
            // Сброс значений полей формы после вывода результатов поиска
            document.getElementById('part_name_or_number').value = '';
            document.getElementById('brand').value = '';
        }

        // Плавный скролл по якорю
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
</body>
</html>