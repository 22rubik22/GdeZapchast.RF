<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\UserPhoneNumber;
use App\Mail\EmailVerification;
use App\Models\Branch;
use Illuminate\Support\Facades\DB; 


class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }


    
public function register(Request $request)
    {
        // Валидация данных
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                function ($attribute, $value, $fail) {
                    if (!preg_match('/[A-Z]/', $value)) {
                        $fail('Пароль должен содержать хотя бы одну заглавную букву.');
                    }
                },
            ],
            'username' => 'required|string|max:255',
            'city' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:19', 'unique:users_phone_number,number_1'],
            'user_status' => 'required|integer',
            'address_line' => 'nullable|string|max:255', // Адрес теперь не обязателен при валидации
        ]);

        // Сохраняем данные пользователя в сессии, включая адрес.  `address_line` может быть null.
        $userData = $request->only(['email', 'password', 'username', 'city', 'phone', 'user_status', 'address_line']);
        $request->session()->put('user_data', $userData);

        // Генерация OTP
        $otp = rand(100000, 999999); // 6-значный код
        $request->session()->put('otp', $otp);

        // Отправка OTP на email
        Mail::to($userData['email'])->send(new EmailVerification($otp)); // Передаем OTP, а не объект пользователя

        // Перенаправление на страницу ввода OTP
        return redirect()->route('verify.otp')->with('success', 'На ваш email отправлен код подтверждения.');
    }

    public function showOtpForm()
    {
        return view('auth.verify-otp');
    }

   public function verifyOtp(Request $request)
{
    // Проверка OTP
    $otp = $request->session()->get('otp');
    if ($request->otp != $otp) {
        return back()->withErrors(['otp' => 'Неверный код подтверждения.']);
    }

    // Получаем данные пользователя из сессии
    $userData = $request->session()->get('user_data');

    // Начинаем транзакцию
    DB::beginTransaction();

    try {
        // Создание пользователя
        $user = User::create([
            'email' => $userData['email'],
            'password' => Hash::make($userData['password']),
            'username' => $userData['username'],
            'user_status' => $userData['user_status'], // Убрали city, так как она сохраняется в user_adresses
        ]);

        // Создание записи в UserPhoneNumber
        UserPhoneNumber::create([
            'user_id' => $user->id,
            'number_1' => $userData['phone'],
        ]);

        // Создание записи в UserAddress
        UserAddress::create([
            'user_id' => $user->id,
            'city' => $userData['city'],
        ]);


        // Создание записи в Branches, если user_status == 1 и есть address_line
        if ($userData['user_status'] == 1 && !empty($userData['address_line'])) {
            Branch::create([
                'user_id' => $user->id,
                'address' => $userData['address_line'],
            ]);
        }

        // Подтверждение транзакции
        DB::commit();

        // Очистка сессии
        $request->session()->forget(['user_data', 'otp']);

        // Авторизация пользователя
        Auth::login($user);

        // Перенаправление на главную страницу
        return redirect()->route('adverts.index')->with('success', 'Регистрация успешно завершена!');

    } catch (\Exception $e) {
        // Откат транзакции в случае ошибки
        DB::rollback();

        // Логирование ошибки (важно для отладки)
        \Log::error('Ошибка при регистрации: ' . $e->getMessage());

        // Перенаправление с сообщением об ошибке
        return back()->withErrors(['error' => 'Произошла ошибка при регистрации. Пожалуйста, попробуйте позже.']);
    }
}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Валидация для email и password
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            return redirect()->route('user.show', ['id' => Auth::id()])->with('success', 'Вы успешно вошли в систему!');
        }

        return back()->withErrors(['email' => 'Неверные учетные данные.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/')->with('success', 'Вы вышли из системы.');
    }
    
    public function changePassword(Request $request)
{
    // Получаем текущего авторизованного пользователя
    $user = auth()->user();

    // Проверяем, что пользователь авторизован
    if (!$user) {
        return response()->json(['message' => 'Пользователь не авторизован'], 401);
    }

    // Логируем информацию о текущем пользователе
    \Log::info('Текущий авторизованный пользователь:', [
        'id' => $user->id,
        'email' => $user->email,
    ]);

    // Валидация данных
    $request->validate([
        'current_password' => 'required|string',
        'new_password' => 'required|string|min:8|confirmed',
    ]);

    // Логируем введенный текущий пароль
    \Log::info('Введенный текущий пароль:', [
        'current_password' => $request->current_password,
    ]);

    // Проверка текущего пароля
    if (!Hash::check($request->current_password, $user->password)) {
        // Логируем ошибку проверки пароля
        \Log::error('Неверный текущий пароль:', [
            'input_password' => $request->current_password,
            'stored_password_hash' => $user->password,
        ]);
        return response()->json(['message' => 'Неверный текущий пароль'], 422);
    }

    // Обновление пароля
    $user->password = Hash::make($request->new_password);
    $user->save();

    // Логируем успешное изменение пароля
    \Log::info('Пароль успешно изменен для пользователя:', [
        'id' => $user->id,
        'email' => $user->email,
    ]);

    // Возвращаем успешный ответ
    return response()->json(['success' => true, 'message' => 'Пароль успешно изменен']);
}
}