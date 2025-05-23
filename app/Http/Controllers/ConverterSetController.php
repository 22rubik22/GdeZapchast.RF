<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use App\Models\ConverterSet;
use Illuminate\Support\Facades\Auth;
use App\Models\UserColumnMapping;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\View\View;


class ConverterSetController extends Controller
{
    
 public function createFromList(): View // Указываем тип возвращаемого значения View
    {
        // Проверяем, есть ли у пользователя записи в таблице user_column_mappings
        $user = Auth::user();
        $hasMappings = UserColumnMapping::where('user_id', $user->id)->exists();

        // Передаем данные в представление
        return view('adverts.createFromList', [
            'hasMappings' => $hasMappings,
            'user' => $user // Передаем переменную $user в представление
        ]);
    }
// Метод для получения настроек
 public function getSettings(Request $request)
{
    // Получаем ID текущего пользователя
    $userId = auth()->id();

    // Ищем настройки пользователя в базе данных
    $settings = ConverterSet::where('user_id', $userId)->first();

    if ($settings) {
        // Список всех возможных брендов
        $brands = [
            'acura', 'alfa_romeo', 'asia', 'aston_martin', 'audi', 'bentley', 'bmw', 'byd',
            'cadillac', 'changan', 'chevrolet', 'citroen', 'daewoo', 'daihatsu', 'datsun',
            'fiat', 'ford', 'gaz', 'geely', 'haval', 'honda', 'hyundai', 'infiniti', 'isuzu',
            'jaguar', 'jeep', 'kia', 'lada', 'land_rover', 'mazda', 'mercedes_benz', 'mitsubishi',
            'nissan', 'opel', 'peugeot', 'peugeot_lnonum', 'porsche', 'renault', 'skoda',
            'ssangyong', 'subaru', 'suzuki', 'toyota', 'uaz', 'volkswagen', 'volvo', 'zaz',
        ];

        // Массив для преобразования имен брендов
        $brandTranslations = [
            'alfa_romeo' => 'alfa romeo',
            'aston_martin' => 'aston martin',
            'gaz' => 'газ',
            'land_rover' => 'land rover',
            'uaz' => 'уаз',
            'zaz' => 'заз',
            'vaz' => 'ваз',
            'mercedes_benz' => 'mercedes',
            'lada' => 'ваз (lada)',
        ];

        // Извлекаем выбранные бренды
        $selectedBrands = [];
        foreach ($brands as $brand) {
            if ($settings->$brand == 1) { // Если значение равно 1, бренд выбран
                // Преобразуем имя бренда, если оно есть в массиве $brandTranslations
                $selectedBrands[] = $brandTranslations[$brand] ?? $brand;
            }
        }

        // Возвращаем выбранные бренды и флаг exists
        return response()->json(['exists' => true, 'settings' => $selectedBrands]);
    }

    // Если настройки не найдены
    return response()->json(['exists' => false, 'error' => 'Настройки не найдены'], 404);
}

public function edit()
{
    // Получаем настройки текущего пользователя
    $converterSet = ConverterSet::where('user_id', Auth::id())->first();

    // Получаем соответствия столбцов для текущего пользователя
    $columnMappings = UserColumnMapping::where('user_id', Auth::id())->first();

    // Если есть соответствия, переводим ключи на русский
    $translatedMappings = [];
    if ($columnMappings && $columnMappings->column_mappings) {
        foreach ($columnMappings->column_mappings as $key => $value) {
            $translatedKey = $this->columnTranslations[$key] ?? $key; // Переводим ключ
            $translatedMappings[$translatedKey] = $value;
        }
    }

    return view('converter_set.edit', [
        'converterSet' => $converterSet,
        'columnMappings' => $translatedMappings, // Передаем переведенные соответствия
    ]);
}

private $columnTranslations = [
    'art_number' => 'Артикул',
    'product_name' => 'Название товара',
    'new_used' => 'Состояние',
    'brand' => 'Марка',
    'model' => 'Модель',
    'body' => 'Кузов',
    'number' => 'Номер запчасти',
    'engine' => 'Номер двигателя',
    'year' => 'Год',
    'L_R' => 'Расположение Л_П',
    'F_R' => 'Расположение Сп_Сз',
    'U_D' => 'Расположение Св_Сн',
    'color' => 'Цвет',
    'applicability' => 'Применимость',
    'quantity' => 'Количество',
    'price' => 'Цена',
    'availability' => 'Доступность',
    'delivery_time' => 'Время доставки',
    'main_photo_url' => 'Главное фото',
    'additional_photo_url_1' => 'Фото1',
    'additional_photo_url_2' => 'Фото2',
    'additional_photo_url_3' => 'Фото3',
];

    public function update(Request $request)
    {
        // Валидация входящих данных
        $request->validate([
            'acura' => 'boolean',
            'alfa_romeo' => 'boolean',
            'asia' => 'boolean',
            'aston_martin' => 'boolean',
            'audi' => 'boolean',
            'bentley' => 'boolean',
            'bmw' => 'boolean',
            'byd' => 'boolean',
            'cadillac' => 'boolean',
            'changan' => 'boolean',
            'chevrolet' => 'boolean',
            'citroen' => 'boolean',
            'daewoo' => 'boolean',
            'daihatsu' => 'boolean',
            'datsun' => 'boolean',
            'fiat' => 'boolean',
            'ford' => 'boolean',
            'gaz' => 'boolean',
            'geely' => 'boolean',
            'haval' => 'boolean',
            'honda' => 'boolean',
            'hyundai' => 'boolean',
            'infiniti' => 'boolean',
            'isuzu' => 'boolean',
            'jaguar' => 'boolean',
            'jeep' => 'boolean',
            'kia' => 'boolean',
            'lada' => 'boolean',
            'land_rover' => 'boolean',
            'mazda' => 'boolean',
            'mercedes_benz' => 'boolean',
            'mitsubishi' => 'boolean',
            'nissan' => 'boolean',
            'opel' => 'boolean',
            'peugeot' => 'boolean',
            'peugeot_lnonum' => 'boolean',
            'porsche' => 'boolean',
            'renault' => 'boolean',
            'skoda' => 'boolean',
            'ssangyong' => 'boolean',
            'subaru' => 'boolean',
            'suzuki' => 'boolean',
            'toyota' => 'boolean',
            'uaz' => 'boolean',
            'volkswagen' => 'boolean',
            'volvo' => 'boolean',
            'zaz' => 'boolean',
    
             // Поля с текстовыми значениями
        'product_name' => 'nullable|string|max:255',
        'price' => 'nullable|string|max:255',
        'car_brand' => 'nullable|string|max:255', 
        'car_model' => 'nullable|string|max:255', 
        'year' => 'nullable|string|max:255', 
        'oem_number' => 'nullable|string|max:255', 
        'picture' => 'nullable|string|max:255', 
        'body' => 'nullable|string|max:255', 
        'engine' => 'nullable|string|max:255', 
        'quantity' => 'nullable|string|max:255', 
        'text_declaration' => 'nullable|string|max:255', 
        'left_right' => 'nullable|string|max:255', 
        'up_down' => 'nullable|string|max:255', 
        'front_back' => 'nullable|string|max:255', 
        'fileformat_col' => 'nullable|string|max:255', 
        'encoding' => 'nullable|string|max:255', 
        'file_price' => 'nullable|string|max:255', 
        'my_file' => 'nullable|string|max:255', 
        'header_str_col' => 'nullable|string|max:255', 
        'separator_col' => 'nullable|string|max:255', 
        'del_duplicate' => 'nullable|string|max:255', 
        'art_number' => 'nullable|string|max:255', 
        'availability' => 'nullable|string|max:255', 
        'color' => 'nullable|string|max:255', 
        'delivery_time' => 'nullable|string|max:255', 
        'new_used' => 'nullable|string|max:255', 
        'many_pages_col' => 'nullable|string|max:255',

        ]);

        // Обновляем или создаем настройки
        ConverterSet::updateOrCreate(
            ['user_id' => Auth::id()],
            $request->only([
                'acura',
                'alfa_romeo',
                'asia',
                'aston_martin',
                'audi',
                'bentley',
                'bmw',
                'byd',
                'cadillac',
                'changan',
                'chevrolet',
                'citroen',
                'daewoo',
                'daihatsu',
                'datsun',
                'fiat',
                'ford',
                'gaz',
                'geely',
                'haval',
                'honda',
                'hyundai',
                'infiniti',
                'isuzu',
                'jaguar',
                'jeep',
                'kia',
                'lada',
                'land_rover',
                'mazda',
                'mercedes_benz',
                'mitsubishi',
                'nissan',
                'opel',
                'peugeot',
                'peugeot_lnonum',
                'porsche',
                'renault',
                'skoda',
                'ssangyong',
                'subaru', 
                'suzuki', 
                'toyota', 
                'uaz', 
                'volkswagen', 
                'volvo', 
                'zaz', 
                'product_name', 
                'price', 
                'car_brand', 
                'car_model', 
                'year', 
                'oem_number', 
                'picture', 
                'body', 
                'engine', 
                'quantity', 
                'text_declaration', 
                'left_right', 
                'up_down', 
                'front_back', 
                'fileformat_col', 
                'encoding', 
                'file_price', 
                'my_file', 
                'header_str_col', 
                'separator_col', 
                'del_duplicate', 
                'art_number', 
                'availability', 
                'color', 
                'delivery_time', 
                'new_used', 
                'many_pages_col'
        
            ])
        );

        return redirect()->back()->with('success', 'Настройки обновлены успешно!');
    }
    
public function convertPriceList(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|mimes:csv,xlsx',
                'header_row' => 'required|integer|min:1'
            ]);

            $file = $request->file('file');
            $filePath = $file->getPathname();
            $headerRow = $request->input('header_row', 1);
            $fileType = $file->getClientOriginalExtension();


            if ($fileType === 'csv') {
              $columnNames = $this->processCsvFile($filePath, $headerRow);
            } else {
               $columnNames = $this->processExcelFile($filePath, $headerRow);
            }


             if(empty($columnNames)){
                return response()->json(['error' => 'Файл не содержит данных или выбранная строка заголовков пустая.'], 400);
             }
             return response()->json($columnNames);

        } catch (\Exception $e) {
            Log::error('Error in convertPriceList: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while processing the file.'], 500);
        }
    }

    protected function processExcelFile($filePath, $headerRow)
    {
        $spreadsheet = IOFactory::load($filePath);
        $worksheet = $spreadsheet->getActiveSheet();
        $highestRow = $worksheet->getHighestRow();

        if ($headerRow > $highestRow) {
            return []; // Return empty array if header row is out of bounds
        }

        $headerRowIterator = $worksheet->getRowIterator($headerRow, $headerRow);
        $columnNames = [];

        foreach ($headerRowIterator as $row) {
            $cellIterator = $row->getCellIterator();
            foreach ($cellIterator as $cell) {
                $columnNames[] = $cell->getValue();
            }
        }

        return array_filter($columnNames, function ($value) {
            return !is_null($value) && $value !== '';
        });
    }


     protected function processCsvFile($filePath, $headerRow)
    {
       $columnNames = [];
        $file = fopen($filePath, 'r');
            if ($file) {
             $row_number = 1;
                while (($row = fgetcsv($file)) !== false) {
                 if($row_number == $headerRow){
                     $columnNames = $row;
                     break;
                    }
                    $row_number++;
                }
            fclose($file);
        }
        return array_filter($columnNames, function ($value) {
           return !is_null($value) && $value !== '';
        });
    }


   public function reset(Request $request)
{
    // Получаем текущего пользователя
    $user = $request->user();

    // Находим запись в таблице converter_set для текущего пользователя
    $converterSet = ConverterSet::where('user_id', $user->id)->first();

    if ($converterSet) {
        // Удаляем запись
        $converterSet->delete();
    }

    return redirect()->back()->with('success', 'Настройки конвертера успешно сброшены.');
}


public function saveColumnMappings(Request $request)
{
    // Валидация данных
    $request->validate([
        'file_name' => 'required|string|max:255',
        'column_mappings' => 'required|array',
    ]);

    // Получаем текущего пользователя
    $user = Auth::user();

    // Проверяем, есть ли уже сохраненные соответствия для этого пользователя
    $existingMappings = UserColumnMapping::where('user_id', $user->id)->exists();

    if ($existingMappings) {
        // Если соответствия уже есть, возвращаем сообщение
        return response()->json([
            'message' => 'Соответствия уже сохранены. Новые данные не будут добавлены.',
            'data' => null,
        ], 200); // Используем код 200, так как это не ошибка
    }

    // Если соответствий нет, сохраняем новые
    $mapping = UserColumnMapping::create([
        'user_id' => $user->id,
        'file_name' => $request->file_name,
        'column_mappings' => $request->column_mappings,
    ]);

    return response()->json([
        'message' => 'Соответствия успешно сохранены.',
        'data' => $mapping,
    ], 201);
}

  public function checkColumnMappings()
        {
            // Получаем текущего пользователя
            $user = Auth::user();
            if ($user) {
                // Проверяем наличие записей в таблице
                $exists = UserColumnMapping::where('user_id', $user->id)->exists();
                return response()->json(['exists' => $exists]);
            } else {
                return response()->json(['exists' => false]);
            }
        }
        
         public function getColumnMappings()
        {
          // Получаем текущего пользователя
            $user = Auth::user();

            if ($user) {
               $mappings = UserColumnMapping::where('user_id', $user->id)->first();

                if ($mappings) {
                    return response()->json(['mappings' => $mappings->column_mappings]);
                } else {
                 return response()->json(['mappings' => null]);
                }
           }
        }

public function deleteColumnMappings(Request $request)
{
    // Получаем текущего пользователя
    $user = Auth::user();

    if ($user) {
        // Удаляем все записи из таблицы user_column_mappings для текущего пользователя
        UserColumnMapping::where('user_id', $user->id)->delete();

        return redirect()->back()->with('success', 'Соответсвия столбцов успешно сброшены.');
    }

    // Если пользователь не авторизован, возвращаем ошибку
    return response()->json([
        'message' => 'Пользователь не авторизован.',
    ], 401);
}

}