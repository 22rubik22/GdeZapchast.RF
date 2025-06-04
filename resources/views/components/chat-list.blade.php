<style>
    a{
        transition: all 0.3s;
    }
    
  .bg-gray-300 {
    background-color: #f0f8ff; /* Серый цвет */
}
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<div class="w-full ">
    <h2 class="text-2xl font-bold w-full text-center mb-4">Чаты</h2>
    <div class="space-y-4 w-full">
        @foreach($userChats as $userChat)
<a href="{{ route('chat.show', ['chat' => $userChat]) }}" class="block hover:bg-gray-200 chat-link">                
<div class="grid grid-cols-[auto_1fr_20%] grid-rows-3 p-1 chat-item border-b border-gray-300" data-chat-id="{{ $userChat->id }}">
                    <!-- Первый столбец: Аватар пользователя -->
                    <div class="col-span-1 row-span-3 mr-2 flex items-center justify-center">
                        <img src="{{ ($userChat->user1_id == auth()->id() ? $userChat->user2->avatar_url : $userChat->user1->avatar_url) ?: asset('images/noava.jpg') }}" alt="Аватар" class="w-12 h-12 rounded-full object-cover">
                    </div>

                    <!-- Второй столбец: Имя пользователя -->
                    <div class="col-span-1 row-span-1 flex items-center">
                        <h3 class="font-semibold text-black">
                            {{ $userChat->user1_id == auth()->id() ? $userChat->user2->username : $userChat->user1->username }}
                        </h3>
                    </div>

                    <!-- Третий столбец: Время и галочки -->
                    <div class="col-span-1 row-span-1 flex items-center justify-end space-x-1">
                        @if($userChat->last_message)
                            <!-- Время последнего сообщения -->
                            <span class="text-sm text-gray-500">
                                {{ $userChat->last_message->created_at->format('H:i') }}
                            </span>
                            <!-- Галочки для прочитанных сообщений -->
                            @if($userChat->unread_count > 0)
                                <img src="{{ asset('images/messageno.png') }}" alt="Не прочитано" class="w-4 message-status-icon">
                            @else
                                <img src="{{ asset('images/messageyes.png') }}" alt="Прочитано" class="w-4 message-status-icon">
                            @endif
                        @endif
                    </div>

                    <!-- Второй и третий столбец: Название товара -->
                    <div class="col-span-2 row-span-1">
                        <p class="text-sm text-black whitespace-nowrap overflow-hidden text-ellipsis">
                            Название товара по которому диалог
                        </p>
                    </div>

                    <!-- Второй столбец: Текст последнего сообщения -->
                    <div class="col-span-1 row-span-1">
                        <p class="text-sm text-gray-400 whitespace-nowrap overflow-hidden text-ellipsis">
                            @if($userChat->last_message)
                                {{ Str::limit($userChat->last_message->message, 20, '...') }}
                            @else
                                Нет сообщений
                            @endif
                        </p>
                    </div>

                    <!-- Третий столбец: Счетчик непрочитанных сообщений -->
                    <div class="col-span-1 row-span-1 flex items-center mr-4 justify-end">
                        <span class="bg-[#ff0000] text-white rounded-full px-2 py-1 text-xs @if($userChat->unread_count == 0) hidden @endif">
                            {{ $userChat->unread_count }}
                        </span>
                    </div>
                </div>
            </a>
        @endforeach
    </div>
</div>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const chatItems = document.querySelectorAll('.chat-item');
    chatItems.forEach(chatItem => {
        const chatId = chatItem.getAttribute('data-chat-id');
        fetch(`/messages/${chatId}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! Status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                const unreadCountElement = chatItem.querySelector('.unread-count');
                const messageStatusIcon = chatItem.querySelector('.message-status-icon');

                if (data.length > 0) {
                    unreadCountElement.textContent = data.length;
                    unreadCountElement.classList.remove('hidden');
                    // Если есть непрочитанные сообщения, показываем messageno.png
                    if (messageStatusIcon) {
                        messageStatusIcon.src = "{{ asset('images/messageno.png') }}";
                        messageStatusIcon.alt = "Не прочитано";
                    }
                } else {
                    unreadCountElement.classList.add('hidden');
                    // Если все сообщения прочитаны, показываем messageyes.png
                    if (messageStatusIcon) {
                        messageStatusIcon.src = "{{ asset('images/messageyes.png') }}";
                        messageStatusIcon.alt = "Прочитано";
                    }
                }
            })
            .catch(error => {
                console.error(`Error fetching unread messages for chat ${chatId}:`, error);
            });
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    // Получаем все ссылки с классом chat-link
    const chatLinks = document.querySelectorAll('.chat-link');

    // Получаем текущий URL без query-параметров (если они есть)
    const currentUrl = window.location.origin + window.location.pathname;

    // Перебираем все ссылки
    chatLinks.forEach(link => {
        // Получаем URL ссылки без query-параметров
        const linkUrl = link.href.split('?')[0];

        // Если URL ссылки совпадает с текущим URL
        if (linkUrl === currentUrl) {
            // Добавляем класс для выделения
            link.classList.add('bg-gray-300');
        }
    });
});
</script>