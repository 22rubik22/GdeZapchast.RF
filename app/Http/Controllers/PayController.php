<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\TransactionHistory;
use Illuminate\Support\Facades\DB; // Import DB facade
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class PayController extends Controller
{
    public function showPayForm()
    {
        return view('pay_form');
    }
    
function generateTinkoffToken(array $params, string $secretKey): string
{
    // 1. Фильтруем параметры, оставляем только параметры корневого объекта
    $filteredParams = [];
    foreach ($params as $key => $value) {
        if (!is_array($value) && !is_object($value)) {  // Проверяем, что это не массив и не объект
            $filteredParams[$key] = strval($value); // Приводим к строке
        }
    }

    // 2. Добавляем Password (SecretKey) к массиву параметров
    $filteredParams['Password'] = $secretKey;

    // 3. Сортируем массив по ключам в алфавитном порядке
    ksort($filteredParams);

    // 4. Конкатенируем значения параметров в одну строку
    $tokenString = implode('', $filteredParams);

    // 5. Вычисляем SHA256-хеш
    $token = hash('sha256', $tokenString);

    return $token;
}

    public function pay(Request $request)
    {
        // Проверка, что сумма была передана и является числом
        $request->validate([
            'amount' => 'required|numeric|min:1',
        ]);

        // Индификатор терминала.
        $TerminalKey = '1684504766185';

        // Сумма в рублях, полученная из запроса.
        $sum = $request->input('amount');

        // Номер заказа.
        $order_id = uniqid();
        // Получаем ID авторизованного пользователя
        $user_id = Auth::id();
  $params = [
        "TerminalKey" => $TerminalKey,
        "Amount" => strval($sum * 100), // Сумма в копейках (строка!)
        "OrderId" => $order_id,
        "Description" => "Пополнение кошелька", // Добавьте описание
    ];

   $secretKey = 'wzolzdu618kdvf74'; 
    Log::info('Параметры перед генерацией токена:', $params);

    $token = $this->generateTinkoffToken($params, $secretKey);

    Log::info('Сгенерированный токен:', ['token' => $token]);

    $data = $params + ["Token" => $token];

    Log::info('Запрос к Tinkoff API:', $data);

    $ch = curl_init('https://securepay.tinkoff.ru/v2/Init');
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data, JSON_UNESCAPED_UNICODE));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    $res = curl_exec($ch);
    curl_close($ch);

    Log::info('Ответ от Tinkoff API:', ['response' => $res]); // Логируем ответ

    $res = json_decode($res, true);


        if (!empty($res['PaymentURL'])) {
            // Сохраняем order_id, сумму платежа и user_id  в базе данных
            DB::table('payments')->insert([
                'order_id' => $order_id,
                'user_id' => $user_id,
                'amount' => $sum,
                'status' => 'pending', // Начальный статус
                'created_at' => now(),
                'updated_at' => now(),
            ]);


            // Логируем данные для отладки
            \Log::info('Данные платежа сохранены в payments:', [
                'order_id' => $order_id,
                'amount' => $sum,
                'user_id' => $user_id,
            ]);

            // Редирект в платёжную систему.
            return redirect($res['PaymentURL']);
        } else {
            // Обработка ошибки
            return back()->withErrors(['payment' => 'Ошибка инициализации платежа']);
        }
    }

  public function handlePaymentSuccess(Request $request)
{
    return view('payment_intermediate'); // Отображаем промежуточную страницу
}

   public function handleWebhook(Request $request)
{
    try {
        // Получаем данные из запроса
        $data = $request->all();

        // Логируем входящие данные для отладки
        \Log::info('Webhook received:', $request->all());
        \Log::info('Webhook request content:', ['content' => $request->getContent()]);

        // Проверяем наличие обязательных полей (оставьте только необходимые для вашей логики)
        $requiredFields = ['TerminalKey', 'OrderId', 'Success', 'Status', 'PaymentId', 'ErrorCode', 'Amount'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                \Log::error('Отсутствует обязательное поле: ' . $field);
                return response()->json([
                    'success' => false,
                    'message' => 'Отсутствует обязательное поле: ' . $field,
                ], 400);
            }
        }

       
        if ($data['Status'] === 'CONFIRMED') {

            // Отправляем успешный ответ банку
            response('OK', 200);

             // Получаем OrderId из данных вебхука
            $orderId = $data['OrderId'];

            // Находим запись о платеже в базе данных
            $payment = DB::table('payments')->where('order_id', $orderId)->first();

            if ($payment) {
                // Получаем user_id и amount из записи о платеже
                $userId = $payment->user_id;
                $amount = $payment->amount;

                // Находим пользователя по user_id
                $user = User::find($userId);

                if ($user) {
                    DB::beginTransaction(); // Start transaction

                    try {
                        // Обновляем баланс пользователя
                        $user->balance += $amount;
                        $user->save();

                        // Добавляем запись в таблицу transaction_history
                        TransactionHistory::create([
                            'user_id' => $user->id,
                            'operation_type' => 'пополнение',
                            'amount' => $amount,
                            'description' => 'Пополнение баланса через Tinkoff',
                            'order_id' => $orderId,  // Store order_id
                        ]);

                        // Обновляем статус платежа в таблице payments
                        DB::table('payments')
                            ->where('order_id', $orderId)
                            ->update(['status' => 'completed', 'updated_at' => now()]);

                        DB::commit(); // Commit transaction

                        // Логируем успешное зачисление
                        \Log::info('Баланс пользователя обновлен через webhook: ' . $user->balance);

                         return response('OK', 200);
                    } catch (\Exception $e) {
                        DB::rollback(); // Rollback transaction
                         \Log::error('Ошибка при обработке платежа:', [
                            'message' => $e->getMessage(),
                            'trace' => $e->getTraceAsString(),
                        ]);
                        return response()->json([
                            'success' => false,
                            'message' => 'Ошибка при обработке платежа',
                            'error' => $e->getMessage(),
                        ], 500);
                    }
                } else {
                     \Log::error('Пользователь не найден по user_id:', ['user_id' => $userId]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Пользователь не найден',
                    ], 404);
                }
            } else {
                 \Log::error('Payment not found for OrderId: ' . $orderId);
                 return response()->json([
                    'success' => false,
                    'message' => 'Payment not found',
                ], 404);
            }

        } else {
            \Log::warning('Статус платежа не CONFIRMED:', ['status' => $data['Status']]);
             return response()->json([
                'success' => false,
                'message' => 'Статус платежа не CONFIRMED',
            ], 400);
        }

    } catch (\Exception $e) {
        // Логируем исключение
        \Log::error('Ошибка при обработке вебхука:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        // Возвращаем JSON-ответ с ошибкой
         return response()->json([
            'success' => false,
            'message' => 'Произошла ошибка при обработке запроса',
            'error' => $e->getMessage(),
        ], 500);
    }
}


 public function showPaymentSuccessPage(Request $request)
{
    // Получаем последнюю транзакцию пополнения для текущего пользователя
    $transaction = TransactionHistory::where('user_id', auth()->id())
        ->where('operation_type', 'пополнение')
        ->latest()
        ->first();

    if (!$transaction) {
        // Если транзакция не найдена, устанавливаем сообщение об ошибке
        session(['payment_error' => 'Транзакция не найдена.']);
        return view('payment_success');
    }

    return view('payment_success', ['transaction' => $transaction]);
}
}




    

