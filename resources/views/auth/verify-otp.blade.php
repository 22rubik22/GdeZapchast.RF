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
    <title>Подтверждение регистрации</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-10 p-4 bg-white rounded shadow-md max-w-md">
        <h2 class="text-2xl font-bold text-center mb-4">Подтверждение регистрации</h2>
        <p class="text-center mb-4">На ваш email отправлен код подтверждения. Введите его ниже:</p>

        <form action="{{ route('verify.otp.submit') }}" method="POST" class="space-y-4">
            @csrf
            <input type="text" name="otp" placeholder="Введите код" required class="w-full p-3 border rounded-md">
            <button type="submit" class="w-full p-3 bg-blue-500 text-white rounded-md hover:bg-blue-600">Подтвердить</button>
        </form>

        @if ($errors->any())
            <div class="mt-4 text-red-500">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>