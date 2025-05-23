<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdvertsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConverterSetController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CarListsController;
use App\Http\Controllers\MessageController;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Controllers\CookiePolicyController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MarketAnalysisController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\CarImportController;
use App\Http\Controllers\TariffController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AdvertTableController;
use App\Http\Controllers\WalletController;
 use Spatie\Sitemap\SitemapGenerator;


Route::get('/users/{userId}/branches', [UserController::class, 'getBranches'])->name('user.branches');
Route::delete('/branches/{id}', [UserController::class, 'deleteBranch'])->name('branch.delete');

Route::get('/advert/{advertId}/all-brands-and-models', [AdvertTableController::class, 'getAllBrandsAndModels']);

// Главная страница
Route::get('/', [AdvertsController::class, 'index'])->name('home'); // Главная страница


// Ресурсный маршрут для контроллера пользователей
Route::apiResource('users', UserController::class);


Route::get('/adverts/filter-by-engine', [AdvertsController::class, 'filterByEngine'])->name('adverts.filterByEngine');
Route::get('/adverts/filter-by-engine-analysis', [MarketAnalysisController::class, 'filterByEngineAnalis'])->name('adverts.filterByEngineAnalis');

// Разлогинивание
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/verify-otp', [AuthController::class, 'showOtpForm'])->name('verify.otp');
Route::post('/verify-otp', [AuthController::class, 'verifyOtp'])->name('verify.otp.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Ресурсный маршрут для получения города
Route::get('/cities', [UserController::class, 'getCities']);

// Оферта
Route::get('/oferta', [OfertaController::class, 'index']);
Route::get('/oferta', [OfertaController::class, 'index'])->name('oferta');

// О проекте
Route::get('/about', [AboutController::class, 'index'])->name('about');


// Франшиза
Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise.index');

// Ресурсный маршрут для контроллера объявлений
Route::resource('adverts', AdvertsController::class);

// Ресурсный маршрут для контроллера объявлений пользователей в ЛК
Route::middleware(['auth'])->group(function () {
    Route::get('/my-adverts', [AdvertsController::class, 'myAdverts'])->name('adverts.my_adverts');
});



// Для списка чатов
Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');

// Отображение конкретного чата
Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');
Route::get('/support-chat', [ChatController::class, 'openSupportChat'])->name('open.support.chat');
Route::get('/adverts/{advert}/chat', [ChatController::class, 'openChatWithSeller'])->name('open.chat.with.seller');

// Отправка сообщения
Route::post('/chat/{chat}/send', [ChatController::class, 'sendMessage'])->middleware('auth')->name('chat.send');

// Открытие нового чата
Route::post('/chat/open/{advert}', [ChatController::class, 'openChat'])->middleware('auth')->name('chat.open');

Route::post('/chat/{chat}/mark-as-read', [ChatController::class, 'markMessagesAsRead'])
    ->name('chat.mark-as-read')
    ->middleware('auth');

// Получение сообщений
Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

//статус сообщения
Route::post('/message/{message}/read', [ChatController::class, 'markAsRead'])->name('message.read');

Route::get('/chat/{chat}/new-messages', [ChatController::class, 'getNewMessages'])->name('chat.new-messages');

// Ресурсный маршрут для настройки конвертера
Route::middleware(['auth'])->group(function () {
    Route::get('/converter-set/edit', [ConverterSetController::class, 'edit'])->name('converter_set.edit');
    Route::put('/converter-set/update', [ConverterSetController::class, 'update'])->name('converter_set.update');
});

// Ресурсный маршрут для настройки регистрации и авторизации

Route::middleware(['auth'])->group(function () {
       Route::get('/user/{id}/{username?}', [UserController::class, 'show'])->name('user.show');
});

// Редактирование профиля
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{id}/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [UserController::class, 'update'])->name('profile.update');
    
});
Route::put('/change-password', [AuthController::class, 'changePassword'])->name('change.password');

// Ресурсный маршрут для поиска города
Route::get('/cities/search', [CityController::class, 'search']);



Route::get('/search/loading', [AdvertsController::class, 'showLoading'])->name('adverts.search.loading');

// Ресурсный маршрут для страницы товара
   Route::get('/adverts/{id}/{product_name_slug?}/{brand?}/{model?}/{year?}/{engine?}/{number?}', [AdvertsController::class, 'show'])->name('adverts.show');
  
// Ресурсный маршрут для страницы результатов поиска товара
Route::get('/search', [AdvertsController::class, 'search'])->name('adverts.search');




// Форма поиска
// Ресурсный маршрут для динамических списков формы 
Route::get('/get-models', [CarListsController::class, 'getModels'])->name('get.models');
Route::get('/get-modelsCreate', [CarListsController::class, 'getModelsCreate'])->name('get.modelsCreate');
//год
Route::get('/get-years', [CarListsController::class, 'getYears']);
//модификации
Route::get('/get-modifications', [CarListsController::class, 'getModifications'])->name('get.modifications');
//id_ модификации

//Подсказки для поля ввода названия запчасти
Route::get('/parts/search', function (Request $request) {
    $query = $request->get('query');
    $parts = Part::where('part_name', 'LIKE', "%{$query}%")->pluck('part_name');
    return response()->json($parts);
});

// Стрианица помощи
Route::get('/help', [HelpController::class, 'index'])->name('help.index');

// Уведомление cookie
Route::get('/cookie-policy', function () {
    return view('cookie-policy');
})->name('cookie.policy');

//Подсказки по маркам

Route::get('/get-brands', [CarListsController::class, 'getBrands'])->name('get.brands');



Route::get('/viewed', [AdvertsController::class, 'viewed'])->name('adverts.viewed');

Route::get('/favorites', [AdvertsController::class, 'favorites'])->name('adverts.favorites');



//подтверждение почты

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Ссылка для подтверждения отправлена!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

Route::put('/adverts/update', [AdvertController::class, 'update'])->name('adverts.update');


Route::get('/search/by-part-number', [AdvertController::class, 'searchByPartNumber'])->name('search.by.part.number');
Route::get('/search/by-part-name', [AdvertController::class, 'searchByPartName'])->name('search.by.part.name');

Route::get('/market-analysis', function () {
    return view('market');
})->name('market.analysis');

Route::get('/market-analysis', [MarketAnalysisController::class, 'index'])->name('market.analysis');

Route::get('/tariff-settings', function () {
    return view('tariff-settings');
})->name('tariff.settings');

Route::post('/adverts/import', [AdvertsController::class, 'import'])->name('adverts.import');

//Оплата

Route::post('/pay', [PayController::class, 'pay'])->name('pay');

Route::get('/pay2', [PayController::class, 'pay2'])->name('pay2');
Route::get('/pay-form', [PayController::class, 'showPayForm'])->name('pay.form');

 Route::get('/successpay', [PayController::class, 'handlePaymentSuccess'])->name('payment.success');
 Route::get('/payment_success', [PayController::class, 'showPaymentSuccessPage'])->name('payment.success.page');
 Route::post('/payment/webhook', [PayController::class, 'handleWebhook'])->name('payment.webhook');

Route::post('/save-tariff', [TariffController::class, 'save'])->name('save.tariff');
Route::post('/create-trial-tariff', [TariffController::class, 'createTrialTariff'])->name('create.trial.tariff');
Route::get('/tariff-settings', [TariffController::class, 'showTariffSettings'])->name('tariff.settings');
//Импорт товаров

Route::post('/admin/cars/import', [CarImportController::class, 'import'])->name('cars.import');
Route::view("/admin/cars/import", 'cars.import')->name('cars.import.form');

//Импорт с конвертацией

Route::post('/convert-price-list', [ConverterSetController::class, 'convertPriceList'])->name('convert.price.list');

Route::get('/fromlist', [ConverterSetController::class, 'createFromList'])->name('fromlist');

Route::post('/get-settings', [ConverterSetController::class, 'getSettings'])->name('get.settings');

Route::post('/reset-converter-set', [ConverterSetController::class, 'reset'])->name('converter_set.reset');

    Route::get('/get-parts', function () {
        $term = request()->input('term');
        $parts = \App\Models\Part::where('part_name', 'like', '%' . $term . '%')->pluck('part_name');
        return response()->json($parts);
    })->name('get.parts');
    
    Route::get('/get-years', [CarListsController::class, 'getYears'])->name('get.years');
    
    Route::post('/adverts/delete-multiple', [AdvertsController::class, 'destroyMultiple'])->name('adverts.destroyMultiple');
    
    
    
        Route::post('/favorites/add/{advertId}', [FavoriteController::class, 'addToFavorites'])->name('favorites.add');
        Route::post('/favorites/remove/{advertId}', [FavoriteController::class, 'removeFromFavorites'])->name('favorites.remove');


     Route::get('/messages/unread-count', [ChatController::class, 'getUnreadCount'])->middleware('auth');
  Route::get('/messages/{chatId}', [ChatController::class, 'getMessagesByChatId'])->name('messages.byChatId');
  
   Route::get('/advert/{advert}/table-data', [AdvertTableController::class, 'getTableData'])->name('advert.table-data');
  Route::get('/advert/{advertId}/brands-and-models', [AdvertTableController::class, 'getBrandsAndModels']);
  
    Route::get('/wallet/history', [WalletController::class, 'getHistory']);
    Route::get('/pay-form', [WalletController::class, 'showPayForm'])->name('pay.form');
    
    Route::post('/save-column-mappings', [ConverterSetController::class, 'saveColumnMappings']);
        Route::post('/check-column-mappings', [ConverterSetController::class, 'checkColumnMappings'])->name('check.column.mappings');
                Route::post('/get-column-mappings', [ConverterSetController::class, 'getColumnMappings'])->name('get.column.mappings');
         Route::delete('/column-mappings/delete', [ConverterSetController::class, 'deleteColumnMappings'])->name('column_mappings.delete');
         
        

Route::get('/sitemap', function () {
    SitemapGenerator::create(config('app.url'))->writeToFile(public_path('sitemap.xml'));
    return response()->file(public_path('sitemap.xml'));
});
                




 



