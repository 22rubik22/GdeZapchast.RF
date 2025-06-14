<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Поиск запчастей</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <style>
        .loader {
            border: 5px solid #f3f3f3;
            border-top: 5px solid #3498db;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
<div class="text-center">
    <div class="loader mx-auto mb-4"></div>
    <h1 class="text-2xl font-bold text-gray-800 mb-2">Идет поиск запчастей…</h1>
    <p class="text-gray-600">Пожалуйста, подождите</p>

    <div class="mt-8 bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
        <h2 class="text-lg font-semibold mb-2">Параметры поиска:</h2>
        <p id="searchParams" class="text-gray-700"></p>
    </div>
</div>

<form id="redirectForm" method="POST" action="{{ route('adverts.search') }}?">
    @csrf
    <input type="hidden" name="selected_modifications" id="selected_modifications" value="[]">
</form>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const params = new URLSearchParams(window.location.search);
        let summary = '';

        if (params.has('search_query')) summary += `Запрос: ${params.get('search_query')}<br>`;
        if (params.has('brand'))        summary += `Марка: ${params.get('brand')}<br>`;
        if (params.has('model'))        summary += `Модель: ${params.get('model')}<br>`;
        if (params.has('year'))         summary += `Год: ${params.get('year')}<br>`;

        document.getElementById('searchParams').innerHTML = summary || 'Не указаны';

        // Собираем GET-параметры обратно в строку
        const qs = params.toString();
        const form = document.getElementById('redirectForm');

        const sellerRouteTemplate = "{{ route('seller.search', [
              'id'       => ':sellerId',
              'username' => ':sellerCode'
          ]) }}";

        let routeUrl = "{{ route('adverts.search') }}";
        if (params.has('sellerId') && params.has('sellerCode')) {
            const id   = encodeURIComponent(params.get('sellerId'));
            const code = encodeURIComponent(params.get('sellerCode'));

            routeUrl = sellerRouteTemplate
                .replace(':sellerId', id)
                .replace(':sellerCode', code);
        }
        form.action = routeUrl + (qs ? '?' + qs : '');

        // Получаем из localStorage выбранные модификации
        const mods = localStorage.getItem('selectedModifications') || '[]';
        document.getElementById('selected_modifications').value = mods;

        // Ждём секунду, а затем отправляем форму POST
        setTimeout(() => form.submit(), 1000);
    });
</script>
</body>
</html>
