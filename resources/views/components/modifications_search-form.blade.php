<div id="modifications-container" class="modification p-4 h-auto xl:h-1/2 bg-[#f3f3f3] flex flex-col rounded-2xl">
    <div class="flex-grow">
        <label class="font-medium">Модификации:</label>
        <div id="modifications-buttons" class="flex space-x-2 mb-4 hidden">
            <button id="select-all-mods" class="text-blue-500 hover:text-blue-700">Отметить все</button>
            <button id="deselect-all-mods" class="text-blue-500 hover:text-blue-700">Убрать все</button>
        </div>
        <div id="modifications" class="flex flex-col overflow-y-auto show_scroll" style="display: none;"></div>
        <div id="modifications-placeholder" class="text-gray-500 mt-2 hidden">
            Для отображения модификаций выберите параметры автомобиля
        </div>
    </div>

    <div class="mt-4">
        <button id="openModificationsModalBtn" class="w-100 l:w-[75%] xl:w-100 bg-[#E9E9E9] text-black px-4 py-2 rounded-lg hidden">
            Показать все модификации
        </button>
    </div>
</div>


<div id="modificationsModal" class="fixed inset-0 bg-black/50 hidden z-50">
    <div id="modificationsModalContent"
         class="bg-white max-w-[80%] mx-auto mt-20 p-4 rounded-lg relative overflow-y-auto max-h-[80vh]">
        <button id="closeModificationsModalBtn" class="absolute top-3 right-3">
            ✕
        </button>
        <div id="modificationsModalContentContainer"></div>
    </div>
</div>
