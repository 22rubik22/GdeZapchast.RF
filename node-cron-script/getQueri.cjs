
const mysql = require('mysql2');

// Настройка подключения к базе данных
const connection = mysql.createPool({ // Используем пул подключений
    host: '172.18.0.3',
    user: 'root',
    password: 'wT8gn!RpC2p/z.M5',
    database: 'where_parts_db',
    waitForConnections: true,
    connectionLimit: 10,
    queueLimit: 0
});


// Функция для получения ID товаров с status_queri = null или '' и number = null или ''
async function getAdvertIdsForCompatibilityCheck() {
    const query = `SELECT id, number FROM adverts WHERE (status_queri IS NULL OR status_queri = '') AND (number IS NULL OR number = '')`;
    try {
        const [rows] = await connection.promise().query(query);
        return rows.map(row => row.id);
    } catch (error) {
        console.error('Ошибка при выполнении запроса getAdvertIdsForCompatibilityCheck:', error);
        throw error;
    }
}

// Функция для обновления status_queri и queri_number
async function updateStatusQueryAndQueriNumber(id, status, queriNumber) {
    const query = `UPDATE adverts SET status_queri = ?, queri_number = ? WHERE id = ?`;
    try {
        await connection.promise().query(query, [status, queriNumber, id]);
    } catch (error) {
        console.error(`Ошибка при обновлении status_queri и queri_number для id ${id}:`, error);
        throw error;
    }
}

// Функция для обновления status_queri
async function updateStatusQuery(id, status) {
    const query = `UPDATE adverts SET status_queri = ? WHERE id = ?`;
    try {
        await connection.promise().query(query, [status, id]);
    } catch (error) {
        console.error(`Ошибка при обновлении status_queri для id ${id}:`, error);
        throw error;
    }
}


async function setNumberStatus(id) {
    try {
        await updateStatusQuery(id, 'is_number');
    } catch (error) {
        console.error(`Не удалось обновить status_queri для id ${id}:`, error);
    }
}

async function setNotDataStatus(id) {
    try {
        await updateStatusQuery(id, 'not_data');
    } catch (error) {
        console.error(`Не удалось обновить status_queri для id ${id}:`, error);
    }
}

async function setNotQueriStatus(id) {
    try {
        await updateStatusQueryAndQueriNumber(id, 'not_queri', 'none');
    } catch (error) {
        console.error(`Не удалось обновить status_queri на not_queri и queri_number на none для id ${id}:`, error);
    }
}

// Функция для получения данных о товаре по ID
async function getAdvertData(id) {
    const query = `SELECT product_name, brand, model, year, engine FROM adverts WHERE id = ?`;
    try {
        const [rows] = await connection.promise().query(query, [id]);
        return rows[0] || null; // Возвращаем первый результат или null
    } catch (error) {
        console.error(`Ошибка при получении данных о товаре с id ${id}:`, error);
        throw error;
    }
}

// Функция для получения id_queri
async function getIdQueri(productName, brand, model, year, engineNumber) {
    try {
        const partNames = productName.split(/[\s()]+/g).filter(Boolean);
        const idQueriList = []; // Массив для хранения всех id_queri

        for (const partName of partNames) {
            const trimmedPartName = partName.trim();

            // Используем таблицу parts_list
            const partQuery = `SELECT part_id, need FROM parts_list WHERE part_name LIKE ? LIMIT 1`;
            const [partRows] = await connection.promise().query(partQuery, [`%${trimmedPartName}%`]);

            if (partRows.length === 0) continue;

            const { part_id: partId, need } = partRows[0]; // Получаем part_id и need

            // Используем таблицу base_avto
            let baseAvtoQuery = `SELECT id_modification, brand, model, year_from, year_before, engine FROM base_avto WHERE 1=1 `;

            const queryParams = [];

            if (brand) {
                baseAvtoQuery += `AND brand = ? `;
                queryParams.push(brand);
            }

            if (model) {
                baseAvtoQuery += `AND model = ? `;
                queryParams.push(model);
            }

            if (year !== null && year !== '') {
                baseAvtoQuery += `AND year_from <= ? AND year_before >= ? `;
                queryParams.push(year, year);
            }

            if (engineNumber) {
                baseAvtoQuery += `AND engine = ? `;
                queryParams.push(engineNumber);
            }

            // Применяем фильтрацию на основе 'need'
            if (need === 'engine' && !engineNumber) {
                continue; // Пропускаем итерацию, если нужен двигатель, но он не указан
            }

            if (need === 'year' && (year === null || year === '')) {
                continue; // Пропускаем итерацию, если нужен год, но он не указан
            }

             // Дополнительная проверка на необходимость указания года или двигателя
            // для более точного определения совместимости
            if (need === 'engine' && engineNumber) {
                baseAvtoQuery += `AND engine = ? `;
                queryParams.push(engineNumber);
            }

            if (need === 'year' && (year !== null && year !== '')) {
                baseAvtoQuery += `AND year_from <= ? AND year_before >= ? `;
                queryParams.push(year, year);
            }

            baseAvtoQuery += ' LIMIT 3';

            const [baseAvtosRows] = await connection.promise().query(baseAvtoQuery, queryParams);

            if (baseAvtosRows.length === 0) continue;

            const idModifications = baseAvtosRows.map(row => row.id_modification);

            for (const idModification of idModifications) {
                // Используем таблицу users_queries
                const userQuerySelect = `SELECT id_queri FROM users_queries WHERE id_car = ? AND id_part = ?`; // Убрали LIMIT 1
                const [userQueryRows] = await connection.promise().query(userQuerySelect, [idModification, partId]);

                if (userQueryRows.length > 0) {
                    // Добавляем все найденные id_queri в массив
                    userQueryRows.forEach(row => idQueriList.push(row.id_queri));
                }
            }
        }
        return idQueriList; // Возвращаем массив id_queri
    } catch (error) {
        console.error('Ошибка при получении id_queri:', error);
        throw error;
    }
}

// Главная функция
async function processAdverts() {
    const startTime = Date.now();
    try {
        const query_number_select = `SELECT id, number FROM adverts WHERE status_queri IS NULL OR status_queri = ''`;
        const [rows] = await connection.promise().query(query_number_select);
        const totalCount = rows.length;
        let skippedCount = 0;

        if (totalCount === 0) {
            console.log('Нет записей для обработки.');
            return; // Выходим из функции, а не из всего скрипта
        }

        const advertIds = await getAdvertIdsForCompatibilityCheck();
        console.log('ID товаров, требующих проверки совместимости:', advertIds);
        console.log('Количество товаров, требующих проверки совместимости:', advertIds.length);

        // Проходим по полученным записям из основного запроса
        for (const row of rows) {
            if (row.number !== null && row.number !== "") {
                await setNumberStatus(row.id);
                skippedCount++;
            }
        }

        const advertsToProcess = totalCount - skippedCount;

        console.log('Общее количество товаров, подлежащих проверке:', totalCount);
        console.log('Количество товаров, которые были пропущены из-за наличия number:', skippedCount);
        console.log('Количество товаров, оставшихся для проверки совместимости:', advertsToProcess);

        // Фильтрация товаров и получение данных
        const filteredAdvertIds = [];
        for (const id of advertIds) {
            const advertData = await getAdvertData(id);
            if (advertData) {
                const { product_name, brand, model, year, engine } = advertData;

                // Проверяем условия фильтрации
                const allDataPresent = product_name && brand && model && year && engine;
                const hasEngineAndSomeDataMissing = !allDataPresent && engine;
                const allDataExceptEngine = product_name && brand && model && year && !engine;

                if (allDataPresent || hasEngineAndSomeDataMissing || allDataExceptEngine) {
                    filteredAdvertIds.push(id);
                    console.log(`Товар с id ${id} прошел фильтрацию:`, advertData);
                } else {
                    await setNotDataStatus(id);
                    console.log(`Товар с id ${id} не прошел фильтрацию, установлен status_queri = not_data`);
                }
            } else {
                console.log(`Товар с id ${id} не найден.`);
            }
        }
        console.log('ID товаров, прошедших фильтрацию:', filteredAdvertIds);
        console.log('Количество товаров, прошедших фильтрацию:', filteredAdvertIds.length);

        const chunkSize = 10; // Например, 10 запросов одновременно

        for (let i = 0; i < filteredAdvertIds.length; i += chunkSize) {
            const chunk = filteredAdvertIds.slice(i, i + chunkSize);

            const idQueriPromises = chunk.map(async id => {
                const advertData = await getAdvertData(id);
                if (advertData) {
                    const { product_name, brand, model, year, engine } = advertData;
                    try {
                        const idQueriList = await getIdQueri(product_name, brand, model, year, engine);
                        const uniqueIdQueriList = [...new Set(idQueriList)];
                        const idQueriString = uniqueIdQueriList.join(', ');
                        return { id, idQueriString, error: null }; // Возвращаем объект с id и idQueriString
                    } catch (error) {
                        return { id, idQueriString: null, error: error }; // Возвращаем объект с id и ошибкой
                    }
                } else {
                    return { id, idQueriString: null, error: 'Товар не найден' }; // Возвращаем объект с id и ошибкой
                }
            });

            try {
                const results = await Promise.all(idQueriPromises);

                for (const result of results) {
                    const { id, idQueriString, error } = result;

                    if (error) {
                        await updateStatusQueryAndQueriNumber(id, 'not_queri', 'none');
                        console.log(`Для товара с id ${id} произошла ошибка: ${error}. status_queri установлен в not_queri, queri_number установлен в none.`);
                    } else if (idQueriString) {
                        await updateStatusQueryAndQueriNumber(id, 'done', idQueriString);
                        console.log(`Для товара с id ${id} найдены id_queri: ${idQueriString}. status_queri установлен в done, queri_number обновлен.`);
                    } else {
                        await updateStatusQueryAndQueriNumber(id, 'not_queri', 'none');
                        console.log(`Для товара с id ${id} id_queri не найден. status_queri установлен в not_queri, queri_number установлен в none.`);
                    }
                }

            } catch (error) {
                console.error('Произошла ошибка при выполнении Promise.all:', error);
            }
        }

    } catch (error) {
        console.error('Произошла ошибка:', error);
    } finally {
        const endTime = Date.now();
        const executionTime = (endTime - startTime) / 1000;
        console.log(`Время выполнения скрипта: ${executionTime} секунд`);

    }
}


// Функция для запуска обработки и повторного запуска через минуту
async function runForever() {
    while (true) {
        try {
            console.log('Запуск обработки записей...');
            await processAdverts();
            console.log('Обработка записей завершена. Ожидание 1 минуты...');
            await new Promise(resolve => setTimeout(resolve, 60000)); // Ждем 1 минуту
        } catch (error) {
            console.error('Произошла ошибка в цикле обработки:', error);
            console.log('Ожидание 1 минуты перед повторной попыткой...');
            await new Promise(resolve => setTimeout(resolve, 60000)); // Ждем 1 минуту даже при ошибке
        }
    }
}

// Запускаем бесконечный цикл обработки
runForever();

// Обработчики завершения процесса (для pm2)
process.on('SIGINT', () => {
    console.log('Получен сигнал SIGINT. Завершение работы...');
    connection.end(() => {
        console.log('Пул соединений с базой данных закрыт.');
        process.exit(0);
    });
});

process.on('SIGTERM', () => {
    console.log('Получен сигнал SIGTERM. Завершение работы...');
    connection.end(() => {
        console.log('Пул соединений с базой данных закрыт.');
        process.exit(0);
    });
});
