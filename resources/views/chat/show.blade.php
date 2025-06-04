<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Yandex.Metrika counter -->
<script type="text/javascript" >
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
    <title>Чат</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet"/>
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <style>
        .message-time-left {
            order: -1;
        }
        .message-time-right {
            order: 1;
        }
        /* Фиксированный блок с формой */
       
        /* Добавляем отступ в контейнер с сообщениями */
        #chat-messages {
            padding-bottom: 180px; /* Отступ, равный высоте формы + навигационной панели */
            overflow-y: auto; /* Добавляем скролл */
        }
        
         #mobilenav{
            display: none !important
        }
    </style>

    <style>
        body {
            font-family: 'Nunito', sans-serif;
        }
    </style>
</head>
<body class="h-screen overflow-hidden">
    <div class='hidden md:block'>
          @include('components.header-seller')
    </div>
  

    <div class="w-full h-full">
        <div class="flex w-full h-full flex-col md:flex-row pb-20">
            <!-- Боковая панель для списка чатов на больших экранах -->
    <div class="chat-list-container w-2/6 lg:block hidden h-full max-h-screen overflow-y-auto bg-gray-100">
    @include('components.chat-list', ['userChats' => $userChats])
</div>



            <div class="flex flex-col w-full h-full border-l border-gray-300">
                @if($chat && $advert)
                    <!-- Ссылка на страницу с чатами (только для мобильных устройств) -->
                    

                    <!-- Шапка с информацией о товаре -->
                    <div class="flex items-center border-b  w-full p-2 bg-white">
                        <a href="{{ route('chats.index') }}" class="md:hidden flex items-center justify-start px-4 bg-white text-blue-500 hover:underline text-sm">
                        <i class="fas fa-arrow-left mr-2 text-lg"></i> 
                    </a>
                        <div class="flex items-center space-x-4">
                            <img alt="Product image" class="w-12 h-12 rounded-full" src="{{ $advert->main_photo_url ?: asset('images/dontfoto.jpg') }}" width="50" height="50"/>
                           <div>
            <h2 class="text-xl font-bold hover:text-[#3b82f6]">
                @if($advert->product_name === 'Техподдержка')
                    {{ $advert->product_name }}
                @else
                     <div class="cursor-pointer"
             onclick="location.href=generateAdvertUrl({
                 id: '{{ $advert->id }}',
                 product_name: '{{ $advert->product_name }}',
                 brand: '{{ $advert->brand }}',
                 model: '{{ $advert->model }}',
                 year: '{{ $advert->year }}',
                 engine: '{{$advert->engine}}',
                 number: '{{$advert->number}}'
             })">
                        {{ $advert->product_name }}
                    </div>
                @endif
            </h2>
            @if($advert->product_name === 'Техподдержка')
             <p class="text-lg text-gray-500">
                Гдезапчасть.рф
            </p>
            @else
            <p class="text-lg text-gray-500">
                {{ $advert->price }}₽
            </p>
             @endif
        </div>
                        </div>
                        @if($messages->isNotEmpty() && $messages->last())
                        @else
                            <span class="text-lg text-gray-500">
                                Нет сообщений
                            </span>
                        @endif
                    </div>

                    <!-- Список сообщений -->
                    <div id="chat-messages" class="flex-1 p-4 w-full space-y-4 overflow-y-auto overflow-x-hidden">
                        @foreach($messages as $message)
                            <div class="flex items-center space-x-2 @if($message->user_id === auth()->id()) justify-end @endif" data-message-id="{{ $message->id }}">
                            <div>
                                @if($message->user_id !== auth()->id())
                                    <img alt="User avatar" class="w-8 h-8 rounded-full" src="{{ $message->user->avatar_url ?: asset('images/noava.jpg') }}" width="50" height="50"/>
                                @endif 
                            </div>
                            
                            <div>
                                 @if($message->user_id === auth()->id())
                                    @if($message->is_read)
                                            <img src="{{ asset('images/messageyes.png') }}" alt="Прочитано" class="w-4 h-4 mt-1 message-status-icon">
                                        @else
                                            <img src="{{ asset('images/messageno.png') }}" alt="Не прочитано" class="w-4 h-4 mt-1 message-status-icon">
                                        @endif
                                    @endif  
                            </div>
                             @if($message->user_id === auth()->id())
                             <div>
                                <span class="text-sm text-gray-500 @if($message->user_id === auth()->id()) message-time-left @else message-time-right @endif">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                             @endif  
                            
                             <div class="bg-gray-100 max-w-64  p-2 rounded-lg @if($message->user_id === auth()->id()) bg-green-100 @endif">
                                <p>{{ $message->message }}</p>
                                 <!-- Добавляем иконку для статуса сообщения -->
                            </div>
                            
                             @if($message->user_id !== auth()->id())
                             <div>
                                <span class="text-sm text-gray-500 @if($message->user_id === auth()->id()) message-time-left @else message-time-right @endif">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                            </div>
                             @endif  
                                   
                                   <div>
                                @if($message->user_id === auth()->id())
                                <img alt="User avatar" style="object-fit: cover;"  class="w-8 h-8 rounded-full" src="{{ $message->user->avatar_url ?: asset('images/noava.jpg') }}" />
                                @endif
                            </div>

                                
                            </div>
                        @endforeach
                    </div>

                    <!-- Форма для отправки сообщения -->
                    <div class="message-form p-4 border-t w-full bg-white flex items-center space-x-4 fixed bottom-0 left-0 right-0 md:static">
        <form action="{{ route('chat.send', ['chat' => $chat]) }}" method="POST" class="flex w-full">
            @csrf
            <input type="text" name="message" class="mr-2 flex-1 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Сообщение" required>
            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg" type="submit">
                Отправить
            </button>
        </form>
    </div>
                @else
                    <p class="text-gray-600 p-4">Выберите чат из списка.</p>
                @endif
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/locale/ru.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <script>
    function generateProductNameSlug(productName) {
        // Функция для транслитерации кириллицы в латиницу (упрощенный вариант)
        function transliterate(text) {
            const transliterationMap = {
                'а': 'a', 'б': 'b', 'в': 'v', 'г': 'g', 'д': 'd', 'е': 'e', 'ё': 'e', 'ж': 'zh',
                'з': 'z', 'и': 'i', 'й': 'j', 'к': 'k', 'л': 'l', 'м': 'm', 'н': 'n', 'о': 'o',
                'п': 'p', 'р': 'r', 'с': 's', 'т': 't', 'у': 'u', 'ф': 'f', 'х': 'h', 'ц': 'c',
                'ч': 'ch', 'ш': 'sh', 'щ': 'sch', 'ъ': '', 'ы': 'y', 'ь': '', 'э': 'e', 'ю': 'yu',
                'я': 'ya', ' ': '-',
            };

            let slug = '';
            for (let i = 0; i < text.length; i++) {
                const char = text[i].toLowerCase();
                slug += transliterationMap[char] || char; // Заменяем кириллицу на латиницу, иначе оставляем как есть
            }

            return slug;
        }

        // Транслитерируем, приводим к нижнему регистру и заменяем пробелы на дефисы
        const transliteratedText = transliterate(productName);
        const slug = transliteratedText.toLowerCase().replace(/[^a-z0-9-]+/g, '-').replace(/^-+|-+$/g, ''); // Удаляем лишние дефисы в начале и конце

        return slug;
    }

    function generateAdvertUrl(advert) {
        const baseUrl = '{{ route("adverts.show", ["id" => ":id", "product_name_slug" => ":product_name_slug", "brand" => ":brand", "model" => ":model", "year" => ":year", "engine" => ":engine", "number" => ":number"]) }}';

        const url = baseUrl
            .replace(':id', advert.id)
            .replace(':product_name_slug', generateProductNameSlug(advert.product_name))
            .replace(':brand', advert.brand || '')
            .replace(':model', advert.model || '')
            .replace(':year', advert.year || '')
            .replace(':engine', advert.engine || '')
            .replace(':number', advert.number || '');

        return url;
    }
</script>  

   <script>

$(document).ready(function() {
    // Устанавливаем русскую локализацию для moment.js
    moment.locale('ru');

    // Переменные состояния
    let initialLoad = true;
    let isUserScrolling = false;
    let scrollDebounceTimer = null;
    let isChatActive = !document.hidden;
    let lastActivityTime = new Date();
    let currentChatId = '{{ $chat->id }}';

    // ====================
    // ОБРАБОТЧИКИ СОБЫТИЙ
    // ====================

    // Отслеживаем изменение видимости вкладки
    document.addEventListener('visibilitychange', handleVisibilityChange);

    // Отслеживаем активность пользователя
    $(document).on('mousemove keydown click', trackUserActivity);
    $('#chat-messages').on('scroll', handleChatScroll);

    // Обработчик отправки сообщений
    $('form').on('submit', handleMessageSubmit);

    // ====================
    // ОСНОВНЫЕ ФУНКЦИИ
    // ====================

    // Обработчик изменения видимости вкладки
    function handleVisibilityChange() {
        isChatActive = !document.hidden;
        if (isChatActive) {
            trackUserActivity();
            checkAndMarkAsRead();
        }
    }

    // Отслеживание активности пользователя
    function trackUserActivity() {
        lastActivityTime = new Date();
    }

    // Обработчик скролла чата
    function handleChatScroll() {
        isUserScrolling = true;
        trackUserActivity();
        
        clearTimeout(scrollDebounceTimer);
        scrollDebounceTimer = setTimeout(() => {
            isUserScrolling = false;
            checkAndMarkAsRead();
        }, 200);
    }

    // Проверка условий и пометка как прочитанных
    function checkAndMarkAsRead() {
        const now = new Date();
        const secondsSinceLastActivity = (now - lastActivityTime) / 1000;
        
        if (isChatActive && secondsSinceLastActivity < 5 && isNearBottom()) {
            markMessagesAsRead();
        }
    }

    // Пометка сообщений как прочитанных
    function markMessagesAsRead() {
        $.ajax({
            url: '/chat/' + currentChatId + '/mark-as-read',
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                last_read_at: new Date().toISOString()
            },
            success: function(response) {
                console.log('Messages marked as read');
                updateReadStatusIcons();
            },
            error: function(xhr) {
                console.error('Error marking messages as read:', xhr.responseText);
            }
        });
    }

    // Обновление иконок статуса сообщений
    function updateReadStatusIcons() {
        $('.message-status-icon').each(function() {
            if ($(this).attr('src').includes('messageno.png')) {
                $(this).attr({
                    'src': '{{ asset("images/messageyes.png") }}',
                    'alt': 'Прочитано'
                });
            }
        });
    }

    // Прокрутка чата вниз
    function scrollToBottom(force = false) {
        if (!force && (isUserScrolling || !isNearBottom())) return;
        
        const chatMessages = document.getElementById('chat-messages');
        if (chatMessages) {
            requestAnimationFrame(() => {
                chatMessages.scrollTop = chatMessages.scrollHeight;
                if (force) {
                    checkAndMarkAsRead();
                }
            });
        }
    }

    // Проверка, находится ли пользователь внизу чата
    function isNearBottom() {
        const chatMessages = document.getElementById('chat-messages');
        if (!chatMessages) return true;
        
        return chatMessages.scrollTop + chatMessages.clientHeight + 100 >= chatMessages.scrollHeight;
    }

    // Загрузка новых сообщений
    function fetchMessages() {
        var shouldScroll = isNearBottom();

        $.ajax({
            url: '/chat/' + currentChatId + '/messages',
            type: 'GET',
            success: function(response) {
                const chatMessages = document.getElementById('chat-messages');
                const oldScrollHeight = chatMessages.scrollHeight;
                const oldScrollTop = chatMessages.scrollTop;
                
                renderMessages(response.messages);

                requestAnimationFrame(() => {
                    if (isUserScrolling || !shouldScroll) {
                        const newScrollHeight = chatMessages.scrollHeight;
                        chatMessages.scrollTop = oldScrollTop + (newScrollHeight - oldScrollHeight);
                    } else {
                        scrollToBottom(true);
                    }
                });

                if (shouldScroll) {
                    checkAndMarkAsRead();
                }
                
                if (initialLoad) {
                    initialLoad = false;
                }
            },
            error: function(xhr) {
                console.error('Error fetching messages:', xhr.responseText);
            }
        });
    }

    // Отрисовка сообщений
    function renderMessages(messages) {
        var chatMessagesElement = $('#chat-messages');
        chatMessagesElement.empty();
        var lastDate = null;

        messages.forEach(function(message) {
            var formattedTime = moment(message.created_at).format('H:mm');
            var messageDate = moment(message.created_at).format('YYYY-MM-DD');

            // Добавляем дату если изменилась
            if (lastDate !== messageDate) {
                chatMessagesElement.append(
                    `<div class="message-date text-xl font-bold text-center text-gray-500 my-2">
                        ${moment(message.created_at).format('DD.MM.YYYY')}
                    </div>`
                );
                lastDate = messageDate;
            }

            // Определяем параметры сообщения
            var avatarUrl = message.user.avatar_url || "{{ asset('images/noava.jpg') }}";
            var isCurrentUser = message.user_id === {{ auth()->id() }};
            var messageClass = isCurrentUser ? 'justify-end' : '';
            var bgClass = isCurrentUser ? 'bg-green-100' : 'bg-gray-100';

            // Создаем элемент сообщения
            var messageElement = $(
                `<div class="flex items-center space-x-2 ${messageClass}" data-message-id="${message.id}">`
            );

            // Аватар для получателя
            if (!isCurrentUser) {
                messageElement.append(
                    `<div>
                        <img alt="User avatar" style="object-fit: cover;" class="w-8 h-8 rounded-full" src="${avatarUrl}"/>
                    </div>`
                );
            }

            // Иконка статуса для отправителя
            if (isCurrentUser) {
                var isReadIcon = message.is_read ?
                    '<img src="{{ asset('images/messageyes.png') }}" alt="Прочитано" class="w-4 h-4 mt-1 message-status-icon">' :
                    '<img src="{{ asset('images/messageno.png') }}" alt="Не прочитано" class="w-4 h-4 mt-1 message-status-icon">';

                messageElement.append(`<div>${isReadIcon}</div>`);
            }

            // Время для отправителя
            if (isCurrentUser) {
                messageElement.append(
                    `<div>
                        <span class="text-sm text-gray-500 message-time-left">${formattedTime}</span>
                    </div>`
                );
            }

            // Текст сообщения
            messageElement.append(
                `<div class="${bgClass} p-2 max-w-64 rounded-lg">
                    <p>${message.message}</p>
                </div>`
            );

            // Время для получателя
            if (!isCurrentUser) {
                messageElement.append(
                    `<div>
                        <span class="text-sm text-gray-500 message-time-right">${formattedTime}</span>
                    </div>`
                );
            }

            // Аватар для отправителя
            if (isCurrentUser) {
                messageElement.append(
                    `<div>
                        <img alt="User avatar" style="object-fit: cover;" class="w-8 h-8 rounded-full" src="${avatarUrl}"/>
                    </div>`
                );
            }

            chatMessagesElement.append(messageElement);
        });
    }

    // Обработчик отправки сообщения
    function handleMessageSubmit(e) {
        e.preventDefault();

        var messageInput = $(this).find('input[name="message"]');
        var message = messageInput.val().trim();
        
        if (!message) return;

        $.ajax({
            url: '/chat/' + currentChatId + '/send',
            type: 'POST',
            data: {
                message: message,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                var formattedTime = moment(response.created_at).format('H:i');
                var newMessage = `
                    <div class="bg-green-100 p-2 max-w-64 rounded-lg">
                        <p>${response.message}</p>
                        ${response.is_read ? 
                            '<img src="{{ asset('images/messageyes.png') }}" alt="Прочитано" class="w-4 h-4 mt-1 message-status-icon">' : 
                            '<img src="{{ asset('images/messageno.png') }}" alt="Не прочитано" class="w-4 h-4 mt-1 message-status-icon">'}
                    </div>
                    <div class="flex items-end space-x-4 justify-end" data-message-id="${response.id}">
                        <span class="text-sm text-gray-500 message-time-left">${formattedTime}</span>
                        <img alt="User avatar" style="object-fit: cover;" class="w-8 h-8 rounded-full" src="{{ auth()->user()->avatar_url ?: asset('images/noava.jpg') }}"/>
                    </div>`;
                
                $('#chat-messages').append(newMessage);
                messageInput.val('');
                scrollToBottom(true);
            },
            error: function(xhr) {
                console.error('Error sending message:', xhr.responseText);
            }
        });
    }

    // ====================
    // ИНИЦИАЛИЗАЦИЯ
    // ====================

    // Первоначальная загрузка сообщений
    fetchMessages();
    scrollToBottom(true);

    // Установка интервалов
    setInterval(fetchMessages, 3000);
    setInterval(checkAndMarkAsRead, 1000);
});
</script>
</body>
</html>