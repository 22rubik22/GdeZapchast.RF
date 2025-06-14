document.addEventListener('DOMContentLoaded', function() {
    fetch('/cities')
        .then(response => response.json())
        .then(data => {
            const citySelect = document.getElementById('city');
            const cityMobileSelect = document.getElementById('city-mobile');

            // Очищаем оба селекта перед заполнением
            citySelect.innerHTML = '<option value="">Все города</option>';
            cityMobileSelect.innerHTML = '<option value="">Все города</option>';

            // Заполняем оба селекта городами
            data.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);

                const mobileOption = option.cloneNode(true);
                cityMobileSelect.appendChild(mobileOption);
            });

            // Получаем значение города из cookie
            const savedCity = getCookie('selectedCity');

            // Устанавливаем выбранное значение из cookie или URL
            const urlParams = new URLSearchParams(window.location.search);
            const selectedCity = urlParams.get('city') || savedCity;

            if (selectedCity) {
                citySelect.value = selectedCity;
                cityMobileSelect.value = selectedCity;
            }

            // Проверяем, если находимся на странице, где не нужно отображать город в URL
            const pagesWithoutCityParam = [
                'http://localhost:8000/chats',
                'http://localhost:8000/adverts/create',
                'http://localhost:8000/my-adverts'
            ];

            // Если текущая страница не в списке исключений, добавляем город в URL
            if (!pagesWithoutCityParam.includes(window.location.origin + window.location.pathname)) {
                updateUrlWithCity(selectedCity);
            }
        })
        .catch(error => console.error('Ошибка при загрузке городов:', error));
});

// Обновленная функция для работы с обоими селектами
function updateCitySelection(selectElement) {
    // Определяем, какой селект вызвал функцию
    const sourceSelect = selectElement || document.getElementById('city');
    const selectedCity = sourceSelect.value;

    // Синхронизируем значение в другом селекте
    if (sourceSelect.id === 'city') {
        document.getElementById('city-mobile').value = selectedCity;
    } else {
        document.getElementById('city').value = selectedCity;
    }

    // Сохраняем выбранный город в cookie
    setCookie('selectedCity', selectedCity, 7); // Сохраняем на 7 дней

    // Обновляем URL с выбранным городом
    updateUrlWithCity(selectedCity);

    // Обновляем страницу
    location.reload();
}

function updateUrlWithCity(city) {
    const baseUrl = window.location.origin + window.location.pathname;
    const currentUrlParams = new URLSearchParams(window.location.search);

    if (city) {
        currentUrlParams.set('city', city);
    } else {
        currentUrlParams.delete('city');
    }

    const pagesWithoutCityParam = [
        'http://localhost:8000/chats',
        'http://localhost:8000/adverts/create',
        'http://localhost:8000/my-adverts'
    ];

    if (!pagesWithoutCityParam.includes(baseUrl)) {
        const newUrl = baseUrl + '?' + currentUrlParams.toString();
        window.history.replaceState({}, '', newUrl);
    }
}

// Функции для работы с cookie остаются без изменений
function setCookie(name, value, days) {
    let expires = "";
    if (days) {
        const date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "") + expires + "; path=/";
}

function getCookie(name) {
    const nameEQ = name + "=";
    const ca = document.cookie.split(';');
    for (let i = 0; i < ca.length; i++) {
        let c = ca[i];
        while (c.charAt(0) === ' ') c = c.substring(1, c.length);
        if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
    }
    return null;
}
