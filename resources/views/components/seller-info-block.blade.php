<div class="w-full mt-20 md:w-[90%] mt-10 mx-auto sm:block">
    <div class="flex flex-col md:flex-row justify-between w-full max-w-6xl mx-auto gap-y-10">
        <div class="flex gap-x-20 gap-y-10 max-sm:flex-col px-[20px] max-md:flex-row-reverse">
            <div class="shrink md:w-auto flex justify-center md:justify-start">
                <div class="w-44 h-44 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden">
                    @if($user->avatar_url)
                        <img src="{{ $user->avatar_url }}" alt="Аватар пользователя" class="w-full h-full object-contain ">
                    @else
                        <i class="fas fa-user text-8xl text-gray-400"></i>
                    @endif
                </div>
            </div>

            <div class="w-2/3 md:w-auto flex-grow">
                <h1 class="text-3xl font-bold mb-4">{{ $user->username }}</h1>
                <p class="text-gray-600 mb-2">Город: <span class="text-black">{{ $user->city }}</span></p>
                <p class="text-gray-600 mb-2">Адрес: <span class="text-black">{{ $user->userAddress->address_line }}</span></p>
                <p class="text-gray-600 mb-2">Телефон: <span class="text-black"><a href="tel:{{ $user->userPhoneNumber->number_1 }}">{{ $user->userPhoneNumber->number_1 }}</a></span></p>
                <p class="text-gray-600 mb-2">Email: <span class="text-black"><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></span></p>
            </div>
        </div>

        <div class="inline-flex flex-col items-stretch gap-4 w-fit max-md:px-[20px]">
            @php
                $btns = [
                    'about' => 'О компании',
                    'contacts' => 'Контакты',
                    'delivery' => 'Доставка и оплата',
                ];
            @endphp
            @foreach($btns as $btnId => $btn)
                <button
                    class="rounded p-2 px-10 text-white max-md:pr-20 text-left bg-[#5e94f7]"
                    id="{{ $btnId }}"
                >
                    {{ $btn }}
                </button>
            @endforeach
        </div>
        <div id="modalSellerAbout"
             class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 hidden">
            <div class="bg-white rounded-lg p-6 w-4/5 max-w-none relative overflow-y-auto max-h-[90vh]">
                <button id="closeSellerAbout" class="absolute top-3 right-3 text-2xl">&times;</button>
                <h2 class="text-2xl font-bold mb-6 text-center">О Магазине</h2>
                {{ $user->sellerInfo->about_shop  }}
            </div>
        </div>

        <script>
            // открыть/закрыть
            document.getElementById('about').onclick = () => {
                document.getElementById('modalSellerAbout').classList.remove('hidden');
            };
            document.getElementById('closeSellerAbout').onclick = () => {
                    document.getElementById('modalSellerAbout').classList.add('hidden');
            };
        </script>
    </div>
</div>
