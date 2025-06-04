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
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
</head>
<style>
    body {
    font-family: 'Nunito', sans-serif;

}
</style>

<body class="h-screen flex flex-col">
    @include('components.header-seller')

<div class="w-full h-full">
    <div class="  h-full flex flex-col md:flex-row">
        <!-- Боковая панель для списка чатов на больших экранах -->
        <div class="chat-list-container w-2/6  md:block hidden max-h-screen overflow-y-auto bg-gray-100">
            @include('components.chat-list', ['userChats' => $userChats])
        </div>

        <!-- Содержимое страницы -->
        <div class="md:flex flex-col hidden w-full h-full border-l p-4 border-gray-300">
            <h2 class="text-2xl font-bold mb-4">Выберите чат</h2>
            <p class="text-gray-600">Пожалуйста, выберите чат из списка слева, чтобы начать общение.</p>
        </div>
        
         <div class="chat-list-mobile w-full md:hidden">
        @include('components.chat-list-mobile', ['userChats' => $userChats])
    </div>
    </div>

    <!-- Мобильный список чатов -->
   
</div>
</body>
</html>