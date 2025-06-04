<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
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
    <title>Настройки конвертера</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200..1000&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class=" flex flex-col min-h-screen">
    @include('components.header-seller')

    <div class=" p-8  w-full max-w-4xl mx-auto flex-grow">
        <h1 class="text-center text-2xl font-semibold mb-8">Настройки конвертера</h1>

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Соответствие столбцов</h2>
            @if($columnMappings)
                <div class="space-y-2 mb-4">
                    @foreach($columnMappings as $key => $value)
                        <div>{{ $key }} = {{ $value }}</div>
                    @endforeach
                    <form action="{{ route('column_mappings.delete') }}" method="POST" class="mt-4" onsubmit="return confirmDelete();">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded-md">Сбросить соответствия</button>
                    </form>
                </div>
            @else
                <p>Соответствия столбцов не найдены.</p>
            @endif
        </div>

        @if(session('success'))
            <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-8">
            <h2 class="text-xl font-semibold mb-4">Выберите марки автомобилей которые есть в Вашем прайс-листе</h2>
            <form action="{{ route('converter_set.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ([
                        'acura', 'alfa_romeo', 'asia', 'aston_martin', 'audi', 'bentley', 'bmw', 'byd',
                        'cadillac', 'changan', 'chevrolet', 'citroen', 'daewoo', 'daihatsu', 'datsun', 'fiat',
                        'ford', 'gaz', 'geely', 'haval', 'honda', 'hyundai', 'infiniti', 'isuzu', 'jaguar',
                        'jeep', 'kia', 'lada', 'land_rover', 'mazda', 'mercedes_benz', 'mitsubishi', 'nissan',
                        'opel', 'peugeot', 'peugeot_lnonum', 'porsche', 'renault', 'skoda', 'ssangyong', 'subaru', 
                        'suzuki', 'toyota', 'uaz', 'volkswagen', 'volvo', 'zaz'
                    ] as $brand)
                        <label class="flex items-center">
                            <input type="hidden" name="{{ $brand }}" value="0">
                            <input class="form-check-input" type="checkbox" name="{{ $brand }}" id="{{ $brand }}" value="1" {{ isset($converterSet) && $converterSet->$brand ? 'checked' : '' }}>
                            <span class="ml-2">{{ ucfirst(str_replace('_', ' ', $brand)) }}</span>
                        </label>
                    @endforeach
                </div>
         
                <div class="text-center">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-md">Сохранить марки</button>
                </div>
            </form>
             <div class="text-center">
                           <form action="{{ route('converter_set.reset') }}" method="POST" class="mt-4" onsubmit="return confirmReset();">
                               
            @csrf
            <button type="submit" class="bg-gray-700 text-white px-4 py-2 rounded">Сбросить выбор марок</button>
        </form>
        </div>
        </div>
    </div>

    <footer class="bg-white text-black py-20 shadow-none text-center mt-20 w-full">
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
        function confirmReset() {
            return confirm("Вы уверены, что хотите сбросить все настройки? Это действие нельзя отменить.");
        }

        function confirmDelete() {
            return confirm("Вы уверены, что хотите удалить все соответствия столбцов? Это действие нельзя отменить.");
        }
    </script>
</body>
</html>