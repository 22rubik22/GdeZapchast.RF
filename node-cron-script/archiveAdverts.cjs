const mysql = require('mysql2');

// Настройка подключения к базе данных
const connection = mysql.createConnection({
    host: '172.18.0.3', // Хост базы данных
    user: 'root', // Имя пользователя
    password: 'wT8gn!RpC2p/z.M5', // Пароль
    database: 'where_parts_db', // Имя базы данных
});

// Функция для архивации старых объявлений
async function archiveOldAdverts() {
    try {
        // Вычисляем дату, которая была 30 дней назад
        const thirtyDaysAgo = new Date();
        thirtyDaysAgo.setDate(thirtyDaysAgo.getDate() - 30);
        const formattedDate = thirtyDaysAgo.toISOString().slice(0, 19).replace('T', ' ');

        // SQL-запрос для выборки объявлений старше 30 дней
        const selectQuery = `
            SELECT id
            FROM adverts
            WHERE created_at <= ? AND status_ad != 'arhiv'
        `;

        // Выполняем запрос
        const [results] = await connection.promise().query(selectQuery, [formattedDate]);

        if (results.length > 0) {
            // Обновляем статус объявлений на 'arhiv'
            const updateQuery = `
                UPDATE adverts
                SET status_ad = 'arhiv'
                WHERE id IN (?)
            `;

            const advertIds = results.map(row => row.id);
            await connection.promise().query(updateQuery, [advertIds]);

            console.log(`Архивировано ${advertIds.length} объявлений.`);
        } else {
            console.log('Нет объявлений для архивации.');
        }
    } catch (error) {
        console.error('Ошибка при архивации объявлений:', error.message);
    }
}

// Подключаемся к базе данных
connection.connect((err) => {
    if (err) {
        console.error('Ошибка подключения к базе данных:', err.message);
    } else {
        console.log('Подключение к базе данных успешно установлено');

        // Запускаем функцию архивации каждую минуту 
        setInterval(archiveOldAdverts, 60000);

    }
});