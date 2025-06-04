<!DOCTYPE html>
<html lang="ru">
<head>
    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
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
    <title>Редактирование профиля</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('images/Group 438.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200..1000;1,200..1000&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<style>
    body {
        font-family: 'Nunito', sans-serif;
    }
    
    .password-icon {
  transition: opacity 0.3s ease;
}

.password-icon:hover {
  opacity: 0.7;
}

.toggle-password {
  z-index: 10; /* Убедитесь, что иконка находится над полем ввода */
}
    .suggest-view {
        position: absolute;
        z-index: 1000;
        background-color: white;
        border: 1px solid #ccc;
        width: 100%;
        max-height: 200px;
        overflow-y: auto;
    }
    .suggestion-item {
        padding: 5px;
        cursor: pointer;
    }
    .suggestion-item:hover {
        background-color: #f0f0f0;
    }
</style>
<body>
@include('components.header-seller')

<div class="container mx-auto p-8">
    <div class="flex flex-col lg:flex-row">
        <div class="w-full lg:w-2/3">
            <h1 class="text-lg font-semibold mb-4">Профиль / Редактировать профиль</h1>
            <div class="mb-8">
                <h2 class="text-xl font-semibold mb-4">О компании</h2>
                <form action="{{ route('profile.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Название магазина</label>
                        <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-2/3 border border-gray-300 rounded p-2" placeholder="Под этим названием покупатели будут видеть ваши товары">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">E-Mail</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Телефон</label>
                        <input type="tel" name="phone" id="phoneInput" value="{{ old('phone', $user->phone) }}" class="w-2/3 border border-gray-300 rounded p-2" oninput="formatPhoneNumber(this)">
                        <div class="mt-2 text-sm cursor-pointer"><i class="fas fa-plus-circle"></i> Добавить дополнительный телефон</div>
                    </div>
                     <div class="mb-4">
                            <label class="block text-sm font-medium mb-1">Адрес</label>
                            <input type="text" class="w-2/3 border border-gray-300 rounded p-2">
                        </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">URL изображения профиля</label>
                        <input type="url" name="avatar_url" value="{{ old('avatar_url', $user->avatar_url) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">URL логотипа</label>
                        <input type="url" name="logo_url" value="{{ old('logo_url', $user->logo_url) }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <!-- Контейнер для ввода адресов филиалов -->
<div id="branchAddressContainer">
    <!-- Первое поле для ввода адреса филиала -->
    <div class="mb-4">
        <label class="block text-sm font-medium mb-1">Адрес филиала</label>
        <input type="text" name="branch_address[]" placeholder="Введите адрес филиала" class="w-2/3 border border-gray-300 rounded p-2" autocomplete="off">
        <div class="suggest-view max-h-52 overflow-y-auto bg-white border rounded-md hidden"></div>
    </div>
</div>
            <!-- Кнопка "Добавить филиал" -->
<div class="mb-4">
    <button type="button" id="addBranchButton" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600">Добавить филиал</button>
    <button type="button" id="showBranchesButton" class="bg-blue-500 text-white px-4 py-2 rounded-md ml-2 hover:bg-blue-600">Показать все филиалы</button>
</div>




<!-- Модальное окно для отображения филиалов -->
<div id="branchesModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Список филиалов</h3>
            <div id="branchesList" class="mt-2">
                <!-- Сюда будут добавляться филиалы -->
            </div>
            <div class="mt-4">
                <button type="button" id="closeBranchesModal" class="bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Закрыть</button>
            </div>
        </div>
    </div>
</div>
                    <h2 class="text-xl font-semibold mt-8 mb-4">Юридическая информация</h2>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Название организации</label>
                        <input type="text" name="organization_name" value="{{ old('organization_name', $user->legalInfo->organization_name ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2" placeholder="Полное название компании (с указанием формы собственности)">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">Юридический адрес</label>
                        <input type="text" name="legal_address" value="{{ old('legal_address', $user->legalInfo->legal_address ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">ИНН</label>
                        <input type="text" name="inn" value="{{ old('inn', $user->legalInfo->inn ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                        @error('inn')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium mb-1">КПП</label>
                        <input type="text" name="kpp" value="{{ old('kpp', $user->legalInfo->kpp ?? '') }}" class="w-2/3 border border-gray-300 rounded p-2">
                        @error('kpp')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <button type="submit" class="bg-blue-500 text-white rounded p-2 hover:bg-blue-600">Сохранить</button>
                </form>
            </div>
        </div>
        <div class="w-full lg:w-1/3 lg:pl-8 mt-8 lg:mt-0 flex flex-col items-end">
            <button id="changePasswordButton" class="w-1/2  bg-[#535151] text-white rounded p-2 mb-8 flex items-center justify-center">
                <i class="fas fa-lock mr-2"></i> Сменить пароль
            </button>
            <button class="w-1/2  bg-gray-100 text-gray-800 rounded p-4 mb-4 font-bold flex items-center justify-center">
                <i class="fas fa-truck mr-2"></i> Условия оплаты и доставки
            </button>
            <button class="w-1/2 bg-gray-100 text-gray-800 rounded p-4 font-bold mb-4 flex items-center justify-center">
                <i class="fas fa-pencil-alt mr-2"></i> Расскажите о Вашем магазине
            </button>
            <div class="text-sm text-gray-600">
                <p class="mb-2">Как выбрать название магазина</p>
                <p>Как добавить дополнительный телефон</p>
            </div>
        </div>
    </div>
</div>

<footer class="bg-white text-white py-20 shadow-none text-center mt-20 w-full hidden md:block">
    <div class="logo2 flex justify-between items-center w-full max-w-screen-2xl mx-auto">
        <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.index', null, request()->get('city')) }}" class="text-black text-xl p-2">
            <span class="text-2xl">ГдеЗапчасть.рф</span>
        </a>
        <div class="w-full text-black">
            <p>&copy; {{ date('Y') }} Все права защищены.</p>
        </div>
    </div>
    <div class="flex justify-center space-x-4 mt-4 w-full max-w-screen-2xl mx-auto">
        <a href="{{ route('about') }}" class="text-black hover:text-blue-500">О проекте</a>
        <a href="{{ route('oferta') }}" class="text-black hover:text-blue-500">Оферта</a>
        <a href="{{ route('franchise.index') }}" class="text-black hover:text-blue-500">Франшиза</a>
        <a href="{{ route('help.index') }}" class="text-black hover:text-blue-500">Справка</a>
    </div>
</footer>

<!-- Модальное окно для смены пароля -->
<div id="passwordModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Сменить пароль</h3>
            <form id="passwordForm" action="{{ route('change.password') }}" method="POST" class="mt-2">
                @csrf
                @method('PUT')
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Текущий пароль</label>
                    <div class="relative">
                        <input type="password" name="current_password" id="currentPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('currentPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Новый пароль</label>
                    <div class="relative">
                        <input type="password" name="new_password" id="newPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('newPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mb-4 relative">
                    <label class="block text-sm font-medium mb-1">Подтвердите пароль</label>
                    <div class="relative">
                        <input type="password" name="new_password_confirmation" id="confirmPassword" class="w-full border border-gray-300 p-2 rounded" required>
                        <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('confirmPassword', this)">
                            <img src="{{asset ('images/close_password.png')}}" alt="Показать" class="password-icon w-5 h-5">
                        </span>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="submit" id="submitPassword" class="bg-blue-500 text-white px-4 py-2 rounded-md">Сохранить</button>
                    <button type="button" id="closeModal" class="ml-2 bg-gray-300 text-gray-700 px-4 py-2 rounded-md">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
     // Добавление функциональности для кнопки "Показать все филиалы"
    document.getElementById('showBranchesButton').addEventListener('click', function() {
        const branchesModal = document.getElementById('branchesModal');
        const branchesList = document.getElementById('branchesList');
        branchesList.innerHTML = ''; // Очищаем список перед заполнением

        // Получаем список филиалов с сервера
        fetch(`/users/{{ $user->id }}/branches`) // Используем Blade для вставки ID пользователя
        .then(response => response.json())
            .then(branches => {
                if (branches.length > 0) {
                    branches.forEach(branch => {
                        const branchElement = document.createElement('div');
                        branchElement.classList.add('mb-2', 'p-2', 'border', 'rounded', 'flex', 'justify-between', 'items-center'); // Добавляем классы Tailwind CSS

                        const addressElement = document.createElement('span');
                        addressElement.textContent = branch.address;

                        const deleteButton = document.createElement('button');
                        deleteButton.textContent = 'Удалить';
                        deleteButton.classList.add('bg-red-500', 'text-white', 'px-2', 'py-1', 'rounded', 'text-sm'); // Добавляем классы Tailwind CSS
                        deleteButton.addEventListener('click', function() {
                            deleteBranch(branch.id_branch); // Используем ID филиала
                        });

                        branchElement.appendChild(addressElement);
                        branchElement.appendChild(deleteButton);
                        branchesList.appendChild(branchElement);
                    });
                } else {
                    branchesList.textContent = 'Филиалы не найдены.';
                }
                branchesModal.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Ошибка при получении филиалов:', error);
                branchesList.textContent = 'Ошибка при загрузке филиалов.';
            });
    });

    // Закрытие модального окна с филиалами
    document.getElementById('closeBranchesModal').addEventListener('click', function() {
        document.getElementById('branchesModal').classList.add('hidden');
    });

    function deleteBranch(branchId) {
    if (confirm('Вы уверены, что хотите удалить этот филиал?')) {
        fetch(`/branches/${branchId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) { // Проверяем статус ответа
                return response.json().then(err => {
                    throw new Error(err.message || 'Ошибка при удалении филиала.'); // Пробрасываем сообщение об ошибке
                });
            }
            return response.json();
        })
        .then(data => {
            if (data.message) {
                alert(data.message);
                // Обновляем список филиалов после удаления
                document.getElementById('showBranchesButton').click(); // Эмулируем клик на кнопке "Показать все филиалы"
            }
        })
        .catch(error => {
            console.error('Ошибка при удалении филиала:', error);
            alert(error.message || 'Ошибка при удалении филиала.'); // Используем сообщение из ошибки
        });
    }
}

</script>
<script>
    // Функция для форматирования номера телефона
    function formatPhoneNumber(input) {
        let phoneNumber = input.value.replace(/\D/g, ''); // Убираем все нецифровые символы
        phoneNumber = phoneNumber.substring(0, 11); // Ограничиваем длину до 11 цифр

        if (phoneNumber.length > 0) {
            phoneNumber = '8 ' + phoneNumber.substring(1).replace(/(\d{3})(\d{3})(\d{2})(\d{2})/, '$1 $2 $3 $4');
        }

        input.value = phoneNumber;
    }

    // Открытие модального окна для смены пароля
    document.getElementById('changePasswordButton').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.remove('hidden');
    });

    // Закрытие модального окна
    document.getElementById('closeModal').addEventListener('click', function() {
        document.getElementById('passwordModal').classList.add('hidden');
    });

    // Обработка отправки формы смены пароля
    document.getElementById('passwordForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Предотвращаем стандартное поведение формы

        const formData = new FormData(this); // Собираем данные формы

        // Отправляем данные на сервер
        fetch(this.action, {
            method: 'POST', // Отправляем методом POST
            body: formData,
            headers: {
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
            .then(response => {
                if (!response.ok) {
                    // Если статус ответа не 200-299, пытаемся распарсить ошибку
                    return response.json().then(err => {
                        throw new Error(err.message || 'Ошибка при изменении пароля');
                    });
                }
                return response.json(); // Парсим JSON-ответ
            })
            .then(data => {
                if (data.success) {
                    alert('Пароль успешно изменен');
                    document.getElementById('passwordModal').classList.add('hidden'); // Закрываем модальное окно
                } else {
                    alert(data.message || 'Ошибка при изменении пароля');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert(error.message || 'Произошла ошибка. Попробуйте снова.');
            });
    });


    // Добавление функциональности для кнопки "Добавить филиал"
  document.getElementById('addBranchButton').addEventListener('click', function() {
    const container = document.getElementById('branchAddressContainer');
    const newBranchField = document.createElement('div');
    newBranchField.classList.add('mb-4');  // добавление класса для отступа

    newBranchField.innerHTML = `
        <label class="block text-sm font-medium mb-1">Адрес филиала</label>
        <input type="text" name="branch_address[]" placeholder="Введите адрес филиала" class="w-2/3 border border-gray-300 rounded p-2" autocomplete="off">
        <div class="suggest-view max-h-52 overflow-y-auto bg-white border rounded-md hidden"></div>
    `;
    container.appendChild(newBranchField);
});

function togglePasswordVisibility(inputId, element) {
  const passwordInput = document.getElementById(inputId);
  const passwordIcon = element.querySelector('img');

  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    passwordIcon.src = "{{asset ('images/open_password.png')}}"; // Путь к иконке "открытый глаз"
    passwordIcon.alt = "Скрыть";
  } else {
    passwordInput.type = 'password';
    passwordIcon.src = "{{asset ('images/close_password.png')}}"; // Путь к иконке "закрытый глаз"
    passwordIcon.alt = "Показать";
  }
}
</script>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=bef13086-2418-4e93-bac9-45e709948f50&lang=ru_RU&suggest_apikey=bef13086-2418-4e93-bac9-45e709948f50" type="text/javascript"></script>
    <script src="{{ asset('js/profile.js') }}" type="text/javascript"></script>
</body>
</html>
