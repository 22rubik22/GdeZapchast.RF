
const mysql = require('mysql2');

// Настройка подключения к базе данных
const connection = mysql.createConnection({
    host: '172.18.0.3',
    user: 'root',
    password: 'wT8gn!RpC2p/z.M5',
    database: 'where_parts_db',
});

// Функция для проверки объявлений
async function checkAdverts() {
    try {
        const query = `
            SELECT id, user_id
            FROM adverts
            WHERE (status_pay = 'not_pay' OR status_pay = 'limited') AND status_ad != 'arhiv'
        `;

        const [results] = await connection.promise().query(query);
        const currentTime = new Date().toISOString();

        const usersAdverts = {};
        for (const row of results) {
            if (!usersAdverts[row.user_id]) {
                usersAdverts[row.user_id] = [];
            }
            usersAdverts[row.user_id].push(row.id);
        }

        for (const userId in usersAdverts) {
            try {
                await checkUserTariff(userId, usersAdverts[userId], currentTime);
            } catch (error) {
                console.error(`Ошибка при обработке пользователя ${userId}:`, error);
            }
        }

        console.log("The Pay function is completed");
    } catch (error) {
        console.error('Ошибка при выполнении запроса:', error);
    }
}

// Функция для проверки активного тарифа пользователя
async function checkUserTariff(userId, advertIds, currentTime) {
    try {
        const tariffQuery = `
            SELECT status, price_day, adverts
            FROM tariffs
            WHERE id_user = ?
        `;

        const allAdvertsCountQuery = `
            SELECT COUNT(*) AS adverts_count
            FROM adverts
            WHERE user_id = ? AND status_ad != 'arhiv'
        `;

        const [tariffResults] = await connection.promise().query(tariffQuery, [userId]);
        const [allAdvertsCountResults] = await connection.promise().query(allAdvertsCountQuery, [userId]);

        if (tariffResults.length > 0) {
            const tariffStatus = tariffResults[0].status;
            const priceDay = tariffResults[0].price_day;
            const allowedAdverts = tariffResults[0].adverts;
            const allAdvertsCount = allAdvertsCountResults[0].adverts_count;

            if (tariffStatus === 'old') {
                // Проверяем, есть ли у пользователя объявления со status_pay = 'not_pay'
                const notPayAdvertsQuery = `
                    SELECT COUNT(*) AS count
                    FROM adverts
                    WHERE user_id = ? AND status_pay = 'not_pay' AND status_ad != 'arhiv'
                `;
                const [notPayAdvertsResults] = await connection.promise().query(notPayAdvertsQuery, [userId]);
                const notPayAdvertsCount = notPayAdvertsResults[0].count;

                if (notPayAdvertsCount > 0) {
                    await processPayment(userId, priceDay, currentTime, allowedAdverts, allAdvertsCount);
                }

                await unlimitAdverts(userId); // Всегда вызываем unlimitAdverts для old тарифа
            } else if (tariffStatus === 'new') {
                await updateAdvertStatus(userId, currentTime, allowedAdverts, allAdvertsCount);
                await unlimitAdverts(userId);
            } else {
                const updateAdvertQuery = `
                    UPDATE adverts
                    SET status_pay = 'not_pay', status_ad = 'not_activ'
                    WHERE user_id = ? AND status_ad != 'arhiv'
                `;
                await connection.promise().query(updateAdvertQuery, [userId]);
            }
        } else {
            const updateAdvertQuery = `
                UPDATE adverts
                SET status_pay = 'not_pay', status_ad = 'not_activ'
                WHERE user_id = ? AND status_ad != 'arhiv'
            `;
            await connection.promise().query(updateAdvertQuery, [userId]);
        }
    } catch (error) {
        console.error('Ошибка при проверке тарифа:', error);
    }
}

// Функция для обновления статуса объявления
async function updateAdvertStatus(userId, currentTime, allowedAdverts, allAdvertsCount) {
    try {
        const formattedTime = new Date(currentTime).toISOString().slice(0, 19).replace('T', ' ');

        const allAdvertsQuery = `
            SELECT id, status_pay
            FROM adverts
            WHERE user_id = ? AND status_ad != 'arhiv'
            ORDER BY id
        `;

        const [allAdvertsResults] = await connection.promise().query(allAdvertsQuery, [userId]);
        const allAdvertisements = allAdvertsResults;
        const allAdvertIds = allAdvertsResults.map(row => row.id);

        const resetPayStatusQuery = `
            UPDATE adverts
            SET status_pay = 'not_pay'
            WHERE user_id = ? AND status_ad != 'arhiv'
        `;
        await connection.promise().query(resetPayStatusQuery, [userId]);

        const advertIdsToActivate = allAdvertIds.slice(0, allowedAdverts);

        if (advertIdsToActivate.length > 0) {
            const updateAdvertQuery = `
                UPDATE adverts
                SET status_pay = 'pay', status_ad = 'activ', time_last_pay = ?
                WHERE id IN (?)
            `;
            await connection.promise().query(updateAdvertQuery, [formattedTime, advertIdsToActivate]);
        }

        const advertIdsToLimited = allAdvertIds.slice(allowedAdverts);

        if (advertIdsToLimited.length > 0) {
            const updateAdvertLimitedQuery = `
                UPDATE adverts
                SET status_pay = 'limited', status_ad = 'not_activ'
                WHERE id IN (?)
            `;
            await connection.promise().query(updateAdvertLimitedQuery, [advertIdsToLimited]);
        }
    } catch (error) {
        console.error('Ошибка при обновлении статуса объявления:', error);
    }
}

async function unlimitAdverts(userId) {
    try {
        const tariffQuery = `
            SELECT adverts
            FROM tariffs
            WHERE id_user = ?
        `;
        const [tariffResults] = await connection.promise().query(tariffQuery, [userId]);

        if (tariffResults.length === 0) {
            return;
        }

        const allowedAdverts = tariffResults[0].adverts;

        const activeAdvertsQuery = `
            SELECT COUNT(*) AS active_count
            FROM adverts
            WHERE user_id = ? AND status_ad = 'activ'
        `;
        const [activeAdvertsResults] = await connection.promise().query(activeAdvertsQuery, [userId]);
        const activeAdvertsCount = activeAdvertsResults[0].active_count;

        if (activeAdvertsCount < allowedAdverts) {
            const advertsToUnlimitCount = allowedAdverts - activeAdvertsCount;

            const limitedAdvertsQuery = `
                SELECT id
                FROM adverts
                WHERE user_id = ? AND status_pay = 'limited'
                ORDER BY id
                LIMIT ?
            `;
            const [limitedAdvertsResults] = await connection.promise().query(limitedAdvertsQuery, [userId, advertsToUnlimitCount]);

            const limitedAdvertIds = limitedAdvertsResults.map(row => row.id);

            if (limitedAdvertIds.length > 0) {
                const updateAdvertsQuery = `
                    UPDATE adverts
                    SET status_pay = 'not_pay', status_ad = 'not_activ'
                    WHERE id IN (?)
                `;
                await connection.promise().query(updateAdvertsQuery, [limitedAdvertIds]);
            }
        }
    } catch (error) {
        console.error('Ошибка в unlimitAdverts:', error);
    }
}

// Функция для обработки оплаты
async function processPayment(userId, priceDay, currentTime, allowedAdverts, allAdvertsCount) {
    try {
        const formattedTime = new Date(currentTime).toISOString().slice(0, 19).replace('T', ' ');

        const balanceQuery = `
            SELECT balance
            FROM users
            WHERE id = ?
        `;

        const [results] = await connection.promise().query(balanceQuery, [userId]);
        const balance = results[0].balance;
        const balanceNumber = Number(balance);
        const priceDayNumber = Number(priceDay);

        if (balanceNumber !== null && balanceNumber >= priceDayNumber) {
            const newBalance = balanceNumber - priceDayNumber;
            const updateBalanceQuery = `
                UPDATE users
                SET balance = ?
                WHERE id = ?
            `;

            await connection.promise().query(updateBalanceQuery, [newBalance, userId]);

            await updateAdvertStatus(userId, formattedTime, allowedAdverts, allAdvertsCount);

            const insertTransactionQuery = `
                INSERT INTO transaction_history (user_id, operation_type, amount, description, details, created_at, updated_at)
                VALUES (?, 'списание', ?, 'Ежедневная оплата за объявления', ?, ?, ?)
            `;

            const details = `Ежедневное списание за тариф: ${allowedAdverts} товаров`;

            await connection.promise().query(insertTransactionQuery, [
                userId,
                priceDayNumber,
                details,
                formattedTime,
                formattedTime,
            ]);
        } else {
            const updateAdvertQuery = `
                UPDATE adverts
                SET status_pay = 'not_pay', status_ad = 'not_activ'
                WHERE user_id = ? AND status_ad != 'arhiv'
            `;

            await connection.promise().query(updateAdvertQuery, [userId]);
        }
    } catch (error) {
        console.error('Ошибка при обработке оплаты:', error);
    }
}

// Подключаемся к базе данных
connection.connect((err) => {
    if (err) {
        console.error('Ошибка подключения к базе данных:', err);
    } else {
        console.log('Подключение к базе данных успешно установлено');

        // Выполняем проверку объявлений каждую минуту
        setInterval(checkAdverts, 60000);
    }
});
