
@section('title', 'Ошибка сервера')

@section('content')
    <div style="display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 80vh; text-align: center;">
        <img src="{{ asset('images/logo.png') }}" alt="Гдезапчасть.рф Logo" style="max-width: 200px; margin-bottom: 20px;">  <!-- Замените 'images/logo.png' на путь к вашему логотипу -->
        <h1 style="font-size: 3em; color: #e74c3c; margin-bottom: 20px;">Ууупс...</h1>
        <p style="font-size: 1.2em; color: #34495e; line-height: 1.6;">
            Что-то пошло не так, пожалуйста, измените запрос или повторите попытку позже.
        </p>
        <a href="/" style="display: inline-block; padding: 10px 20px; background-color: #3498db; color: #fff; text-decoration: none; border-radius: 5px; margin-top: 20px;">
            Вернуться на Гдезапчасть.рф
        </a>
    </div>
@endsection
