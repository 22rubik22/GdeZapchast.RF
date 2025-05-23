const mysql = require('mysql2');

// Функция для очистки бинлогов
async function purgeBinlogs() {
  // Настройка подключения к базе данных (для очистки бинлогов).
  const binlogConnection = mysql.createConnection({
    host: '172.18.0.3', // Хост базы данных
    user: 'root', // Имя пользователя
    password: 'wT8gn!RpC2p/z.M5', // Пароль
    database: 'where_parts_db', // Имя базы данных
  });

  try {
    // SQL-запрос для очистки бинлогов старше 1 часа
    const purgeQuery = `PURGE BINARY LOGS BEFORE NOW() - INTERVAL 1 HOUR;`; // Оберните запрос в кавычки

    // Выполняем запрос
    await binlogConnection.promise().query(purgeQuery);

    console.log('Бинлоги успешно очищены.');
  } catch (error) {
    console.error('Ошибка при очистке бинлогов:', error.message);
  } finally {
    binlogConnection.end(); // Закрываем соединение после использования
  }
}

// Запускаем функцию очистки бинлогов немедленно, а затем по расписанию
purgeBinlogs();
setInterval(purgeBinlogs, 3600000);
