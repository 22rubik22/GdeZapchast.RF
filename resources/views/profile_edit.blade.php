<!DOCTYPE html>
<html lang="ru">
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
    <title>Редактирование профиля</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }

    .password-icon {
  transition: opacity 0.3s ease;
}

.password-icon:hover {
  opacity: 0.7;
}

.toggle-password {
  z-index: 10; /* Убедитесь, что иконка находится над полем ввода */
}
    .suggest-view {
        position: absolute;
        z-index: 1000;
        background-color: white;
        border: 1px solid #ccc;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
    }
    .suggestion-item {
        padding: 5px;
        cursor: pointer;
    }
    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
<body>
@include('components.header-seller')

<div class="container mx-auto p-8 max-md:mb-20">
    <h1 class="text-lg font-semibold mb-4">Профиль / Редактировать профиль</h1>
    <div class="flex flex-col lg:flex-row">
        <div class="w-full flex-grow">

            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">О компании</h2>
                <form action="{{ route('profile.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Название магазина</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-2/3 border border-gray-300 rounded p-2" placeholder="Под этим названием покупатели будут видеть ваши товары">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">E-Mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Телефон</label>
                        <input type="tel" name="phone" id="phoneInput" value="{{ old('phone', $user->phone) }}" class="w-2/3 border border-gray-300 rounded p-2" oninput="formatPhoneNumber(this)">
                        <div class="mt-2 text-sm cursor-pointer"><i class="fas fa-plus-circle"></i> Добавить дополнительный телефон</div>
                    </div>
                     <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Адрес</label>
                            <input type="text" class="w-2/3 border border-gray-300 rounded p-2">
                        </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">URL изображения профиля</label>
                        <input type="url" name="avatar_url" value="{{ old('avatar_url', $user->avatar_url) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">URL логотипа</label>
                        <input type="url" name="logo_url" value="{{ old('logo_url', $user->logo_url) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <!-- Контейнер для ввода адресов филиалов -->
<div id="branchAddressContainer">
    <!-- Первое поле для ввода адреса филиала -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Адрес филиала</label>
        <input type="text" name="branch_address[]" placeholder="Введите адрес филиала" class="w-2/3 border border-gray-300 rounded p-2" autocomplete="off">
        <div class="suggest-view max-h-52 overflow-y-auto bg-white border rounded-md hidden"></div>
    </div>
</div>
            <!-- Кнопка "Добавить филиал" -->
<div class="mb-4 flex flex-wrap gap-2 ">
    <button type="button" id="addBranchButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Добавить филиал</button>
    <button type="button" id="showBranchesButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Показать все филиалы</button>
</div>

<!-- Модальное окно для отображения филиалов -->
<div id="branchesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Список филиалов</h3>
            <div id="branchesList" class="mt-2">
                <!-- Сюда будут добавляться филиалы -->
            </div>
            <div class="mt-4">
                <button type="button" id="closeBranchesModal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Закрыть</button>
            </div>
        </div>
    </div>
</div>
                    <h2 class="text-xl font-semibold mt-8 mb-4">Юридическая информация</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Название организации</label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $user->legalInfo->organization_name ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2" placeholder="Полное название компании (с указанием формы собственности)">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Юридический адрес</label>
                        <input type="text" name="legal_address" value="{{ old('legal_address', $user->legalInfo->legal_address ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">ИНН</label>
                        <input type="text" name="inn" value="{{ old('inn', $user->legalInfo->inn ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                        @error('inn')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">КПП</label>
                        <input type="text" name="kpp" value="{{ old('kpp', $user->legalInfo->kpp ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                        @error('kpp')
{{--                        <span class="text-red-500 text-sm">{{ $message }}</span>--}}
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-500 text-white rounded p-2 hover:bg-blue-600">Сохранить</button>
                </form>
            </div>
        </div>
        <div class="w-full mt-8 lg:mt-0 flex flex-col">
            <button id="changePasswordButton" class="w-100 sm:w-1/2  bg-[#535151] text-white rounded p-2 mb-8 flex items-center justify-center">
                <i class="fas fa-lock mr-2"></i> Сменить пароль
            </button>
            <button id="btnDelivery"
                    class="w-full sm:w-1/2 bg-gray-100 text-gray-800 rounded p-4 mb-4 font-bold flex items-center justify-center">
                <i class="fas fa-truck mr-2"></i> Условия оплаты и доставки
            </button>
            <button id="btnShop"
                    class="w-full sm:w-1/2 bg-gray-100 text-gray-800 rounded p-4 mb-4 font-bold flex items-center justify-center">
                <i class="fas fa-pencil-alt mr-2"></i> Расскажите о Вашем магазине
            </button>

            {{-- Модалка: Условия оплаты и доставки --}}
            <div id="modalDelivery"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg p-6 w-4/5 max-w-none relative overflow-y-auto max-h-[90vh]">
                    {{-- Закрыть --}}
                    <button id="closeDelivery" class="absolute top-3 right-3 text-2xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-6 text-center">Доставка, оплата и гарантия</h2>

                    <form id="formDelivery" method="POST" action="{{ route('profile.update_delivery', $user->id) }}">
                        @csrf

                        {{-- Условия гарантии и возврата --}}
                        <div class="mb-6">
                            <label for="warranty" class="block mb-2 font-medium">Условия гарантии и возврата</label>
                            <textarea id="warranty"
                                      name="warranty_return_policy"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500"
                                      placeholder="Условия гарантии и возврата">{{ old('warranty_return_policy', $user->sellerInfo->warranty_return_policy ?? '') }}</textarea>
                        </div>

                        {{-- Самовывоз --}}
                        <div class="mb-6 relative">
                            <label class="block mb-2 font-medium">Самовывоз (филиалы)</label>
                            <div id="pickupDisplay"
                                 class="w-full bg-white border border-gray-300 rounded-lg px-4 py-2 flex items-center justify-between cursor-pointer">
                                  <span id="pickupPlaceholder" class="text-gray-500">
                                    {{ $user->branches->isEmpty() ? 'Филиалы не указаны' : 'Выберите филиалы' }}
                                  </span>
                                <svg id="pickupArrow"
                                     class="w-5 h-5 text-gray-400 transition-transform duration-200"
                                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                          d="M19 9l-7 7-7-7"/>
                                </svg>
                            </div>
                            <select id="pickup" name="branch_ids[]" multiple hidden>
                                @foreach($user->branches as $branch)
                                    <option value="{{ $branch->id_branch }}"
                                        {{ in_array($branch->id_branch, old('branch_ids', $user->sellerInfo->branch_ids ?? [])) ? 'selected' : '' }}>
                                        {{ $branch->address }}
                                    </option>
                                @endforeach
                            </select>
                            <div id="pickupDropdown"
                                 class="absolute z-50 mt-1 w-full bg-white border border-gray-300 rounded-lg shadow-lg hidden max-h-60 overflow-auto divide-y divide-gray-200">
                                @foreach($user->branches as $branch)
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50">
                                        <input type="checkbox"
                                               class="branch-checkbox form-checkbox h-4 w-4 text-blue-600"
                                               value="{{ $branch->id_branch }}"
                                            {{ in_array($branch->id_branch, old('pickup_branches', $user->sellerInfo->branch_ids ?? [])) ? 'checked' : '' }}>
                                        <span class="ml-3 text-gray-700">{{ $branch->address }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        {{-- Доставка --}}
                        <div class="mb-6">

                            <div class="flex flex-col gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           id="deliveryInCity"
                                           name="delivery_in_city"
                                           class="form-checkbox h-4 w-4"
                                        {{ old('delivery_in_city', $user->sellerInfo->delivery_in_city ?? false) ? 'checked' : '' }}>
                                    <span class="ml-2">Доставка по городу ({{ $user->city }})</span>
                                </label>

                                {{-- Опции при доставке по городу --}}
                                <div
                                    id="cityOptions"
                                    class="
                                        ml-6 mt-2 space-y-2
                                        {{ old('delivery_in_city', $user->sellerInfo->delivery_in_city ?? false) ? '' : 'hidden'  }}
                                    "
                                >
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               id="cityFree"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('delivery_in_city_cost') === null && old('city_cost_toggle') ? '' : (old('delivery_in_city_cost', $user->sellerInfo->delivery_in_city_cost ?? null) === 0 ? 'checked' : '') }}>
                                        <span class="ml-2">Бесплатная</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               id="cityPaid"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('delivery_in_city_cost', $user->sellerInfo->delivery_in_city_cost ?? null) > 0 ? 'checked' : '' }}>
                                        <span class="ml-2">Платная, фиксированная -</span>
                                        <input type="text"
                                               id="cityCostInput"
                                               name="delivery_in_city_cost"
                                               value="{{ old('delivery_in_city_cost', $user->sellerInfo->delivery_in_city_cost ?? '') }}"
                                               class="ml-3 border border-gray-300 p-1 w-24 focus:ring-blue-500"
                                            {{ old('delivery_in_city_cost', $user->sellerInfo->delivery_in_city_cost ?? 0) > 0 ? '' : 'disabled' }}>
                                        <span class="ml-2">₽</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Доставка до ТК --}}
                        <div class="mb-6">
                            <label class="inline-flex items-center">
                                <input type="checkbox"
                                       id="deliveryToTk"
                                       name="delivery_to_transport_company"
                                       class="form-checkbox h-4 w-4"
                                    {{ old('delivery_to_transport_company', $user->sellerInfo->delivery_to_transport_company ?? false) ? 'checked' : '' }}>
                                <span class="ml-2">Доставка до транспортной компании</span>
                            </label>
                            <div
                                id="tkOptions"
                                class="
                                    ml-6 mt-2
                                    {{ old('delivery_to_transport_company', $user->sellerInfo->delivery_to_transport_company ?? false) ? '' : 'hidden' }}
                                "
                            >
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           id="tkFree"
                                           class="form-checkbox h-4 w-4"
                                        {{ old('delivery_to_transport_company_cost', $user->sellerInfo->delivery_to_transport_company_cost ?? 0) == 0 ? 'checked' : '' }}>
                                    <span class="ml-2">Бесплатная</span>
                                </label>
                                <label class="flex items-center mt-2">
                                    <input type="checkbox"
                                           id="tkPaid"
                                           class="form-checkbox h-4 w-4"
                                        {{ old('delivery_to_transport_company_cost', $user->sellerInfo->delivery_to_transport_company_cost ?? 0) > 0 ? 'checked' : '' }}>
                                    <span class="ml-2">Платная, фиксированная -</span>
                                    <input type="text"
                                           id="tkCostInput"
                                           name="delivery_to_transport_company_cost"
                                           value="{{ old('delivery_to_transport_company_cost', $user->sellerInfo->delivery_to_transport_company_cost ?? '') }}"
                                           class="ml-3 border border-gray-300 p-1 w-24 focus:ring-blue-500"
                                        {{ old('delivery_to_transport_company_cost', $user->sellerInfo->delivery_to_transport_company_cost ?? 0) > 0 ? '' : 'disabled' }}>
                                    <span class="ml-2">₽</span>
                                </label>
                                <p class="mt-4 mb-2 font-medium">Доступные ТК</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-2 text-gray-700">
                                    @foreach($deliveryCols as $col)
                                        <label class="flex items-center">
                                            <input
                                                type="checkbox"
                                                name="available_tk[]"
                                                value="{{ $col }}"
                                                {{ $user->deliveryService[$col] == 1 ? 'checked' : '' }}
                                                class="form-checkbox h-4 w-4"
                                            >
                                            <span class="ml-2">{{ $col }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Доставка до маршрутного такси --}}
                        <div class="mb-6">

                            <div class="flex flex-col gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           id="deliveryToRouteTaxi"
                                           name="delivery_to_route_taxi"
                                           class="form-checkbox h-4 w-4"
                                        {{ old('delivery_to_route_taxi', $user->sellerInfo->delivery_to_route_taxi ?? false) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2">Доставка до маршрутного такси</span>
                                </label>

                                {{-- Опции при доставке до маршрутного такси --}}
                                <div
                                    id="routeTaxiOptions"
                                    class="
                                        ml-6 mt-2 space-y-2
                                        {{ old('delivery_to_route_taxi', $user->sellerInfo->delivery_to_route_taxi ?? false) ? '' : 'hidden' }}
                                    "
                                >
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               id="routeTaxiFree"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('delivery_to_route_taxi_cost') === null && old('taxi_cost_toggle') ? '' : (old('delivery_to_route_taxi_cost', $user->sellerInfo->delivery_to_route_taxi_cost ?? null) === 0 ? 'checked' : '') }}>
                                        <span class="ml-2">Бесплатная</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               id="taxiPaid"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('delivery_to_route_taxi_cost', $user->sellerInfo->delivery_to_route_taxi_cost ?? null) > 0 ? 'checked' : '' }}>
                                        <span class="ml-2">Платная, фиксированная -</span>
                                        <input type="text"
                                               id="taxiCostInput"
                                               name="delivery_to_route_taxi_cost"
                                               value="{{ old('delivery_to_route_taxi_cost', $user->sellerInfo->delivery_to_route_taxi_cost ?? '') }}"
                                               class="ml-3 border border-gray-300 p-1 w-24 focus:ring-blue-500"
                                            {{ old('delivery_to_route_taxi_cost', $user->sellerInfo->delivery_to_route_taxi_cost ?? 0) > 0 ? '' : 'disabled' }}>
                                        <span class="ml-2">₽</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Доставка Почтой России --}}
                        <div class="mb-6">

                            <div class="flex flex-col gap-2">
                                <label class="inline-flex items-center">
                                    <input type="checkbox"
                                           id="deliveryRussianPost"
                                           name="delivery_russian_post"
                                           class="form-checkbox h-4 w-4"
                                        {{ old('delivery_russian_post', $user->sellerInfo->delivery_russian_post ?? false) ? 'checked' : '' }}
                                    >
                                    <span class="ml-2">Доставка Почтой России</span>
                                </label>

                                {{-- Опции при доставке Почтой России --}}
                                <div
                                    id="russianPostOptions"
                                    class="
                                        ml-6 mt-2 space-y-2
                                        {{ old('delivery_russian_post', $user->sellerInfo->delivery_russian_post ?? false) ? '' : 'hidden' }}
                                    "
                                >
                                    <label class="inline-flex items-center">
                                        <input type="checkbox"
                                               id="postFree"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('russian_post_additional_cost') === null && old('post_cost_toggle') ? '' : (old('russian_post_additional_cost', $user->sellerInfo->russian_post_additional_cost ?? null) === 0 ? 'checked' : '') }}>
                                        <span class="ml-2">Бесплатная</span>
                                    </label>
                                    <label class="flex items-center">
                                        <input type="checkbox"
                                               id="postPaid"
                                               class="form-checkbox h-4 w-4"
                                            {{ old('russian_post_additional_cost', $user->sellerInfo->russian_post_additional_cost ?? null) > 0 ? 'checked' : '' }}>
                                        <span class="ml-2">Дополнительные расходы (доставка до отделения, доп. упаковка) -</span>
                                        <input type="text"
                                               id="postCostInput"
                                               name="russian_post_additional_cost"
                                               value="{{ old('russian_post_additional_cost', $user->sellerInfo->russian_post_additional_cost ?? '') }}"
                                               class="ml-3 border border-gray-300 p-1 w-24 focus:ring-blue-500"
                                            {{ old('russian_post_additional_cost', $user->sellerInfo->russian_post_additional_cost ?? 0) > 0 ? '' : 'disabled' }}>
                                        <span class="ml-2">₽</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Дополнительные условия доставки --}}
                        <div class="mb-6">
                            <label for="additionalConditions" class="block mb-2 font-medium">Дополнительные условия доставки</label>
                            <textarea id="additionalConditions"
                                      name="additional_delivery_conditions"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500"
                                      placeholder="Дополнительные условия доставки">{{ old('additional_delivery_conditions', $user->sellerInfo->additional_delivery_conditions ?? '') }}</textarea>
                        </div>

                        {{-- Кнопки --}}
                        <div class="flex justify-end mt-8">
                            <button type="button"
                                    id="cancelDelivery"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg px-5 py-2 mr-4">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-6 py-2">
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // открыть/закрыть
                document.getElementById('btnDelivery').onclick = () => {
                    document.getElementById('modalDelivery').classList.remove('hidden');
                };
                document.getElementById('closeDelivery').onclick =
                    document.getElementById('cancelDelivery').onclick = () => {
                        document.getElementById('modalDelivery').classList.add('hidden');
                    };

                // city shipping toggle
                const cityCb = document.getElementById('deliveryInCity'),
                    taxiCb = document.getElementById('deliveryToRouteTaxi'),
                    postCb = document.getElementById('deliveryRussianPost'),
                    cityOpts = document.getElementById('cityOptions'),
                    taxiOpts = document.getElementById('routeTaxiOptions'),
                    postOpts = document.getElementById('russianPostOptions'),
                    freeCity = document.getElementById('cityFree'),
                    freeTaxi = document.getElementById('routeTaxiFree'),
                    freePost = document.getElementById('postFree'),
                    paidCity = document.getElementById('cityPaid'),
                    paidTaxi = document.getElementById('taxiPaid'),
                    paidPost = document.getElementById('postPaid'),
                    costCityInput = document.getElementById('cityCostInput'),
                    costTaxiInput = document.getElementById('taxiCostInput'),
                    costPostInput = document.getElementById('postCostInput');

                cityCb.onchange = () => cityOpts.classList.toggle('hidden', !cityCb.checked);
                taxiCb.onchange = () => taxiOpts.classList.toggle('hidden', !taxiCb.checked);
                postCb.onchange = () => postOpts.classList.toggle('hidden', !postCb.checked);

                freeCity.onchange = () => {
                    if (freeCity.checked) {
                        paidCity.checked = false;
                        costCityInput.value = 0;
                        costCityInput.disabled = true;
                    }
                };
                paidCity.onchange = () => {
                    if (paidCity.checked) {
                        freeCity.checked = false;
                        costCityInput.disabled = false;
                        costCityInput.focus();
                    }
                };

                freeTaxi.onchange = () => {
                    if (freeTaxi.checked) {
                        paidTaxi.checked = false;
                        costTaxiInput.value = 0;
                        costTaxiInput.disabled = true;
                    }
                };
                paidTaxi.onchange = () => {
                    if (paidTaxi.checked) {
                        freeTaxi.checked = false;
                        costTaxiInput.disabled = false;
                        costTaxiInput.focus();
                    }
                };

                freePost.onchange = () => {
                    if (freePost.checked) {
                        paidPost.checked = false;
                        costPostInput.value = 0;
                        costPostInput.disabled = true;
                    }
                };
                paidPost.onchange = () => {
                    if (paidPost.checked) {
                        freePost.checked = false;
                        costPostInput.disabled = false;
                        costPostInput.focus();
                    }
                };


                // TK toggle
                const tkCb = document.getElementById('deliveryToTk'),
                    tkOpts = document.getElementById('tkOptions'),
                    freeTk = document.getElementById('tkFree'),
                    paidTk = document.getElementById('tkPaid'),
                    costTkInput = document.getElementById('tkCostInput');

                tkCb.onchange = () => tkOpts.classList.toggle('hidden', !tkCb.checked);

                freeTk.onchange = () => {
                    if (freeTk.checked) {
                        paidTk.checked = false;
                        costTkInput.value = 0;
                        costTkInput.disabled = true;
                    }
                };
                paidTk.onchange = () => {
                    if (paidTk.checked) {
                        freeTk.checked = false;
                        costTkInput.disabled = false;
                        costTkInput.focus();
                    }
                };

                // custom multiselect для филиалов (без изменений)
                const display = document.getElementById('pickupDisplay'),
                    dropdown = document.getElementById('pickupDropdown'),
                    hiddenSelect = document.getElementById('pickup'),
                    placeholder = document.getElementById('pickupPlaceholder'),
                    arrow = document.getElementById('pickupArrow');

                display.onclick = () => {
                    dropdown.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                };
                document.addEventListener('click', e => {
                    if (!display.contains(e.target) && !dropdown.contains(e.target)) {
                        dropdown.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    }
                });
                let changePlaceHolder = () => {
                    let sel = [];
                    hiddenSelect.querySelectorAll('option').forEach(opt => opt.selected = false);
                    dropdown.querySelectorAll('.branch-checkbox:checked').forEach(box => {
                        hiddenSelect.querySelector(`option[value="${box.value}"]`).selected = true;
                        sel.push(box.nextElementSibling.textContent.trim());
                    });
                    placeholder.textContent = sel.length ? sel.join(', ') : 'Выберите филиалы';
                }
                dropdown.querySelectorAll('.branch-checkbox').forEach(chk => {
                    chk.onchange = changePlaceHolder;
                });
                document.addEventListener('DOMContentLoaded', changePlaceHolder);
            </script>

            <div id="modalAbout"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
                <div class="bg-white rounded-lg p-6 w-4/5 max-w-none relative overflow-y-auto max-h-[90vh]">
                    {{-- Закрыть --}}
                    <button id="closeAbout" class="absolute top-3 right-3 text-2xl">&times;</button>
                    <h2 class="text-2xl font-bold mb-6 text-center">О Магазине</h2>

                    <form id="formDelivery" method="POST" action="{{ route('profile.update_shop_about', $user->id) }}">
                        @csrf

                        <div class="mb-6">
                            <label for="shop_about" class="block mb-2 font-medium">Расскажите о своем магазине</label>
                            <textarea id="shop_about"
                                      name="about_shop"
                                      rows="4"
                                      class="w-full border border-gray-300 rounded-lg p-3 focus:ring-blue-500"
                                      placeholder="Расскажите о своем магазине">{{ old('about_shop', $user->sellerInfo->about_shop ?? '') }}</textarea>
                        </div>

                        {{-- Кнопки --}}
                        <div class="flex justify-end mt-8">
                            <button type="button"
                                    id="cancelAbout"
                                    class="bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg px-5 py-2 mr-4">
                                Отмена
                            </button>
                            <button type="submit"
                                    class="bg-blue-500 hover:bg-blue-600 text-white rounded-lg px-6 py-2">
                                Сохранить
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <script>
                // открыть/закрыть
                document.getElementById('btnShop').onclick = () => {
                    document.getElementById('modalAbout').classList.remove('hidden');
                };
                document.getElementById('closeAbout').onclick =
                    document.getElementById('cancelAbout').onclick = () => {
                        document.getElementById('modalAbout').classList.add('hidden');
                    };
            </script>



            <div class="text-sm text-gray-600">
                <p class="mb-2">Как выбрать название магазина</p>
                <p>Как добавить дополнительный телефон</p>
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

<!-- Модальное окно для смены пароля -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Сменить пароль</h3>
            <form id="passwordForm" action="{{ route('change.password') }}" method="POST" class="mt-2">
                @csrf
                @method('PUT')
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Текущий пароль</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="currentPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('currentPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Новый пароль</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="newPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('newPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Подтвердите пароль</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="confirmPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('confirmPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" id="submitPassword" class="bg-blue-500 text-white px-4 py-2 rounded-md">Сохранить</button>
                    <button type="button" id="closeModal" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
     // Добавление функциональности для кнопки "Показать все филиалы"

    document.getElementById('showBranchesButton').addEventListener('click', function() {
        const branchesModal = document.getElementById('branchesModal');
        const branchesList = document.getElementById('branchesList');
        branchesList.innerHTML = ''; // Очищаем список перед заполнением

        // Получаем список филиалов с сервера
        fetch(`/users/{{ $user->id }}/branches`) // Используем Blade для вставки ID пользователя
        .then(response => response.json())
            .then(branches => {
                if (branches.length > 0) {
                    branches.forEach(branch => {
                        const branchElement = document.createElement('div');
                        branchElement.classList.add('mb-2', 'p-2', 'border', 'rounded', 'flex', 'justify-between', 'items-center'); // Добавляем классы Tailwind CSS

                        const addressElement = document.createElement('span');
                        addressElement.textContent = branch.address;

                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'Удалить';
                        deleteButton.classList.add('bg-red-500', 'text-white', 'px-2', 'py-1', 'rounded', 'text-sm'); // Добавляем классы Tailwind CSS
                        deleteButton.addEventListener('click', function() {
                            deleteBranch(branch.id_branch); // Используем ID филиала
                        });

                        branchElement.appendChild(addressElement);
                        branchElement.appendChild(deleteButton);
                        branchesList.appendChild(branchElement);
                    });
                } else {
                    branchesList.textContent = 'Филиалы не найдены.';
                }
                branchesModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Ошибка при получении филиалов:', error);
                branchesList.textContent = 'Ошибка при загрузке филиалов.';
            });
    });

    // Закрытие модального окна с филиалами
    document.getElementById('closeBranchesModal').addEventListener('click', function() {
        document.getElementById('branchesModal').classList.add('hidden');
    });

    function deleteBranch(branchId) {
    if (confirm('Вы уверены, что хотите удалить этот филиал?')) {
        fetch(`/branches/${branchId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) { // Проверяем статус ответа
                return response.json().then(err => {
                    throw new Error(err.message || 'Ошибка при удалении филиала.'); // Пробрасываем сообщение об ошибке
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                alert(data.message);
                // Обновляем список филиалов после удаления
                document.getElementById('showBranchesButton').click(); // Эмулируем клик на кнопке "Показать все филиалы"
            }
        })
        .catch(error => {
            console.error('Ошибка при удалении филиала:', error);
            alert(error.message || 'Ошибка при удалении филиала.'); // Используем сообщение из ошибки
        });
    }
}

</script>
<script>
    // Функция для форматирования номера телефона
    function formatPhoneNumber(input) {
        let phoneNumber = input.value.replace(/\D/g, ''); // Убираем все нецифровые символы
        phoneNumber = phoneNumber.substring(0, 11); // Ограничиваем длину до 11 цифр

        if (phoneNumber.length > 0) {
            phoneNumber = '8 ' + phoneNumber.substring(1).replace(/(\d{3})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4');
        }

        input.value = phoneNumber;
    }

    // Открытие модального окна для смены пароля
    document.getElementById('changePasswordButton').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.remove('hidden');
    });

    // Закрытие модального окна
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.add('hidden');
    });

    // Обработка отправки формы смены пароля
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        const formData = new FormData(this); // Собираем данные формы

        // Отправляем данные на сервер
        fetch(this.action, {
            method: 'POST', // Отправляем методом POST
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    // Если статус ответа не 200-299, пытаемся распарсить ошибку
                    return response.json().then(err => {
                        throw new Error(err.message || 'Ошибка при изменении пароля');
                    });
                }
                return response.json(); // Парсим JSON-ответ
            })
            .then(data => {
                if (data.success) {
                    alert('Пароль успешно изменен');
                    document.getElementById('passwordModal').classList.add('hidden'); // Закрываем модальное окно
                } else {
                    alert(data.message || 'Ошибка при изменении пароля');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Произошла ошибка. Попробуйте снова.');
            });
    });


    // Добавление функциональности для кнопки "Добавить филиал"
  document.getElementById('addBranchButton').addEventListener('click', function() {
    const container = document.getElementById('branchAddressContainer');
    const newBranchField = document.createElement('div');
    newBranchField.classList.add('mb-4');  // добавление класса для отступа

    newBranchField.innerHTML = `
        <label class="block text-sm font-medium mb-1">Адрес филиала</label>
        <input type="text" name="branch_address[]" placeholder="Введите адрес филиала" class="w-2/3 border border-gray-300 rounded p-2" autocomplete="off">
        <div class="suggest-view max-h-52 overflow-y-auto bg-white border rounded-md hidden"></div>
    `;
    container.appendChild(newBranchField);
});

function togglePasswordVisibility(inputId, element) {
  const passwordInput = document.getElementById(inputId);
  const passwordIcon = element.querySelector('img');

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    passwordIcon.src = "{{asset ('images/open_password.png')}}"; // Путь к иконке "открытый глаз"
    passwordIcon.alt = "Скрыть";
  } else {
    passwordInput.type = 'password';
    passwordIcon.src = "{{asset ('images/close_password.png')}}"; // Путь к иконке "закрытый глаз"
    passwordIcon.alt = "Показать";
  }
}
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=bef13086-2418-4e93-bac9-45e709948f50&lang=ru_RU&suggest_apikey=bef13086-2418-4e93-bac9-45e709948f50" type="text/javascript"></script>
    <script src="{{ asset('js/profile.js') }}" type="text/javascript"></script>
</body>
</html>
