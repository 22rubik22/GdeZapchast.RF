<div class="bg-white rounded-lg"
     onclick="location.href=generateAdvertUrl({
         id: '{{ $advert->id }}',
         product_name: '{{ $advert->product_name }}',
         brand: '{{ $advert->brand }}',
         model: '{{ $advert->model }}',
         year: '{{ $advert->year }}',
         engine: '{{$advert->engine}}',
         number: '{{$advert->number}}'
     })">
    <div class="relative">
        @if ($advert->main_photo_url)
            <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="w-full h-48 object-cover rounded-lg">
        @else
            <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="w-full h-48 object-cover rounded-lg">
        @endif
        <span class="absolute top-2 right-2 bg-[#FFE6C1] text-black text-xs font-normal px-2 py-1 rounded">
             {{ $advert->user->userAddress->city ?? 'Не указан' }}
        </span>
    </div>
    <div class="px-2 py-1 flex flex-col" style="min-height: 100px;"> <!-- Фиксированная минимальная высота -->
        <div class="text-lg font-bold overflow-hidden whitespace-nowrap relative">
            {{ $advert->product_name }}
            <div class="absolute inset-y-0 right-0 w-20 bg-gradient-to-l from-white to-transparent"></div>
        </div>
        <div class="text-xl text-black font-semibold">
            {{ $advert->price }} ₽
        </div>
        <div class="flex flex-wrap items-center text-gray-500 text-sm mt-2" style='min-height: 20px;'>
            @if($advert->brand)
                <i class="fas fa-car mr-2"></i>
                <span>{{ $advert->brand }}</span>
                <span class="mx-1">|</span>
            @endif
            
            @if($advert->model)
                <span>{{ $advert->model }}</span>
                <span class="mx-1">|</span>
            @endif
            
            @if($advert->year)
                <span>{{ $advert->year }}</span>
            @endif
        </div>
        
        <div style='min-height: 20px;'>
            @if($advert->number)
            <p class="text-sm text-gray-600">
                <i class="fas fa-barcode"></i>
                <span>{{ $advert->number }}</span>
            </p>
        @endif     
        </div>
       
        
        <!-- Блок со временем, который всегда будет внизу -->
         <div class="text-gray-500 text-sm mt-auto" style="display: block !important;">
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