<div class="w-full mt-20 md:mt-0 md:w-[90%] mt-10 mx-auto sm:block">
    <div class="flex flex-col-reverse md:flex-row justify-between w-full max-w-6xl mx-auto gap-y-10 ">
        <div class="inline-flex flex-col md:flex-row items-start md:items-center gap-4 w-fit max-md:px-[20px]">
        @php
                $btns = [
                    'about' => ['name' => 'О компании', 'popup' => 'modalSellerAbout'],
                    'contacts' => ['name' => 'Контакты', 'popup' => ''],
                    'delivery' => ['name' => 'Доставка и оплата', 'popup' => 'modalSellerDelivery'],
                ];
            @endphp
            @foreach($btns as $btnId => $btn)
                <button
                    class="rounded p-2 px-10 text-white max-md:pr-20 text-left bg-[#5e94f7] {{ $btn['popup'] ? 'js_seller_popup_button' : '' }}"
                    id="{{ $btnId }}"
                    data-popup="{{ $btn['popup'] }}"
                >
                    {{ $btn['name'] }}
                </button>
            @endforeach
        </div>

        <div class="flex gap-x-20 gap-y-10 max-sm:flex-col px-[20px] max-md:flex-row-reverse">
            <div class="shrink md:w-auto flex justify-center md:justify-start md:hidden">
                <div class="w-44 h-44 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Аватар пользователя" class="w-full h-full object-contain ">
                    @else
                        <i class="fas fa-user text-8xl text-gray-400"></i>
                    @endif
                </div>
            </div>

            <div class="w-2/3 md:w-auto flex-grow">
                <h1 class="text-3xl font-bold mb-4 md:hidden">{{ $user->username }}</h1>
                <p class="text-gray-600 mb-2 md:hidden">Город: <span class="text-black">{{ $user->city }}</span></p>
                <p class="text-gray-600 mb-2">Адрес: <span class="text-black">{{ $user->userAddress->address_line }}</span></p>
                <p class="text-gray-600 mb-2">Телефон: <span class="text-black"><a href="tel:{{ $user->userPhoneNumber->number_1 }}">{{ $user->userPhoneNumber->number_1 }}</a></span></p>
                <p class="text-gray-600 mb-2 md:hidden">Email: <span class="text-black"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span></p>
            </div>
        </div>

        <div id="modalSellerAbout"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden js_popup_container">
            <div class="bg-white rounded-lg p-6 w-4/5 max-w-none relative overflow-y-auto max-h-[90vh]">
                <button id="closeSellerAbout" class="absolute top-3 right-3 text-2xl js_close_popup">&times;</button>
                <h2 class="text-2xl font-bold mb-6 text-center">О Магазине</h2>
                <div>
                {!! nl2br(e($user->sellerInfo->about_shop)) !!}
                </div>
            </div>
        </div>

        <div id="modalSellerDelivery"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden js_popup_container">
            <div class="bg-white rounded-lg p-6 w-4/5 max-w-none relative overflow-y-auto max-h-[90vh]">
                <button id="closeSellerAbout" class="absolute top-3 right-3 text-2xl js_close_popup">&times;</button>
                <h2 class="text-2xl font-bold text-center">Доставка и оплата</h2>
                <div>
                    <h3 class="text-xl font-bold my-6 text-left">Условия гарантии и возврата</h3>
                    {!! nl2br(e($user->sellerInfo->warranty_return_policy)) !!}

                    <h3 class="text-xl font-bold my-6 text-left">Доставка по городу ({{ $user->city }})</h3>
                    @if ($user->sellerInfo->delivery_in_city)
                        <p>Стоимость: <b>{{ $user->sellerInfo->delivery_in_city_cost ? $user->sellerInfo->delivery_in_city_cost . ' руб.' : 'Бесплатно' }}</b></p>
                    @else
                        <p>Доставка по городу недоступна.</p>
                    @endif

                    <h3 class="text-xl font-bold my-6 text-left">Самовывоз</h3>
                    @php
                        $selectedBranches = [];
                        foreach ($user->branches->toArray() as $branch) {
                            if (in_array($branch['id_branch'], $user->sellerInfo->branch_ids ?? [])) {
                                $selectedBranches[] = $branch['address'];
                            }
                        }
                    @endphp
                    @if (!empty($selectedBranches))
                        <p>Самовывоз из: {{ implode(', ', $selectedBranches) }}</p>
                    @else
                        <p>Самовывоза нет.</p>
                    @endif

                    <h3 class="text-xl font-bold my-6 text-left">Доставка до транспортной компании</h3>
                    @if ($user->sellerInfo->delivery_to_transport_company)
                        @php
                            $enabledDS = collect($user->deliveryService->toArray())
                                ->except(['id', 'user_id', 'created_at', 'updated_at'])
                                ->filter(fn($value) => $value === 1)
                                ->keys()
                                ->toArray();
                        @endphp
                        <p>Стоимость доставки до ТК: <b>{{ $user->sellerInfo->delivery_to_transport_company_cost ? $user->sellerInfo->delivery_to_transport_company_cost.' руб.' : 'Бесплатно' }}</b></p>
                        <br>
                        <p>Доступные ТК: {{ implode(', ', $enabledDS) }}</p>
                    @else
                        Доставки до транспортной компании нет.
                    @endif

                    <h3 class="text-xl font-bold my-6 text-left">Доставка до маршрутного такси</h3>
                    @if ($user->sellerInfo->delivery_to_route_taxi)
                        <p>Стоимость: <b>{{ $user->sellerInfo->delivery_to_route_taxi_cost ? $user->sellerInfo->delivery_to_route_taxi_cost . ' руб.' : 'Бесплатно' }}</b></p>
                    @else
                        <p>Доставка до маршрутного такси недоступна.</p>
                    @endif

                    <h3 class="text-xl font-bold my-6 text-left">Доставка Почтой России</h3>
                    @if ($user->sellerInfo->delivery_russian_post)
                        <p>Стоимость дополнительных расходов: <b>{{ $user->sellerInfo->russian_post_additional_cost ? $user->sellerInfo->russian_post_additional_cost . ' руб.' : 'Бесплатно' }}</b></p>
                    @else
                        <p>Доставка Почтой России недоступна.</p>
                    @endif


                    <h3 class="text-xl font-bold my-6 text-left">Дополнительные условия доставки</h3>
                    {!! nl2br(e($user->sellerInfo->additional_delivery_conditions)) !!}
                </div>
            </div>
        </div>

        <script>

            document.addEventListener('DOMContentLoaded', () => {
                document.querySelectorAll('.js_seller_popup_button').forEach(el => {
                    el.addEventListener('click', () => {
                        if (!el.dataset.popup) return;
                        let popup = document.querySelector(`#${el.dataset.popup}`);
                        if (!popup) return;
                        popup.classList.remove('hidden');
                    })
                });
                document.querySelectorAll('.js_close_popup').forEach(el => {
                    el.addEventListener('click', () => {
                        const popupContainer = el.closest('.js_popup_container');
                        if (popupContainer) {
                            popupContainer.classList.add('hidden');
                        }
                    });
                });
            })
        </script>
    </div>
</div>

@if ($user->sellerInfo->banner_url && !isset($not_index))
    <style>
        div.banner {
            padding: 9px 6px 9px 6px;
            background-color: #E9510E;
            color: #FFFFFF;
            font-weight: bold;
            font-size: 112%;
            text-align: center;
            position: absolute;
            left: 40%;
            bottom: 30px;
            width: 20%;
        }
    </style>
    <div class="w-full md:w-[90%] mx-auto mt-10 hidden md:block">
        <img src="{{ $user->sellerInfo->banner_url }}" alt="" class="banner w-full rounded-2xl hidden md:block">
    </div>
@endif
