<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Advert;
use Illuminate\Support\Facades\Log;
use App\Models\DeliveryService;
use Illuminate\Support\Facades\Schema;
use App\Models\SellerInfo;

class UserController extends Controller
{
    // Отображение списка пользователей
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

// Отображение конкретного пользователя
    public function show($id, $username = null)
    {
        $currentUser = auth()->user();

        if (!$currentUser || $currentUser->id !== (int)$id) {
            return redirect()->route('login');
        }

        $user = User::with('legalInfo')->find($id);

        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        // Check if the provided username matches the user's actual username
        if ($username === null || Str::slug($user->username) !== $username) {
            return redirect()->route('user.show', ['id' => $id, 'username' => Str::slug($user->username)]);
        }

        return view('profile', ['user' => $user, 'balance' => $user->balance]);

    }


    // Создание нового пользователя
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'city' => 'nullable|string|max:255',
            // Добавьте другие валидации по необходимости
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            // Добавьте другие поля по необходимости
        ]);

        return response()->json($user, 201);
    }

    // Обновление существующего пользователя


   public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        // Валидация данных
        try {
            $request->validate([
                'username' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'sometimes|required|string|min:8',
                'avatar_url' => 'nullable|url',
                'logo_url' => 'nullable|url',
                'organization_name' => 'nullable|string|max:255',
                'legal_address' => 'nullable|string|max:255',
                'inn' => 'nullable|string|max:12', // Максимум 12 символов для ИНН
                'kpp' => 'nullable|string|max:9', // Максимум 9 символов для КПП
            ], [
                'inn.max' => 'ИНН не может быть длиннее 12 символов.',
                'kpp.max' => 'КПП не может быть длиннее 9 символов.',
            ]);
        } catch (IlluminateValidationValidationException $e) {
            return redirect()->back()->withErrors($e->errors())->withInput(); // Перенаправляем обратно с ошибками
        }

        // Обновление данных пользователя
        try {
            $user->update(array_filter([
                'username' => $request->username,
                'email' => $request->email,
                'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
                'city' => $request->city,
                'avatar_url' => $request->input('avatar_url'),
                'logo_url' => $request->input('logo_url'),
            ]));
        } catch (Exception $e) {
            // Обработка ошибки обновления пользователя (можно добавить логирование, если нужно)
        }

        // Обработка добавления филиалов
        $branchAddresses = $request->input('branch_address');
        if (is_array($branchAddresses) && count($branchAddresses) > 0) {
            foreach ($branchAddresses as $branchAddress) {
                if (!empty($branchAddress)) { // Проверка на пустой адрес
                    try {
                        $branch = new Branch();
                        $branch->user_id = $user->id;
                        $branch->address = $branchAddress;
                        $branch->save();
                    } catch (Exception $e) {
                        // Обработка ошибки добавления филиала (можно добавить логирование, если нужно)
                    }
                }
            }
        }

        // Обновление или создание юридической информации
        try {
            $user->legalInfo()->updateOrCreate(
                ['user_id' => $user->id], // Условие поиска
                [
                    'organization_name' => $request->input('organization_name'),
                    'legal_address' => $request->input('legal_address'),
                    'inn' => $request->input('inn'),
                    'kpp' => $request->input('kpp'),
                ]
            );
        } catch (Exception $e) {
            // Обработка ошибки обновления/создания юридической информации (можно добавить логирование, если нужно)
        }

        // Перенаправление на страницу пользователя
        return redirect()->route('user.show', ['id' => $user->id, 'username' => Str::slug($user->username)])->with('success', 'Профиль успешно обновлён!');
    }

// Получение списка филиалов пользователя
    public function getBranches($userId)
    {
        $branches = Branch::where('user_id', $userId)->get();
        return response()->json($branches);
    }

   // Удаление филиала
   public function deleteBranch($id)
    {
        $branch = Branch::find($id);

        if (!$branch) {
            return response()->json(['message' => 'Филиал не найден'], 404);
        }

        // Проверяем, есть ли связанные объявления
        $advertsCount = Advert::where('id_branch', $id)->count();

        if ($advertsCount > 0) {
            return response()->json(['message' => 'Невозможно удалить филиал, так как с ним связаны объявления.  Удалите или измените объявления, использующие этот филиал.'], 400);
        }

        $branch->delete();
        return response()->json(['message' => 'Филиал успешно удален']);
    }
    // Удаление пользователя
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }

    // Отображение формы редактирования профиля
    public function edit($id)
    {
        $user = User::with(['sellerInfo', 'deliveryService'])->find($id);
        if (! $user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        $table = (new DeliveryService)->getTable();

        $deliveryCols = Schema::getColumnListing($table);
        $deliveryCols = array_values(array_diff(
            $deliveryCols,
            ['id', 'user_id', 'created_at', 'updated_at']
        ));

        return view('profile_edit', compact('user','deliveryCols'));
    }

    public function updateShopAbout($id, Request $request)
    {
        $user = User::findOrFail($id);

        $rules = [
            'about_shop' => 'nullable|string',
            'banner_url' => 'nullable|string',
        ];

        $data = $request->validate($rules);

        SellerInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'about_shop'             => $data['about_shop']              ?? null,
                'banner_url'             => $data['banner_url']              ?? null,
            ]
        );

        return back()->with('success', 'Настройки доставки сохранены');
    }

    public function updateDelivery($id, Request $request)
    {
        $user = User::findOrFail($id);

        // 1) Приводим чекбоксы "on" → true
        $request->merge([
            'delivery_in_city'              => $request->has('delivery_in_city'),
            'delivery_to_transport_company' => $request->has('delivery_to_transport_company'),
            'delivery_to_route_taxi'        => $request->has('delivery_to_route_taxi'),
            'delivery_russian_post'         => $request->has('delivery_russian_post'),
        ]);

        // 2) Базовые правила
        $rules = [
            'warranty_return_policy'             => 'nullable|string',
            'delivery_in_city'                   => 'boolean',
            'delivery_in_city_cost'              => 'nullable|integer|min:0',
            'delivery_to_transport_company'      => 'boolean',
            'delivery_to_transport_company_cost' => 'nullable|integer|min:0',
            'delivery_to_route_taxi'             => 'boolean',
            'delivery_to_route_taxi_cost'        => 'nullable|integer|min:0',
            'delivery_russian_post'              => 'boolean',
            'russian_post_additional_cost'       => 'nullable|integer|min:0',
            'additional_delivery_conditions'     => 'nullable|string',
            'branch_ids'                         => 'nullable|array',
            'branch_ids.*'                       => 'integer|exists:branches,id_branch',
            // добавляем правило для массива выбранных ТК
            'available_tk'                       => 'nullable|array',
            'available_tk.*'                     => 'in:' . implode(',', (new DeliveryService)->getFillable()),
        ];

        // 3) Валидация
        $data = $request->validate($rules);
        Log::info("✅ validated data for updateDelivery", $data);

        // 4) Обновляем seller_info
        SellerInfo::updateOrCreate(
            ['user_id' => $user->id],
            [
                'warranty_return_policy'             => $data['warranty_return_policy']              ?? null,
                'delivery_in_city'                   => $data['delivery_in_city']                    ?? false,
                'delivery_in_city_cost'              => $data['delivery_in_city_cost']               ?? 0,
                'delivery_to_transport_company'      => $data['delivery_to_transport_company']       ?? false,
                'delivery_to_transport_company_cost' => $data['delivery_to_transport_company_cost']  ?? 0,
                'delivery_to_route_taxi'             => $data['delivery_to_route_taxi']              ?? false,
                'delivery_to_route_taxi_cost'        => $data['delivery_to_route_taxi_cost']         ?? 0,
                'delivery_russian_post'              => $data['delivery_russian_post']               ?? false,
                'russian_post_additional_cost'       => $data['russian_post_additional_cost']        ?? 0,
                'additional_delivery_conditions'     => $data['additional_delivery_conditions']      ?? null,
                'branch_ids'                         => $data['branch_ids']                          ?? null,
            ]
        );

        // 5) Обновляем delivery_service
        $serviceCols   = collect((new DeliveryService)->getFillable())
            ->reject(fn($col) => $col === 'user_id')
            ->all();
        $selectedTks   = $data['available_tk'] ?? [];
        $dsPayload     = ['user_id' => $user->id];

        foreach ($serviceCols as $col) {
            // ставим 1 если пользователь выбрал именно эту колонку
            $dsPayload[$col] = in_array($col, $selectedTks, true) ? 1 : 0;
        }

        DeliveryService::updateOrCreate(
            ['user_id' => $user->id],
            $dsPayload
        );

        return back()->with('success', 'Настройки доставки сохранены');
    }




    // получеение городов для хэдера
    public function getCities()
    {
        $cities = User::select('city')
            ->distinct()
            ->whereNotNull('city') // Исключаем пустые значения
            ->where('city', '!=', '') // Исключаем пустые строки
            ->orderBy('city') // Сортировка по алфавиту
            ->pluck('city'); // Получение списка городов

        return response()->json($cities);
    }
}


