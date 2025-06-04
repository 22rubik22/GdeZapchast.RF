<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Кошелёк</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <style>
        .hidden {
            display: none;
        }
    </style>
</head>
<body class="bg-white text-gray-800">
    @include('components.header-seller')
    <div class="container mx-auto p-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
            <div class="text-gray-600 md:block hidden">
                <a href="#" class="hover:underline">Профиль</a> / <span>Кошелёк</span>
            </div>
            <div class="space-y-2 text-right text-sm text-gray-600">
                <a href="#" class="block hover:underline">Как пополнить кошелёк</a>
                <a href="#" class="block hover:underline">Как происходят списания</a>
                <a href="#" class="block hover:underline">Возврат средств</a>
                <a href="#" class="block hover:underline">Часто задаваемые вопросы</a>
            </div>
        </div>
        <div class="text-center">
            <h1 class="text-2xl font-bold mb-2">Кошелёк</h1>
            <p class="text-lg mb-4">Баланс <span class="font-bold balance">{{ number_format($balance, 2, '.', ' ') }} ₽</span></p>
            <div class="flex justify-center space-x-4 mb-6">
                <a href="#" id="top-up-link" class="text-black font-bold border-b-2 border-black">Пополнение кошелка</a>
                <a href="#" id="history-link" class="text-gray-600">История операций</a>
            </div>
           <div id="content-section" class="mb-14">
    <form action="{{ route('pay') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="amount" class="block text-gray-600 mb-2">Введите сумму</label>
            <div class="inline-flex items-center border border-gray-300 rounded-md px-3 py-2">
                <input type="number" name="amount" id="amount" required value="1500"
                       class="w-20 text-center outline-none">
                <span class="ml-2">₽</span>
            </div>
        </div>
        <p class="text-gray-600 mb-4">Выберите способ пополнения</p>

        <div class="flex flex-wrap justify-center gap-4">
            <!-- Первая кнопка -->
            <button class="w-full sm:w-auto flex items-center justify-center p-6 bg-gray-100 rounded-md shadow-sm hover:bg-gray-200 text-lg">
                <i class="fas fa-credit-card text-2xl mr-2"></i>
                <span>Банковская карта</span>
            </button>

            <!-- Вторая кнопка -->
            <div class="relative w-full sm:w-auto">
                <button class="flex items-center justify-center p-6 bg-gray-100 rounded-md shadow-sm opacity-50 cursor-not-allowed text-lg"
                        disabled>
                    <i class="fas fa-file-invoice text-2xl mr-2"></i>
                    <span>Сформировать счёт</span>
                </button>
                <div class="absolute inset-0 flex items-center justify-center bg-gray-50 bg-opacity-70 rounded-md">
                    <span class="text-gray-700 text-lg font-medium">Временно недоступно</span>
                </div>
            </div>
        </div>

        <!-- Картинки под кнопками -->
        <div class="flex justify-center mt-4 space-x-4">
           <button> <img src="{{ asset('images/sber-pey.png') }}" alt="Sber Pay" class="h-8"></button>
           <button>  <img src="{{ asset('images/t-pay.svg') }}" alt="T-Pay" class="h-8"></button>
           <button>   <img src="{{ asset('images/dolyame_black.svg') }}" alt="Dolyame" class="h-8 w-32"></button>
        </div>
    </form>
</div>


            <!-- Секция с историей операций -->
            <div id="history-section" class="hidden mt-4 mb-14">
                <div class="container mx-auto p-4">
                    <div class="flex justify-center">
                        <div>
                            <div class="mt-4 flex space-x-4">
                                <button id="all-operations" class="text-blue-500 font-semibold">Все операции</button>
                                <button id="replenishment" class="text-gray-500">Пополнение</button>
                                <button id="withdrawal" class="text-gray-500">Списание</button>
                            </div>
                            <div id="history-items" class="mt-4 space-y-4">
                                <!-- Операции будут добавляться динамически -->
                            </div>
                        </div>
                    </div>
                </div>
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

    <script>
        // Функция для загрузки данных об операциях
        async function loadOperations() {
            try {
                const response = await fetch('/wallet/history');
                const operations = await response.json();
                renderOperations('all', operations);
            } catch (error) {
                console.error('Ошибка при загрузке операций:', error);
            }
        }

        // Функция для отображения операций
        function renderOperations(filter, operations) {
            const historyItems = document.getElementById('history-items');
            historyItems.innerHTML = '';

            operations.forEach(operation => {
                if (filter === 'all' || operation.type === filter) {
                    const item = document.createElement('div');
                    item.className = 'bg-gray-100 p-4 rounded-lg';
                    item.innerHTML = `
                        <p class="font-semibold">${operation.text}</p>
                        ${operation.details ? `<p class="text-gray-500 text-sm">${operation.details}</p>` : ''}
                        <p class="text-gray-500 text-sm">${operation.date}</p>
                        <p class="text-right font-semibold text-lg ${operation.color}">${operation.amount}</p>
                    `;
                    historyItems.appendChild(item);
                }
            });
        }

        // Функция для обновления стилей кнопок
        function updateButtonStyles(activeButton) {
            const buttons = document.querySelectorAll('#all-operations, #replenishment, #withdrawal');
            buttons.forEach(button => {
                if (button === activeButton) {
                    button.classList.add('text-blue-500', 'font-semibold');
                    button.classList.remove('text-gray-500');
                } else {
                    button.classList.remove('text-blue-500', 'font-semibold');
                    button.classList.add('text-gray-500');
                }
            });
        }

        // Обработчики событий для кнопок фильтрации
        document.getElementById('all-operations').addEventListener('click', async () => {
            const response = await fetch('/wallet/history');
            const operations = await response.json();
            renderOperations('all', operations);
            updateButtonStyles(document.getElementById('all-operations'));
        });

        document.getElementById('replenishment').addEventListener('click', async () => {
            const response = await fetch('/wallet/history');
            const operations = await response.json();
            renderOperations('replenishment', operations);
            updateButtonStyles(document.getElementById('replenishment'));
        });

        document.getElementById('withdrawal').addEventListener('click', async () => {
            const response = await fetch('/wallet/history');
            const operations = await response.json();
            renderOperations('withdrawal', operations);
            updateButtonStyles(document.getElementById('withdrawal'));
        });

        // Загрузка данных при открытии страницы
        loadOperations();

        // Функция для загрузки баланса
        async function loadBalance() {
            try {
                const response = await fetch('/wallet/balance');
                const data = await response.json();
                document.querySelector('.balance').textContent = `${data.balance.toLocaleString()} ₽`;
            } catch (error) {
                console.error('Ошибка при загрузке баланса:', error);
            }
        }

        // Загрузка баланса при открытии страницы
        loadBalance();

        // Переключение между секциями
        document.getElementById('history-link').addEventListener('click', function(event) {
            event.preventDefault();
            var contentSection = document.getElementById('content-section');
            var historySection = document.getElementById('history-section');
            var topUpLink = document.getElementById('top-up-link');
            var historyLink = document.getElementById('history-link');

            if (historySection.classList.contains('hidden')) {
                contentSection.classList.add('hidden');
                historySection.classList.remove('hidden');
                topUpLink.classList.remove('text-black', 'font-bold', 'border-b-2', 'border-black');
                topUpLink.classList.add('text-gray-600');
                historyLink.classList.remove('text-gray-600');
                historyLink.classList.add('text-black', 'font-bold', 'border-b-2', 'border-black');
            } else {
                contentSection.classList.remove('hidden');
                historySection.classList.add('hidden');
                topUpLink.classList.add('text-black', 'font-bold', 'border-b-2', 'border-black');
                topUpLink.classList.remove('text-gray-600');
                historyLink.classList.add('text-gray-600');
                historyLink.classList.remove('text-black', 'font-bold', 'border-b-2', 'border-black');
            }
        });

        document.getElementById('top-up-link').addEventListener('click', function(event) {
            event.preventDefault();
            var contentSection = document.getElementById('content-section');
            var historySection = document.getElementById('history-section');
            var topUpLink = document.getElementById('top-up-link');
            var historyLink = document.getElementById('history-link');

            if (contentSection.classList.contains('hidden')) {
                contentSection.classList.remove('hidden');
                historySection.classList.add('hidden');
                topUpLink.classList.add('text-black', 'font-bold', 'border-b-2', 'border-black');
                topUpLink.classList.remove('text-gray-600');
                historyLink.classList.add('text-gray-600');
                historyLink.classList.remove('text-black', 'font-bold', 'border-b-2', 'border-black');
            } else {
                contentSection.classList.add('hidden');
                historySection.classList.remove('hidden');
                topUpLink.classList.remove('text-black', 'font-bold', 'border-b-2', 'border-black');
                topUpLink.classList.add('text-gray-600');
                historyLink.classList.remove('text-gray-600');
                historyLink.classList.add('text-black', 'font-bold', 'border-b-2', 'border-black');
            }
        });
    </script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        form.addEventListener('submit', function(event) {
            const amountInput = document.getElementById('amount');
            const amount = parseFloat(amountInput.value);
            const minAmount = 1; // Минимальная сумма пополнения

            if (amount < minAmount) {
                event.preventDefault(); // Отменяем отправку формы
                alert(`Минимальная сумма пополнения: ${minAmount} ₽`);
            }
        });
    });
</script>


</body>
</html>