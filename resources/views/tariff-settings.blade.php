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
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <title>Настройки тарифа</title>
</head>
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
   /* Стили для уведомления */
    .notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        display: none;
        z-index: 1000;
    }
    

 
</style>
<body class="flex justify-center flex-col">
    @include('components.header-seller')
    @extends('layouts.app')

    <div class="container mx-auto p-4 mt-20 mb-20">
        <h1 class="text-3xl font-bold mb-2">Укажите максимальное количество активных товаров:</h1>
        <p class="text-gray-400 mb-12">*Активные товары - товары доступные для поиска и просмотра всем пользователям сайта</p>

        @if(!$hasTariff)
        <div class="mt-6 text-center">
            <p class="text-lg text-gray-700">Вам доступен пробный тариф "99999 объявлений на 14 дней!"</p>
            <form action="{{ route('create.trial.tariff') }}" method="POST">
                @csrf
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2">Попробовать</button>
            </form>
        </div>
        @else
    <div class="slider-container">

    <div class="flex justify-center">
        <input type="text" id="ad-count-manual" min="1000" max="300000" step="100" value="1000" class="w-[20%] px-6 py-4 rounded-xl bg-[#f5f5f5] text-4xl text-center mb-6">
    </div>
    <input type="range" id="ad-count" name="ad-count" min="1000" max="300000" step="100" value="1000" class="w-3/4">
    <span id="ad-count-value" class="inline-block ml-2 font-bold"></span>
</div>
 <!-- Окошко с информацией о скидках -->

<div id="discount-info" class="p-4 max-w-md w-full fixed right-0 top-20 ">
    <div class="flex justify-between items-center mb-4">
        <button id="close-button" class="text-xs bg-gray-200 mb-[-4%] w-24 h-6 border rounded-t-lg ml-[2%]">Скрыть</button>
    </div>
    <div class="bg-[#e0f4ff] p-4 rounded-lg">
        <div class="flex items-center mb-2">
            <img src="{{ asset('images/mishen.png') }}" class="h-12">
            <h1 class="text-lg font-bold">Специальное предложение!</h1>
        </div>
        <p class="text-sm font-semibold mb-4">Удвоенный пробный период + бонусы!</p>
        <p class="text-sm font-semibold mb-2">Программа "Взаимные скидки":</p>
        <ul class="list-disc font-semibold list-inside text-sm mb-4">
            <li>Увеличиваем бесплатный период с 14 до 30 дней</li>
            <li>Вы сами выбираете размер скидок для покупателей</li>
            <li>Мы удваиваем вашу щедрость персональной скидкой</li>
            <li>Свободно меняйте условия когда угодно</li>
        </ul>
        <p class="text-sm font-semibold mb-2">Бонусы:</p>
      <ul class="list-none list-inside text-sm mb-4">
    <li class="flex items-center mb-1 font-semibold">
        <span class="mr-2">✔</span> Пробный период 30 дней вместо 14
    </li>
    <li class="flex items-center mb-1 font-semibold">
        <span class="mr-2">✔</span> Полная свобода в настройке условий
    </li>
    <li class="flex items-center mb-1 font-semibold">
        <span class="mr-2">✔</span> Индивидуальный расчёт вашей выгоды
    </li>
</ul>
        <p class="text-xs mb-4">* Размер вашей скидки рассчитывается индивидуально и зависит от того, насколько щедрые условия вы предлагаете покупателям</p>
        <div class="flex">
            <button class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-12 rounded-lg">Подключить</button>
            <button class="bg-white hover:bg-gray-100 text-gray-700 py-2 px-12 rounded-lg ml-[2%]">Подробнее</button>
        </div>
    </div>
</div>

<!-- Модальное окно -->
<div id="myModal" class="modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-gray-200 rounded-lg p-6 w-[70%] ">
        <span class="close cursor-pointer text-gray-600 float-right text-2xl">&times;</span>
        <h2 class="text-xl mb-16 text-center text-gray-500">Ваша скидка на тарифный план:</h2>
         <p class="mb-4 mt-16 font-bold text-center">У вас пока нет скидки на оплату тарифного плана. Для получения скидки добавьте скидку для покупателей</p>
        <p class="mb-4 mt-16 text-center text-gray-500 text-xl">Укажите сумму покупки и размер предоставляемой скидки для Ваших покупателей:</p>
        <form id="discount-form" class="space-y-4">
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label for="purchase-from" class="block mb-1">При сумме покупки от:</label>
                    <input type="number" id="purchase-from" name="purchase-from" required class="w-full border rounded-md p-2">
                </div>
                <div class="flex-1">
                    <label for="purchase-to" class="block mb-1">При сумме покупки до:</label>
                    <input type="number" id="purchase-to" name="purchase-to" required class="w-full border rounded-md p-2">
                </div>
                <div class="flex-1">
                    <label for="discount-size" class="block mb-1">Вы предоставляете скидку:</label>
                    <input type="number" id="discount-size" name="discount-size" required class="w-full border rounded-md p-2">
                </div>
            </div>
            <button class="px-6 py-1 bg-blue-500 text-white rounded-lg float-right mb-16">+ Добавить еще</button>
            <p class="mt-4 text-center text-gray-500">Если есть какие-либо дополнительные условия и/или ограничения, укажите их ниже:</p>
            <textarea id="additional-conditions" name="additional-conditions" rows="4" class="w-full border rounded-md p-2"></textarea>
            
            <button type="submit" class="bg-blue-500 text-white py-2 px-4 rounded-lg mt-4">Сохранить</button>
        </form>
    </div>
</div>



        <div class="price-container mt-6">
            <p class="mb-2 font-bold">Стоимость размещения в день: <span id="daily-cost" class="text-green-600 font-semibold">₽0.00</span></p>
            <p class="mb-4 font-bold">Стоимость размещения в месяц: <span id="monthly-cost" class="text-green-600 font-semibold">₽0.00</span></p>
            <form action="{{ route('save.tariff') }}" method="POST" id="tariff-form" class="flex justify-center">
                @csrf
                <input type="hidden" id="ad-count-hidden" name="ad-count" value="1000">
                <button type="submit" id="save-button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Сохранить</button>
            </form>
        </div>
        @endif
    </div>
  <div class="w-[90%] mx-auto bg-orange-100 p-4 rounded-xl mb-4 ">
    <p class="ml-4">Списание средств за использование тарифа происходит автоматически на еженедельной основе с вашего внутреннего кошелька на платформе ГдеЗапчасть.<br>
    Размер еженедельного списания соответствует размеру стоимости размещения товаров в день выбранного вами тарифа.<br>
    Первое списание производится сразу после активации или изменения тарифа. <br>
    При недостаточном балансе кошелька размещенные товары автоматически становятся неактивными.</p>
</div>


    <!-- Уведомление об успешном обновлении тарифа -->
    <div id="success-notification" class="notification">
        Тариф успешно обновлен!
    </div>

    <!-- Вывод сообщения с подтверждением -->
    @if(session('warning'))
    <div class="fixed bottom-0 left-0 w-full bg-yellow-100 text-yellow-700 p-4 text-center" id="confirmation-message">
        {{ session('warning') }}
        <div class="mt-4">
            <button id="confirm-yes" class="bg-green-500 text-white px-4 py-2 rounded-md mr-2">Да</button>
            <button id="confirm-no" class="bg-red-500 text-white px-4 py-2 rounded-md">Нет</button>
        </div>
    </div>
    @endif

    <h3 class="text-xl font-bold text-center mt-6">Нужно разместить больше 300.000 товаров?</h3>
    <h3 class="text-xl font-bold text-center">Напишите нам и мы подготовим для Вас персональное предложение</h3>


    <script>
        // Получаем модальное окно
        var modal = document.getElementById("myModal");

        // Получаем кнопку "Подключить"
        var btn = document.querySelector(".bg-blue-500.hover\\:bg-blue-600.text-white.py-2.px-12.rounded-lg");
        

        // Получаем элемент <span>, который закрывает модальное окно
        var span = document.getElementsByClassName("close")[0];

        // Когда пользователь нажимает на кнопку, открываем модальное окно
        btn.onclick = function() {
            modal.style.display = "block";
            modal.style.display = "flex";
        }

        // Когда пользователь нажимает на <span> (x), закрываем модальное окно
        span.onclick = function() {
            modal.style.display = "none";
        }

        // Когда пользователь нажимает в любом месте вне модального окна, закрываем его
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
    <script>
     // Обработка клика по кнопке "Скрыть"
    document.getElementById('close-button').addEventListener('click', function() {
        document.getElementById('discount-info').style.display = 'none';
    });
    </script>
 <script>
        const pricingData = [
            { quantity: 1000, monthlyPrice: 3900, dailyPricePerItem: 0.13 },
            { quantity: 1500, monthlyPrice: 4275, dailyPricePerItem: 0.095 },
            { quantity: 2000, monthlyPrice: 4800, dailyPricePerItem: 0.08 },
            { quantity: 2300, monthlyPrice: 5175, dailyPricePerItem: 0.075 },
            { quantity: 2600, monthlyPrice: 5304, dailyPricePerItem: 0.068 },
            { quantity: 3000, monthlyPrice: 5400, dailyPricePerItem: 0.056 },
            { quantity: 3300, monthlyPrice: 5544, dailyPricePerItem: 0.053 },
            { quantity: 3600, monthlyPrice: 5724, dailyPricePerItem: 0.046 },
            { quantity: 4000, monthlyPrice: 5880, dailyPricePerItem: 0.049 },
            { quantity: 4300, monthlyPrice: 5934, dailyPricePerItem: 0.046 },
            { quantity: 4600, monthlyPrice: 6072, dailyPricePerItem: 0.043 },
            { quantity: 5000, monthlyPrice: 6150, dailyPricePerItem: 0.041 },
            { quantity: 5300, monthlyPrice: 6360, dailyPricePerItem: 0.040 },
            { quantity: 5600, monthlyPrice: 6552, dailyPricePerItem: 0.039 },
            { quantity: 6000, monthlyPrice: 6600, dailyPricePerItem: 0.037 },
            { quantity: 6300, monthlyPrice: 6785, dailyPricePerItem: 0.0359 },
            { quantity: 6600, monthlyPrice: 6870, dailyPricePerItem: 0.0347 },
            { quantity: 7000, monthlyPrice: 6930, dailyPricePerItem: 0.033 },
            { quantity: 7300, monthlyPrice: 7073, dailyPricePerItem: 0.0323 },
            { quantity: 7600, monthlyPrice: 7182, dailyPricePerItem: 0.0315 },
            { quantity: 8000, monthlyPrice: 7200, dailyPricePerItem: 0.03 },
            { quantity: 8300, monthlyPrice: 7345, dailyPricePerItem: 0.0295 },
            { quantity: 8600, monthlyPrice: 7482, dailyPricePerItem: 0.029 },
            { quantity: 9000, monthlyPrice: 7560, dailyPricePerItem: 0.028 },
            { quantity: 9300, monthlyPrice: 7756, dailyPricePerItem: 0.0278 },
            { quantity: 9600, monthlyPrice: 7920, dailyPricePerItem: 0.0275 },
            { quantity: 10000, monthlyPrice: 8100, dailyPricePerItem: 0.027 },
            { quantity: 13000, monthlyPrice: 9750, dailyPricePerItem: 0.025 },
            { quantity: 16000, monthlyPrice: 10944, dailyPricePerItem: 0.0228 },
            { quantity: 20000, monthlyPrice: 12000, dailyPricePerItem: 0.02 },
            { quantity: 26000, monthlyPrice: 13593, dailyPricePerItem: 0.0197 },
            { quantity: 30000, monthlyPrice: 17000, dailyPricePerItem: 0.019 },
            { quantity: 33000, monthlyPrice: 18513, dailyPricePerItem: 0.0187 },
            { quantity: 36000, monthlyPrice: 19764, dailyPricePerItem: 0.0183 },
            { quantity: 40000, monthlyPrice: 21600, dailyPricePerItem: 0.018 },
            { quantity: 43000, monthlyPrice: 22900, dailyPricePerItem: 0.0178 },
            { quantity: 46000, monthlyPrice: 24288, dailyPricePerItem: 0.0176 },
            { quantity: 50000, monthlyPrice: 25500, dailyPricePerItem: 0.017 },
            { quantity: 53000, monthlyPrice: 26712, dailyPricePerItem: 0.0168 },
            { quantity: 56000, monthlyPrice: 27888, dailyPricePerItem: 0.0166 },
            { quantity: 60000, monthlyPrice: 28800, dailyPricePerItem: 0.016 },
            { quantity: 63000, monthlyPrice: 29862, dailyPricePerItem: 0.0158 },
            { quantity: 66000, monthlyPrice: 30888, dailyPricePerItem: 0.0156 },
            { quantity: 70000, monthlyPrice: 31500, dailyPricePerItem: 0.015 },
            { quantity: 73000, monthlyPrice: 32412, dailyPricePerItem: 0.0148 },
            { quantity: 76000, monthlyPrice: 33060, dailyPricePerItem: 0.0145 },
            { quantity: 80000, monthlyPrice: 33600, dailyPricePerItem: 0.0144 },
            { quantity: 83000, monthlyPrice: 33864, dailyPricePerItem: 0.014 },
            { quantity: 86000, monthlyPrice: 34959, dailyPricePerItem: 0.01355 },
            { quantity: 90000, monthlyPrice: 36450, dailyPricePerItem: 0.0137 },
            { quantity: 93000, monthlyPrice: 37860, dailyPricePerItem: 0.01357 },
            { quantity: 96000, monthlyPrice: 38851, dailyPricePerItem: 0.01349 },
            { quantity: 100000, monthlyPrice: 39000, dailyPricePerItem: 0.013 },
            { quantity: 150000, monthlyPrice: 49500, dailyPricePerItem: 0.011 },
            { quantity: 200000, monthlyPrice: 54000, dailyPricePerItem: 0.009 },
            { quantity: 250000, monthlyPrice: 63750, dailyPricePerItem: 0.0085 },
            { quantity: 300000, monthlyPrice: 72000, dailyPricePerItem: 0.008 },
        ];

        const slider = document.getElementById('ad-count');
        const manualInput = document.getElementById('ad-count-manual');
        const dailyCost = document.getElementById('daily-cost');
        const monthlyCost = document.getElementById('monthly-cost');
        const adCountHidden = document.getElementById('ad-count-hidden');
        const successNotification = document.getElementById('success-notification');

        function formatNumber(number) {
            return number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        }

        function unformatNumber(number) {
            return number.toString().replace(/,/g, '');
        }

        slider.oninput = function() {
            let value = parseInt(this.value);
            manualInput.value = formatNumber(value);
            adCountHidden.value = value;
            updatePrices(value);
        }

        manualInput.addEventListener('blur', function() {
            let value = this.value;

            // Remove non-numeric characters except commas
            value = value.replace(/[^0-9,]/g, '');

            // Remove all commas
            let number = parseInt(unformatNumber(value));

            if (isNaN(number)) {
                number = 1000; // Default value
            }

            // Ensure the number is within the allowed range
            number = Math.max(parseInt(this.min), Math.min(number, parseInt(this.max)));

            slider.value = number;
            adCountHidden.value = number;
            this.value = formatNumber(number);
            updatePrices(number);
        });

        function updatePrices(adCount) {
            const selectedData = pricingData.find(item => item.quantity >= adCount) || pricingData[pricingData.length - 1];

            const dailyCostValue = selectedData.monthlyPrice / 30;
            const monthlyCostValue = selectedData.monthlyPrice;

            dailyCost.textContent = `₽${dailyCostValue.toFixed(2)}`;
            monthlyCost.textContent = `₽${monthlyCostValue.toFixed(2)}`;
        }

        document.addEventListener('DOMContentLoaded', function() {
            const initialAdCount = 1000;
            slider.value = initialAdCount;
            manualInput.value = formatNumber(initialAdCount);
            adCountHidden.value = initialAdCount;
            updatePrices(initialAdCount);
        });

        // Обработка подтверждения
        document.getElementById('confirm-yes')?.addEventListener('click', function() {
            const form = document.getElementById('tariff-form');
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'confirm';
            hiddenInput.value = 'true';
            form.appendChild(hiddenInput);
            form.submit();
        });

        document.getElementById('confirm-no')?.addEventListener('click', function() {
            document.getElementById('confirmation-message').remove();
        });

        // Обработка отправки формы
        document.getElementById('tariff-form')?.addEventListener('submit', function(event) {
            event.preventDefault(); // Предотвращаем стандартную отправку формы

            adCountHidden.value = unformatNumber(adCountHidden.value); // Ensure adCountHidden has no commas

            // Показываем уведомление
            successNotification.style.display = 'block';
            setTimeout(() => {
                successNotification.style.display = 'none';
            }, 3000); // Уведомление исчезнет через 3 секунды

            // Отправляем форму (если нужно)
            setTimeout(() => {
                this.submit();
            }, 1000); // Задержка перед отправкой формы
        });
    </script>

</body>
</html>
