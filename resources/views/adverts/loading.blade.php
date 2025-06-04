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
            animation: spin 2s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 flex flex-col items-center justify-center min-h-screen">
    <div class="text-center">
        <div class="loader mx-auto mb-4"></div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Идет поиск запчастей...</h1>
        <p class="text-gray-600">Пожалуйста, подождите</p>
        
        <div class="mt-8 bg-white p-6 rounded-lg shadow-md max-w-md mx-auto">
            <h2 class="text-lg font-semibold mb-2">Параметры поиска:</h2>
            <p id="searchParams" class="text-gray-700"></p>
        </div>
    </div>

 <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Отображаем параметры поиска
        const params = new URLSearchParams(window.location.search);
        let searchText = '';
        
        if (params.get('search_query')) {
            searchText += `Запрос: ${params.get('search_query')}<br>`;
        }
        if (params.get('brand')) {
            searchText += `Марка: ${params.get('brand')}<br>`;
        }
        if (params.get('model')) {
            searchText += `Модель: ${params.get('model')}<br>`;
        }
        if (params.get('year')) {
            searchText += `Год: ${params.get('year')}<br>`;
        }
        
        document.getElementById('searchParams').innerHTML = searchText || 'Не указаны';
        
        // Перенаправляем на страницу результатов через 1 секунду
        setTimeout(() => {
            window.location.href = "{{ route('adverts.search') }}?" + window.location.search;
        }, 1000);
    });
</script>
</body>
</html>