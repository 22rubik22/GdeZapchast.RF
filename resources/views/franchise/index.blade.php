<!DOCTYPE html>
<html lang="ru">
<head>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Франшиза</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/franchise.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
        .list-custom {
            list-style-type: disc;
            margin-left: 20px;
            margin-bottom: 10px;
        }
        .list-custom li {
            margin-bottom: 5px;
        }

        .list-none {
            list-style-type: none; /* Убираем маркеры списка */
            margin-left: 20px; /* Добавляем отступ, если необходимо */
            padding-left: 0; /* Убираем стандартный отступ списка */
        }

        .list-none li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
@include('components.header-seller')
<div class="container mx-auto p-4 mb-20">

    <h2 class="text-3xl font-bold mb-6 text-center">СТАНЬТЕ ЭКСКЛЮЗИВНЫМ ПРЕДСТАВИТЕЛЕМ ФЕДЕРАЛЬНОЙ ПЛАТФОРМЫ АВТОЗАПЧАСТЕЙ В СВОЁМ ГОРОДЕ</h2>

    <p class=" leading-relaxed mb-8">Мы создали инновационную B2B платформу, объединяющую магазины автозапчастей и авторазборы по всей России в единую экосистему. Предлагаем вам стать частью быстрорастущего рынка с гарантированной доходностью и готовой бизнес-моделью.</p>

   
    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">ПОЧЕМУ ЭТО РАБОТАЕТ:</h3>
        <ul class="list-none pl-5 mb-4">
            <li> - Объем рынка автозапчастей превышает 1,5 трлн рублей и стабильно растет даже в кризис</li>
            <li> - Готовая база клиентов: магазины автозапчастей и авторазборы активно ищут новые каналы продаж</li>
            <li> - Покупатели экономят до 70% времени на поиске нужных запчастей</li>
            <li> - Инновационная система поиска автоматически находит аналоги и проверяет совместимость</li>
            <li> - Встроенная бизнес-аналитика для участников платформы</li>
            <li> - Простая интеграция для продавцов автозапчастей через автоматический конвертер прайс-листов</li>
            <li> - Прозрачная система тарификации без скрытых платежей</li>
            <li> - Круглосуточная техническая поддержка</li>
        </ul>
    </section>

    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">ПОЧЕМУ КЛИЕНТЫ ВЫБИРАЮТ НАШУ ПЛАТФОРМУ:</h3>

        <h4 class="text-lg font-semibold mb-2">Продавцы получают:</h4>
        <ul class="list-none pl-5 mb-4">
            <li> - Новый мощный канал продаж без затрат на рекламу</li>
            <li> - Доступ к профессиональной аналитике рынка</li>
            <li> - Увеличение объема продаж без дополнительных вложений</li>
            <li> - Автоматизацию процессов и экономию времени</li>
        </ul>

        <h4 class="text-lg font-semibold mb-2">Покупатели получают:</h4>
        <ul class="list-none pl-5 mb-4">
            <li> - Единую базу автозапчастей всего региона</li>
            <li> - Интеллектуальный поиск с учетом аналогов</li>
            <li> - Актуальные цены и наличие в реальном времени</li>
            <li> - Экономию времени на поиске нужных запчастей</li>
        </ul>
    </section>

    <div class="container mx-auto p-4">
        <h2 class="text-2xl font-bold mb-4 text-center">ВАШ ГАРАНТИРОВАННЫЙ ДОХОД:</h2>
        <p class="text-gray-700 leading-relaxed mb-4 text-center">20% от всех ежемесячных платежей компаний вашего города за размещение на платформе</p>
        <div class="overflow-x-auto">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                <tr>
                    <th class="border border-gray-300 p-2 bg-gray-200 text-center">Кол-во магазинов</th>
                    <th class="border border-gray-300 p-2 bg-gray-200 text-center">Ваши доходы в месяц</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td class="border border-gray-300 p-2 text-center">50 компаний</td>
                    <td class="border border-gray-300 p-2 text-center">от 100 000 ₽/мес</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 p-2 text-center">100 компаний</td>
                    <td class="border border-gray-300 p-2 text-center">от 200 000 ₽/мес</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 p-2 text-center">200 компаний</td>
                    <td class="border border-gray-300 p-2 text-center">от 400 000 ₽/мес</td>
                </tr>
                <tr>
                    <td class="border border-gray-300 p-2 text-center">500 компаний</td>
                    <td class="border border-gray-300 p-2 text-center">от 1 000 000 ₽/мес</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

    <section class="mb-8">
        <h2 class="text-2xl font-bold mb-4 text-center">ЧТО ВЫ ПОЛУЧАЕТЕ:</h2>
        <ul>
            <li> - Стабильный доход: ежемесячные платежи от подключенных партнеров</li>
            <li> - Поддержка 24/7: профессиональная команда поможет на всех этапах развития</li>
            <li> - Персонального менеджера по развитию</li>
            <li> - Эксклюзивные права на развитие территории</li>
            <li> - Готовую технологичную платформу</li>
<li> - Профессиональное обучение методам привлечения клиентов</li>
            <li> - Полный комплект маркетинговых материалов</li>
        </ul>
    </section>

    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">ВАШИ КЛЮЧЕВЫЕ ЗАДАЧИ:</h3>
        <ul class="list-none pl-5 mb-4">
            <li> - Привлечение и подключение магазинов автозапчастей и авторазборов</li>
            <li> - Выстраивание долгосрочных отношений с клиентами</li>
            <li> - Развитие бизнеса в вашем регионе</li>
        </ul>
    </section>

    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">МЫ ОБЕСПЕЧИВАЕМ:</h3>
        <ul class="list-none pl-5 mb-4">
            <li> - Постоянную разработку и обновление платформы</li>
            <li> - Профессиональную техническую поддержку 24/7</li>
            <li> - Регулярное внедрение нового функционала</li>
            <li> - Федеральный маркетинг и PR-поддержку</li>
        </ul>
    </section>

    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">ИДЕАЛЬНО ПОДХОДИТ ДЛЯ:</h3>
        <ul class="list-none pl-5 mb-4">
            <li> - Действующих предпринимателей из автобизнеса</li>
            <li> - Опытных специалистов по B2B-продажам</li>
            <li> - Инвесторов, нацеленных на стабильный растущий доход</li>
        </ul>
    </section>

    <section class="mb-8">
        <h3 class="text-xl font-semibold mb-4">КЛЮЧЕВЫЕ ПРЕИМУЩЕСТВА:</h3>
        <ul class="list-none pl-5 mb-4">
            <li> - Вы получаете проверенную бизнес-модель с доказанной эффективностью</li>
            <li> - Быстрый старт без крупных инвестиций</li>
            <li> - Минимальные риски: не требуется аренда помещения и найм большого штата</li>
            <li> - Стабильный пассивный доход от повторных платежей</li>
            <li> - Активно растущий рынок с высоким потенциалом</li>
            <li> - Отсутствие прямых конкурентов</li>
            <li> - Защищенная территория</li>
        </ul>
    </section>

    <section>
        <h3 class="text-xl font-semibold mb-4">ИНВЕСТИЦИИ:</h3>
        <p class="mb-4"> - Стоимость франшизы рассчитывается индивидуально, исходя из потенциала города</p>
        <p class="mb-4"> - Окупаемость: от 6 до 12 месяцев</p>
        <p class="font-bold"> - Количество свободных городов ограничено! Забронируйте свою территорию прямо сейчас!</p>
    </section>

    <p class="text-sm text-gray-500 mt-4">* Все указанные показатели являются прогнозируемыми и зависят от активности партнера и специфики региона</p>
</div>

 @include('components.footer')   
</body>
</html>