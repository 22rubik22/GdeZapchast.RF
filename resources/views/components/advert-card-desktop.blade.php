<div class="blockadvert bg-white rounded-lg shadow-md flex max-w-5xl w-full mt-8 cursor-pointer transition-colors duration-300 hover:bg-[#f0f8ff] relative"  
     onclick="location.href=generateAdvertUrl({
         id: '{{ $advert->id }}',
         product_name: '{{ str_replace("'", "", $advert->product_name) }}', 
         brand: '{{ $advert->brand }}',
         model: '{{ $advert->model }}',
         year: '{{ $advert->year }}',
         engine: '{{ $advert->engine }}',
         number: '{{ $advert->number }}'
     })" tabindex="0" role="button">
   <!-- Кружок с надписью -->
   <!--<div class="absolute -right-9 top-2/3 transform -translate-y-1/2 w-20 h-20 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-semibold shadow-lg">
       <span class="flex items-center justify-center text-center">Скидка до -20%</span>
   </div>-->

   <!-- Вывод главного фото -->
   <div class="w-1/4 flex-shrink-0">
       <div class="w-[220px] h-[175px] bg-gray-200 rounded-lg overflow-hidden">
           @if ($advert->main_photo_url)
               <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="w-full h-full object-cover">
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
               <p class="beg bg-gray-200 mt-4 px-3 py-1 w-24 text-sm rounded-lg text-center">{{ $advert->number }}</p>
           @endif
           </div>
           <div class="text-right pr-4 pt-4">
               <p class="text-xl font-semibold">{{ $advert->price }} ₽</p>
               <p class="text-red-500">{{ $advert->user->userAddress->city ?? 'Не указан' }}</p>
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
        <!-- Добавленное время в правом нижнем углу -->
            <div class="absolute bottom-2 right-4 text-gray-500 text-sm" style="display: block !important;">
                @if($advert->created_at)
                    @if($advert->created_at->isToday())
                        сегодня в {{ $advert->created_at->format('H:i') }}
                    @elseif($advert->created_at->isYesterday())
                        вчера в {{ $advert->created_at->format('H:i') }}
                    @else
                        {{ $advert->created_at->format('d.m.Y в H:i') }}
                    @endif
                @else
                    дата не указана
                @endif
            </div>
   </div>
</div>