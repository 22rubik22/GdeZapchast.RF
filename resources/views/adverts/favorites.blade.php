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
    <title>Избранное</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{ asset('images/Group 438.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
  <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    .blockadvert {
        border: 0.2px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        border-radius: 15px;
    }
    
    .favorite-btn i.fas {
        color: red; /* Красный цвет для избранного */
    }

    .truncate {
        white-space: nowrap; /* Запретить перенос строк */
        overflow: hidden; /* Скрыть переполнение */
        text-overflow: ellipsis; /* Добавить многоточие в конце */
    }

    body {
        font-family: 'Nunito', sans-serif;
    }
    
    /* Стили для мобильной версии */
    .mobile-card {
        background: white;
        padding: 12px;
        border-radius: 12px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    
    .mobile-card-img {
        width: 130px;
        height: 130px;
        border-radius: 8px;
        object-fit: cover;
        position: relative;
    }
    
    .availability-badge {
        position: absolute;
        top: 4px;
        left: 4px;
        background: #FFE6C1;
        color: black;
        font-size: 10px;
        padding: 2px 6px;
        border-radius: 4px;
    }
    
    .mobile-card-content {
        flex: 1;
    }
    
    .mobile-card-price {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 4px;
    }
    
    .mobile-card-title {
        font-weight: 500;
        color: #333;
        margin-bottom: 4px;
    }
    
    .mobile-card-details {
        font-size: 13px;
        color: #666;
        display: flex;
        align-items: center;
        margin-bottom: 4px;
    }
    
    .mobile-card-phone {
        font-size: 13px;
        color: #555;
        margin-bottom: 4px;
    }
    
    .mobile-card-address {
        font-size: 12px;
        color: #777;
    }
    
    
    .truncate {
        
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
}

.truncate::after {
    content: '';
    position: absolute;
    right: 0;
    top: 0;
    bottom: 0;
    width: 30px;
    background: linear-gradient(to right, rgba(255,255,255,0), rgba(255,255,255,1));
}
</style>
<body class="flex flex-col items-center">

@include('components.header-seller')

<div class="w-full md:w-[90%] mx-auto mt-10">
    <h2 class="text-2xl font-bold mb-4 text-center md:text-left">Избранное</h2>

    @if($favorites->isEmpty())
        <p class="text-center text-lg mt-8">Нет товаров в избранном.</p>
    @else
        <!-- Мобильная версия (новая адаптация) -->
        <div class="w-full mx-auto p-4 space-y-4 sm:hidden">
            @foreach($favorites as $favorite)
                @php
                    $advert = $favorite->advert;
                @endphp
                <div class="mobile-card" onclick="location.href=generateAdvertUrl({
                    id: '{{ $advert->id }}',
                    product_name: '{{ $advert->product_name }}',
                    brand: '{{ $advert->brand }}',
                    model: '{{ $advert->model }}',
                    year: '{{ $advert->year }}',
                    engine: '{{$advert->engine}}',
                    number: '{{$advert->number}}'
                })">
                    <div>
                        @if ($advert->main_photo_url)
                            <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }}" class="mobile-card-img">
                        @else
                            <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="mobile-card-img">
                        @endif
                    </div>
                    <div class="mobile-card-content">
                        <p class="mobile-card-price">{{ $advert->price }} ₽</p>
                    <p class="mobile-card-title truncate w-40">{{ $advert->product_name }}</p>
                                <p class="mobile-card-details h-[20px]">
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
                        </p>
                        
                         <div class='h-[20px]'>
                        @if($advert->number)
                             <i class="fas fa-barcode"></i> <span class="mobile-card-phone">{{ $advert->number }}</span>

                        @endif
                         </div>
                         
                        <p class="mobile-card-address mt-2">{{ $advert->user->userAddress->city ?? 'Адрес не указан' }}</p>
                    </div>
                    <button class="favorite-btn text-xl" data-advert-id="{{ $advert->id }}" onclick="event.stopPropagation()">
                        <i class="{{ $favorite->advert->isFavorite ? 'fas' : 'far' }} fa-heart"></i>
                    </button>
                </div>
            @endforeach
        </div>

        <!-- Десктопная версия -->
        <div class="sm:flex w-full flex-col items-start justify-center mr-20 hidden sm:block">
            @foreach($favorites as $favorite)
                @php
                    $advert = $favorite->advert;
                @endphp
                <div class="blockadvert bg-white rounded-lg shadow-md flex max-w-5xl w-full mt-8 cursor-pointer transition-colors duration-300 hover:bg-[#f0f8ff]" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
                    <div class="w-1/4 flex-shrink-0">
                        <div class="w-[220px] h-[175px] bg-gray-200 rounded-lg overflow-hidden">
                            @if ($advert->main_photo_url)
                                <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }}" class="w-full h-full object-cover">
                            @else
                                <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="w-full h-full object-cover">
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col justify-between w-3/4 lg:ml-10 sm:ml-20">
                        <div class="flex justify-between items-start">
                            <div class="pt-4">
                                <h2 class="text-xl font-semibold">{{ $advert->product_name }}</h2>
                                @if($advert->number)
                                    <p class="beg bg-gray-20 px-3 py-1 w-24 text-sm rounded-lg text-center">{{ $advert->number }}</p>
                                @endif
                            </div>
                            <div class="text-right pr-4 pt-4">
                                <p class="text-xl font-semibold">{{ $advert->price }} ₽</p>
                                <p class="text-red-500">{{ $advert->user->userAddress->city ?? 'Не указан' }}</p>
                                <button class="text-gray-500 hover:text-gray-700 favorite-btn" data-advert-id="{{ $advert->id }}">
                                    <i class="{{ $favorite->advert->isFavorite ? 'fas' : 'far' }} fa-heart text-2xl"></i>
                                </button>
                            </div>
                        </div>
                        <div class="flex space-x-3 pb-4 w-full justify-start">
                            @if($advert->brand)
                                <span class="bg-[#FFE6C1] text-black text-sm font-semibold px-2.5 py-0.5 rounded">{{ $advert->brand }}</span>
                            @endif
                            @if($advert->model)
                                <span class="bg-[#FFE6C1] text-black text-sm font-semibold px-2.5 py-0.5 rounded">{{ $advert->model }}</span>
                            @endif
                            @if($advert->body)
                                <span class="bg-[#FFE6C1] text-black text-sm font-semibold px-2.5 py-0.5 rounded">{{ $advert->body }}</span>
                            @endif
                            @if($advert->engine)
                                <span class="bg-[#FFE6C1] text-black text-sm font-semibold px-2.5 py-0.5 rounded">{{ $advert->engine }}</span>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<footer class="bg-white text-white py-20 shadow-none text-center mt-20 w-full">
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

<script>
    // Функция для генерации URL объявления
    function generateAdvertUrl(advert) {
        let baseUrl = '/adverts/' + advert.id;
        let slug = '';
        
        if (advert.product_name) {
            slug += advert.product_name.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '');
        }
        
        if (advert.brand) {
            slug += '-' + advert.brand.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '');
        }
        
        if (advert.model) {
            slug += '-' + advert.model.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '');
        }
        
        if (advert.year) {
            slug += '-' + advert.year;
        }
        
        if (advert.engine) {
            slug += '-' + advert.engine.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '');
        }
        
        if (advert.number) {
            slug += '-' + advert.number.toLowerCase().replace(/[^a-zа-яё0-9]+/g, '-').replace(/^-|-$/g, '');
        }
        
        return baseUrl + (slug ? '/' + slug : '');
    }

    // Обработчики для кнопок избранного
    document.querySelectorAll('.favorite-btn').forEach(button => {
        button.addEventListener('click', function(event) {
            event.stopPropagation();
            const advertId = this.getAttribute('data-advert-id');
            const icon = this.querySelector('i');
            const isFavorite = icon.classList.contains('fas');

            const url = isFavorite ? `/favorites/remove/${advertId}` : `/favorites/add/${advertId}`;
            
            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Content-Type': 'application/json',
                },
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    if (isFavorite) {
                        icon.classList.remove('fas');
                        icon.classList.add('far');
                    } else {
                        icon.classList.remove('far');
                        icon.classList.add('fas');
                    }
                }
            })
            .catch(error => console.error('Error:', error));
        });
    });
    
    
</script>


<script>
    const titleElement = document.getElementById('product-name');
    const containerWidth = titleElement.parentElement.offsetWidth;

    if (titleElement.scrollWidth > containerWidth) {
        titleElement.style.opacity = '0'; // Делаем текст прозрачным
    }
</script>
</body>
</html>