<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tariff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class TariffController extends Controller
{
    
    public function showTariffSettings()
{
    $user = Auth::user();
    $hasTariff = $user->tariffs()->exists(); // Проверяем, есть ли у пользователя запись в таблице tariffs

    return view('tariff-settings', compact('hasTariff'));
}

  // Метод для создания пробного тарифа
    public function createTrialTariff(Request $request)
    {
        // Получаем текущего пользователя
        $user = Auth::user();

        // Проверяем, есть ли у пользователя уже тариф
        if ($user->tariffs()->exists()) {
            return redirect()->route('tariff.settings')->with('error', 'У вас уже есть тариф.');
        }

        // Создаем запись в таблице tariffs
        Tariff::create([
            'id_tariff' => null, // Если id_tariff генерируется автоматически, оставьте null
            'id_user' => $user->id,
            'price_day' => 0,
            'price_day_one_advert' => 0,
            'price_month' => 0,
            'adverts' => 99999,
            'status' => 'new',
        ]);

        // Перенаправляем пользователя на страницу настроек тарифа с сообщением об успехе
        return redirect()->route('tariff.settings')->with('success', 'Пробный тариф успешно активирован.');
    }
    
public function save(Request $request)
{
    // Валидация данных
    $request->validate([
        'ad-count' => 'required|integer|min:1000|max:100000', // Минимальное значение изменено на 1000
    ]);

    // Получение пользователя
    $user = auth()->user();

    // Проверка наличия тарифа со статусом 'new'
    $tariff = Tariff::where('id_user', $user->id)->where('status', 'new')->first();

    if ($tariff && !$request->has('confirm')) {
        // Если есть тариф со статусом 'new' и нет подтверждения, возвращаем сообщение с подтверждением
        return redirect()->back()->with('warning', 'У вас действует пробный период, вы уверены что хотите изменить тариф?');
    }

    // Получение количества объявлений
    $adCount = $request->input('ad-count');

    // Данные из таблицы
    $pricingData = [
        ['quantity' => 1000, 'monthlyPrice' => 3900, 'dailyPricePerItem' => 0.13],
        ['quantity' => 1500, 'monthlyPrice' => 4275, 'dailyPricePerItem' => 0.095],
        ['quantity' => 2000, 'monthlyPrice' => 4800, 'dailyPricePerItem' => 0.08],
        ['quantity' => 2300, 'monthlyPrice' => 5175, 'dailyPricePerItem' => 0.075],
        ['quantity' => 2600, 'monthlyPrice' => 5304, 'dailyPricePerItem' => 0.068],
        ['quantity' => 3000, 'monthlyPrice' => 5400, 'dailyPricePerItem' => 0.056],
        ['quantity' => 3300, 'monthlyPrice' => 5544, 'dailyPricePerItem' => 0.053],
        ['quantity' => 3600, 'monthlyPrice' => 5724, 'dailyPricePerItem' => 0.046],
        ['quantity' => 4000, 'monthlyPrice' => 5880, 'dailyPricePerItem' => 0.049],
        ['quantity' => 4300, 'monthlyPrice' => 5934, 'dailyPricePerItem' => 0.046],
        ['quantity' => 4600, 'monthlyPrice' => 6072, 'dailyPricePerItem' => 0.043],
        ['quantity' => 5000, 'monthlyPrice' => 6150, 'dailyPricePerItem' => 0.041],
        ['quantity' => 5300, 'monthlyPrice' => 6360, 'dailyPricePerItem' => 0.040],
        ['quantity' => 5600, 'monthlyPrice' => 6552, 'dailyPricePerItem' => 0.039],
        ['quantity' => 6000, 'monthlyPrice' => 6600, 'dailyPricePerItem' => 0.037],
        ['quantity' => 6300, 'monthlyPrice' => 6785, 'dailyPricePerItem' => 0.0359],
        ['quantity' => 6600, 'monthlyPrice' => 6870, 'dailyPricePerItem' => 0.0347],
        ['quantity' => 7000, 'monthlyPrice' => 6930, 'dailyPricePerItem' => 0.033],
        ['quantity' => 7300, 'monthlyPrice' => 7073, 'dailyPricePerItem' => 0.0323],
        ['quantity' => 7600, 'monthlyPrice' => 7182, 'dailyPricePerItem' => 0.0315],
        ['quantity' => 8000, 'monthlyPrice' => 7200, 'dailyPricePerItem' => 0.03],
        ['quantity' => 8300, 'monthlyPrice' => 7345, 'dailyPricePerItem' => 0.0295],
        ['quantity' => 8600, 'monthlyPrice' => 7482, 'dailyPricePerItem' => 0.029],
        ['quantity' => 9000, 'monthlyPrice' => 7560, 'dailyPricePerItem' => 0.028],
        ['quantity' => 9300, 'monthlyPrice' => 7756, 'dailyPricePerItem' => 0.0278],
        ['quantity' => 9600, 'monthlyPrice' => 7920, 'dailyPricePerItem' => 0.0275],
        ['quantity' => 10000, 'monthlyPrice' => 8100, 'dailyPricePerItem' => 0.027],
        ['quantity' => 13000, 'monthlyPrice' => 9750, 'dailyPricePerItem' => 0.025],
        ['quantity' => 16000, 'monthlyPrice' => 10944, 'dailyPricePerItem' => 0.0228],
        ['quantity' => 20000, 'monthlyPrice' => 12000, 'dailyPricePerItem' => 0.02],
        ['quantity' => 26000, 'monthlyPrice' => 13593, 'dailyPricePerItem' => 0.0197],
        ['quantity' => 30000, 'monthlyPrice' => 17000, 'dailyPricePerItem' => 0.019],
        ['quantity' => 33000, 'monthlyPrice' => 18513, 'dailyPricePerItem' => 0.0187],
        ['quantity' => 36000, 'monthlyPrice' => 19764, 'dailyPricePerItem' => 0.0183],
        ['quantity' => 40000, 'monthlyPrice' => 21600, 'dailyPricePerItem' => 0.018],
        ['quantity' => 43000, 'monthlyPrice' => 22900, 'dailyPricePerItem' => 0.0178],
        ['quantity' => 46000, 'monthlyPrice' => 24288, 'dailyPricePerItem' => 0.0176],
        ['quantity' => 50000, 'monthlyPrice' => 25500, 'dailyPricePerItem' => 0.017],
        ['quantity' => 53000, 'monthlyPrice' => 26712, 'dailyPricePerItem' => 0.0168],
        ['quantity' => 56000, 'monthlyPrice' => 27888, 'dailyPricePerItem' => 0.0166],
        ['quantity' => 60000, 'monthlyPrice' => 28800, 'dailyPricePerItem' => 0.016],
        ['quantity' => 63000, 'monthlyPrice' => 29862, 'dailyPricePerItem' => 0.0158],
        ['quantity' => 66000, 'monthlyPrice' => 30888, 'dailyPricePerItem' => 0.0156],
        ['quantity' => 70000, 'monthlyPrice' => 31500, 'dailyPricePerItem' => 0.015],
        ['quantity' => 73000, 'monthlyPrice' => 32412, 'dailyPricePerItem' => 0.0148],
        ['quantity' => 76000, 'monthlyPrice' => 33060, 'dailyPricePerItem' => 0.0145],
        ['quantity' => 80000, 'monthlyPrice' => 33600, 'dailyPricePerItem' => 0.0144],
        ['quantity' => 83000, 'monthlyPrice' => 33864, 'dailyPricePerItem' => 0.014],
        ['quantity' => 86000, 'monthlyPrice' => 34959, 'dailyPricePerItem' => 0.01355],
        ['quantity' => 90000, 'monthlyPrice' => 36450, 'dailyPricePerItem' => 0.0137],
        ['quantity' => 93000, 'monthlyPrice' => 37860, 'dailyPricePerItem' => 0.01357],
        ['quantity' => 96000, 'monthlyPrice' => 38851, 'dailyPricePerItem' => 0.01349],
        ['quantity' => 100000, 'monthlyPrice' => 39000, 'dailyPricePerItem' => 0.013],
        ['quantity' => 150000, 'monthlyPrice' => 49500, 'dailyPricePerItem' => 0.011],
        ['quantity' => 200000, 'monthlyPrice' => 54000, 'dailyPricePerItem' => 0.009],
        ['quantity' => 250000, 'monthlyPrice' => 63750, 'dailyPricePerItem' => 0.0085],
        ['quantity' => 300000, 'monthlyPrice' => 72000, 'dailyPricePerItem' => 0.008],
    ];

    // Поиск соответствующего тарифа
    $selectedData = collect($pricingData)->first(function ($item) use ($adCount) {
        return $item['quantity'] >= $adCount;
    }) ?? end($pricingData);

    // Расчет цен
    $dailyCost = $selectedData['monthlyPrice'] / 30; // Стоимость в день
    $dailyCostPerItem = $selectedData['dailyPricePerItem']; // Стоимость в день за один товар
    $monthlyCost = $selectedData['monthlyPrice']; // Стоимость в месяц

    // Определение статуса (устанавливаем принудительно 'old')
    $status = 'old';

    // Поиск тарифа для пользователя
    $tariff = Tariff::where('id_user', $user->id)->first();

    if ($tariff) {
        // Если тариф уже существует, обновляем данные
        $tariff->update([
            'price_day' => $dailyCost,
            'price_day_one_advert' => $dailyCostPerItem,
            'price_month' => $monthlyCost,
            'adverts' => $adCount,
            'status' => $status,
            'updated_at' => Carbon::now(),
        ]);
    } else {
        // Если тарифа нет, создаем новый
        $tariff = Tariff::create([
            'id_user' => $user->id,
            'price_day' => $dailyCost,
            'price_day_one_advert' => $dailyCostPerItem,
            'price_month' => $monthlyCost,
            'adverts' => $adCount,
            'status' => $status,
        ]);
    }

    return redirect()->back()->with('success', 'Тариф успешно сохранен!');
}
}